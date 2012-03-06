<?php
class ProyectoZF_View_Helper_UsuariosLogin extends Zend_View_Helper_Abstract  {
    
    public function UsuariosLogin() {

        $view = new Zend_view();
        $html = null;
        
        if ( !Usuarios_Model_Login::isLoggedIn() ) {
            
            $html = '<a href="' . $view->baseUrl() .'/usuarios/login/">Login</a>';
            
        } else {       
             
            $user = Usuarios_Model_Login::getIdentity(); 
                               
            $html = '<small>conectado como :</small> &nbsp; <a href="#">' .$user->usuario . '</a> &nbsp;&nbsp;<span>|</span>&nbsp;&nbsp; <a href="' . $view->baseUrl() . '/usuarios/login/logout/">Logout</a>';            
        } 
        
        return $html;               

    }

}