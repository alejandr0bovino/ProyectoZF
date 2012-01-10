<?php

class Default_IndexController extends ProyectoZF_Controller_Action
{

    public function indexAction()
    {
        $this->view->headMeta()->appendName('description', $this->_config->parametros->mvc->default->index->index->description);
        
        $this->view->headMeta()->appendName('keywords', $this->_config->parametros->mvc->default->index->index->keywords);
        
        $this->view->titulo = $this->_config->parametros->mvc->default->index->index->titulo;
        
    }
    
    public function demoJqueryUiAction(){
        
    }

}

