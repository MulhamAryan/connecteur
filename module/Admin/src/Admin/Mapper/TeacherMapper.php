<?php

namespace Admin\Mapper;

use Admin\Model\TeacherModel;
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

class TeacherMapper extends AbstractMapper {

    protected $dbAdapter;
    protected $hydrator;
    protected $model;

    protected $tablename = 'teachers';
    protected $temptablename = 'teachers_tmp';
    protected $updatestablename = 'teachers_updates';
    protected $frombanner = array(
            'netid',
            'firstname',
            'lastname',
            'email',
            'bannerid');

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, TeacherModel $teacherModel){

        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->model = $teacherModel;
    }
}


?>
