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

class BannerChargeMapper {

    protected $dbAdapter;
    protected $hydrator;

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator){

        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
    }

    public function getChargesByTerm_Array($term){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->quantifier('DISTINCT');
        $select->from(array('c' => 'ULB_CURSUS.VW_COURS_NRE'));
        $select->columns(array(
            'subjcode' => 'SUBJ_CODE',
            'crsenumb' => 'CRSE_NUMB',
            'term' => 'TERM_CODE'));
        $select->join(array('e' => 'ULB_CURSUS.MV_ETUDIANTS'), 'c.PIDM = e.PIDM', array('bannerid' => 'BANNER_ID'), $select::JOIN_INNER);
        $select->where(array(
            "c.TERM_CODE = ?" => $term,
            "c.CRSE_NUMB NOT LIKE 'Z%'",
            "e.NETID IS NOT NULL"
        ));

        $stmt = $sql->prepareStatementForSqlO   bject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;
        }

        return array();
    }


}


?>
