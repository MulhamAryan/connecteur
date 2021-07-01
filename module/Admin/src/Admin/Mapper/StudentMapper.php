<?php

namespace Admin\Mapper;

use Admin\Model\StudentModel;
use Admin\Mapper\AbstractMapper;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Predicate;

class StudentMapper extends AbstractMapper {

    protected $dbAdapter;
    protected $hydrator;
    protected $model;

    protected $tablename = 'students';
    protected $temptablename = 'students_tmp';
    protected $updatestablename = 'students_updates';
    protected $frombanner = array(
            'netid',
            'firstname',
            'lastname',
            'email',
            'bannerid',
            'cohort',
            'faculty',
            'status');

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, StudentModel $studentModel){

        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->model = $studentModel;
    }

    public function getByTerm_Array($term){

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->quantifier('DISTINCT');
        $select->from(array('s' => 'students'));
        $select->join(array('e' => 'enrollments'),'s.sourcedid = e.sourcedid',array(),$select::JOIN_INNER);
        $select->where(array('e.term = ?' => $term));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        if($result->count() == 0){
            return array();
        }else{
            return $result;
        }

    }

}


?>
