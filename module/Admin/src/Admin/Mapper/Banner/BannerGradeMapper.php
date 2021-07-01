<?php 

namespace Admin\Mapper\Banner;

use Admin\Model\GradeModel;
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

class BannerGradeMapper {
    
    protected $dbAdapter;
    protected $hydrator;
    protected $gradeModel;
    
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, GradeModel $gradeModel){
        
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->gradeModel = $gradeModel;
    }
    
    public function getGradesByTermAndNre_Array($term,$nre){
        
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('n' => 'ULB_CURSUS.VW_NOTES_COURS'));
        $select->columns(array(
                'bannerid' => 'BANNER_ID',
                'grade' => 'QUALITY_POINTS',
                'grade_code' => 'GRDE_CODE',
                'type' => 'NOTE_TYPE',
                'nre' => 'CRN',
                'term' => 'TERM_CODE',
                'grade_term' => 'TERM_CODE_NOTE',
                'session' => 'SESSION_NUM'));  
        $select->join(array('g' => 'GOBSRID'),'n.PIDM = g.GOBSRID_PIDM',array('sourcedid' => 'GOBSRID_SOURCED_ID'),$select::JOIN_INNER);
        $select->where(array(
                'n.TERM_CODE_NOTE = ?' => $term, // ou TERM_CODE_NOTE 
                'n.CRN = ?' => $nre)); //TYPE_NOTE = NS note de session
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;

        }
        
        return array();
    }
    
    
    public function getGradesByNreAndSourcedidIn_Array($nre,$sourcedidsstring){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('n' => 'ULB_CURSUS.VW_NOTES_COURS'));
        $select->columns(array(
                'bannerid' => 'BANNER_ID',
                'grade' => 'QUALITY_POINTS',
                'grade_code' => 'GRDE_CODE',
                'type' => 'TYPE_NOTE',
                'nre' => 'CRN',
                'term' => 'TERM_CODE',
                'grade_term' => 'TERM_CODE_NOTE',
                'sessionnum' => 'SESSION_NUM'));
        $select->join(array('g' => 'GOBSRID'),'n.PIDM = g.GOBSRID_PIDM',array('sourcedid' => 'GOBSRID_SOURCED_ID'),$select::JOIN_INNER);
        $select->where(array(
                'n.CRN = ?' => $nre));
        $select->where->in('GOBSRID_SOURCED_ID',$sourcedidsstring);
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();    
        if ($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            return $resultSet;
        }
    
        return array();
    }
}


?>