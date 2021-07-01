<?php 

namespace Admin\Model;

class InscriptionModel {
    
    protected $id;
    protected $sourcedid;
    protected $bannerid;  
    protected $code;
    protected $faculty;
    protected $term;
    protected $sessionnum;
    protected $status;
    protected $status_p;
    protected $echecs;
    protected $credits_acquis;
    protected $timecreated;
    protected $timemodified;
    protected $manually_modified;
    protected $diff_from_source;
    protected $createdby;
    
    
    public function exchangeArray($data){
        
        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->soucedid = (isset($data['sourcedid'])) ? $data['sourcedid'] :null;
        $this->bannerid = (isset($data['bannerid'])) ? $data['bannerid'] :null;
        $this->code = (isset($data['code'])) ? $data['code'] :null;
        $this->faculty = (isset($data['faculty'])) ? $data['faculty'] :null;
        $this->term = (isset($data['term'])) ? $data['term'] :null;
        $this->sessionnum = (isset($data['sessionnum'])) ? $data['sessionnum'] :null;
        $this->status = (isset($data['status'])) ? $data['status'] :null;
        $this->status_p = (isset($data['status_p'])) ? $data['status_p'] :null;
        $this->echecs = (isset($data['echecs'])) ? $data['echecs'] :null;
        $this->credits_acquis = (isset($data['credits_acquis'])) ? $data['credits_acquis'] :null;
        $this->timecreated = (isset($data['timecreated'])) ? $data['timecreated'] :null;
        $this->timemodified = (isset($data['timemodified'])) ? $data['timemodified'] :null;        
        $this->manually_modified = (isset($data['manually_modified'])) ? $data['manually_modified'] :null;
        $this->diff_from_source = (isset($data['diff_from_source'])) ? $data['diff_from_source'] :null;
        $this->createdby = (isset($data['createdby'])) ? $data['createdby'] :null;
        
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getSoucedid(){
        return $this->sourcedid;
    }
    
    public function getBannerid(){
        return $this->bannerid;
    }
    
    public function getCode(){
        return $this->code;
    }
    
    public function getFaculty(){
        return $this->faculty;
    }
    
    public function getTerm(){
        return $this->term;
    }
    
    public function getSessionnum(){
        return $this->sessionnum;
    }
    
    public function getStatus(){
        return $this->status;
    }
    
    public function getStatus_p(){
        return $this->status_p;
    }
    
    public function getEchecs(){
        return $this->echecs;
    }
    
    public function getCredits_acquis(){
        return $this->credits_acquis;
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
    
    public function setCode($code){
        $this->code = $code;
    }
    
    public function setFaculty($faculty){
        $this->faculty = $faculty;
    }

    public function setTerm($term){
        $this->term = $term;
    }
    
    public function setSessionnum($sessionnum){
        $this->sessionnum = $sessionnum;
    }
    
    public function setStatus($status){
        $this->status = $status;
    }
    
    public function setStatus_p($status_p){
        $this->status_p = $status_p;
    }
    
    public function setEchecs($echecs){
        $this->echecs = $echecs;
    }
    
    public function setCredits_acquis($credits_acquis){
        $this->credits_acquis = $credits_acquis;
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