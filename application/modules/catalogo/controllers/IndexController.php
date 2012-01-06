<?php

class Catalogo_IndexController extends ProyectoZF_Controller_Action
{  
    private $repository;
    	
    public function init()
    {	
        parent::init();
                
        $this->repository = new Catalogo_Model_Repository_Common();
    }
    
    public function preDispatch()
    {
        if ($this->_acl->isAllowed($this->_role, "catalogo:index", "con-precio")) {
            
            $this->view->verPrecios = true;
            
        } else if ($this->_acl->isAllowed($this->_role, "catalogo:index", "sin-precio")) {
            
            $this->view->verPrecios = false;
            
        } 
    } 
        
    public function indexAction()
    {
        $this->_forward("listado");
    }

    public function listadoAction()
    {
        $titulo = $this->_config->parametros->mvc->catalogo->index->listado->titulo;
        
        $this->view->headTitle()->prepend($titulo);
                
        $this->view->titulo = $titulo;
        
        $this->view->listaProducto = $this->repository->obtenerTodos();
        
        $this->view->form = $this->_getForm();
    }
    
    public function verAction()
    {
        $id = (int) $this->getRequest()->getParam("id", 0);

        if(0 >= $id){
                $this->_redirect("/");
        }
        
        $producto = $this->repository->obtenerPorId($id);                
        
        if(null === $producto){
               $this->_redirect("/");
        }       
        
        $this->view->producto = $producto;
        
        $titulo = sprintf($this->_config->parametros->mvc->catalogo->index->ver->titulo, $producto->getDescripcion());
                        
        $this->view->headTitle()->prepend($titulo);
        
        $this->view->titulo = $titulo ;
    }
    
    public function buscarAction()
    {
        
        if (!$this->getRequest()->isPost()) {
            return $this->_forward('index');
        }        
        
        $postParams = $this->_request->getPost();

        $form = $this->_getForm();
       
        if ( $form->isValid($postParams) ){
                        
            $descripcion = $form->descripcion->getValue();

            $this->view->listaProducto = $this->repository->buscarPorDescripcion($descripcion);
                        
            $this->view->linkVolver = true;

            $this->view->form = $form;
            
            $titulo = sprintf($this->_config->parametros->mvc->catalogo->index->buscar->titulo, count($this->view->listaProducto));

            $this->view->headTitle()->prepend($titulo);

            $this->view->titulo = $titulo ;
            
            $this->render("listado");

        }
        
    }
    
    private function _getForm()
    {
        return new Catalogo_Form_Buscador();
    }    
       
   
}













