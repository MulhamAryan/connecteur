<?php

namespace Admin\Mapper;

use Admin\Model\EnrollmentModel;
use Admin\Mapper\AbstractMapper;
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

class EnrollmentMapper extends AbstractMapper {
    protected $dbAdapter;
    protected $hydrator;
    protected $model;

    protected $tablename = 'enrollments';
    protected $temptablename = 'enrollments_tmp';
    protected $updatestablename = 'enrollments_updates';
    protected $frombanner = array('bannerid', 'nre', 'subjcode', 'crsenumb', 'term');

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, EnrollmentModel $enrollmentModel) {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->model = $enrollmentModel;
    }
}
