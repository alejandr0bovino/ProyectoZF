<?php

class Admin_UsuarioController extends ProyectoZF_Controller_Action
{   
    private $usuarioService;
        
    public function init()
    {
        parent::init();
        
        $this->usuarioService = $this->_helper->Service('Usuarios_Model_Service_Common');      
    }
    
    public function preDispatch()
    {
        if (!Admin_Model_Login::isLoggedIn()) {          
            $this->_forward("index", "login", "admin");
        }
    }    

    public function indexAction()
    {
        $this->_forward("listado");
    }
    
    public function listadoAction()
    {
        $titulo = $this->_config->parametros
                                ->mvc
                                ->admin
                                ->usuario
                                ->listado
                                ->titulo;
        
        $this->view->headTitle()->prepend($titulo);
                
        $this->view->titulo = $titulo;
        
        $this->view->listaUsuario = $this->usuarioService->obtenerTodos();
    }
    
    public function crearAction()
    {
        $titulo = $this->_config->parametros->mvc->admin->usuario->crear->titulo;
        $this->view->headTitle()->prepend($titulo);
        $this->view->titulo = $titulo;
        
        $form = $this->_getForm();
        
        $form->removeElement('passwordText');
                
        $this->view->form = $form;                
        
    }
    
    public function guardarAction()
    {
        if ( !$this->getRequest()->isPost() ) {
            return $this->_forward('index');
        }
        
        $form = $this->_getForm();
        
        $form->password->setRenderPassword(true);
        $form->passwordVerify->setRenderPassword(true);
        
        $postParams = $this->_request->getPost();
        $id = $postParams['id'] > 0 ? $postParams['id'] : 0;

        if ( $id > 0 ) {
            
            if ( $postParams["passwordVerify"] == null && $postParams["password"] == null ) {            
                $form->password->setRequired(false); 
                $form->passwordVerify->setRequired(false); 
            }             
            
            $usuarioActual = $this->usuarioService->obtenerPorId($id);
            $claveActual = $usuarioActual->getClave();        
        
        }
                
        $this->view->form = $form;
        
        if ( !$form->isValid($postParams) ) {
                    
            if ( $id > 0 ) { 
                 
                $form->password->setLabel('Nueva Clave');
                $form->passwordVerify->setLabel('Confirmar Nueva Clave');                                
                $form->passwordText->setValue($claveActual);    
                
                $titulo = $this->_config->parametros->mvc->admin->usuario->editar->titulo;
                $this->view->headTitle()->prepend($titulo);                
                
             }  else {
        
                $form->removeElement('passwordText');
                
                $titulo = $this->_config->parametros->mvc->admin->usuario->crear->titulo;
                $this->view->headTitle()->prepend($titulo);

             }
             
            $this->view->titulo = 'Validando Usuario';
            $this->view->form = $form;
                        
            return $this->render('crear');
        }
        
        $clave = $form->password->getValue() != null ? $form->password->getValue() : $claveActual;
                
        $data = array(
            "id" => $form->id->getValue(),
            "nombre" => $form->nombre->getValue(),
            "apellido" => $form->apellido->getValue(),
            "email" => $form->email->getValue(),
            "clave" => $clave
        );        
        
        $this->usuarioService->guardar($data);
        
        $this->_redirect('/admin/usuario/');    
        
    }
    
    public function editarAction()
    {

        $id = (int) $this->getRequest()->getParam("id", 0);

        $form = $this->_getForm();

        $form->password->addDecorator('Label', array('escape' => false)); 
        $form->password->setLabel('Nueva Clave');
        $form->passwordVerify->addDecorator('Label', array('escape' => false, 'class' => 'passwordVerify'));        
        $form->passwordVerify->setLabel('Confirmar Nueva Clave');
                  
        $usuario = $this->usuarioService->obtenerPorId($id);

        $form->populate(array(
            'id' => $usuario->getId(),
            'nombre' => $usuario->getNombre(),
            'apellido' => $usuario->getApellido(),
            'email' => $usuario->getEmail(),
            'passwordText' => $usuario->getClave()));

        $this->view->form = $form;                 

        $titulo = $this->_config->parametros->mvc->admin->usuario->editar->titulo;
        $this->view->headTitle()->prepend($titulo);
        $this->view->titulo = "Editar Usuario";
        
        $this->render('crear');
    
    }    

    public function eliminarAction()
    {
        
        $id = (int) $this->getRequest()->getParam("id", 0);
                        
        $this->usuarioService->eliminar($id);
                
        $this->_redirect('/admin/usuario/');
  
    }

    private function _getForm()
    {
        return new Admin_Form_Usuario();
    }
    
}

