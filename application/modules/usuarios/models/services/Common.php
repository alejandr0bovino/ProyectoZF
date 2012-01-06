<?php

//namespace Service;

use Doctrine\ORM\EntityManager;

class Usuarios_Model_Service_Common 
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function obtenerTodos()
    {         
         return $this->em->getRepository("Usuarios_Model_Entity_Usuario")->obtenerTodos();
    }
  
    public function buscarPorNombre($nombre)
    {
        return $this->em->getRepository("Usuarios_Model_Entity_Usuario")->buscarPorNombre($nombre);
    }

    
    public function obtenerPorId($id)
    {         
         return $this->em->find("Usuarios_Model_Entity_Usuario", $id);         
    }      
    
    public function guardar($data)
    {    
        if ( $data['id'] > 0 ) {
            $usuarioNuevoOModificado = $this->em->find("Usuarios_Model_Entity_Usuario", $data['id']);            
        } else {
            $usuarioNuevoOModificado = new Usuarios_Model_Entity_Usuario();
        }     
        
        $usuarioNuevoOModificado->setNombre($data['nombre']);
        $usuarioNuevoOModificado->setApellido($data['apellido']);
        $usuarioNuevoOModificado->setEmail($data['email']);        
        $usuarioNuevoOModificado->setClave($data['clave']);        

        $this->em->persist($usuarioNuevoOModificado);
        $this->em->flush();           
        
    }

    public function eliminar($id)
    {    
        $usuarioEliminado = $this->em->find("Usuarios_Model_Entity_Usuario", $id);
        
        if (null !== $usuarioEliminado) {
            $this->em->remove($usuarioEliminado);
            $this->em->flush();
            
            return true;
        }
        
        return false;           
        
    }    
    
    
} 


