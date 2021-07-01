<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\Banner\BannerCourseMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class BannerCourseMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new BannerCourseMapper($serviceLocator->get('db_banner'),
                new ClassMethods(false)
                );
    }
}

?>