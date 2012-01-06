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
    
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $resourceName = '';
        if ($request->getModuleName() != 'default') {
            $resourceName .= $request->getModuleName() . ':';
        }
        $resourceName .= $request->getControllerName();
        if (!$this->getAcl()->isAllowed($this->_roleName, $resourceName, $request->getActionName())) {
           // $message = 'No tiene privilegios de acceso al recurso: "'.$resourceName.'"'; 
           // throw new Zend_Acl_Exception($message, 4463); 
            $this->denyAccess();
        }
    }
    
    public function denyAccess() { 
        $this->_request->setModuleName($this->_errorPage['module']);
        $this->_request->setControllerName($this->_errorPage['controller']);
        $this->_request->setActionName($this->_errorPage['action']);
    }

}
