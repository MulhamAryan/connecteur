<?php 

namespace Admin\Mapper;

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

class ResourceMapper {
    
    protected $dbAdapter;
    
    public function __construct(AdapterInterface $dbAdapter){
        
        $this->dbAdapter = $dbAdapter;
    }
    
    public function getAll_Array($paginated=false){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('resources');
    
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