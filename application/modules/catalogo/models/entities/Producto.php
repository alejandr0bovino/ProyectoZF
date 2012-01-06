<?php
 
//namespace Entity;
 
/**
 * @Entity(repositoryClass="Catalogo_Model_Repository_Common")
 * @Table(name="productos")
 */

class Catalogo_Model_Entity_Producto
{
 
 /**
     * @Id
     * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */    
    private $id;

    /** 
     * @Column(name="descripcion", type="string", length=32)
     */    
    private $descripcion;
    
    /** 
     * @Column(name="precio", type="integer")
     */    
    private $precio;

    /** 
     * @Column(name="cantidad", type="integer")
     */     
    private $cantidad;

    /** 
     * @param \Doctring\Common\Collections\ArrayCollection $property
     * @OneToMany(targetEntity="Catalogo_Model_Entity_Comentario", mappedBy="producto", cascade={"persist", "remove"})
     */        
    private $comentarios;
    
    

    
    
 
    public function __contstruct()
    {
        $this->comentarios =  new \Doctrine\Common\Collections\ArrayCollection();
    }

    
    public function getId() {
        return $this->id;
    }

    
    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }


    public function getComentarios() {
        return $this->comentarios;
    }   
    
    public function setComentarios ($comentarios) {
        $this->comentarios = $comentarios;
       // return $this->comentarios;
    }
    
    public function addComentario (Catalogo_Model_Entity_Comentario $comentario) {
        $this->comentarios[] = $comentario;
    }    
      
    
//    public function hasComentario (Catalogo_Model_Entity_Comentario $comentario) {
//        $comentarios = array();
//        foreach ($this->getComentarios() as $oc) {
//            $comentarios[] = $oc->getComentario();
//        }
//        if (in_array($comentario->getComentario(), $comentarios))   
//            return true;
//    }    




 
}