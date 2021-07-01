<?php

namespace Admin\Mapper\Banner;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class BannerStudentMapper {

    protected $dbAdapter;
    protected $hydrator;

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator) {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
    }

    public function getStudentsByTerm_Array($term) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('i' => 'ULB_CURSUS.MV_INSCRIPTIONS'));
        $select->columns(array(
            'bannerid' => 'BANNER_ID',
            'netid' => 'NETID',
            'firstname' => 'FIRST_NAME',
            'lastname' => 'LAST_NAME',
            'email' => 'EMAILULB',
            'cohort' => new Expression("LISTAGG(AREA, ',') WITHIN GROUP (ORDER BY AREA)"),
            'faculty' => new Expression("LISTAGG(COLL_CODE, ',') WITHIN GROUP (ORDER BY AREA)"),
            'status' => new Expression("LISTAGG(ESTS_CODE, ',') WITHIN GROUP (ORDER BY AREA)")
        ));
        $select->where(array('i.TERM_CODE = ?' => $term));
        $select->group('i.BANNER_ID, i.NETID, i.FIRST_NAME, i.LAST_NAME, i.EMAILULB');

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;
        }
        return array();
    }

    public function getStudentByBannerId($bannerId) {
        return $this->getStudent(array('e.BANNER_ID = ?' => $bannerId));
    }

    public function getStudentByNetId($netId) {
        return $this->getStudent(array('e.NETID = ?' => $netId));
    }

    private function getStudent($where) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->quantifier('DISTINCT');
        $select->from(array('e' => 'ULB_CURSUS.MV_ETUDIANTS'));
        $select->columns(array(
                'pidm' => 'PIDM',
                'netid' => 'NETID',
                'title' => 'CIVILITE',
                'firstname' => 'PRENOM',
                'lastname' => 'NOM',
                'email' => 'EMAILULB',
                'matricule' => 'MATRICULE',
                'bannerid' => 'BANNER_ID',
                'sex' => 'SEXE',
                'initials' => 'INITIALES'));
        $select->where($where);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;
        }
        return array();
    }

    public function getStudentInscriptionByBannerId($bannerId, $termCode) {
        return $this->getStudentInscription(array('i.BANNER_ID = ?' => $bannerId, 'i.TERM_CODE = ?' => $termCode));
    }

    public function getStudentInscriptionByNetId($netId, $termCode) {
        return $this->getStudentInscription(array('i.NETID = ?' => $netId, 'i.TERM_CODE = ?' => $termCode));
    }

    private function getStudentInscription($where) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('i' => 'ULB_CURSUS.MV_INSCRIPTIONS'));
        $select->columns(array(
            'bannerid' => 'BANNER_ID',
            'netid' => 'NETID',
            'firstname' => 'FIRST_NAME',
            'lastname' => 'LAST_NAME',
            'email' => 'EMAILULB',
            'cohort' => new Expression("LISTAGG(AREA, ',') WITHIN GROUP (ORDER BY AREA)"),
            'faculty' => new Expression("LISTAGG(COLL_CODE, ',') WITHIN GROUP (ORDER BY AREA)"),
            'status' => new Expression("LISTAGG(ESTS_CODE, ',') WITHIN GROUP (ORDER BY AREA)")
        ));
        $select->where($where);
        $select->group('i.BANNER_ID, i.NETID, i.FIRST_NAME, i.LAST_NAME, i.EMAILULB');

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;
        }
        return array();
    }

    public function getStudentEnrollments($bannerId, $termCode, $order = null) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->quantifier('DISTINCT');
        $select->from(array('i' => 'ULB_CURSUS.VW_INSCRIPTIONS_COURS'));
        $select->columns(array(
                'nre' => 'CRN',
                'crsenumb' => 'CRSE_NUMB',
                'subjcode' => 'SUBJ_CODE',
                'term' => 'TERM_CODE'));
        $select->join(array('e' => 'ULB_CURSUS.MV_ETUDIANTS'), 'i.PIDM = e.PIDM', array(), $select::JOIN_LEFT);
        $select->where(array('i.TERM_CODE = ?' => $termCode, 'e.BANNER_ID = ?' => $bannerId));

        if ($order == 'mnemonic') {
            //$select->orderBy('');
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;
        }
        return array();
    }
}
