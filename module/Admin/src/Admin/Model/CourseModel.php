<?php

namespace Admin\Model;

class CourseModel {

    public $id;
    public $subjcode;
    public $crsenumb;
    public $title;
    public $term;
    public $faculty;
    public $timecreated;
    public $timemodified;

    public function exchangeArray($data){
        $this->id = (isset($data['id'])) ? $data['id'] :null;
        $this->subjcode = (isset($data['subjcode'])) ? $data['subjcode'] :null;
        $this->crsenumb = (isset($data['crsenumb'])) ? $data['crsenumb'] :null;
        $this->title = (isset($data['title'])) ? $data['title'] :null;
        $this->term = (isset($data['term'])) ? $data['term'] :null;
        $this->faculty = (isset($data['faculty'])) ? $data['faculty'] :null;
        $this->timecreated = (isset($data['timecreated'])) ? $data['timecreated'] :null;
        $this->timemodified = (isset($data['timemodified'])) ? $data['timemodified'] :null;
    }


    public function getId(){
        return $this->id;
    }

    public function getSubjcode(){
        return $this->subjcode;
    }

    public function getCrsenumb(){
        return $this->crsenumb;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getTerm(){
        return $this->term;
    }

    public function getFaculty(){
        return $this->faculty;
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

    public function setSubjcode($subjcode){
        $this->subjcode = $subjcode;
    }

    public function setCrsenumb($crsenumb){
        $this->crsenumb = $crsenumb;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function setTerm($term){
        $this->term = $term;
    }

    public function setFaculty($faculty){
        $this->faculty = $faculty;
    }

    public function setTimecreated($timecreated){
        $this->timecreated = $timecreated;
    }

    public function setTimemodified($timemodified){
        $this->timemodified = $timemodified;
    }
}
