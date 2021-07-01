<?php 

namespace Admin\Mapper;

use Admin\Model\CourseModel;
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

abstract class AbstractMapper {
    
    public function getAll($paginated=false){
        
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->tablename);
        
        $resultSet = new HydratingResultSet($this->hydrator/*, $this->model*/);
        
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
            //$resultSet = new ResultSet();
            //return $resultSet->initialize($result);
            return $result;
        }
        return array();
    }
    
    public function getUniqueWhere(array $where = array()){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->tablename);
        $select->where($where);
        
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
    
    
    public function createTemporaryTable(){        
        $sqlstring = "CREATE TABLE ".$this->temptablename." LIKE ".$this->tablename;
        $this->dbAdapter->query($sqlstring,Adapter::QUERY_MODE_EXECUTE);        
    }

    public function dropTemporaryTable(){
        $sqlstring = "DROP TABLE IF EXISTS ".$this->temptablename;
        $this->dbAdapter->query($sqlstring,Adapter::QUERY_MODE_EXECUTE);
    }

    
    public function loadTemporaryTable_Array(ResultSet $records){
        
        $nbentries = 0;
        if ($records != null){
            $sql = new Sql($this->dbAdapter);
            foreach ($records as $record){
                $nbentries++;
                
                $action = new Insert($this->temptablename);
                $action->values($record->getArrayCopy());

                $stmt = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();    
            }
        }
            
        return $nbentries;
    }
    
    
    public function compareTablesWhere($wherearray = null){
        
        $where = "";
        if (! is_null($wherearray)){
            $where = "WHERE ";    
            $where .= implode(" AND ",$wherearray);
        }
        $frombannerstring = join(",",$this->frombanner);
        $sqlstring = "SELECT ".$frombannerstring.",MIN(action) as action, MIN(origine) as origine, MIN(origid) as origid FROM ".
                "(SELECT ".$frombannerstring.", 3 as action, 1 as origine, id as origid FROM ".$this->tablename." ".$where." UNION ALL ".
                "SELECT ".$frombannerstring.", 1 as action, 2 as origine, id as origid FROM ".$this->temptablename." ".$where.") t ".
                "GROUP BY ".$frombannerstring." HAVING COUNT(*) =1";
    
        $results = $this->dbAdapter->query($sqlstring,Adapter::QUERY_MODE_EXECUTE);
//TODO verifier le resultat
    
        $resultSet = new ResultSet();
        $resultSet->initialize($results);            
        return $resultSet;
    
    }
    
    public function clearTable(){
    
        $sql = new Sql($this->dbAdapter);
        $action = new Delete($this->tablename);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        //verifier result
    }

    public function clearUpdatesTable(){
    
        $sql = new Sql($this->dbAdapter);
        $action = new Delete($this->updatestablename);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        //verifier result
    }

    public function clearTemporaryTable() {
        $sql = new Sql($this->dbAdapter);
        $action = new Delete($this->temptablename);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }
    
    public function loadUpdatesTable_Array(ResultSet $records){
        
        $nbentries = 0;
        if ($records != null){
            $sql = new Sql($this->dbAdapter);

            foreach($records as $record){

                $nbentries++;
                $action = new Insert($this->updatestablename);
                
                $action->values($record->getArrayCopy());
    
                $stmt = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();
//TODO verifier $result 

            }
        }
        return $nbentries;
    }
    
    
    public function getAllUpdates_Array($paginated=false){
        
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->updatestablename);
    
        if ($paginated){
            $adapter = new DbSelect($select,$this->dbAdapter);
            $paginator = new Paginator($adapter);
            return $paginator;
        }
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            $resultSet->initialize($result);        
            return $resultSet;
        }
    
        return array();
    
    }
    
    
    public function getUpdatesWhere_Array(array $where = array()){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->updatestablename);
        $select->where($where);
    
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
    
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;
        }
        return array();
    }
    
    
    public function getUpdateWhere_Array(array $where){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->updatestablename);
        $select->where($where);
    
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
    
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
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
        
    
    public function save($recordmodel){
        
        $time = time();
        $recordmodel->setTimemodified($time);
        if (!$recordmodel->getId()) $recordmodel->setTimecreated($time);
        
        $data = $recordmodel->get_attr();
        
        if ($recordmodel->getId()){
            $action = new Update($this->tablename);

            $action->set($data);
            $action->where(array('id = ?' => $recordmodel->getId()));
        }else{
            $action = new Insert($this->tablename);
            $action->values($data);
        }
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        
        if ($result instanceof ResultInterface){
            if ($newId = $result->getGeneratedValue()){
                $recordmodel->setId($newId);
            }
            
            return $recordmodel;
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
    
    
    public function saveUpdate_Array(array $record){
    
        if (isset($record['id'])){
            $action = new Update($this->updatestablename);
    
            $action->set($record);
            $action->where(array('id = ?' => $record['id']));
        }else{
            $action = new Insert($this->updatestablename);
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
    
    
    public function saveTemporary_Array(array $record){
    
        if (isset($record['id'])){
            $action = new Update($this->temptablename);
            $action->set($record);
            $action->where(array('id = ?' => $record['id']));
        }else{
            $action = new Insert($this->temptablename);
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
    
    
    public function delete($id){
    
        $sql = new Sql($this->dbAdapter);
        $action = new Delete($this->tablename);
        $action->where(array('id = ?' => $id));
        
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        //verifier result
    }
    
    
    public function deleteUpdate($id){
    
        $sql = new Sql($this->dbAdapter);
        $action = new Delete($this->updatestablename);
        $action->where(array('id = ?' => $id));
    
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        //verifier result
    }
    
    
    public function countUpdatesWhere(array $where = array()){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select($this->updatestablename);
        $select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT(*)')));
        $select->where($where);
    
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
    
        return ($result->current()['count']);
    
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
