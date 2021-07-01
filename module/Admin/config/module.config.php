<?php

return array (
        'controllers' => array (
                'invokables' => array(

                ),
                'factories' => array (

                        ),
                ),

        'service_manager' => array (
                'factories' => array (
                        'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
                        'Admin\Mapper\UserMapper' => 'Admin\Factory\Mapper\UserMapperFactory',
                        'Admin\Mapper\PermissionMapper' => 'Admin\Factory\Mapper\PermissionMapperFactory',
                        'Admin\Mapper\RoleMapper' => 'Admin\Factory\Mapper\RoleMapperFactory',
                        'Admin\Mapper\FacultyMapper' => 'Admin\Factory\Mapper\FacultyMapperFactory',
                        'Admin\Mapper\EnrollmentMapper' => 'Admin\Factory\Mapper\EnrollmentMapperFactory',
                        'Admin\Mapper\GradeMapper' => 'Admin\Factory\Mapper\GradeMapperFactory',
                        'Admin\Mapper\CourseMapper' => 'Admin\Factory\Mapper\CourseMapperFactory',
                        'Admin\Mapper\ChargeMapper' => 'Admin\Factory\Mapper\ChargeMapperFactory',
                        'Admin\Mapper\StudentMapper' => 'Admin\Factory\Mapper\StudentMapperFactory',
                        'Admin\Mapper\TeacherMapper' => 'Admin\Factory\Mapper\TeacherMapperFactory',
                        'Admin\Mapper\NontitulaireMapper' => 'Admin\Factory\Mapper\NontitulaireMapperFactory',
                        'Admin\Mapper\Banner\BannerCourseMapper' => 'Admin\Factory\Mapper\BannerCourseMapperFactory',
                        'Admin\Mapper\Banner\BannerChargeMapper' => 'Admin\Factory\Mapper\BannerChargeMapperFactory',
                        'Admin\Mapper\Banner\BannerStudentMapper' => 'Admin\Factory\Mapper\BannerStudentMapperFactory',
                        'Admin\Mapper\Banner\BannerTeacherMapper' => 'Admin\Factory\Mapper\BannerTeacherMapperFactory',
                        'Admin\Mapper\Banner\BannerNtChargeMapper' => 'Admin\Factory\Mapper\BannerNtChargeMapperFactory',
                        'Admin\Mapper\Banner\BannerNontitulaireMapper' => 'Admin\Factory\Mapper\BannerNontitulaireMapperFactory',
                        'Admin\Mapper\Banner\BannerEnrollmentMapper' => 'Admin\Factory\Mapper\BannerEnrollmentMapperFactory',
                        'Admin\Mapper\Banner\BannerGradeMapper' => 'Admin\Factory\Mapper\BannerGradeMapperFactory',
                        'Admin\Mapper\Dcor\DcorMapper' => 'Admin\Factory\Mapper\DcorMapperFactory',
                        'Admin\Service\SyncCoursesService' => 'Admin\Factory\Service\SyncCoursesServiceFactory',
                        'Admin\Service\SyncChargesService' => 'Admin\Factory\Service\SyncChargesServiceFactory',
                        'Admin\Service\SyncEnrollmentsService' => 'Admin\Factory\Service\SyncEnrollmentsServiceFactory',
                        'Admin\Service\SyncGradesService' => 'Admin\Factory\Service\SyncGradesServiceFactory',
                        'Admin\Service\SyncStudentsService' => 'Admin\Factory\Service\SyncStudentsServiceFactory',
                        'Admin\Service\SyncTeachersService' => 'Admin\Factory\Service\SyncTeachersServiceFactory',
                        'Admin\Service\SyncTeachersService' => 'Admin\Factory\Service\SyncTeachersServiceFactory',
                        'Admin\Service\SyncNtChargesService' => 'Admin\Factory\Service\SyncNtChargesServiceFactory',
                        'Admin\Service\SyncNontitulairesService' => 'Admin\Factory\Service\SyncNontitulairesServiceFactory',
                        'SyncCourses' => 'Admin\Factory\Service\SyncCoursesServiceFactory',
                        'SyncCharges' => 'Admin\Factory\Service\SyncChargesServiceFactory',
                        'SyncEnrollments' => 'Admin\Factory\Service\SyncEnrollmentsServiceFactory',
                        'SyncGrades' => 'Admin\Factory\Service\SyncGradesServiceFactory',
                        'SyncStudents' => 'Admin\Factory\Service\SyncStudentsServiceFactory',
                        'SyncTeachers' => 'Admin\Factory\Service\SyncTeachersServiceFactory',
                        'SyncNtCharges' => 'Admin\Factory\Service\SyncNtChargesServiceFactory',
                        'SyncNontitulaires' => 'Admin\Factory\Service\SyncNontitulairesServiceFactory',
                ),
        ),
        'controller_plugins' => array(
                'factories' => array (
                        'PermissionPlugin' => 'Admin\Factory\Plugin\PermissionPluginFactory',
                        'MyLogger' => 'Admin\Factory\Plugin\MyLoggerFactory'
                ),
        ),
        'router' => array(
                'routes' => array (

                        ),
                ),

        'view_manager' => array(
                'template_path_stack' => array (
                        'layout/layout'           => __DIR__ . '/../../../template/gentelella/index.phtml',
                        'admin' => __DIR__ . '/../view',
                        ),
                ),
        'strategies' => array(
                'ViewJsonStrategy',
        ),






        );
