<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\TeacherMapper;
use Admin\Model\TeacherModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class TeacherMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new TeacherMapper($serviceLocator->get('db_local'),
                new ClassMethods(false),
                new TeacherModel()
                );
    }
}

?>