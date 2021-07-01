<?php 

namespace Admin\Mapper;

use Admin\Model\PermissionModel;
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

class PermissionMapper {
    
    protected $dbAdapter;
    protected $hydrator;
    protected $permissionModel;
    
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, PermissionModel $permissionModel){
        
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->permissionModel = $permissionModel;
    }
    
    public function getAll($paginated=false){
        
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(array('p' => 'permissions'));
        $select->join(array('r' => 'roles'),'r.id = p.roleid',array('rolename' => 'name'),$select::JOIN_INNER);
        $select->join(array('re' => 'resources'),'re.id = p.resource',array('resourceid' => 'resourceid'),$select::JOIN_INNER);
        
        if ($paginated){
            
            $adapter = new DbSelect($select,$this->dbAdapter);
            $paginator = new Paginator($adapter);
            
            return $paginator;    
        }            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        if ($result instanceof ResultInterface && $result->isQueryResult()){
            return $result;
        }
        
        return array();
    }    
    
}


?>