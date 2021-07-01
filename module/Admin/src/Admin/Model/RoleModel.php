<?php 

namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class RoleModel {
    
    protected $id;
    protected $name; 
    protected $inheritfrom;
    protected $description;
    
    
    public function exchangeArray($data){
        
        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->name = (isset($data['name'])) ? $data['name'] :null;
        $this->inheritfrom = (isset($data['inheritfrom'])) ? $data['inheritfrom'] :null;
        $this->description = (isset($data['description'])) ? $data['description'] :null;
        
    }
        
    public function getId(){
        return $this->id;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getInheritfrom(){
        return $this->inheritfrom;
    }
    
    public function getDescription(){
        return $this->description;
    }

    
    public function setId($id){
        $this->id = $id;
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function setInheritfrom($inheritfrom){
        $this->inheritfrom = $inheritfrom;
    }
    
    public function setDescription($description){
        $this->description = $description;
    }
    
}
?>