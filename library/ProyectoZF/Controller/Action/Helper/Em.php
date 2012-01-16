<?php

namespace ProyectoZF\Controller\Action\Helper;

//use Doctrine\ORM\EntityManager;

class Em extends \Zend_Controller_Action_Helper_Abstract
{

    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function direct($serviceClass)
    {
        return new $serviceClass($this->em);
    }

}