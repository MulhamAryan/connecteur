<?php 
namespace Admin\Factory\Plugin;

use Admin\Controller\Plugin\MyLogger;
use Zend\Log\Writer\Db;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MyLoggerFactory implements FactoryInterface{
    
public function createService(ServiceLocatorInterface $serviceLocator)
     {

         $mapping = array(
                 'timestamp' => 'time',
                 'priority' => 'type',
                 'message' => 'message',
                 'extra' => array(
                         'processid' => 'processid',
                         'userid' => 'userid',
                         'module' => 'module'));
         
         $adapter = $serviceLocator->getServiceLocator()->get('db_local');
         $logger = new MyLogger($serviceLocator->get('params'));
        $writer = new Db($adapter,'logs',$mapping);
        $logger->addWriter($writer);

        return $logger;
     }
    
} 


?>