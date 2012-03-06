<?php

class Usuarios_AjaxController extends Zend_Controller_Action
{
    private $repository;

    public function init()
    {
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $this->repository = $this->_helper->Em('Usuarios_Model_Repository_Common');
    }    
       
    public function buscarAction()
    {        
        $term = $this->getRequest()->getParam("term");
        
        $listaUsuarioNombre = array();
        foreach ($this->repository->obtenerTodos() as $usuario) { 
            if( stripos($usuario->getNombre(), $term) === 0 ){
                $listaUsuarioNombre[] = $usuario->getNombre();
            }
        } 
        echo json_encode($listaUsuarioNombre);
    }      

}