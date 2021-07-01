<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\NtChargeMapper;
use Admin\Model\NtChargeModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NtChargeMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new NtChargeMapper($serviceLocator->get('db_local'),
                new ClassMethods(false),
                new NtChargeModel()
                );
    }
}

?>