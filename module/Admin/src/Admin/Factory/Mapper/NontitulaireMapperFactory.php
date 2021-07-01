<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\NontitulaireMapper;
use Admin\Model\NontitulaireModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NontitulaireMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new NontitulaireMapper($serviceLocator->get('db_local'),
                new ClassMethods(false),
                new NontitulaireModel()
                );
    }
}

?>