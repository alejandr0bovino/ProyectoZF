<?php

class Admin_LoginController extends ProyectoZF_Controller_Action
{
    private $_login;
    private $_referrer;
    
    public function init()
    {
        parent::init();
        
        $this->_login = new Admin_Model_Login();
        
        $this->view->headScript()->prependFile($this->view->baseUrl. '/js/jquery.form-validation-and-hints.js');
                
    }
 
    public function indexAction()
    {         
        
        if (Admin_Model_Login::isLoggedIn()) {     
               
            $this->_redirect('/admin/');    
                  
        } else {            

            if ($this->_helper->flashMessenger->setNamespace('loginMessages')->hasMessages()) {

                $loginMessages = $this->_helper->flashMessenger->setNamespace('loginMessages')->getMessages();

                if ( $loginMessages[0]["loginEstado"] != "logoutCorrecto" ) {

                    $titulo = "Error en Login";

                } else {

                    $titulo = $this->_config->parametros
                                        ->mvc
                                        ->admin
                                        ->index
                                        ->index
                                        ->titulo;
                }       


            } else {

                $titulo = $this->_config->parametros
                                        ->mvc
                                        ->admin
                                        ->index
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
            return $this->_forward('index','index','admin');
        }

        $postParams = $this->_request->getPost();

        $form = $this->_getForm();

        $form->password->setRenderPassword(true);

        if(!$form->isValid($postParams)) {

            $form->populate($postParams);

            $this->view->form = $form;

            $titulo = $this->_config->parametros
                                    ->mvc
                                    ->admin
                                    ->index
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

            $this->_login->setMessage('No existe ningún <span>Usuario</span> (Admin) relacionado con el <span>E-mail</span> ingresado <em>' . $email . '</em>', Admin_Model_Login::NOT_IDENTITY);
            $this->_login->setMessage('La <span>Contraseña</span> ingresada es incorrecta, inténtalo de nuevo', Admin_Model_Login::INVALID_CREDENTIAL);
            $this->_login->setMessage('Los campos <span>E-mail</span> y <span>Contraseña</span> no pueden estar en blanco', Admin_Model_Login::INVALID_LOGIN);
            $this->_login->login($email, $clave);

            $adminUser = Admin_Model_Login::getIdentity();

            $this->_helper->flashMessenger->setNamespace('loginMessages')->addMessage(array(
                                            'loginEstado' => 'loginCorrecto',
                                            'loginTitulo' => 'Login Correcto',
                                            'loginMensaje' => 'Bienvenido <strong>' . $adminUser->usuario . '</strong>'));            

            return $this->_redirect("/admin/");
            
        } 

        catch(Exception $e){

            $this->view->responseLogin = $e->getMessage();            
                           
            $this->_helper->flashMessenger->setNamespace('loginMessages')->addMessage(array(
                                                            'loginEstado' => 'loginIncorrecto',
                                                            'loginTitulo' => 'Login Incorrecto',
                                                            'loginMensaje' => $e->getMessage()));            

            return $this->_redirect("/admin/login/");

        }     
        
    }
    
    public function logoutAction()
    {
        $adminUser = Admin_Model_Login::getIdentity();

        $this->_login->logout();             

        $this->_helper->flashMessenger->setNamespace('loginMessages')->addMessage(array(
                                        'loginEstado' => 'logoutCorrecto',
                                        'loginTitulo' => 'Has salido del sistema',
                                        'loginMensaje' => 'Hasta pronto <strong>' . $adminUser->usuario . '</strong>'));            
    
        return $this->_redirect("/admin/login/");
            
    }

    private function _getForm()
    {
        return new Admin_Form_Login();
    }    


    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        
    }    
    
}



