<?php
namespace Admin\Factory\Service;

use Admin\Service\SyncStudentsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SyncStudentsServiceFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        
        $studentMapper = $serviceLocator->get('Admin\Mapper\StudentMapper');
        $bannerStudentMapper = $serviceLocator->get('Admin\Mapper\Banner\BannerStudentMapper');
        
        return new SyncStudentsService($studentMapper,$bannerStudentMapper);
    }
}
