<?php

class Admin_CatalogoController extends ProyectoZF_Controller_Action
{      
    private $productoService;
        
    public function init()
    {
        parent::init();
                
        $this->productoService = $this->_helper->Service('Catalogo_Model_Service_Common');      
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
                                ->catalogo
                                ->listado
                                ->titulo;
        
        $this->view->headTitle()->prepend($titulo);
        $this->view->titulo = $titulo;
        
        $this->view->listaProducto = $this->productoService->obtenerTodos();
    }
    
    public function crearAction()
    {
        $titulo = $this->_config->parametros->mvc->admin->catalogo->crear->titulo;
        $this->view->headTitle()->prepend($titulo);
        $this->view->titulo = $titulo;
        
        $form = $this->_getForm();
                
        $this->view->form = $form;                
        
    }
    
    public function guardarAction()
    {
        if ( !$this->getRequest()->isPost() ) {
            return $this->_forward('index');
        }
        
        $form = $this->_getForm();
        
        $postParams = $this->_request->getPost();
        $id = $postParams['id'] > 0 ? $postParams['id'] : 0;
        
        $this->view->form = $form;
                
        if ( !$form->isValid($postParams) ) {
            
            if ( $id > 0 ) {
                $titulo = $this->_config->parametros->mvc->admin->catalogo->editar->titulo;
                $this->view->headTitle()->prepend($titulo);  
            } else {
                $titulo = $this->_config->parametros->mvc->admin->catalogo->crear->titulo;
                $this->view->headTitle()->prepend($titulo);                  
            }
            
            $this->view->titulo = 'Validando Producto';
            $this->view->form = $form;
            
            return $this->render('crear');
        }
        
        $data = array(
            "id" => $form->id->getValue(),
            "descripcion" => $form->descripcion->getValue(),
            "precio" => $form->precio->getValue(),
            "cantidad" => $form->cantidad->getValue()
        );        
        
        $this->productoService->guardar($data);
                
        $this->_redirect('/admin/catalogo/');    
        
    }
    
    public function editarAction()
    {

        $id = (int) $this->getRequest()->getParam("id", 0);

        $form = $this->_getForm();

        $producto = $this->productoService->obtenerPorId($id);

        $form->populate(array(
            'id' => $producto->getId(),
            'descripcion' => $producto->getDescripcion(),
            'precio' => $producto->getPrecio(),
            'cantidad' => $producto->getCantidad()));

        $this->view->form = $form;                 
        
        $titulo = $this->_config->parametros->mvc->admin->catalogo->editar->titulo;
        $this->view->headTitle()->prepend($titulo);
        $this->view->titulo = "Editar Producto";
        
        $this->render('crear');
    
    }    

    public function eliminarAction()
    {
        
        $id = (int) $this->getRequest()->getParam("id", 0);      
                
        $this->productoService->eliminar($id);
        
        $this->_redirect('/admin/catalogo/');
  
    }

    private function _getForm()
    {
        return new Admin_Form_Producto();
    }
    
}

