<?php
class ProyectoZF_Controller_Action extends Zend_Controller_Action 
{
    protected $_config;
    protected $_acl;
    protected $_role;
        
    public function init()
    {
        $this->view->baseUrl = $this->getRequest()->getBaseUrl();
        $this->_config = Zend_Registry::get('config');
        
        $aclPlugin = Zend_Controller_Front::getInstance()->getPlugin('ProyectoZF_Controller_Plugin_Acl');

        $this->_acl = $aclPlugin->getAcl();
        $this->_role = $aclPlugin->getRoleName();
  
        $this->view->addHelperPath('ZendX/JQuery/View/Helper','ZendX_JQuery_View_Helper');
        $this->view->jQuery()
                         ->setVersion('1.7.1')
                         ->setUiVersion('1.8.17');        
    }    
}
