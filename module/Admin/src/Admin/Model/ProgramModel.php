<?php 

namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class ProgramModel implements InputFilterAwareInterface {
    
    protected $id;
    protected $code;
    protected $title;
    protected $faculty;
    protected $term;
    protected $timecreated;
    protected $timemodified;
    protected $manually_modified;
    protected $diff_from_source;
    
    protected $inputFilter;
    
    
    public function exchangeArray($data){
        
        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->code = (isset($data['code'])) ? $data['code'] :null;
        $this->title = (isset($data['title'])) ? $data['title'] :null;
        $this->faculty = (isset($data['faculty'])) ? $data['faculty'] :null;
        $this->term = (isset($data['term'])) ? $data['term'] :null;    
        $this->timecreated = (isset($data['timecreated'])) ? $data['timecreated'] :null;
        $this->timemodified = (isset($data['timemodified'])) ? $data['timemodified'] :null;        
        $this->manually_modified = (isset($data['manually_modified'])) ? $data['manually_modified'] :null;
        $this->diff_from_source = (isset($data['diff_from_source'])) ? $data['diff_from_source'] :null;
        
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter){
        throw new \Exception("Not used");
    }
    
    
    public function getId(){
        return $this->id;
    }

    public function getCode(){
        return $this->code;
    }

    public function getTitle(){
        return $this->title;
    }
    
    public function getFaculty(){
        return $this->faculty;
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

    
    
    
    public function setId($id){
        $this->id = $id;
    }

    public function setCode($code){
        $this->code = $code;
    }

    public function setTitle($title){
        $this->title = $title;
    }
    
    public function getFaculty($faculty){
        $this->role = $faculty;
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
    
}
?>