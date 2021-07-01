<?php 

namespace Admin\Mapper;

use Admin\Model\NtChargeModel;
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

class NtChargeMapper extends AbstractMapper {
    
    protected $dbAdapter;
    protected $hydrator;
    protected $model;
    
    protected $tablename = 'ntcharges';
    protected $temptablename = 'ntcharges_tmp';
    protected $updatestablename = 'ntcharges_updates';
    protected $frombanner = array(
            'ntid',
            'netid',
            'firstname',
            'lastname',
            'nre',
            'term');
    
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, NtChargeModel $ntChargeModel){
        
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->model = $ntChargeModel;
    }
    
    
}


?>