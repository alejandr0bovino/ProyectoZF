<?php
 
//namespace Entity;
 
/**
 * @Entity
 * @Table(name="usuarios")
 */

class Usuarios_Model_Entity_Usuario
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USUARIO = 'usuario';
 /**
     * @Id
     * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */    
    private $id;

    /** 
     * @Column(name="usuario", type="string", length=30)
     */
    private $usuario;
    
    /** 
     * @Column(name="email", type="string", length=50)
     */
    private $email;

    /** 
     * @Column(name="clave", type="string", length=30)
     */
    private $clave;

    /**
     *  @Column(name="role", type="string", length=30)
     */
    private $role = self::ROLE_USUARIO;  
        
    /** 
     * @Column(name="fecha_cre", type="datetime")
     */
    private $fecha_cre;

    /** 
     * @Column(name="fecha_mod", type="datetime")
     */
    private $fecha_mod;    

    /** 
     * @Column(name="nombre", type="string", length=30)
     */
    private $nombre;
    
    /** 
     * @Column(name="apellido", type="string", length=30)
     */
    private $apellido;

    /** 
     * @Column(name="website", type="string", length=100)
     */
    private $website;

    /** 
     * @Column(name="foto", type="string", length=50)
     */
    private $foto;    
    

    

  

    /** 
     * @param \Doctring\Common\Collections\ArrayCollection $property
     * @OneToMany(targetEntity="Catalogo_Model_Entity_Comentario", mappedBy="usuario", cascade={"persist", "remove"})
     */        
    private $comentarios;
    
    
    

    public function __contstruct()
    {
        $this->comentarios =  new \Doctrine\Common\Collections\ArrayCollection();
    }    
    
    public function getId()
    {
        return $this->id;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }
      
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }    

    public function getClave()
    {
        return $this->clave;
    }
    public function setClave($clave)
    {
        $this->clave = $clave;
    }
            
    public function getRole()
    {
        return $this->role;
    }
    public function setRole($role)
    {
        $this->role = $role;
    }
    
    public function getFecha_cre()
    {
        return $this->fecha_cre;
    }
    public function setFecha_cre($fecha_cre)
    {
        $this->fecha_cre = $fecha_cre;
    }
    
    public function getFecha_mod()
    {
        return $this->fecha_mod;
    }
    public function setFecha_mod($fecha_mod)
    {
        $this->fecha_mod = $fecha_mod;
    }       
                    
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }    

    public function getApellido()
    {
        return $this->apellido;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }  

    public function getWebsite()
    {
        return $this->website;
    }
    public function setWebsite($website)
    {
        $this->website = $website;
    }       
     
    public function getFoto()
    {
        return $this->foto;
    }
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }        


    
    
    public function getComentarios() {
        return $this->comentarios;
    }   
    public function setComentarios ($comentarios) {
        $this->comentarios = $comentarios;
        return $this->comentarios;
    }
  

    public function addComentario (Catalogo_Model_Entity_Comentario $comentario) {
        $this->comentarios[] = $comentario;
    }     
 
}