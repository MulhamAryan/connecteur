<?php
namespace Admin\Service;

use Admin\Mapper\GradeMapper;
use Admin\Mapper\Banner\BannerGradeMapper;

class SyncGradesService {
    
    protected $gradeMapper;
    protected $bannerGradeMapper;
    
    public function __construct(GradeMapper $gradeMapper, BannerGradeMapper $bannerGradeMapper){
    
        $this->gradeMapper = $gradeMapper;
        $this->bannerGradeMapper = $bannerGradeMapper;
    
    }
    
    public function check($term, $nre = null){
        ini_set('memory_limit', '512M');
        //TODO

        if (is_null($nre)){
            $results = $this->bannerGradeMapper->getStudentsEnrollmentsByTerm_Array($term);
        }else{
            $results = $this->bannerGradeMapper->getStudentsEnrollmentsByTermAndNre_Array($term,$nre);
        }

        $stats = array('insert' => 0,'update' => 0,'delete' => 0);
        
        $this->gradeMapper->createTemporaryTable();
        $this->gradeMapper->loadTemporaryTable_Array($results);
        $this->gradeMapper->clearUpdatesTable();
        $results = $this->gradeMapper->compareTablesWhere(array("term = '".$term."'","nre = '".$nre."'"));
        $this->gradeMapper->loadUpdatesTable_Array($results);                    
        $updates = $this->gradeMapper->getAllUpdates_Array();

        foreach ($updates as $update){
            $res = $this->gradeMapper->getUpdatesWhere_Array(array('sourcedid = '.$update['sourcedid'],'nre = '.$update['nre'],'term = '.$update['term'],'session = '.$update['session']));
            if (($res->count()==2) && ($update['origine'] == 2)){
                $tmp = $this->gradeMapper->getUpdateWhere_Array(array('origine = 1','sourcedid = '.$update['sourcedid'],'nre = '.$update['nre'],'term = '.$update['term'],'session = '.$update['session']));
                $update['origid'] = $tmp['origid'];
                $update['action'] = 2;
                $this->gradeMapper->saveUpdate_Array($update->getArrayCopy());
                $stats['update']++;
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
        
        $updates = $this->gradeMapper->getAllUpdates_Array();
        return $updates;
    }
    
    public function apply(){
        
        $updates = $this->gradeMapper->getAllUpdates_Array();
        $stats = array('insert' => 0,'update' => 0,'delete' => 0);
        foreach ($updates as $update){
            $action = $update['action'];
            $origid = $update['origid'];
            unset($update['action']);
            unset($update['origine']);
            unset($update['origid']);
            
            if ($action == 1){
                unset($update['id']);
                $this->gradeMapper->save_Array($update->getArrayCopy());
            }elseif ($action == 2){
                $this->gradeMapper->save_Array($update->getArrayCopy());
            }elseif ($action == 3){
                $this->gradeMapper->delete($origid);
            }
//TODO set done to 1 : de cette maniere on peut consulte les updates effectues mais plus les faire une seconde fois
        }
        return $stats;
        
    }
}