<?php 

namespace Admin\Factory\Mapper;

use Admin\Mapper\Dcor\DcorMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class DcorMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new DcorMapper($serviceLocator->get('db_dcor'),
                new ClassMethods(false)
                );
    }
}

?>