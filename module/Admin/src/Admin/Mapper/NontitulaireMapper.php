<?php 

namespace Admin\Mapper;

use Admin\Model\NontitulaireModel;
use Admin\Mapper\AbstractMapper;
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

class NontitulaireMapper extends AbstractMapper {
    
    protected $dbAdapter;
    protected $hydrator;
    protected $model;
    
    protected $tablename = 'nontitulaires';
    protected $temptablename = 'nontitulaires_tmp';
    protected $updatestablename = 'nontitulaires_updates';
    protected $frombanner = array(
            'netid',
            'firstname',
            'lastname',
            'matricule',
            'bannerid');
    
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, NontitulaireModel $nontitulaireModel){
        
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->model = $nontitulaireModel;
    }
    
    
    public function get_Array($entry){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('nontitulaires');
        $select->where(array(
                'netid = ?' => $entry['netid'],
        ));
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $results = $stmt->execute();
    
        if ($results instanceof ResultInterface && $results->isQueryResult()) {
            if ($results->count() > 0){
                $dbentry = null;
                foreach($results as $result){
                    if(!empty($result['matricule']) && $result['matricule'] == $entry['matricule']){
                        $dbentry = $result;
                        break;
                    }else if(!empty($result['bannerid']) && $result['bannerid'] == $entry['bannerid']){ //matricule == null
                        $dbentry = $result;
                    }
                } //pour le cas bannerid == null, matricule == xxxx <=> bannerid = xxxxxxx, matricule = null, pas possible de faire un match => 2 entrees pour une meme personne
                return $dbentry;
            }else {
                return null;
            }
        }
    }
    
    
    public function getByTerm_Array($term){
    
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(array( 'nt' => 'nontitulaires'));
        $select->join(array('ntc' => 'ntcharges'),'ntc.ntid = nt.id',array(),$select::JOIN_INNER);
        $select->where(array(
                'ntc.term = ?' => $term,
        ));
    
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        if($result->count() == 0){
            return array();
        }else{
            return $result;
        }
    }
    
    
}


?>