<?php

namespace Admin\Factory\Plugin;

use Admin\Controller\Plugin\PermissionPlugin;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PermissionPluginFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $permissionMapper = $realServiceLocator->get('Admin\Mapper\PermissionMapper');
        $roleMapper = $realServiceLocator->get('Admin\Mapper\RoleMapper');
        $userMapper = $realServiceLocator->get('Admin\Mapper\UserMapper');
        return new PermissionPlugin($permissionMapper,$roleMapper,$userMapper);
    }
}