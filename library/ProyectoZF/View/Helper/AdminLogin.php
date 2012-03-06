<?php
class ProyectoZF_View_Helper_AdminLogin extends Zend_View_Helper_Abstract  {
    
    public function AdminLogin() {

        $view = new Zend_view();
        $html = null;
        
        if ( Admin_Model_Login::isLoggedIn() ) {
            
            $user = Admin_Model_Login::getIdentity();
                       
            $html = '<small>conectado como :</small> &nbsp; <a href="#">' . $user->usuario . '</a> &nbsp;&nbsp;<span>|</span>&nbsp;&nbsp; <a href="' . $view->baseUrl() . '/admin/login/logout/">Logout</a>';            
        } 
        
        return $html;               

    }

}