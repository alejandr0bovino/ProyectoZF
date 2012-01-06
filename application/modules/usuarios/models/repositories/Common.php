<?php

//namespace Repository;
 
class Usuarios_Model_Repository_Common 
{
    protected $_em;
    
    public function __construct() 
    {
        $this->_em = Zend_Registry::get('em');
    }  
    
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

    
    public function obtenerPorId($id)
    {         
         return $this->_em->find("Usuarios_Model_Entity_Usuario", $id);         
    }      
    
    public function guardar($data)
    {    
        if ( $data['id'] > 0 ) {
            $usuarioNuevoOModificado = $this->_em->find("Usuarios_Model_Entity_Usuario", $data['id']);            
        } else {
            $usuarioNuevoOModificado = new Usuarios_Model_Entity_Usuario();
        }     
        
        $usuarioNuevoOModificado->setNombre($data['nombre']);
        $usuarioNuevoOModificado->setApellido($data['apellido']);
        $usuarioNuevoOModificado->setEmail($data['email']);        
        $usuarioNuevoOModificado->setClave($data['clave']);        

        $this->_em->persist($usuarioNuevoOModificado);
        $this->_em->flush();           
        
    }

    public function eliminar($id)
    {    
        $usuarioEliminado = $this->_em->find("Usuarios_Model_Entity_Usuario", $id);
        
        if (null !== $usuarioEliminado) {
            $this->_em->remove($usuarioEliminado);
            $this->_em->flush();
            
            return true;
        }
        
        return false;           
        
    }    
        
}