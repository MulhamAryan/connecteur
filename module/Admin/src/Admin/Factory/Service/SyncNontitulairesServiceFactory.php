<?php
namespace Admin\Factory\Service;

use Admin\Service\SyncNontitulairesService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SyncNontitulairesServiceFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        
        $nontitulaireMapper = $serviceLocator->get('Admin\Mapper\NontitulaireMapper');
        $bannerNontitulaireMapper = $serviceLocator->get('Admin\Mapper\Banner\BannerNontitulaireMapper');
        $dcorMapper = $serviceLocator->get('Admin\Mapper\Dcor\DcorMapper');
        $studentMapper = $serviceLocator->get('Admin\Mapper\StudentMapper');
        $teacherMapper = $serviceLocator->get('Admin\Mapper\TeacherMapper');
        
        return new SyncNontitulairesService($nontitulaireMapper,$bannerNontitulaireMapper,$dcorMapper,$studentMapper,$teacherMapper);
    }
}