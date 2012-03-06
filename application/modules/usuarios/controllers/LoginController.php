<?php

class Usuarios_LoginController extends ProyectoZF_Controller_Action
{
    private $_login;
    private $_referrer;
    
    public function init()
    {
        parent::init();
        
        $this->_login = new Usuarios_Model_Login();
        
        $this->view->headScript()->prependFile($this->view->baseUrl. '/js/jquery.form-validation-and-hints.js');
                
        $this->_referrer = new Zend_Session_Namespace('referrer'); 
    }
 
    public function indexAction()
    {         
        
        if (Usuarios_Model_Login::isLoggedIn()) {     
               
            $this->_redirect('/');   
                  
        } else {            

            if ($this->_helper->flashMessenger->setNamespace('loginMessages')->hasMessages()) {
                
                $titulo = "Error en Login";

            } else {

                $titulo = $this->_config->parametros
                                        ->mvc
                                        ->usuarios
                                        ->login
                                        ->index
                                        ->titulo;
            }
                                                
            $this->view->headTitle()->prepend($titulo);
            $this->view->titulo = $titulo;
            $this->view->form = $this->_getForm();                                                
        } 
        
    }

    public function autenticarAction()
    {

        if (!$this->getRequest()->isPost()) {
            return $this->_forward('index','index','default');
        }

        $postParams = $this->_request->getPost();

        $form = $this->_getForm();

        $form->password->setRenderPassword(true);

        if(!$form->isValid($postParams)) {

            $form->populate($postParams);

            $this->view->form = $form;

            $titulo = $this->_config->parametros
                                    ->mvc
                                    ->usuarios
                                    ->login
                                    ->index
                                    ->titulo;

            $this->view->headTitle()->prepend($titulo);
            $this->view->titulo = "Error en Login";
            $this->view->errors = $form->getMessages();

            return $this->render('index');

        }

        $email = $form->email->getValue();
        $clave = $form->password->getValue();

        $responseLogin = null;

        try {

            $this->_login->setMessage('No existe ningún <span>Usuario</span> relacionado con el <span>E-mail</span> ingresado <em>' . $email . '</em>', Usuarios_Model_Login::NOT_IDENTITY);
            $this->_login->setMessage('La <span>Contraseña</span> ingresada es incorrecta, inténtalo de nuevo', Usuarios_Model_Login::INVALID_CREDENTIAL);
            $this->_login->setMessage('Los campos <span>E-mail</span> y <span>Contraseña</span> no pueden estar en blanco', Usuarios_Model_Login::INVALID_LOGIN);
            $this->_login->login($email, $clave);

            $user = Usuarios_Model_Login::getIdentity();            

            $this->_helper->flashMessenger->setNamespace('loginMessages')->addMessage(array(
                            'loginEstado' => 'loginCorrecto',
                            'loginTitulo' => 'Login Correcto',
                            'loginMensaje' => 'Bienvenido <strong>' . $user->usuario . '</strong>'));            

            if (isset($this->_referrer->afterLogin)) { 

                return $this->_redirect($this->_referrer->afterLogin, array('prependBase' => false));         

            }  else {  
                          
                return $this->_redirect("/");

            }

        } 

        catch(Exception $e){

            $this->view->responseLogin = $e->getMessage();            
                  
            $this->_helper->flashMessenger->setNamespace('loginMessages')->addMessage(array(
                            'loginEstado' => 'loginIncorrecto',
                            'loginTitulo' => 'Login Incorrecto',
                            'loginMensaje' => $e->getMessage()));            

            return $this->_redirect("/usuarios/login/");

        }
     
        
    }
    
    public function logoutAction()
    {
        
        $user = Usuarios_Model_Login::getIdentity();            
        
        $this->_login->logout();   
             
        $this->_helper->flashMessenger->setNamespace('loginMessages')->addMessage(array(
                            'loginEstado' => 'logoutCorrecto',
                            'loginTitulo' => 'Has salido del sistema',
                            'loginMensaje' => 'Hasta pronto <strong>' . $user->usuario . '</strong>'));            

        $this->_referrer->unsetAll();        
        return $this->_redirect("/");

            
    }

    private function _getForm()
    {
        return new Usuarios_Form_Login();
    }    
  
    
}



