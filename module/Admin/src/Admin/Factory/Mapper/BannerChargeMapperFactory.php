<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\Banner\BannerChargeMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class BannerChargeMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new BannerChargeMapper($serviceLocator->get('db_banner'),
                new ClassMethods(false)
                );
    }
}

?>