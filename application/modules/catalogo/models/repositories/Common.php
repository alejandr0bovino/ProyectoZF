<?php

//namespace Repository;
 
class Catalogo_Model_Repository_Common 
{
    protected $_em;

    public function __construct($em)
    {
        $this->_em = $em;
    }    
    
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

    
    public function obtenerPorId($id)
    {                 
        return $this->_em->find("Catalogo_Model_Entity_Producto", $id);
    }    
    
    
    public function guardar($data)
    {    
        if ( $data['id'] > 0 ) {
            $productoNuevo = $this->_em->find("Catalogo_Model_Entity_Producto", $data['id']);
        } else {
            $productoNuevo = new Catalogo_Model_Entity_Producto();
           
        }     
        
        $productoNuevo->setDescripcion($data['descripcion']);
        $productoNuevo->setPrecio($data['precio']);
        $productoNuevo->setCantidad($data['cantidad']);        

        $this->_em->persist($productoNuevo);
        
//        if ( $data['id'] == 0 ) {
//            
//            $comentario = new Catalogo_Model_Entity_Comentario();
//            
//            $comentario->setProducto($productoNuevo);
//            
//            $u = $this->_em->find("Usuarios_Model_Entity_Usuario", '7');
//            $comentario->setUsuario($u);
//            
//            $comentario->setComentario("comentario 1ooooooooooo oooooooooooo ooooooooooo");
//            
//            $productoNuevo->addComentario($comentario);            
//            
//        }
        
        $this->_em->flush();           
        
    }    
    
    
    public function eliminar($id)
    {    
        $productoEliminado = $this->_em->find("Catalogo_Model_Entity_Producto", $id);
        
        if (null !== $productoEliminado) {
            $this->_em->remove($productoEliminado);
            $this->_em->flush();
            
            return true;
        }
        
        return false;           
        
    }     
    
}



//    public function createPost($data)
//    {
//        $post = new Post();
//        $post->setTitle($data['title']);
//        $post->setContent($data['content']);
//
//        $this->em->persist($post);
//        $this->em->flush(); // flush now so we can get Post ID
//
//        return $post;
//    }

//    public function updateProduct(array $data, $id)
//    {
//        $product = $this->em->getRepository('\ProyectoZF\Domain\Catalogo\Entity\Producto')->find($id);
// 
//        if(!$product){
//            throw new ProductNotFoundException();
//        }
// 
//        $product->setName($data['name']);
//        $product->setPrice($data['price']);
// 
//        $this->dm->flush();
//
//    }  