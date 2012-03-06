<?php

class Admin_AjaxController extends Zend_Controller_Action 
{
    
    private $usuarioRepository;
    
    public function init()
    {
    
        //$this->view->baseUrl = $this->getRequest()->getBaseUrl();
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $this->usuarioRepository = $this->_helper->Em('Usuarios_Model_Repository_Common');
    }
    
    public function existEmailAction()
    {
        $id = (int) $this->_request->getPost("id");
        
        $emailParam = trim($this->_request->getPost("email"));
               
        $encontrado = $this->usuarioRepository->buscarPorEmail($emailParam);
        
        if ( $id > 0 ) {
            
            $usuario = $this->usuarioRepository->obtenerPorId($id);
            
            if ($usuario->getEmail() == $emailParam ) {
                
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
    
    public function existUsuarioAction()
    {
        $id = (int) $this->_request->getPost("id");
        
        $usuarioParam = trim($this->_request->getPost("usuario"));
                
        $encontrado = $this->usuarioRepository->buscarPorUsuario($usuarioParam);
        
        if ( $id > 0 ) {
            
            $usuario = $this->usuarioRepository->obtenerPorId($id);
            
            if ($usuario->getUsuario() == $usuarioParam ) {
                
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
