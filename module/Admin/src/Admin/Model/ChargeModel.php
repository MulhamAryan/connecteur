<?php

namespace Admin\Model;

class ChargeModel {

    public $id;
    public $bannerid;
    public $subjcode;
    public $crsenumb;
    public $term;
    public $timecreated;
    public $timemodified;


    public function exchangeArray($data){

        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->bannerid = (isset($data['bannerid'])) ? $data['bannerid'] :null;
        $this->subjcode = (isset($data['subjcode'])) ? $data['subjcode'] :null;
        $this->crsenumb = (isset($data['crsenumb'])) ? $data['crsenumb'] :null;
        $this->term = (isset($data['term'])) ? $data['term'] :null;
        $this->timecreated = (isset($data['timecreated'])) ? $data['timecreated'] :null;
        $this->timemodified = (isset($data['timemodified'])) ? $data['timemodified'] :null;

    }

    public function getId(){
        return $this->id;
    }

    public function getBannerid(){
        return $this->bannerid;
    }

    public function getSubjcode(){
        return $this->subjcode;
    }

    public function getCrsenumb(){
        return $this->crsenumb;
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

    public function setId($id){
        $this->id = $id;
    }

    public function setBannerid($bannerid){
        $this->bannerid = $bannerid;
    }

    public function setSubjcode($subjcode){
        $this->subjcode = $subjcode;
    }

    public function setCrsenumb($crsenumb){
        $this->crsenumb = $crsenumb;
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

}
?>
