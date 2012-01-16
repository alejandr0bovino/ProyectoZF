<?php


class Admin_AjaxController extends Zend_Controller_Action{
    
    public function init()
    {
    
        $this->view->baseUrl = $this->getRequest()->getBaseUrl();
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }
    
    public function existEmailAction()
    {
        $id = (int) $this->_request->getPost("id");
        
        $email = trim($this->_request->getPost("email"));
        
        $repository = $this->_helper->Em('Usuarios_Model_Repository_Common');
        
        $encontrado = $repository->buscarPorEmail($email);
        
        if ( $id > 0 ) {
            
            $usuario = $repository->obtenerPorId($id);
            
            if ($usuario->getEmail() == $email ) {
                
                echo 0;
                
            } else {
                
                echo count($encontrado);
                
            }
            
        } else {
            
            echo count($encontrado);
            
        }
        
        // count($encontrado) 
        // Retorna
        // 0 = no encontrado
        // 1 = encontrado

        
    }
}
