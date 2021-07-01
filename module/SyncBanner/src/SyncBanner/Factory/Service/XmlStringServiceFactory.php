<?php
 namespace SyncBanner\Factory\Service;

 use SyncBanner\Service\XmlStringService;
 use Zend\ServiceManager\FactoryInterface;
 use Zend\ServiceManager\ServiceLocatorInterface;

 class XmlStringServiceFactory implements FactoryInterface
 {

     public function createService(ServiceLocatorInterface $serviceLocator)
     {

         $facultyMapper = $serviceLocator->get('Admin\Mapper\FacultyMapper');

         return new XmlStringService($facultyMapper);
     }
 }