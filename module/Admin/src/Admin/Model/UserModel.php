<?php 

namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class UserModel /*implements InputFilterAwareInterface*/ {
    
    protected $id;
    protected $authtype;  
    protected $suspended;  //statut banner ?
    protected $netid;
    protected $password;
    protected $idnumber;
    protected $sourcedid;
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $matricule;
    protected $bannerid;
    protected $academicrole;
    protected $anets;
    protected $faculties;
    protected $lang;
    protected $picture;
    protected $timecreated;
    protected $timemodified;
    protected $manually_modified;
    protected $diff_from_source;
    protected $createdby;
    
    
    protected $inputFilter;
    
    
    public function exchangeArray($data){
        
        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->authtype = (isset($data['authtype'])) ? $data['authtype'] :null;
        $this->suspended = (isset($data['suspended'])) ? $data['suspended'] :null;
        $this->netid = (isset($data['netid'])) ? $data['netid'] :null;
        $this->password = (isset($data['password'])) ? $data['password'] :null;
        $this->idnumber = (isset($data['idnumber'])) ? $data['idnumber'] :null;
        $this->sourcedid = (isset($data['sourcedid'])) ? $data['sourcedid'] :null;
        $this->firstname = (isset($data['firstname'])) ? $data['firstname'] :null;
        $this->lastname = (isset($data['lastname'])) ? $data['lastname'] :null;
        $this->email = (isset($data['email'])) ? $data['email'] :null;
        $this->matricule = (isset($data['matricule'])) ? $data['matricule'] :null;        
        $this->bannerid = (isset($data['bannerid'])) ? $data['bannerid'] :null;
        $this->academicrole = (isset($data['academicrole'])) ? $data['academicrole'] :null;        
        $this->anets = (isset($data['anets'])) ? $data['anets'] :null;
        $this->faculties = (isset($data['faculties'])) ? $data['faculties'] :null;
        $this->lang = (isset($data['lang'])) ? $data['lang'] :null;
        $this->picture = (isset($data['picture'])) ? $data['picture'] :null;
        $this->timecreated = (isset($data['timecreated'])) ? $data['timecreated'] :null;
        $this->timemodified = (isset($data['timemodified'])) ? $data['timemodified'] :null;
        $this->manually_modified = (isset($data['manually_modified'])) ? $data['manually_modified'] :null;
        $this->diff_from_source = (isset($data['diff_from_source'])) ? $data['diff_from_source'] :null;
        $this->createdby = (isset($data['createdby'])) ? $data['createdby'] :null;
        
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter){
        throw new \Exception("Not used");
    }
    
    public function getInputFilter(){
        
        return $this->inputFilter;
    
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getAuthtype(){
        return $this->authtype;
    }
    
    public function getSuspended(){
        return $this->suspended;
    }
    
    public function getNetid(){
        return $this->netid;
    }
    
    public function getPassword(){
        return $this->password;
    }
    
    public function getIdnumber(){
        return $this->idnumber;
    }
    
    public function getSourcedid(){
        return $this->sourcedid;
    }
    
    public function getFirstname(){
        return $this->firstname;
    }
    
    public function getLastname(){
        return $this->lastname;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function getMatricule(){
        return $this->matricule;
    }    
    
    public function getBannerid(){
        return $this->bannerid;
    }
    
    public function getAcademicrole(){
        return $this->academicrole;
    }
    
    public function getAnets(){
        return $this->anets;
    }
    
    public function getFaculties(){
        return $this->faculties;
    }
    
    public function getLang(){
        return $this->lang;
    }
    
    public function getPicture(){
        return $this->picture;
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
    
    public function setAuthtype($authtype){
        $this->authtype= $authtype;
    }
    
    public function setSuspended($suspended){
        $this->suspended = $suspended;
    }
    
    public function setNetid($netid){
        $this->netid = $netid;
    }
    
    public function setPassword($password){
        $this->password = $password;
    }
    
    public function setIdnumber($idnumber){
        $this->idnumber = $idnumber;
    }
    
    public function setSourcedid($sourcedid){
        $this->sourcedid = $sourcedid;
    }
    
    public function setFirstname($firstname){
        $this->firstname = $firstname;
    }
    
    public function setLastname($lastname){
        $this->lastname = $lastname;
    }
    
    public function setEmail($email){
        $this->email = $email;
    }
    
    public function setMatricule($matricule){
        $this->matricule = $matricule;
    }
    
    public function setBannerid($bannerid){
        $this->bannerid = $bannerid;
    }
    
    public function setAcademicrole($academicrole){
        $this->academicrole = $academicrole;
    }
    
    public function setAnets($anets){
        $this->anets = $anets;
    }
    
    public function setFaculties($faculties){
        $this->faculties = $faculties;
    }
    
    public function setLang($lang){
        $this->lang = $lang;
    }
    
    public function setPicture($picture){
        $this->picture = $picture;
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

    
    public function equal(UserModel $user){
        $data = $user->get_attr();        //return array with attr != null
        foreach($data as $key => $value){
            if ($this->$key != $value){
                return(false);
                break;            
            }
        }
        return(true);    
    }
    
    
    public function update(UserModel $user){
        $data = $user->get_attr();
        foreach($data as $key => $value){
            $this->$key = $value;
        }
        return(true);
    }
    
    
    public function get_attr(){
        $data = array();
        foreach ($this as $key => $value){
            if (!is_null($value)){
                $data[$key]=$value;
            }
        }
        unset($data['id']);
        unset($data['inputFilter']);
        return $data;
    }
    
    public function init_attr(){
        if (!isset($this->authtype)) $this->authtype = 'ldap';
        if (!isset($this->suspended)) $this->suspended = 0;
    }

}
?>