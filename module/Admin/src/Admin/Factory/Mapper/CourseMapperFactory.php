<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\CourseMapper;
use Admin\Model\CourseModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CourseMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new CourseMapper($serviceLocator->get('db_local'),
                new ClassMethods(false),
                new CourseModel()
                );
    }
}

?>