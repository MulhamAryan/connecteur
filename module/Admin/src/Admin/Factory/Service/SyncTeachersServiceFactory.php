<?php
namespace Admin\Factory\Service;

use Admin\Service\SyncTeachersService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SyncTeachersServiceFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        
        $teacherMapper = $serviceLocator->get('Admin\Mapper\TeacherMapper');
        $bannerTeacherMapper = $serviceLocator->get('Admin\Mapper\Banner\BannerTeacherMapper');
        
        return new SyncTeachersService($teacherMapper,$bannerTeacherMapper);
    }
}