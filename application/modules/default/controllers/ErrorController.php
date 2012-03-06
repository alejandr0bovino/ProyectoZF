<?php

class Default_ErrorController extends ProyectoZF_Controller_Action
{
    public function deniedAction() {
        $this->view->denyAction =  ucfirst($this->_getParam('denyAction', ''));
        //$this->view->denyModule =  $this->_getParam('denyModule', '');
    }
    
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'Error de sistema';
            return;
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Página no encontrada';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                switch ($errors->exception->getCode()) {
                    case 4462:
                        $this->view->message = 'Recurso no encontrado';
                        break;
                    case 4463:
                        $this->view->message = 'Recurso no accesible';
                        break;
                    default:
                        $this->view->message = 'Error de la aplicación';
                        break;
                }
                break;
        }
                 
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($this->view->message, $priority, $errors->exception);
            $log->log('Request Parameters', $priority, $errors->request->getParams());
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
                        
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request   = $errors->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

    
 /*   public function denyAction(){
         $this->view->recursoDenegado =  $this->_getParam('denegado', '');
    }*/

}

