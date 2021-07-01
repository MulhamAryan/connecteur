<?php 

namespace Admin\Model;

class FacultyModel {
    
    public $id;
    public $code;  
    public $name;
    public $timecreated;
    public $timemodified;
    public $manually_modified;
    public $diff_from_source;
    public $createdby;
    
    
    public function exchangeArray($data){
        
        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->code = (isset($data['code'])) ? $data['code'] :null;
        $this->name = (isset($data['name'])) ? $data['name'] :null;
        $this->timecreated = (isset($data['timecreated'])) ? $data['timecreated'] :null;
        $this->timemodified = (isset($data['timemodified'])) ? $data['timemodified'] :null;        
        $this->manually_modified = (isset($data['manually_modified'])) ? $data['manually_modified'] :null;
        $this->diff_from_source = (isset($data['diff_from_source'])) ? $data['diff_from_source'] :null;
        $this->createdby = (isset($data['createdby'])) ? $data['createdby'] :null;
        
    }
    
    
    public function getId(){
        return $this->id;
    }
    
    public function getCode(){
        return $this->code;
    }
    
    public function getName(){
        return $this->name;
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
    
    public function setCode($code){
        $this->code = $code;
    }
    
    public function setName($name){
        $this->name = $name;
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