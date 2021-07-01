<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\GradeMapper;
use Admin\Model\GradeModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class GradeMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new GradeMapper($serviceLocator->get('db_local'),
                new ClassMethods(false),
                new GradeModel()
                );
    }
}

?>