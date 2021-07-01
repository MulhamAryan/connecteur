<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\UserMapper;
use Admin\Model\UserModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class UserMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new UserMapper($serviceLocator->get('db_local'),
                new ClassMethods(false),
                new UserModel()
                );
    }
}

?>