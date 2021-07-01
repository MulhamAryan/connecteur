<?php 

namespace Admin\Mapper\Dcor;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class DcorMapper {
    
    protected $dbAdapter;
    protected $hydrator;
    
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator){
        
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
    }
    
    /**
     * Return
     * @param
     */
    public function getPersonByNetid_Array($netid){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->quantifier('DISTINCT');
        $select->from(array('d' => 'digger_DCOR_PERSON_INFO_tmp'));
        $select->columns(array(
                'netid' => 'netID',
                'firstname' => 'prenom',
                'lastname' => 'nom',
                'email' => 'emailInstit',
                'matricule' => 'matricule'));
        $select->where(array(
                'source = ?' => 'PERSO',
                'profil = ?' => 'B',
                'netID = ?' => $netid)); //b : EMPLOYEE, A : STUDENT
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
    
        if ($result instanceof ResultInterface && $result->isQueryResult()){
            return $result;
        }
        return array();
    }

    
    public function getStudentByNetid_Array($netid){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->quantifier('DISTINCT');
        $select->from(array('d' => 'digger_DCOR_PERSON_INFO_tmp'));
        $select->columns(array(
                'netid' => 'netID',
                'firstname' => 'prenom',
                'lastname' => 'nom',
                'email' => 'emailInstit',
                'matricule' => 'matricule'));
        $select->where(array(
                'source = ?' => 'ULBDB',
                'profil = ?' => 'A',
                'netID = ?' => $netid)); //b : EMPLOYEE, A : STUDENT
    
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
    
        if ($result instanceof ResultInterface && $result->isQueryResult()){
            //$resultSet = new ResultSet();
            //$resultSet->initialize($result);
            //return $resultSet;
            return $result;
        }
        return array();
    }
    
}


?>