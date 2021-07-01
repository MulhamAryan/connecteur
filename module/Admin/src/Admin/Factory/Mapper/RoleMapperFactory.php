<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\RoleMapper;
use Admin\Model\RoleModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class RoleMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new RoleMapper($serviceLocator->get('db_local'),
                new ClassMethods(false),
                new RoleModel()
                );
    }
}

?>