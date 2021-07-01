<?php 

namespace Admin\Model;

class PermissionModel {
    
    protected $id;
    public $privilege;  
    public $resource;
    public $rolename;
    
    
    public function exchangeArray($data){
        
        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->privilege = (isset($data['privilege'])) ? $data['privilege'] :null;
        $this->resource = (isset($data['resource'])) ? $data['resource'] :null;
        $this->rolename = (isset($data['rolename'])) ? $data['rolename'] :null;

    }
    
    
    public function getId(){
        return $this->id;
    }
    
    public function getPrivilege(){
        return $this->privilege;
    }
    
    public function getResource(){
        return $this->resource;
    }
    
    public function getRolename(){
        return $this->rolename;
    }

    
    public function setId($id){
        $this->id = $id;
    }
    
    public function setPrivilege($privilege){
        $this->privilege = $privilege;
    }
    
    public function setResource($resource){
        $this->resource = $resource;
    }
    
    public function setRolename($rolename){
        $this->rolename = $rolename;
    }
    

}
?>