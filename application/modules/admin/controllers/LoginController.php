<?php

class Admin_LoginController extends ProyectoZF_Controller_Action
{
    private $_login;
    
    public function init()
    {
        parent::init();
        
        $this->_login = new Admin_Model_Login();
        
        $this->view->headScript()->prependFile($this->view->baseUrl. '/js/jquery.form-validation-and-hints.js');
        $this->view->headScript()->prependFile('http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
    }
 
    public function indexAction()
    {         
        
         if (Admin_Model_Login::isLoggedIn()) {
            
            $this->_redirect('/admin/');
         
         } else {
            
             $titulo = $this->_config->parametros
                                    ->mvc
                                    ->admin
                                    ->index
                                    ->index
                                    ->titulo;

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
	
	try{
		
            $this->_login->setMessage('El nombre de Usuario y Password no coinciden.', Admin_Model_Login::NOT_IDENTITY);
            $this->_login->setMessage('La contraseña ingresada es incorrecta. Inténtelo de nuevo.', Admin_Model_Login::INVALID_CREDENTIAL);
            $this->_login->setMessage('Los campos de Usuario y Password no pueden dejarse en blanco.', Admin_Model_Login::INVALID_LOGIN);
            $this->_login->login($email, $clave);
            $this->_helper->layout->assign("mensaje", "Login Correcto!!!");
            $this->_helper->layout->assign("estado", "correcto");

            return $this->_forward('menu','index','admin');
		
	} 
	
	catch(Exception $e){
            
            $this->view->responseLogin = $e->getMessage();
            
            $this->_helper->layout->assign("mensaje", $e->getMessage());
            $this->_helper->layout->assign("estado", "incorrecto");
            
            return $this->_forward('index');

	}
     
        
    }
    
    public function logoutAction()
    {
        
        $this->_login->logout();
            $this->_helper->layout->assign("mensaje", "Ha salido del sistema");
            $this->_helper->layout->assign("estado", "correcto");        
        $this->_forward('index', 'index', 'default');
            
    }

    private function _getForm()
    {
        return new Admin_Form_Login();
    }    


}



