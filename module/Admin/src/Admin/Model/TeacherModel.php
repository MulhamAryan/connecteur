<?php

namespace Admin\Model;

class TeacherModel {

    protected $id;
    protected $netid;
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $bannerid;
    protected $timecreated;
    protected $timemodified;

    public function exchangeArray($data){

        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->netid = (isset($data['netid'])) ? $data['netid'] :null;
        $this->firstname = (isset($data['firstname'])) ? $data['firstname'] :null;
        $this->lastname = (isset($data['lastname'])) ? $data['lastname'] :null;
        $this->email = (isset($data['email'])) ? $data['email'] :null;
        $this->bannerid = (isset($data['bannerid'])) ? $data['bannerid'] :null;
        $this->timecreated = (isset($data['timecreated'])) ? $data['timecreated'] :null;
        $this->timemodified = (isset($data['timemodified'])) ? $data['timemodified'] :null;
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

    public function getEmail(){
        return $this->email;
    }

    public function getBannerid(){
        return $this->bannerid;
    }

    public function getTimecreated(){
        return $this->timecreated;
    }

    public function getTimemodified(){
        return $this->timemodified;
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

    public function setEmail($email){
        $this->email = $email;
    }

    public function setBannerid($bannerid){
        $this->bannerid = $bannerid;
    }

    public function setTimecreated($timecreated){
        $this->timecreated = $timecreated;
    }

    public function setTimemodified($timemodified){
        $this->timemodified = $timemodified;
    }
}
