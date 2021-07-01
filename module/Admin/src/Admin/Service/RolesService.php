<?php
namespace Admin\Service;

use Admin\Controller\Plugin\MyLogger;
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
use Zend\ServiceManager\ServiceLocatorInterface;

class RolesService {
    
    protected $logger;
    protected $adapter;
    protected $sl;
    
    public function __construct(MyLogger $logger, AdapterInterface $adapter, ServiceLocatorInterface $sl){
        $this->logger = $logger;
        $this->adapter = $adapter;
        $this->sl = $sl;
    }    
    
    public function getAllRoles(){
    
        $sql = new Sql($this->adapter);
        $select = $sql->select('roles');

        $stmt = $sql->prepareStatementForSqlObject($select);
        $results = $stmt->execute();
    
        if ($results->count() > 0){
            $roles = array();
            foreach($results as $role){
                array_push($roles,$role);
            }
            return $roles;
        }else{
            return array();
        }
    
    }
    
    
    public function getUsersRolesByUserid($userid){
        
        $sql = new Sql($this->adapter);
        $select = $sql->select(array( 'a' => 'role_assignements'));
        $select->join(array('r' => 'roles'),'a.roleid = r.id',array('name','description'),$select::JOIN_INNER);
        $select->where(array(
                'a.userid = ?' => $userid));
        $stmt = $sql->prepareStatementForSqlObject($select);
        $results = $stmt->execute();
        
        if ($results->count() > 0){
            $roles = array();
            foreach($results as $role){
                array_push($roles,$role);
            }
            return $roles;
        }else{
            return array();
        }
        
    }
    
    public function addRoleToUserid($roleid,$userid){
        
        //check if user has already this role
        $sql = new Sql($this->adapter);
        $select = $sql->select(array( 'a' => 'role_assignements'));
        $select->where(array(
                'a.userid = ?' => $userid,
                'a.roleid = ?' => $roleid));
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $results = $stmt->execute();
        if($results->count() >0) return null;
        
        $roleassignment = array();
        $roleassignment['userid'] = $userid;
        $roleassignment['roleid'] = $roleid;
        
        $action = new Insert('role_assignements');
        $action->values($roleassignment);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
//TODO verifier les erreurs        
        return true;
    }
    
    
    public function removeRoleFromUserid($roleid,$userid){
    
        $sql = new Sql($this->adapter);
        $action = new Delete('role_assignements');
        $action->where(array(
                'userid = ?' => $userid,
                'roleid = ?' => $roleid));
        
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
//TODO verifier les erreurs
        return true;
    }

}