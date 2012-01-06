<?php

class Usuarios_Bootstrap extends Zend_Application_Module_Bootstrap {
   
    protected function _initAutoload()
    {
           
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Usuarios_',
            'basePath'  => APPLICATION_PATH . '/modules/usuarios',
            'resourceTypes' => 
            array(                            
                    'services' => array('path' => '/models/services', 'namespace' => 'Model_Service_'),
                    'entities' => array('path' => '/models/entities', 'namespace' => 'Model_Entity_'),
                    'repositories' => array('path' => '/models/repositories','namespace' => 'Model_Repository_')            
                )));
        
            return $autoloader;
    }    
}
