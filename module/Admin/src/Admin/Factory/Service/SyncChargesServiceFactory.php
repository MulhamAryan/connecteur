<?php
 namespace Admin\Factory\Service;

 use Admin\Service\SyncChargesService;
 use Zend\ServiceManager\FactoryInterface;
 use Zend\ServiceManager\ServiceLocatorInterface;

 class SyncChargesServiceFactory implements FactoryInterface
 {
     /**
      * Create service
      *
      * @param ServiceLocatorInterface $serviceLocator
      *
      * @return mixed
      */
     public function createService(ServiceLocatorInterface $serviceLocator)
     {
         $chargeMapper = $serviceLocator->get('Admin\Mapper\ChargeMapper');
         $bannerChargeMapper = $serviceLocator->get('Admin\Mapper\Banner\BannerChargeMapper');

         return new SyncChargesService($chargeMapper,$bannerChargeMapper);
     }
 }