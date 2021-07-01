<?php 

namespace Admin\Model;

class EnrollmentModel {
    
    public $id;
    public $sourcedid;
    public $bannerid;  
    public $matricule;
    public $nre;
    public $term;
    public $timecreated;
    public $timemodified;
    public $manually_modified;
    public $diff_from_source;
    public $createdby;
    
    
    public function exchangeArray($data){
        
        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->sourcedid = (isset($data['sourcedid'])) ? $data['sourcedid'] :null;
        $this->bannerid = (isset($data['bannerid'])) ? $data['bannerid'] :null;
        $this->matricule = (isset($data['matricule'])) ? $data['matricule'] :null;
        $this->nre = (isset($data['nre'])) ? $data['nre'] :null;
        $this->term = (isset($data['term'])) ? $data['term'] :null;
        $this->timecreated = (isset($data['timecreated'])) ? $data['timecreated'] :null;
        $this->timemodified = (isset($data['timemodified'])) ? $data['timemodified'] :null;        
        $this->manually_modified = (isset($data['manually_modified'])) ? $data['manually_modified'] :null;
        $this->diff_from_source = (isset($data['diff_from_source'])) ? $data['diff_from_source'] :null;
        $this->createdby = (isset($data['createdby'])) ? $data['createdby'] :null;
        
    }    
    
    public function getId(){
        return $this->id;
    }
    
    public function getSourcedid(){
        return $this->sourcedid;
    }
    
    public function getBannerid(){
        return $this->bannerid;
    }
    
    public function getMatricule(){
        return $this->matricule;
    }
    
    public function getNre(){
        return $this->nre;
    }
    
    public function getTerm(){
        return $this->term;
    }
    
    public function getTimecreated(){
        return $this->timecreated;
    }
    
    public function getTimemodified(){
        return $this->timemodified;
    }
    
    public function getManually_modified(){
        return $this->manually_modified;
    }
    
    public function getDiff_from_source(){
        return $this->diff_from_source;
    }

    public function getCreatedby(){
        return $this->createdby;
    }
    
    
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function setSourcedid($sourcedid){
        $this->sourcedid = $sourcedid;
    }
    
    public function setBannerid($bannerid){
        $this->bannerid = $bannerid;
    }
    
    public function setMatricule($matricule){
        $this->matricule = $matricule;
    }
    
    public function setNre($nre){
        $this->nre = $nre;
    }
    
    public function setTerm($term){
        $this->term = $term;
    }
    
    public function setTimecreated($timecreated){
        $this->timecreated = $timecreated;
    }
    
    public function setTimemodified($timemodified){
        $this->timemodified = $timemodified;
    }
    
    public function setManually_modified($manually_modified){
        $this->manually_modified = $manually_modified;
    }
    
    public function setDiff_from_source($diff_from_source){
        $this->diff_from_source = $diff_from_source;
    }
    
    public function setCreatedby($createdby){
        $this->createdby = $createdby;
    }
    
}
?>