<?php

class ProyectoZF_Controller_Plugin_Layout extends Zend_Controller_Plugin_Abstract 
{

	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{

	    $module = $request->getModuleName();
	    $layout = Zend_Layout::getMvcInstance();

	    $layoutsDir = $layout->getLayoutPath();

	    if(file_exists($layoutsDir . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . "layout.phtml")) {
	        $layout->setLayout($module . DIRECTORY_SEPARATOR . "layout");
	    } else {
	        $layout->setLayout("layout");
	    }
	        
	}

}	