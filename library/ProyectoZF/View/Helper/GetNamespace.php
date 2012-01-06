<?php

 class Zend_View_Helper_GetNamespace 
    { 
        public $front; 

        public function __construct() 
        { 
            $this->front = Zend_Controller_Front::getInstance(); 
        } 

        public function getNamespace() 
        { 
            return $this->front->getRequest()->getModuleName(); 
        } 
    } 