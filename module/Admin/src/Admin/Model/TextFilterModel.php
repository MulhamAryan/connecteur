<?php 

namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class TextFilterModel /*implements InputFilterAwareInterface */{
    
    protected $filter;
    protected $entry;  
    
    protected $inputFilter;
    
    public function setInputFilter(InputFilterInterface $inputFilter){
        throw new \Exception("Not used");
    }
    
    
    public function getFilter(){
        return $this->filter;
    }
    
    public function getEntry(){
        return $this->entry;
    }

    
    public function setfilter($filter){
        $this->filter = $filter;
    }
    
    public function setentry($entry){
        $this->entry = $entry;
    }
    
}
?>