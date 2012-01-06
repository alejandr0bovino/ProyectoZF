<?php

class ProyectoZF_View_Helper_AdminLogin extends Zend_View_Helper_Abstract  {
    
    public function AdminLogin() {

        $view = new Zend_view();
        $html = null;
        
        if ( !Admin_Model_Login::isLoggedIn() ) {
            
            $html = '<a href="' . $view->baseUrl() .'/admin/login/">Admin Login</a>';
        } else {
            // no me borres
            //$user = Admin_Model_Login::getIdentity();
            //$html = '&nbsp;&nbsp;<span>|</span>&nbsp;&nbsp; <a href="' . $view->baseUrl() . '/admin/usuario/editar/id/' . $user->id . '/">' .$user->nombre . '</a> ';
            $html = '<a href="' . $view->baseUrl() .'/admin/">Admin Menú</a>';
            $html .= '&nbsp;&nbsp;<span>|</span>&nbsp;&nbsp; <a href="' . $view->baseUrl() . '/admin/login/logout/">Cerrar</a>';            
        } 
        
        return $html;               

    }

}