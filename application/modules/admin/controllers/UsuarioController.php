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
        $this->_addScriptCheck();
        
        $titulo = $this->_config->parametros->mvc->admin->usuario->crear->titulo;
        $this->view->headTitle()->prepend($titulo);
        $this->view->titulo = $titulo;
        
        $form = $this->_getForm();
        
        $form->removeElement('passwordText');
        //$form->getElement('foto')->setDescription('');
        $form->removeElement('fotoEliminar');
                
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
        
        $fotoActual = $postParams['fotoActual'];

        $this->_addScriptCheck($id);

        if ( $id > 0 ) {
                                    
            if ( $postParams["passwordVerify"] == null && $postParams["password"] == null ) {            
                $form->password->setRequired(false); 
                $form->passwordVerify->setRequired(false); 
            }             
            
            $usuario = $this->repository->obtenerPorId($id);
            $usuarioActual = $usuario->getUsuario();
            $nombreActual = $usuario->getNombre();
            $emailActual = $usuario->getEmail();
            $claveActual = $usuario->getClave();
            
            
            if ( $postParams["usuario"] == $usuarioActual ) {            
                $form->usuario->removeValidator('Zend_Validate_Db_NoRecordExists');                
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

                    $form->password
                            //->setOptions(array('class' => 'optionalElement'))
                            ->addDecorator('HtmlTag', array(
                                        'tag' => 'fieldset',
                                        'class' => 'element_form clearfix'));

                    $form->passwordVerify
                            //->setOptions(array('class' => 'optionalElement'))
                            ->addDecorator('HtmlTag', array(
                                        'tag' => 'fieldset',
                                        'class' => 'element_form clearfix'));                    
                }
                
                $form->foto->setDescription('<img src="' . $this->view->baseUrl . '/' . $this->_config->parametros->mvc->usuarios->perfil->foto->thumb . $fotoActual . '" />');
                
                $form->passwordText->setValue($claveActual);                
                

                if ( $form->fotoActual->getValue() == $this->_config->parametros->mvc->usuarios->perfil->foto->default ) {
                    $form->removeElement('fotoEliminar');
                }


                $titulo = $this->_config->parametros->mvc->admin->usuario->editar->titulo;
                $this->view->headTitle()->prepend($titulo);                
                
            }  else {
        
                $form->removeElement('passwordText');
                $form->removeElement('fotoEliminar');
                
                $titulo = $this->_config->parametros->mvc->admin->usuario->crear->titulo;
                $this->view->headTitle()->prepend($titulo);

            }
             
            $this->view->titulo = 'Validando Usuario';
            $this->view->form = $form;
                        
            return $this->render('crear');
        }
        
        $clave = $form->password->getValue() != null ? $form->password->getValue() : $claveActual;


        if ( $form->fotoEliminar->getValue() == 1 ) {
            
            $usuarioFoto = $this->_config->parametros->mvc->usuarios->perfil->foto->default;
            $this->repository->eliminarFotoUsuario($fotoActual);

        } else {

            $fotoFileName = $form->foto->getFileName();

            if ( $fotoFileName != null ) { 
                
                $form->foto->addFilter(new Zend_Filter_File_Rename(
                        //array('target' => $this->_config->parametros->mvc->usuarios->perfil->foto->large . mt_rand() . basename($fotoFileName),
                        array('target' => $this->_config->parametros->mvc->usuarios->perfil->foto->large . $form->usuario->getValue() . mt_rand() . "." .pathinfo($fotoFileName, PATHINFO_EXTENSION),                    
                            'overwrite' => false)));
            }
                        
            $formFotoValue = $form->foto->getValue();

            $usuarioFoto =  $formFotoValue != null ? $formFotoValue : $fotoActual;

        }

 
        
                      

        $data = array(
            "id" => $form->id->getValue(),
            "usuario" => $form->usuario->getValue(),
            "email" => $form->email->getValue(),
            "clave" => $clave,
            "nombre" => $form->nombre->getValue(),
            "apellido" => $form->apellido->getValue(),
            "website" => $form->website->getValue(),                        
            "foto" => $usuarioFoto
        );        
        
        $this->repository->guardar($data);
        
        $this->_redirect('/admin/usuario/');    
        
    }
    
    public function editarAction()
    {

        $id = (int) $this->getRequest()->getParam("id", 0);
        $this->_addScriptCheck($id);
        
        $usuario = $this->repository->obtenerPorId($id);

        if(null === $usuario){
                $this->_redirect("/admin/");
        } 

        $form = $this->_getForm();

        $form->password->addDecorator('Label', array('escape' => false))
                ->setLabel('Nueva Clave')
                //->setOptions(array('class' => 'optionalElement'))
                ->addDecorator('HtmlTag', array(
                            'tag' => 'fieldset',
                            'class' => 'element_form clearfix'));
                
        
        $form->passwordVerify->addDecorator('Label', array('escape' => false, 'class' => 'passwordVerify'))
                ->setLabel('Confirmar Nueva Clave')
                //->setOptions(array('class' => 'optionalElement'))
                ->addDecorator('HtmlTag', array(
                            'tag' => 'fieldset',
                            'class' => 'element_form clearfix'));
                                              
        
        $form->populate(array(
            'id' => $usuario->getId(),
            'usuario' => $usuario->getUsuario(),
            'email' => $usuario->getEmail(),
            'passwordText' => $usuario->getClave(),
            'nombre' => $usuario->getNombre(),
            'apellido' => $usuario->getApellido(),
            'website' => $usuario->getWebsite()));
                    

        $usuarioFoto = $usuario->getFoto();

        if ( $usuarioFoto == $this->_config->parametros->mvc->usuarios->perfil->foto->default ) {
            $form->removeElement('fotoEliminar');
        }

        $form->fotoActual->setValue($usuarioFoto);
        $form->foto->setDescription('<img src="' . $this->view->baseUrl . '/' . $this->_config->parametros->mvc->usuarios->perfil->foto->thumb . $usuarioFoto . '" />');

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
    
    private function _addScriptCheck($id = 0) {
    
        $this->view->headScript()->prependFile($this->view->baseUrl. '/js/jquery.form-validation-and-hints.js');        
        $this->view->headScript()->appendScript('         var baseUrl = "' . $this->view->baseUrl . '", usuarioId = "' . $id . '";' . PHP_EOL);
        $this->view->headScript()->appendFile($this->view->baseUrl .'/js/checkUser.js');      

    }    
}

