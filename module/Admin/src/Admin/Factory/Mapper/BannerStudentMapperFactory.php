<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\Banner\BannerStudentMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class BannerStudentMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new BannerStudentMapper($serviceLocator->get('db_banner'),
                new ClassMethods(false)
                );
    }
}

?>