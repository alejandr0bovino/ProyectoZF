<?php

class Usuarios_IndexController extends ProyectoZF_Controller_Action
{
    private $repository;

    public function init()
    {
        parent::init();
        
        $this->repository = new Usuarios_Model_Repository_Common();
    }    
    
    public function preDispatch()
    {
        if ($this->_acl->isAllowed($this->_role, "usuarios:index", "con-email")) {
            
            $this->view->verEmails = true;
            
        } else if ($this->_acl->isAllowed($this->_role, "usuarios:index", "sin-email")) {
            
            $this->view->verEmails = false;
            
        }         
    } 
    
    public function indexAction()
    {
        $this->_forward("listado");
    }
    
    public function listadoAction()
    {
        $titulo = $this->_config->parametros->mvc->usuarios->index->listado->titulo;
        
        $this->view->headTitle()->prepend($titulo);
                
        $this->view->titulo = $titulo;
        
        $this->view->listaUsuario = $this->repository->obtenerTodos();
        
        $this->view->form = $this->_getForm();
    }

    public function verAction()
    {
        $id = (int) $this->getRequest()->getParam("id", 0);

        if(0 >= $id){
                $this->_redirect("/");
        }

        $usuario = $this->repository->obtenerPorId($id);

        if(null === $usuario){
                $this->_redirect("/");
        }       
        
        $this->view->usuario = $usuario;
                
        $this->view->headMeta()->appendName('description', $this->_config->parametros->mvc->usuarios->index->ver->description);
        
        $this->view->headMeta()->appendName('keywords', $this->_config->parametros->mvc->usuarios->index->ver->keywords);
        
        $titulo = sprintf($this->_config->parametros->mvc->usuarios->index->ver->titulo, $usuario->getNombre() . " " . $usuario->getApellido());
                        
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

            $nombre = $form->nombre->getValue();

            $this->view->listaUsuario = $this->repository->buscarPorNombre($nombre);

            $this->view->linkVolver = true;

            $titulo = sprintf($this->_config->parametros->mvc->usuarios->index->buscar->titulo, count($this->view->listaUsuario));

            $this->view->headTitle()->prepend($titulo);

            $this->view->titulo = $titulo ; 

            $this->view->form = $form;

            $this->render("listado");

        }
        
    }
    
    private function _getForm()
    {
        return new Usuarios_Form_Buscador();
    }    

}