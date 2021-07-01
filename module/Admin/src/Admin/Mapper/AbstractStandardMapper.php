<?php 

namespace Admin\Mapper;

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

abstract class AbstractStandardMapper {
    
    public function getAll($paginated=false){
        
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->tablename);
        
        $resultSet = new HydratingResultSet($this->hydrator, $this->model);
        
        if ($paginated){
            
            $adapter = new DbSelect($select,$this->dbAdapter,$resultSet);
            $paginator = new Paginator($adapter);
            
            return $paginator;
            
        }
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();        
        if ($result instanceof ResultInterface && $result->isQueryResult()){        
            return $resultSet->initialize($result);
        }        
        return array();
    }
    
    public function getAll_Array($paginated=false){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->tablename);
    
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
    
    
    public function getWhere(array $where = array(),$paginated=false){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->tablename);
        $select->where($where);
    
        $resultSet = new HydratingResultSet($this->hydrator, $this->model);
        
        if ($paginated){
    
            $adapter = new DbSelect($select,$this->dbAdapter,$resultSet);
            $paginator = new Paginator($adapter);
    
            return $paginator;
    
        }
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        if ($result instanceof ResultInterface && $result->isQueryResult()){
            return $resultSet->initialize($result);
        }
        return array();
    }
    
    
    public function getWhere_Array(array $where = array(),$paginated=false){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->tablename);
        $select->where($where);
    
        if ($paginated){
                
            $adapter = new DbSelect($select,$this->dbAdapter);
            $paginator = new Paginator($adapter);
                
            return $paginator;
                
        }
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
        }
        return array();
    }
    
    
    public function getById($id){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->tablename);
        $select->where(array('id = ?' => $id));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new HydratingResultSet($this->hydrator, $this->model);
        
        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet->initialize($result);
            if ($resultSet->count() == 1){
                return $resultSet->current();
            }elseif ($resultSet->count() == 0){
                return null;
            }else{
                throw new \Exception("Too many result from request");
            }
        }
        return array();
    }
    
    
    public function getById_Array($id){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->tablename);
        $select->where(array('id = ?' => $id));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        if ($result instanceof ResultInterface && $result->isQueryResult()){
        if ($result->count() == 1){
                return $result->current();
            }elseif ($result->count() == 0){
                return null;
            }else{
                throw new \Exception("Too many result from request"); 
            }
        }
        return array();
    }
    
    public function save($model){
//verifier la classe de l'objet
    
        $time = time();
        $model->setTimemodified($time);
        if (!$model->getId()) $model->setTimecreated($time);
        
        $dataarray = $model->DBArrayCopy();
        
        if ($model->getId()){
            $action = new Update($this->tablename);
    
            $action->set($dataarray);
            $action->where(array('id = ?' => $model->getId()));
        }else{
            $action = new Insert($this->tablename);
            $action->values($dataarray);
        }
    
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    
        if ($result instanceof ResultInterface){
            if ($newId = $result->getGeneratedValue()){
                $model->setId($newId);
            }
    
            return $model;
        }
    
        throw new \Exception("Database error");
    
    }
    
    public function save_Array(array $record){
    
        $time = time();
        $record['timemodified'] = $time;
        if (!isset($record['id'])) $record['timecreated'] = $time;
    
        if (isset($record['id'])){
            $action = new Update($this->tablename);
    
            $action->set($record);
            $action->where(array('id = ?' => $record['id']));
        }else{
            $action = new Insert($this->tablename);
            $action->values($record);
        }
    
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    
        if ($result instanceof ResultInterface){
            if ($newId = $result->getGeneratedValue()){
                $record['id'] = $newId;
            }
                
            return $record;
        }
    
        throw new \Exception("Database error");
    
    }
    
    
    public function delete($object){
        
        $id = $object;
        
        $sql = new Sql($this->dbAdapter);
        $action = new Delete($this->tablename);
        $action->where(array('id = ?' => $id));
        
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        //verifier result
    }
    
    public function deleteWhere($where){
        
        $sql = new Sql($this->dbAdapter);
        $action = new Delete($this->tablename);
        $action->where($where);
error_log($action->getSqlString());    
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        //verifier result
    }

    
    public function countWhere(array $where = array()){
        
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->tablename);
        $select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT(*)')));
        $select->where($where);
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        return ($result->current()['count']);

    }
    
}


?>