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
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class BannerEnrollmentMapper {
    protected $dbAdapter;
    protected $hydrator;

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator) {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
    }

    public function getEnrollmentsByTerm_Array($term) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->quantifier('DISTINCT');
        $select->from(array('i' => 'ULB_CURSUS.VW_INSCRIPTIONS_COURS'));
        $select->columns(array(
            'nre' => 'CRN',
            'subjcode' => 'SUBJ_CODE',
            'crsenumb' => 'CRSE_NUMB',
            'term' => 'TERM_CODE'));
        $select->join(array('e' => 'ULB_CURSUS.MV_ETUDIANTS'), 'i.PIDM = e.PIDM', array('bannerid' => 'BANNER_ID'), $select::JOIN_INNER);
        $select->where(array('i.TERM_CODE = ?' => $term, "i.CRSE_NUMB NOT LIKE 'Z%'"));

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
