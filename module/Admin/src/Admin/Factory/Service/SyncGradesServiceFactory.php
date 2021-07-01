<?php
namespace Admin\Factory\Service;

use Admin\Service\SyncGradesService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SyncGradesServiceFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        
        $gradeMapper = $serviceLocator->get('Admin\Mapper\GradeMapper');
        $bannerGradeMapper = $serviceLocator->get('Admin\Mapper\Banner\BannerGradeMapper');
        
        return new SyncGradesService($gradeMapper,$bannerGradeMapper);
    }
}