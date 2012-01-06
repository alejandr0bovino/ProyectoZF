<?php

//namespace Repository;

interface Usuarios_Model_Repository_Interface 
{

    public function obtenerTodos(); 
        
    public function buscarPorNombre($nombre);
    
}

