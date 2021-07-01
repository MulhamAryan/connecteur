<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\Banner\BannerNtChargeMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class BannerNtChargeMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new BannerNtChargeMapper($serviceLocator->get('db_banner'),
                new ClassMethods(false)
                );
    }
}

?>