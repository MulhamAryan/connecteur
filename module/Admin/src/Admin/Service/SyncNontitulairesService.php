<?php
namespace Admin\Service;

use Admin\Mapper\NontitulaireMapper;
use Admin\Mapper\NtChargeMapper;
use Admin\Mapper\Banner\BannerNontitulaireMapper;
use Admin\Mapper\Dcor\DcorMapper;
use Admin\Mapper\StudentMapper;
use Admin\Mapper\TeacherMapper;
use Admin\Controller\Plugin\MyLogger;

class SyncNontitulairesService {
    
    protected $nontitulaireMapper;
    protected $bannerNontitulaireMapper;
    protected $dcorMapper;
    protected $studentMapper;
    protected $teacherMapper;
    protected $ntchargeMapper;
    protected $logger;
    
    public function __construct(
            NontitulaireMapper $nontitulaireMapper,
            BannerNontitulaireMapper $bannerNontitulaireMapper,
            DcorMapper $dcorMapper,
            StudentMapper $studentMapper,
            TeacherMapper $teacherMapper,
            NtChargeMapper $ntchargeMapper,
            MyLogger $logger){
    
        $this->nontitulaireMapper = $nontitulaireMapper;
        $this->bannerNontitulaireMapper = $bannerNontitulaireMapper;
        $this->dcorMapper = $dcorMapper;
        $this->studentMapper = $studentMapper;
        $this->teacherMapper = $teacherMapper;
        $this->ntchargeMapper = $ntchargeMapper;
        $this->logger = $logger;
    
    }    
    
    
    public function check($term){
        ini_set('memory_limit', '512M');
        //TODO
        $logger = $this->logger;
        $nbbannerentries = 0;
        $stats['nontitulaires'] = array('insert' => 0,'update' => 0,'delete' => 0);
        $stats['ntcharges'] = array('insert' => 0,'update' => 0,'delete' => 0);
        $this->nontitulaireMapper->clearUpdatesTable();
        $this->nontitulaireMapper->createTemporaryTable();
        
        $this->ntchargeMapper->clearUpdatesTable();
        $this->ntchargeMapper->createTemporaryTable();
        
        $results = $this->bannerNontitulaireMapper->getNontitulairesByTerm_Array($term);
        $netidinfos = array();
        foreach($results as $result){
            //add matricule and/or bannerid from dcor
            $netid = $result['NETID'];
            $nbbannerentries++;
            if(array_key_exists($netid,$netidinfos)){
                $nt = $netidinfos[$netid];
            }else{
                $nt = array();
                $nt['netid'] = $netid;
                $nt['firstname'] = $result['FIRSTNAME'];
                $nt['lastname'] = $result['LASTNAME'];
                $dcorres = $this->dcorMapper->getPersonByNetid_Array($netid);
                $count = $dcorres->count(); 
                if ($count == 1){
                    $dcorperson = $dcorres->current();
                    $nt['matricule'] = $dcorperson['matricule'];
                }else if($count > 1){
                    // TODO error
                    //$logger->warn("Multiple entries in DCOR perso for netid : ".$netid."(".$count.")");
                    echo "Multiple entries in DCOR perso for netid : ".$netid."(".$count.")\n";
                    continue;
                }
            
                $dcorres = $this->dcorMapper->getStudentByNetid_Array($netid);
                $count = $dcorres->count();
                if ($count == 1){
                    $dcorperson = $dcorres->current();
                    $nt['bannerid'] = $dcorperson['matricule']; //dcor matricule == bannerid pour un etudiant
                }else if($count > 1){
                    // TODO error
                    echo "Multiple or no entries in DCOR students for netid : ".$netid."(".$count.")\n";
                    continue;
                }    

                if (!isset($nt['matricule']) && !isset($nt['bannerid'])){
                    $nt = null;
                    echo "No infos for netid : ".$netid."\n";
                }else{
                    //this entry already exist in table "nontitulaires" ?
                    $dbentry = $this->nontitulaireMapper->get_Array($nt);
                    if($dbentry != null){   //entry found in "nontitulaires" : must be updated ?
                        if((empty($dbentry['matricule']) && !empty($nt['matricule'])) || (empty($dbentry['bannerid']) && !empty($nt['bannerid']))){
                            //update mais on ne verfifie pas les updates dans les champs firstname et lastname
                            $updateentry = $nt;
                            $updateentry['origid'] = $dbentry['id'];
                            $updateentry['action'] = '2';
                            $this->nontitulaireMapper->saveUpdate_Array($updateentry);
                        
                            $entry = $nt;
                            $entry['id'] = $dbentry['id'];
                            $this->nontitulaireMapper->save_Array($entry);
                        
                            unset($updateentry);
                            unset($entry);
                            $stats['nontitulaires']['update']++;
                        }
                        $nt['id'] = $dbentry['id'];
                    
                    }else{ //create an entry
                        $updateentry = $nt;
                        $updateentry['action'] = '1';
                        $this->nontitulaireMapper->saveUpdate_Array($updateentry);
                    
                        $entry = $nt;
                        $nt = $this->nontitulaireMapper->save_Array($entry);
                    
                        unset($updateentry);
                        unset($entry);
                        $stats['nontitulaires']['insert']++;
                    } //rem : on ne retire aucuns entrees comme pour "teachers" et "students"
                }
                $netidinfos[$netid] = $nt;                
            }            
               
            if($nt == null)continue;
            $entry = array();
            $entry['ntid'] = $nt['id'];
            $entry['nre'] = $result['NRE'];
            $entry['term'] = $result['TERM'];
            $entry['netid'] = $result['NETID'];
            $entry['firstname'] = $result['FIRSTNAME'];
            $entry['lastname'] = $result['LASTNAME'];
            
            $this->ntchargeMapper->saveTemporary_Array($entry);
            
        }
                 
        $results = $this->ntchargeMapper->compareTablesWhere(array('term = "'.$term.'"'));

        $this->ntchargeMapper->loadUpdatesTable_Array($results);
        $updates = $this->ntchargeMapper->getAllUpdates_Array();
echo $nbbannerentries."\n";
exit(0);        
        foreach ($updates as $update){
            $res = $this->ntchargeMapper->getUpdatesWhere_Array(array('ntid = "'.$update['ntid'].'"', 'nre = '.$update['nre']));
            if (($res->count()==2) && ($update['origine'] == 2)){
                $tmp = $this->ntchargeMapper->getUpdateWhere_Array(array('origine = 1','ntid = "'.$update['ntid'].'"', 'nre = '.$update['nre']));
                $update['origid'] = $tmp['origid'];
                $update['action'] = 2;
                $tmp['action'] = 0;
                $this->ntchargeMapper->saveUpdate_Array($update->getArrayCopy());
                $this->ntchargeMapper->saveUpdate_Array($tmp);
                $stats['ntcharges']['update']++;
            }elseif ($res->count()==1){
                if ($update['origine'] == 1){
                    $stats['ntcharges']['delete']++;
                }
                else {
                    $stats['ntcharges']['insert']++;
                }
            }
        }           
        return($stats);
    }
    
    
    
    public function display(){
        
        $updates = $this->ntchargeMapper->getAllUpdates_Array();
        return $updates;
    }
    
    
    public function apply(){
        
        $stats = array('insert' => 0,'update' => 0,'delete' => 0);
        
        $updates = $this->ntchargeMapper->getAllUpdates_Array();
        foreach ($updates as $update){
            $action = $update['action'];
            $origid = $update['origid'];
            unset($update['action']);
            unset($update['origine']);
            unset($update['origid']);
            
            if ($action == 1){
                unset($update['id']);
                $this->ntchargeMapper->save_Array($update->getArrayCopy());
                $stats['insert']++;
            }elseif ($action == 2){
                $update['id'] = $origid;
                $this->ntchargeMapper->save_Array($update->getArrayCopy());
                $stats['update']++;
            }elseif ($action == 3){
                $this->ntchargeMapper->delete($update['id']);
                $stats['delete']++;
            }
        }
        return $stats;
        
    }
}