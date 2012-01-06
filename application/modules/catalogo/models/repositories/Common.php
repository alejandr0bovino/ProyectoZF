<?php

//namespace Repository;
 
use Doctrine\ORM\EntityRepository;

class Catalogo_Model_Repository_Common extends 
    EntityRepository implements
    Catalogo_Model_Repository_Interface
{
      
    public function obtenerTodos() 
    {
        $q = 'SELECT p FROM Catalogo_Model_Entity_Producto p ORDER BY p.id DESC';
        return $this->_em->createQuery($q)->getResult();                 
    }    
    
    public function buscarPorDescripcion($descripcion)
    {
        $q = "SELECT p FROM Catalogo_Model_Entity_Producto p WHERE p.descripcion = '$descripcion'";
        return $this->_em->createQuery($q)->getResult();
    }    
}