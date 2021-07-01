<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\ChargeMapper;
use Admin\Model\ChargeModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ChargeMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new ChargeMapper($serviceLocator->get('db_local'),
                new ClassMethods(false),
                new ChargeModel()
                );
    }
}

?>