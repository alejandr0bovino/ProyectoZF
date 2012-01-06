<?php

//namespace Service;

use Doctrine\ORM\EntityManager;

class Catalogo_Model_Service_Common 
{
    private $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function obtenerTodos()
    {         
         return $this->em->getRepository("Catalogo_Model_Entity_Producto")->obtenerTodos();
    }
   
    public function buscarPorDescripcion($descripcion)
    {
        return $this->em->getRepository("Catalogo_Model_Entity_Producto")->buscarPorDescripcion($descripcion);
    }
    
    
    public function obtenerPorId($id)
    {         
         return $this->em->find("Catalogo_Model_Entity_Producto", $id);
    }     
    
    public function guardar($data)
    {    
        if ( $data['id'] > 0 ) {
            $productoNuevo = $this->em->find("Catalogo_Model_Entity_Producto", $data['id']);
        } else {
            $productoNuevo = new Catalogo_Model_Entity_Producto();
           
        }     
        
        $productoNuevo->setDescripcion($data['descripcion']);
        $productoNuevo->setPrecio($data['precio']);
        $productoNuevo->setCantidad($data['cantidad']);        

        $this->em->persist($productoNuevo);
        
        if ( $data['id'] == 0 ) {
            
            $comentario = new Catalogo_Model_Entity_Comentario();
            
            $comentario->setProducto($productoNuevo);
            
            $u = $this->em->find("Usuarios_Model_Entity_Usuario", '7');
            $comentario->setUsuario($u);
            
            $comentario->setComentario("comentario 1ooooooooooo oooooooooooo ooooooooooo");
            
            $productoNuevo->addComentario($comentario);            
            
        }
        
        $this->em->flush();           
        
    }

    public function eliminar($id)
    {    
        $productoEliminado = $this->em->find("Catalogo_Model_Entity_Producto", $id);
        
        if (null !== $productoEliminado) {
            $this->em->remove($productoEliminado);
            $this->em->flush();
            
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



