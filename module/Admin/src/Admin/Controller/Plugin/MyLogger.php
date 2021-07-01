<?php 
namespace Admin\Controller\Plugin;

use Zend\Log\Logger;
use Zend\Mvc\Controller\Plugin\PluginInterface;

class MyLogger extends Logger implements PluginInterface{

    
    /* @var \Zend\Mvc\Controller\Plugin\Params */
    private $paramPlugin;
    
    public function __construct(\Zend\Mvc\Controller\Plugin\Params $paramPlugin)
    {
        $this->paramPlugin = $paramPlugin;
        
        parent::__construct();
    }
    
    
    public function setController(\Zend\Stdlib\DispatchableInterface $controller)
    {
        $this->paramPlugin->setController($controller);
    }
    
    public function getController()
    {
        return $this->paramPlugin->getController();
    }
    
    
}

?>