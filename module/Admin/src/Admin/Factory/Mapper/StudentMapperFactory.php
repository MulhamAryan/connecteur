<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\StudentMapper;
use Admin\Model\StudentModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class StudentMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new StudentMapper($serviceLocator->get('db_local'),
                new ClassMethods(false),
                new StudentModel()
                );
    }
}

?>