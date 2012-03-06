<?php

class ProyectoZF_Controller_Plugin_Referrer extends Zend_Controller_Plugin_Abstract 
{ 

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
         
        if (!Admin_Model_Login::isLoggedIn() && $request->getControllerName() != "login") {

            $referrer = new Zend_Session_Namespace('referrer');

           /* $referrer->afterLogin = array( 
               "action" => $request->getActionName(), 
               "controller" => $request->getControllerName(), 
               "module" => $request->getModuleName(), 

            ); */
            

/*            $referrerModule = $request->getModuleName();              
            $referrerController = $request->getControllerName();
            $referrerAction = $request->getActionName();
            $referrer->afterLogin = "/" . $referrerModule . "/" . $referrerController . "/" . $referrerAction . "/";*/

            $referrer->afterLogin = $request->getRequestUri();

        }        

      
    }
    
} 