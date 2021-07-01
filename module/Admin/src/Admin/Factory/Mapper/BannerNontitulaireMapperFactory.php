<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\Banner\BannerNontitulaireMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class BannerNontitulaireMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new BannerNonTitulaireMapper($serviceLocator->get('db_banner'),
                new ClassMethods(false)
                );
    }
}

?>