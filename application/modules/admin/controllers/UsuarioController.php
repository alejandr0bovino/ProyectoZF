<?php

class Admin_UsuarioController extends ProyectoZF_Controller_Action
{   
    private $repository;
        
    public function init()
    {
        parent::init();    
        
        //$this->repository = new Usuarios_Model_Repository_Common(); 
        $this->repository = $this->_helper->Em('Usuarios_Model_Repository_Common');
             
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
        
        $this->view->listaUsuario = $this->repository->obtenerTodos();
    }
    
    public function crearAction()
    {
        $this->_addScriptCheckEmail();
        
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
        
        $this->_addScriptCheckEmail($id);

        if ( $id > 0 ) {
                                    
            if ( $postParams["passwordVerify"] == null && $postParams["password"] == null ) {            
                $form->password->setRequired(false); 
                $form->passwordVerify->setRequired(false); 
            }             
            
            $usuarioActual = $this->repository->obtenerPorId($id);
            $nombreActual = $usuarioActual->getNombre();
            $emailActual = $usuarioActual->getEmail();
            $claveActual = $usuarioActual->getClave();
            
            
            if ( $postParams["nombre"] == $nombreActual ) {            
                $form->nombre->removeValidator('Zend_Validate_Db_NoRecordExists');                
            }   
            if ( $postParams["email"] == $emailActual ) {            
                $form->email->removeValidator('Zend_Validate_Db_NoRecordExists');                
            }               
        
        }
                
        $this->view->form = $form;
        
        if ( !$form->isValid($postParams) ) {
                    
            if ( $id > 0 ) { 
                
                $form->password->setLabel('Nueva Clave');
                $form->passwordVerify->setLabel('Confirmar Nueva Clave');
                
                if ( $form->password->getValue()!= null || $form->passwordVerify->getValue()!= null ) {
                    
                    $form->password->setOptions(array('class' => 'verifyPassword'))
                            ->addDecorator('HtmlTag', array(
                                        'tag' => 'fieldset',
                                        'class' => 'element_form required clearfix'));

                    $form->passwordVerify->setOptions(array('class' => 'verifyPasswordConfirm'))
                            ->addDecorator('HtmlTag', array(
                                        'tag' => 'fieldset',
                                        'class' => 'element_form required clearfix'));   
                  
                } else {

                    $form->password->setOptions(array('class' => 'optionalElement'))
                            ->addDecorator('HtmlTag', array(
                                        'tag' => 'fieldset',
                                        'class' => 'element_form clearfix'));

                    $form->passwordVerify->setOptions(array('class' => 'optionalElement'))
                            ->addDecorator('HtmlTag', array(
                                        'tag' => 'fieldset',
                                        'class' => 'element_form clearfix'));                    
                }

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
        
        $this->repository->guardar($data);
        
        $this->_redirect('/admin/usuario/');    
        
    }
    
    public function editarAction()
    {

        $id = (int) $this->getRequest()->getParam("id", 0);
        $this->_addScriptCheckEmail($id);
        
        $form = $this->_getForm();

        $form->password->addDecorator('Label', array('escape' => false))
                ->setLabel('Nueva Clave')
                ->setOptions(array('class' => 'optionalElement'))
                ->addDecorator('HtmlTag', array(
                            'tag' => 'fieldset',
                            'class' => 'element_form clearfix'));
                
        
        $form->passwordVerify->addDecorator('Label', array('escape' => false, 'class' => 'passwordVerify'))
                ->setLabel('Confirmar Nueva Clave')
                ->setOptions(array('class' => 'optionalElement'))
                ->addDecorator('HtmlTag', array(
                            'tag' => 'fieldset',
                            'class' => 'element_form clearfix'));
                  
        $usuario = $this->repository->obtenerPorId($id);

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
                        
        $this->repository->eliminar($id);
                
        $this->_redirect('/admin/usuario/');
  
    }

    private function _getForm()
    {
        return new Admin_Form_Usuario();
    }
    
    private function _addScriptCheckEmail($id = 0) {
    
        $this->view->headScript()->prependFile($this->view->baseUrl. '/js/jquery.form-validation-and-hints.js');        
        $this->view->headScript()->prependFile('http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
        $this->view->headScript()->captureStart();
        echo '         var baseUrl = "'. $this->view->baseUrl .'", usuarioId = "'. $id .'";'. PHP_EOL;
        $this->view->headScript()->captureEnd() ;
        $this->view->headScript()->appendFile($this->view->baseUrl .'/js/checkEmail.js');      

    }    
}

