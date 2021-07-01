<?php
namespace Admin\Factory\Service;

use Admin\Service\SyncEnrollmentsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SyncEnrollmentsServiceFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        
        $enrollmentMapper = $serviceLocator->get('Admin\Mapper\EnrollmentMapper');
        $bannerEnrollmentMapper = $serviceLocator->get('Admin\Mapper\Banner\BannerEnrollmentMapper');
        
        return new SyncEnrollmentsService($enrollmentMapper,$bannerEnrollmentMapper);
    }
}