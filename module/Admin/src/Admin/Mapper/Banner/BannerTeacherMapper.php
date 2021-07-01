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

class BannerTeacherMapper {

    protected $dbAdapter;
    protected $hydrator;

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator){

        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
    }

    /**
     * Return
     * @param
     */
    public function getTeachersByTerm_Array($term){

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->quantifier('DISTINCT');
        $select->from(array('e' => 'ULB_CURSUS.MV_ETUDIANTS'));
        $select->columns(array(
            'netid' => 'NETID',
            'firstname' => 'PRENOM',
            'lastname' => 'NOM',
            'email' => 'EMAILULB',
            'bannerid' => 'BANNER_ID'
        ));
        $select->join(array('c' => 'ULB_CURSUS.VW_COURS_NRE'), 'e.PIDM = c.PIDM', array(), $select::JOIN_INNER);
        $select->where(array(
            "c.TERM_CODE = ?" => $term,
            "c.CRSE_NUMB NOT LIKE 'Z%'",
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
}


?>
