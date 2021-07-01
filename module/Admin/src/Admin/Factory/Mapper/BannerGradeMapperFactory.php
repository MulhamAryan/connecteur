<?php 

namespace Admin\Factory\Mapper;

use Admin\Model\GradeModel;
use Admin\Mapper\Banner\BannerGradeMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class BannerGradeMapperFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        return new BannerGradeMapper($serviceLocator->get('db_banner'),
                new ClassMethods(false),
                new GradeModel()
                );
    }
}

?>