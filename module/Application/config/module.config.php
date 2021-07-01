<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


return array(
        
        'console' => array(
                'router' => array(
                        'routes' => array(
                                
        
                        )
                )
        ),
        
        
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Display',
                        'action'        => 'display4',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'home' => array(
                    'type'    => 'literal',
                    'options' => array(
                            'route'    => '/',
                            'defaults' => array(
                                    'controller'    => 'Application\Controller\Display',
                                    'action'        => 'display1',
                            ),
                    ),
                    'may_terminate' => true,
                ),

        ),
    ),
    
        
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            
        ),
    ),
    'navigation' => array(
        'default' => array(
                
        ),
            
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'translator' => array(
                'locale' => 'fr_BE',
                'translation_file_patterns' => array(    
                        array(
                                'type'     => 'phpArray',
                                'base_dir' => __DIR__ . '/../language',
                                'pattern' => '%s/labels.php',
                                'text_domain' => 'labels',
                        ),
                        array(
                                'type'     => 'phpArray',
                                'base_dir' => __DIR__ . '/../language',
                                'pattern' => '%s/login.php',
                                'text_domain' => 'login',
                        ),
                        array(
                                'type'     => 'phpArray',
                                'base_dir' => __DIR__ . '/../language',
                                'pattern' => '%s/messages.php',
                                'text_domain' => 'messages',
                        ),
                        array(
                                'type'     => 'phpArray',
                                'base_dir' => __DIR__ . '/../language',
                                'pattern' => '%s/titles.php',
                                'text_domain' => 'titles',
                        ),
                ),
    ),
    'controllers' => array(
        'invokables' => array(
        ),
        'factories' => array(
        ),
    ),
    
    'form_elements' => array(
        'factories' => array(
            'CondFieldset' => 'Application\Factory\Fieldset\CondFieldsetFactory',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/admin.phtml',
            'layout/login'              => __DIR__ . '/../view/layout/login.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
            'invokables' => array(
                    'loggedinas' => 'Login\View\Helper\LoggedInAs',
                    'Indicators' => 'Application\View\Helper\Indicators',
                    'Dimension' => 'Application\View\Helper\Dimension'
                    ),
            ),
);
