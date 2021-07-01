<?php
namespace Admin\Factory\Service;

use Admin\Service\SyncNtChargesService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SyncNtChargesServiceFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        
        $ntchargeMapper = $serviceLocator->get('Admin\Mapper\NtChargeMapper');
        $bannerNtChargeMapper = $serviceLocator->get('Admin\Mapper\Banner\BannerNtChargeMapper');
        
        return new SyncNtChargesService($nontitulaireMapper,$bannerNontitulaireMapper);
    }
}