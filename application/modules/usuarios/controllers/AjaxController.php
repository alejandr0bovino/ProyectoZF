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
        
        $dateFormat = "d ' de ' MMMM ' de ' YYYY";

        foreach ($this->repository->buscarPorTerm($term) as $usuario) {           
            //$listaUsuarioNombre[] = $usuario->getNombre();                  
            $listaUsuarioNombre[] = array("label" => $usuario->getNombre(), "foto" => $usuario->getFoto(), "fecha" => $usuario->getFecha_cre()->toString($dateFormat)  );
        }                

        echo json_encode($listaUsuarioNombre);
    }      

}