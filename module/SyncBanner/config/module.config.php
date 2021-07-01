<?php
return array (

        'console' => array(
                'router' => array(
                        'routes' => array(
                                'synccourses' => array(
                                        'options' => array(
                                                'route'    => 'synccourses',
                                                'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'synccourses'
                                                )
                                        )
                                ),
                                'syncstudents' => array(
                                        'options' => array(
                                                'route'    => 'syncstudents',
                                                'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'syncstudents'
                                                )
                                        )
                                ),
                                'syncenrollments' => array(
                                        'options' => array(
                                                'route'    => 'syncenrollments [<max_unenrollments>]',
                                                'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'syncenrollments'
                                                )
                                        )
                                ),
                                'syncteachers' => array(
                                        'options' => array(
                                                'route'    => 'syncteachers',
                                                'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'syncteachers'
                                                )
                                        )
                                ),
                                'synccharges' => array(
                                        'options' => array(
                                                'route'    => 'synccharges [<max_unenrollments>]',
                                                'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'synccharges'
                                                )
                                        )
                                ),
                                'syncnontitulaires' => array(
                                        'options' => array(
                                                'route'    => 'syncnontitulaires',
                                                'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'syncnontitulaires'
                                                )
                                        )
                                ),
                                'sendteachersupdates' => array(
                                        'options' => array(
                                                'route'    => 'sendteachersupdates',
                                                'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Syncuv',
                                                        'action' => 'sendteachersupdates'
                                                )
                                        )
                                ),
                                'sendstudentsupdates' => array(
                                        'options' => array(
                                                'route'    => 'sendstudentsupdates',
                                                'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Syncuv',
                                                        'action' => 'sendstudentsupdates'
                                                )
                                        )
                                ),
                                'sendenrollmentsupdates' => array(
                                        'options' => array(
                                                'route'    => 'sendenrollmentsupdates',
                                                'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Syncuv',
                                                        'action' => 'sendenrollmentsupdates'
                                                )
                                        )
                                ),
                                'sendcoursesupdates' => array(
                                        'options' => array(
                                                'route'    => 'sendcoursesupdates',
                                                'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Syncuv',
                                                        'action' => 'sendcoursesupdates'
                                                )
                                        )
                                ),
                                'sendchargesupdates' => array(
                                        'options' => array(
                                                'route'    => 'sendchargesupdates',
                                                'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Syncuv',
                                                        'action' => 'sendchargesupdates'
                                                )
                                        )
                                ),
                                'syncstudent' => array(
                                       'options' => array(
                                              'route'    => 'syncstudent <id> [<term>]',
                                              'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'syncstudent'
                                               )
                                        )
                                ),
                                'getstudent' => array(
                                       'options' => array(
                                              'route'    => 'getstudent <id> [<term>]',
                                              'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'getstudent'
                                               )
                                        )
                                ),
                                'getcourse' => array(
                                       'options' => array(
                                              'route'    => 'getcourse <id> [<term>]',
                                              'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'getcourse'
                                               )
                                        )
                                ),
                                'synccourse' => array(
                                       'options' => array(
                                              'route'    => 'synccourse <id> [<term>]',
                                              'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'synccourse'
                                               )
                                        )
                                ),
                                'unenroll' => array(
                                       'options' => array(
                                              'route'    => 'unenroll <bannerid> <course>',
                                              'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'unenroll'
                                               )
                                        )
                                ),
                                'cleanup' => array(
                                       'options' => array(
                                              'route'    => 'cleanup <type>',
                                              'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'cleanup'
                                               )
                                        )
                                ),
                                'test' => array(
                                       'options' => array(
                                              'route'    => 'test',
                                              'defaults' => array(
                                                        'controller' => 'SyncBanner\Controller\Sync',
                                                        'action' => 'test'
                                               )
                                        )
                                ),
                        )
                )
        ),

        'controllers' => array(
                'invokables' => array(
                        'SyncBanner\Controller\Sync' => 'SyncBanner\Controller\SyncController',
                        'SyncBanner\Controller\Syncuv' => 'SyncBanner\Controller\SyncuvController',
                ),
                'factories' => array(
                ),
        ),

        'service_manager' => array (
                'factories' => array (
                        'SyncBanner\Service\XmlStringService' => 'SyncBanner\Factory\Service\XmlStringServiceFactory'
                ),
        ),

        );
