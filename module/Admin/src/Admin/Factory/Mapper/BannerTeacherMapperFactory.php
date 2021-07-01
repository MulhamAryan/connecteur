<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\Banner\BannerTeacherMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class BannerTeacherMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new BannerTeacherMapper($serviceLocator->get('db_banner'),
                new ClassMethods(false)
                );
    }
}

?>