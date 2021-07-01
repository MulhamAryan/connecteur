<?php 

namespace Admin\Mapper\Banner;

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

class BannerNontitulaireMapper {
    
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
    public function getNontitulairesByTerm_Array($term){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->quantifier('DISTINCT');
        $select->from(array('e' => 'ULB_CURSUS.VW_NON_TITULAIRES'));
        $select->columns(array(
                'netid' => 'NETID',
                'firstname' => 'YCRNTID_FIRST_NAME',
                'lastname' => 'YCRNTID_LAST_NAME',
                'nre' => 'CRN',
                'term' => 'TERM_CODE'));
        $select->where(array(
                'TERM_CODE = ?' => $term,
                ));
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
    
        if ($result instanceof ResultInterface && $result->isQueryResult()){
            return $result;
        }
        return array();
    }
            
    
}


?>