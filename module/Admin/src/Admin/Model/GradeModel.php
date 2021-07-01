<?php 

namespace Admin\Model;


class GradeModel {
    
    protected $id;
    protected $sourcedid;
    protected $bannerid;  
    protected $grade;
    protected $grade_code;
    protected $nre;
    protected $term;
    protected $sessionnum;
    protected $resourceid;
    protected $source;
    protected $timecreated;
    protected $timemodified;
    protected $manually_modified;
    protected $diff_from_source;
    protected $createdby;

    
    public function exchangeArray($data){
        
        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->sourcedid = (isset($data['sourcedid'])) ? $data['sourcedid'] :null;
        $this->bannerid = (isset($data['bannerid'])) ? $data['bannerid'] :null;
        $this->grade = (isset($data['grade'])) ? $data['grade'] :null;
        $this->grade_code = (isset($data['grade_code'])) ? $data['grade_code'] :null;
        $this->nre = (isset($data['nre'])) ? $data['nre'] :null;
        $this->term = (isset($data['term'])) ? $data['term'] :null;
        $this->sessionnum = (isset($data['session'])) ? $data['session'] :null;
        $this->resourceid = (isset($data['resourceid'])) ? $data['resourceid'] :null;
        $this->source = (isset($data['source'])) ? $data['source'] :null;
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
    
    public function getGrade(){
        return $this->grade;
    }
    
    public function getGrade_code(){
        return $this->grade_code;
    }
    
    public function getNre(){
        return $this->nre;
    }
    
    public function getTerm(){
        return $this->term;
    }
    
    public function getSessionnum(){
        return $this->session;
    }
    
    public function getResourceid(){
        return $this->resourceid;
    }
    
    public function getSource(){
        return $this->source;
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
    
    public function setGrade($grade){
        $this->grade = $grade;
    }
    
    public function setGrade_code($grade_code){
        $this->grade_code = $grade_code;
    }
    
    public function setNre($nre){
        $this->nre = $nre;
    }
    
    public function setTerm($term){
        $this->term = $term;
    }
    
    public function setSessionnum($session){
        $this->session = $session;
    }
    
    public function setResourceid($resourceid){
        $this->resourceid = $resourceid;
    }
    
    public function setSource($source){
        $this->source = $source;
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