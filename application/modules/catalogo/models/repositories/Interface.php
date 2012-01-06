<?php

//namespace Repository;

interface Catalogo_Model_Repository_Interface 
{

    public function obtenerTodos(); 
        
    public function buscarPorDescripcion($descripcion);
    
}

