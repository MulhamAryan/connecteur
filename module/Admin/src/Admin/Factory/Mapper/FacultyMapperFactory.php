<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\FacultyMapper;
use Admin\Model\FacultyModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class FacultyMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new FacultyMapper($serviceLocator->get('db_local'),
                new ClassMethods(false),
                new FacultyModel()
                );
    }
}

?>