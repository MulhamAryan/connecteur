<?php 

namespace Admin\Model;

class TextFilterModel {
    
    protected $id;
    protected $filter; 
    protected $entry;
    
    protected $valuearray = array(
                                "0" => array ("ISEQUAL" => "= ?"),
                                "1" => array ("CONTAIN" => "LIKE %?%"),
                                "2" => array ("BEGINWITH" => "LIKE ?%"),
                                "3" => array ("ENDWITH" => "LIKE %?"));
        
    public function getSqlWhereArray(){
        
    }
    
}
?>