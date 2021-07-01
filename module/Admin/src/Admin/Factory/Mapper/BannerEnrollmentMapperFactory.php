<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\Banner\BannerEnrollmentMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class BannerEnrollmentMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new BannerEnrollmentMapper($serviceLocator->get('db_banner'),
                new ClassMethods(false)
                );
    }
}

?>