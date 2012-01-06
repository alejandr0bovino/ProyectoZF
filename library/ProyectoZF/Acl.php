<?php

class ProyectoZF_Acl  extends Zend_Acl {

    public function __construct() {
        
        $this->addRole(new Zend_Acl_Role('invitado'))
            ->addRole(new Zend_Acl_Role('usuario'), 'invitado')
            ->addRole(new Zend_Acl_Role('admin'));

        $this->addResource(new Zend_Acl_Resource('error'))
            ->addResource(new Zend_Acl_Resource('index'))
            ->addResource(new Zend_Acl_Resource('usuarios:index'))            
            ->addResource(new Zend_Acl_Resource('catalogo:index'))                
            ->addResource(new Zend_Acl_Resource('admin:index'))
            ->addResource(new Zend_Acl_Resource('admin:login'))
            ->addResource(new Zend_Acl_Resource('admin:usuario'))
            ->addResource(new Zend_Acl_Resource('admin:catalogo'));

        $this->allow('invitado', 'error')
            ->allow('invitado', 'index')
            ->allow('invitado', 'usuarios:index', array('index', 'listado', 'ver', 'buscar', 'sin-email'))
            ->allow('invitado', 'catalogo:index', array('index', 'listado', 'ver', 'buscar', 'create-post', 'sin-precio'))                
            ->allow('invitado', 'admin:index')                
            ->allow('invitado', 'admin:login', array('index', 'autenticar')) 
            ->allow('usuario', 'usuarios:index', array('index', 'listado', 'ver', 'buscar', 'con-email'))
            ->allow('usuario', 'catalogo:index', array('index', 'listado', 'ver', 'buscar', 'con-precio'))                
            ->allow('usuario', 'admin:login', 'logout')
            ->deny('usuario', 'admin:usuario')
            ->deny('usuario', 'admin:catalogo')
//            ->allow('usuario', 'admin:usuario')
//            ->allow('usuario', 'admin:catalogo')
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

