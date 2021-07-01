<?php
namespace Admin\Service;

use Admin\Mapper\NtChargeMapper;
use Admin\Mapper\Banner\BannerNtChargeMapper;

class SyncNtChargesService {
    
    protected $ntChargeMapper;
    protected $bannerNtChargeMapper;
    
    public function __construct(NtChargeMapper $ntChargeMapper, BannerNtChargeMapper $bannerNtChargeMapper){
    
        $this->ntChargeMapper = $ntChargeMapper;
        $this->bannerNtChargeMapper = $bannerNtChargeMapper;
    
    }    
    
    
    public function check($term){
        ini_set('memory_limit', '512M');
        //TODO
        
        $results = $this->bannerNtChargeMapper->getNtChargesByTerm_Array($term);

        $stats = array('insert' => 0,'update' => 0,'delete' => 0);        
        
        $this->ntChargeMapper->createTemporaryTable();
        $this->ntChargeMapper->loadTemporaryTable_Array($results);
        $this->ntChargeMapper->clearUpdatesTable();
        $results = $this->ntChargeMapper->compareTablesWhere(array('term = '.$term));
        $this->ntChargeMapper->loadUpdatesTable_Array($results);                    
        $updates = $this->ntChargeMapper->getAllUpdates_Array();

        foreach ($updates as $update){
            $res = $this->ntChargeMapper->getUpdatesWhere_Array(array('netid = "'.$update['netid'].'"', 'nre = '.$update['nre']));
            if (($res->count()==2) && ($update['origine'] == 2)){
                $tmp = $this->ntChargeMapper->getUpdateWhere_Array(array('origine = 1','netid = "'.$update['netid'].'"', 'nre = '.$update['nre']));
                $update['origid'] = $tmp['origid'];
                $update['action'] = 2;
                $tmp['action'] = 0;
                $this->ntChargeMapper->saveUpdate_Array($update->getArrayCopy());
                $this->ntChargesMapper->saveUpdate_Array($tmp);
                $stats['update']++;            //TODO normalement il n'y a pas d'update, seulement des inserts ou deletes
            }elseif ($res->count()==1){
                if ($update['origine'] == 1){
                    $stats['delete']++;
                }
                else {
                    $stats['insert']++;
                }
            }
        }            
        return($stats);
    }
    
    
    
    public function display(){
        
        $updates = $this->ntChargeMapper->getAllUpdates_Array();
        return $updates;
    }
    
    
    public function apply(){
        
        $stats = array('insert' => 0,'update' => 0,'delete' => 0);
        
        $updates = $this->ntChargeMapper->getAllUpdates_Array();
        foreach ($updates as $update){
            $action = $update['action'];
            $origid = $update['origid'];
            unset($update['action']);
            unset($update['origine']);
            unset($update['origid']);
            
            if ($action == 1){
                unset($update['id']);
                $this->ntChargeMapper->save_Array($update->getArrayCopy());
                $stats['insert']++;
            }elseif ($action == 2){
                $update['id'] = $origid;
                $this->ntChargeMapper->save_Array($update->getArrayCopy());
                $stats['update']++;
            }elseif ($action == 3){
                // loguer !!!!!!
                //$this->teacherMapper->delete($origid);
                $stats['delete']++;
            }
//TODO set done to 1 : de cette maniere on peut consulte les updates effectues mais plus les faire une seconde fois
        }
        return $stats;
        
    }
}