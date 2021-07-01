<?php
 namespace Admin\Factory\Service;

 use Admin\Service\SyncCoursesService;
 use Zend\ServiceManager\FactoryInterface;
 use Zend\ServiceManager\ServiceLocatorInterface;

 class SyncCoursesServiceFactory implements FactoryInterface
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
         $courseMapper = $serviceLocator->get('Admin\Mapper\CourseMapper');
         $bannerCourseMapper = $serviceLocator->get('Admin\Mapper\Banner\BannerCourseMapper');

         return new SyncCoursesService($courseMapper,$bannerCourseMapper);
     }
 }