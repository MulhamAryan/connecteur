<?php 

namespace Admin\Mapper;

use Admin\Model\GradeModel;
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

class GradeMapper extends AbstractMapper {
    
    protected $dbAdapter;
    protected $hydrator;
    protected $model;
    
    protected $tablename = 'grades';
    protected $temptablename = 'grades_tmp';
    protected $updatestablename = 'grades_updates';
    protected $frombanner = array(
            'sourcedid',
            'bannerid',
            'nre',
            'term',
            'session');
    
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, GradeModel $gradeModel){
        
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->model = $gradeModel;
    }    
    
}


?>