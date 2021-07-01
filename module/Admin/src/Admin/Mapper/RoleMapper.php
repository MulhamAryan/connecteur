<?php 

namespace Admin\Mapper;

use Admin\Model\RoleModel;
use Admin\Model\UserModel;
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

class RoleMapper {
    
    protected $dbAdapter;
    protected $hydrator;
    protected $roelModel;
    
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, RoleModel $roleModel){
        
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->roleModel = $roleModel;
    }
    
    public function getAll_Array($paginated=false){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('roles');
    
        if ($paginated){
                
            $adapter = new DbSelect($select,$this->dbAdapter,$resultSet);
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
    
    
    public function getUserRoles(UserModel $user){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('ra' => 'role_assignements'));
        $select->join(array('r' => 'roles'),'r.id = ra.roleid',array('rolename' => 'name'),$select::JOIN_INNER);
        $select->where(array('userid = ?' => $user->getId()));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
    
        $rolesarray = array();
        foreach ($result as $userrole){
            array_push($rolesarray ,$userrole['rolename']);
        }
    
        return $rolesarray;
    }

}


?>