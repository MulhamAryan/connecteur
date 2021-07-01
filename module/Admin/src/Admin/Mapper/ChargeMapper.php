<?php

namespace Admin\Mapper;

use Admin\Model\ChargeModel;
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

class ChargeMapper extends AbstractMapper {

    protected $dbAdapter;
    protected $hydrator;
    protected $model;

    protected $tablename = 'charges';
    protected $temptablename = 'charges_tmp';
    protected $updatestablename = 'charges_updates';
    protected $frombanner = array('bannerid', 'subjcode', 'crsenumb', 'term');

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, ChargeModel $chargeModel){

        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->model = $chargeModel;
    }

}


?>
