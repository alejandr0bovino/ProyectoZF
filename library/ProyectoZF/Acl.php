<?php

class ProyectoZF_Acl  extends Zend_Acl {

    public function __construct() {
        
        $this->addRole(new Zend_Acl_Role('invitado'))
            ->addRole(new Zend_Acl_Role('usuario'), 'invitado')
            ->addRole(new Zend_Acl_Role('admin'));

        $this->addResource(new Zend_Acl_Resource('error'))
            ->addResource(new Zend_Acl_Resource('index'))
            ->addResource(new Zend_Acl_Resource('usuarios:index'))
            ->addResource(new Zend_Acl_Resource('usuarios:login'))
            ->addResource(new Zend_Acl_Resource('usuarios:ajax'))
            ->addResource(new Zend_Acl_Resource('catalogo:index'))                
            ->addResource(new Zend_Acl_Resource('admin:index'))
            ->addResource(new Zend_Acl_Resource('admin:login'))
            ->addResource(new Zend_Acl_Resource('admin:usuario'))
            ->addResource(new Zend_Acl_Resource('admin:catalogo'))
            ->addResource(new Zend_Acl_Resource('admin:ajax'));

        $this->allow('invitado', 'error')
            
            ->allow('invitado', 'index')
            ->deny('invitado', 'index', 'hola')
            ->deny('invitado', 'index', 'fool')
                        
            ->allow('invitado', 'usuarios:index', array('index', 'listado', 'ver', 'buscar', 'sin-email'))
            ->allow('invitado', 'usuarios:ajax')
            ->allow('invitado', 'usuarios:login') 
                    
            ->allow('invitado', 'catalogo:index', array('index', 'listado', 'ver', 'buscar', 'create-post', 'sin-precio'))                                      
            
            ->allow('invitado', 'admin:login')

            ->allow('usuario', 'index', 'hola')
            ->allow('usuario', 'index', 'fool')
           
            ->allow('usuario', 'usuarios:index', array('index', 'listado', 'ver', 'buscar', 'con-email'))
            ->allow('usuario', 'usuarios:login')
           
            ->allow('usuario', 'catalogo:index', array('index', 'listado', 'ver', 'buscar', 'con-precio'))                
            

            ->allow('admin');
    }
    
    public function _getRole() {
        
        $auth = Zend_Auth::getInstance();

        $role = "invitado";

        if($auth->hasIdentity()) {
            
            if(!empty($auth->getIdentity()->role)){
                $role = $auth->getIdentity()->role;
            } else {
                $auth->getIdentity()->role = "usuario";
                $role = $auth->getIdentity()->role;
            }
            
        }
        
        return $role;
        
    }  
    
    //override orginal
    public function get($resource)
    {
        if ($resource instanceof Zend_Acl_Resource_Interface) {
            $resourceId = $resource->getResourceId();
        } else {
            $resourceId = (string) $resource;
        }

        if (!$this->has($resource)) {
            require_once 'Zend/Acl/Exception.php';
            throw new Zend_Acl_Exception("Recurso '$resourceId' no encontrado", 4462);
        }

        return $this->_resources[$resourceId]['instance'];
    }
}

