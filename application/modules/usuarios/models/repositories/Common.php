<?php

//namespace Repository;
//use ProyectoZF\DoctrineExtensions\Paginate\Paginate;    

class Usuarios_Model_Repository_Common 
{

    protected $_config;
    protected $_em;

    public function __construct($em)
    {
        $this->_config = Zend_Registry::get('config');
        $this->_em = $em;        
    }
    
    public function obtenerTodos() 
    {
        $q = 'SELECT u FROM Usuarios_Model_Entity_Usuario u ORDER BY u.fecha_cre DESC';
        return $this->_em->createQuery($q)->getResult();                 
    } 


    public function buscarPorNombre($nombre)
    {
        //$q = "SELECT u FROM Usuarios_Model_Entity_Usuario u WHERE u.nombre = '$nombre'";
        //return $this->_em->createQuery($q)->getResult();
        
        $q = "SELECT u FROM Usuarios_Model_Entity_Usuario u WHERE u.nombre LIKE '$nombre%' ORDER BY u.fecha_cre DESC";
        return $this->_em->createQuery($q)->getResult();        
    }    
    
    public function obtenerPorId($id)
    {         
         return $this->_em->find("Usuarios_Model_Entity_Usuario", $id);         
    }      
    
    public function guardar($data)
    {    
        
        $editando = $data['id'] > 0 ? true : false;

        $date = new Zend_Date();

        if ( $editando ) {
            $usuarioNuevoOModificado = $this->obtenerPorId($data['id']);   
        } else {
            $usuarioNuevoOModificado = new Usuarios_Model_Entity_Usuario();
            $usuarioNuevoOModificado->setFecha_cre($date);
        }     
        
        $usuarioNuevoOModificado->setUsuario($data['usuario']);
        $usuarioNuevoOModificado->setEmail($data['email']);        
        $usuarioNuevoOModificado->setClave($data['clave']);
        $usuarioNuevoOModificado->setFecha_mod($date);                 
        $usuarioNuevoOModificado->setNombre($data['nombre']);
        $usuarioNuevoOModificado->setApellido($data['apellido']);
        $usuarioNuevoOModificado->setWebsite($data['website']);
        $usuarioNuevoOModificado->setFoto($data['foto']);
            
        if ( !$editando ) {               
            $this->_em->persist($usuarioNuevoOModificado);
        }
        $this->_em->flush();           
        
    }

    public function eliminar($id)
    {    
        $usuarioEliminado = $this->obtenerPorId($id);
        
        if (null !== $usuarioEliminado) {

            if ( $usuarioEliminado->getFoto() != $this->_config->parametros->mvc->usuarios->perfil->foto->default) {
                $this->eliminarFotoUsuario($usuarioEliminado->getFoto());
            }

            $this->_em->remove($usuarioEliminado);
            $this->_em->flush();
            
            return true;
        }
        
        return false;           
        
    }  

    public function eliminarFotoUsuario($foto)
    {            
        unlink($this->_config->parametros->mvc->usuarios->perfil->foto->large . $foto);
        unlink($this->_config->parametros->mvc->usuarios->perfil->foto->thumb . $foto);        
        
    }  



    //////////////////////////
    // ajax //////////////////
    //////////////////////////


    //////////////////////////

    // form usuario checkUser.js
    public function buscarPorUsuario($usuario)
    {
        $q = "SELECT u FROM Usuarios_Model_Entity_Usuario u WHERE u.usuario = '$usuario'";
        return $this->_em->createQuery($q)->getResult();
    }

    // form usuario checkUser.js
    public function buscarPorEmail($email)
    {
        $q = "SELECT u FROM Usuarios_Model_Entity_Usuario u WHERE u.email = '$email'";
        return $this->_em->createQuery($q)->getResult();
    }  


    //////////////////////////

    // búsqueda de usuarios por nombre - Autocomplete    
    public function buscarPorTerm($term) 
    {
        $q = "SELECT u FROM Usuarios_Model_Entity_Usuario u WHERE u.nombre LIKE '$term%' ORDER BY u.fecha_cre DESC";
        //return $this->_em->createQuery($q)->setMaxResults(6)->getResult();
        return $this->_em->createQuery($q)->getResult();
    }
        
}