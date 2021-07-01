<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\PermissionMapper;
use Admin\Model\PermissionModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class PermissionMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new PermissionMapper($serviceLocator->get('db_local'),
                new ClassMethods(false),
                new PermissionModel()
                );
    }
}

?>