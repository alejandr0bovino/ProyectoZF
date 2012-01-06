<?php
 
//namespace Entity;

/**
 * @Entity
 * @Table(name="productos_comentarios")
 */

class Catalogo_Model_Entity_Comentario
{
 
 /**
     * @Id
     * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */    
    private $id;

    /** 
     * @Column(name="comentario", type="string", length=255)
     */    
    private $comentario;
       
    /**
     * @var Catalogo_Model_Entity_Producto
     * @ManyToOne(targetEntity="Catalogo_Model_Entity_Producto", inversedBy="comentarios")
     * @JoinColumns({
     *  @JoinColumn(name="producto_id", referencedColumnName="id")
     * })
     */
    private $producto;

    /**
     * @var Usuarios_Model_Entity_Usuario
     * @ManyToOne(targetEntity="Usuarios_Model_Entity_Usuario", inversedBy="comentarios")
     * @JoinColumns({
     *  @JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;    


    
    

    public function getId() {
        return $this->id;
    }

   
    public function getComentario() {
        return $this->comentario;
    }

    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }

    public function getProducto() {
        return $this->producto;
    }    
   
    public function setProducto(Catalogo_Model_Entity_Producto $producto)
    {
       $this->producto = $producto;
    }

    public function getUsuario() {
        return $this->usuario;
    }    
   
    public function setUsuario(Usuarios_Model_Entity_Usuario $usuario)
    {
       $this->usuario = $usuario;
    }    

    
    
    
}