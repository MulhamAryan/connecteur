<?php 

namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class RoleAssignementModel /*implements InputFilterAwareInterface*/ {
    
    protected $id;
    protected $roleid;
    protected $userid;
    
    protected $inputFilter;
    
    
    public function exchangeArray($data){
        
        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->roleid = (isset($data['roleid'])) ? $data['roleid'] :null;
        $this->userid = (isset($data['userid'])) ? $data['userid'] :null;
        
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter){
        throw new \Exception("Not used");
    }
    
    
    public function getId(){
        return $this->id;
    }
    
    public function getRoleid(){
        return $this->roleid;
    }
    
    public function getUserid(){
        return $this->userid;
    }
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function setRoleid($roleid){
        $this->roleid = $roleid;
    }
    
    public function setUserid($userid){
        $this->userid = $userid;
    }

}
?>