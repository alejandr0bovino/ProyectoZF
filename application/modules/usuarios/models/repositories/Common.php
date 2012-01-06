<?php

//namespace Repository;
 
use Doctrine\ORM\EntityRepository;

class Usuarios_Model_Repository_Common extends 
    EntityRepository implements
    Usuarios_Model_Repository_Interface
{
    
    public function obtenerTodos() 
    {
        $q = 'SELECT u FROM Usuarios_Model_Entity_Usuario u ORDER BY u.nombre ASC';
        return $this->_em->createQuery($q)->getResult();                 
    }          
    
    public function buscarPorNombre($nombre)
    {
        $q = "SELECT u FROM Usuarios_Model_Entity_Usuario u WHERE u.nombre = '$nombre'";
        return $this->_em->createQuery($q)->getResult();
    }    
}