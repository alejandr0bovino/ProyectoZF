<?php

class ProyectoZF_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract {
    
    protected $_acl;
    protected $_roleName;
    protected $_errorPage;
    
    public function __construct(ProyectoZF_Acl $aclData, $roleName = 'invitado') {
        $this->_errorPage = array('module' => 'default',
        'controller' => 'error',
        'action' => 'denied');
        $this->_roleName = $roleName;
        if (null !== $aclData) {
            $this->setAcl($aclData);
        }
    }
    
    public function setAcl(ProyectoZF_Acl $aclData) {
        $this->_acl = $aclData;
    }
    
    public function getAcl() {
        return $this->_acl;
    }
    
    public function setRoleName($roleName) {
        $this->_roleName = $roleName;
    }
    
    public function getRoleName() {
        return $this->_roleName;
    }

    public function setErrorPage($action, $controller = 'error', $module = null) {
        $this->_errorPage = array('module' => $module,
        'controller' => $controller,
        'action' => $action);
    }
    
    public function getErrorPage() {
        return $this->_errorPage;
    }
    
//    public function preDispatch(Zend_Controller_Request_Abstract $request) {
    public function routeShutdown(Zend_Controller_Request_Abstract $request) {  
    
        $requestModule = $request->getModuleName();
        $requestController = $request->getControllerName();
        $requestAction = $request->getActionName();
      
        $resourceName = '';
        if ($requestModule != 'default') {
            $resourceName .= $requestModule . ':';
        }
        $resourceName .= $requestController;
        if (!$this->getAcl()->isAllowed($this->_roleName, $resourceName, $requestAction)) {
            //$message = 'No tiene privilegios de acceso al recurso: "'.$resourceName.'"'; 
            //throw new Zend_Acl_Exception($message, 4463); 
            
            if ($requestModule == "admin") {                
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $redirector->gotoUrl('/admin/login/');
            }
            
            $this->_request->setParam("denyAction", $requestAction);
            return $this->denyAccess();
        }
    }
    
    public function denyAccess() { 
        $this->_request->setModuleName($this->_errorPage['module']);
        $this->_request->setControllerName($this->_errorPage['controller']);
        $this->_request->setActionName($this->_errorPage['action']);
    }



}
