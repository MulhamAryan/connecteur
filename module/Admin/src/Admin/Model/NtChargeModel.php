<?php 

namespace Admin\Model;

class NtChargeModel {
    
    protected $id;
    protected $netid;
    protected $firstname;
    protected $lastname;
    protected $nre;
    protected $term;
    protected $timecreated;
    protected $timemodified;
    protected $manually_modified;
    protected $diff_from_source;
    protected $createdby;
    
    
    public function exchangeArray($data){
        
        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->netid = (isset($data['netid'])) ? $data['netid'] :null;
        $this->firstname = (isset($data['firstname'])) ? $data['firstname'] :null;
        $this->lastname = (isset($data['lastname'])) ? $data['lastname'] :null;
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
    
    public function getNetid(){
        return $this->netid;
    }
    
    public function getFirstname(){
        return $this->firstname;
    }
    
    public function getLastname(){
        return $this->lastname;
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

    public function setNetid($netid){
        $this->netid = $netid;
    }
    
    public function setFirstname($firstname){
        $this->firstname = $firstname;
    }
    
    public function setLastname($lastname){
        $this->lastname = $lastname;
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