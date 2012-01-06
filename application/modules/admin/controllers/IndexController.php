<?php

class Admin_IndexController extends ProyectoZF_Controller_Action
{
      
    public function preDispatch()
    {
        if (Admin_Model_Login::isLoggedIn()) {
            $this->view->loggedIn = true;
            $this->view->user = Admin_Model_Login::getIdentity();
        } else {
            $this->_forward("index", "login", "admin");
        }
    }      
    
    public function indexAction()
    {
        $this->_forward("menu");
    }

    public function menuAction()
    {
        
        $titulo = $this->_config->parametros
                        ->mvc
                        ->admin
                        ->index
                        ->menu
                        ->titulo;

        $this->view->headTitle()->prepend($titulo);

        $this->view->titulo = $titulo;
        
    }    

}