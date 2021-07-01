<?php

namespace Admin\Mapper\Banner;

use Admin\Model\CourseModel;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;

class BannerCourseMapper {

    protected $dbAdapter;
    protected $hydrator;

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator){

        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
    }

    public function getCoursesByTerm_Array($term){
        // Get all courses (either active or inactive), but not Erasmus ones (Z courses). In the past, tried to synchronize only
        // courses in ULB_CATALOGUE.CATALOGUE but this was too restricted, because some courses that must be in UV (such as FRLE-*
        // or guidances) are not in the programme catalogue.
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('c' => 'ULB_CURSUS.VW_COURS_NRE'));
        $select->quantifier('DISTINCT');    // There is an entry for every NRE and every teacher in charge of the course.
        $select->columns(array(
            'subjcode' => 'SUBJ_CODE',      // Subject code
            'crsenumb' => 'CRSE_NUMB',      // Course number
            'title' => 'LONG_TITLE',
            'faculty' => 'COLL_CODE',       // Faculty code (1 letter)
            'term' => 'TERM_CODE'           // Academy year
        ));
        $select->where(array(
            "TERM_CODE = ?" => $term,
            "CRSE_NUMB NOT LIKE 'Z%'"
        ));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;
        }

        return array();
    }

    public function getMnemonicForNre($nre, $term) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('c' => 'ULB_CURSUS.VW_COURS_NRE'));
        $select->quantifier('DISTINCT');    // There is an entry for every teacher in charge of the course.
        $select->columns(array('mnemonic' => 'MNEMONIQUE'));
        $select->where(array('TERM_CODE = ?' => $term, 'CRN = ?' => $nre));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;
        }

        return array();
    }

    public function getCourseByMnemonicAndTerm($mnemonic, $term) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('c' => 'ULB_CURSUS.VW_COURS_NRE'));
        $select->columns(array(
                'mnemonique' => 'MNEMONIQUE',
                'subjcode' => 'SUBJ_CODE',
                'crsenumb' => 'CRSE_NUMB',
                'title' => 'LONG_TITLE',
                'nre' => 'CRN',
                'term' => 'TERM_CODE',
                'faculty' => 'COLL_CODE',
                'quadri' => 'PTRM_CODE',
                'fullname' => 'FULL_NAME',
                'activities' => new Expression("NVL(c.ACTIVITES, ' ')")));

        $select->where(array(
                'TERM_CODE = ?' => $term,
                'MNEMONIQUE = ?' => $mnemonic
                ));
        $select->order('MNEMONIQUE, CRN');

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;
        }

        return array();
    }

    public function getCourseTeachers($mnemonic, $term) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->quantifier('DISTINCT');
        $select->from(array('c' => 'ULB_CURSUS.VW_COURS_NRE'));
        $select->columns(array(
            'subjcode' => 'SUBJ_CODE',
            'crsenumb' => 'CRSE_NUMB',
            'term' => 'TERM_CODE'));
        $select->join(array('e' => 'ULB_CURSUS.MV_ETUDIANTS'), 'c.PIDM = e.PIDM',
                      array('bannerid' => 'BANNER_ID', 'firstname' => 'PRENOM', 'lastname' => 'NOM'), $select::JOIN_INNER);
        $select->where(array(
            "c.TERM_CODE = ?" => $term,
            "c.MNEMONIQUE = ?" => $mnemonic,
            "e.NETID IS NOT NULL"
        ));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;
        }

        return array();
    }

    public function getCourseStudents($mnemonic, $term) {
        $parts = explode('-', $mnemonic);
        $subjcode = $parts[0];
        $crsenumb = substr($parts[1], 0, 1) . $parts[2];

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->quantifier('DISTINCT');
        $select->from(array('i' => 'ULB_CURSUS.VW_INSCRIPTIONS_COURS'));
        $select->columns(array(
            'nre' => 'CRN',
            'subjcode' => 'SUBJ_CODE',
            'crsenumb' => 'CRSE_NUMB',
            'term' => 'TERM_CODE'));
        $select->join(array('e' => 'ULB_CURSUS.MV_ETUDIANTS'), 'i.PIDM = e.PIDM',
                      array('bannerid' => 'BANNER_ID', 'firstname' => 'PRENOM', 'lastname' => 'NOM'), $select::JOIN_INNER);
        $select->where(array('i.TERM_CODE = ?' => $term, 'i.SUBJ_CODE = ?' => $subjcode, 'i.CRSE_NUMB = ?' => $crsenumb));
        $select->order('e.BANNER_ID');

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;
        }

        return array();
    }
}


?>
