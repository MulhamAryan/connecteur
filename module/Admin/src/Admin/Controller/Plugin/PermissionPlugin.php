<?php

namespace Admin\Controller\Plugin;

use Admin\Mapper\PermissionMapper;
use Admin\Mapper\RoleMapper;
use Admin\Mapper\UserMapper;
use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Session\Container,
    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource;  
use Zend\ServiceManager\ServiceManagerAwareInterface;
   
class PermissionPlugin extends AbstractPlugin
{
    protected $sessioncontainer ;
    protected $roleMapper;
    protected $permissionMapper;
    protected $userMapper;
    protected $acl;
    protected $services;
     
    public function __construct(PermissionMapper $permissionMapper,RoleMapper $roleMapper,UserMapper $userMapper){  
        $this->permissionMapper = $permissionMapper;
        $this->roleMapper = $roleMapper;
        $this->userMapper = $userMapper;
           $this->sessioncontainer = new Container('evalens');
    }
 
    public function loadAcl(){
       
        $acl = new Acl();
        //load roles
        $roles = $this->roleMapper->getAll();
        foreach ($roles as $role){
            $acl->addRole(new Role($role->getName()));
        }
        //load resources
        //$acl->addResource(new Resource('Application')); //retrouver toutes les ressources à partir des permission (select distinct)
        $acl->addResource('Application');
        $acl->addResource(new Resource('Login'));
        $acl->addResource(new Resource('Admin'));
        
        //load permission
        $acl->allow(null,'Login','view');
        $acl->allow('admin');
        $acl->allow('loggeduser','Application');
        /*$permissions = $this->permissionMapper->getAll();
        foreach ($permissions as $permission){
            if($permission->privilege == "*" ){
                $acl->allow($permission->rolename,$permission->resource);
            }else{
                $acl->allow($permission->rolename,$permission->resource,$permission->privilege);
            }
        }*/
        
        $this->sessioncontainer->acls = $acl;
        //add to session container
    }
     
    
    public function doAuthorization($e)
    {
        
        $application   = $e->getApplication();
        $sm            = $application->getServiceManager();
        $request = $sm->get('request');
        $router = $e->getRouter();
        $matchedroute = $router->match($request);
        
        $route = $matchedroute->getMatchedRouteName();

        if ($route == 'login'){
            return;
        }
        
        if (!$this->sessioncontainer->offsetExists('acls')){
            $this->loadAcl();
        }    
        $acls = $this->sessioncontainer->acls;
        //Test
        //$this->sessioncontainer->user = $this->userMapper->findByNetid('emmbairy');
        
        if (!isset($this->sessioncontainer->user) /*|| !isset($this->sessioncontainer->userrole)*/){
            $this->sessioncontainer->page_requested = $route;
            
            $url    = $router->assemble(array(), array('name' => 'login'));
            $response = $e->getResponse();
            $response->setStatusCode(302);
            $response->getHeaders()->addHeaderLine('Location', $url);
            $e->stopPropagation();
        }else{
            $user = $this->sessioncontainer->user;          
            if (!isset($this->sessioncontainer->userrole)){
                $parentrolesarray = $this->roleMapper->getUserRoles($user);
                $userrolename = 'role'.$user->getId();
                $acls->addRole(new Role($userrolename),$parentrolesarray);
                $this->sessioncontainer->userrole = $userrolename;
            }
        
            $userrole = $this->sessioncontainer->userrole;
        
            $vm = $e->getApplication()->getMvcEvent()->getViewModel();
            $vm->userrole = $userrole;
            $vm->user = $user;
            $vm->acls = $acls;        
            $controller = $e->getTarget();
            $controllerClass = get_class($controller);
            $namespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            if ( ! $acls->isAllowed($userrole, $namespace, 'view')){     
                $response = $e->getResponse();
                $response->setStatusCode(404);         
            }
        }

    }
}