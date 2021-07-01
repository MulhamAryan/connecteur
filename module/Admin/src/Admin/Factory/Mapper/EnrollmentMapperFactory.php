<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\EnrollmentMapper;
use Admin\Model\EnrollmentModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EnrollmentMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new EnrollmentMapper($serviceLocator->get('db_local'),
                new ClassMethods(false),
                new EnrollmentModel()
                );
    }
}

?>