<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 15/03/17
 * Time: 19:40
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 */

class Usuario implements UserInterface, \Serializable {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $rol;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=25, unique=true, nullable=false)
     * @Assert\Length(
     *     min = 4,
     *     max = 16,
     *     minMessage = "El nickname debe tener como mínimo 4 caracteres",
     *     maxMessage = "El nickname debe tener como mínimo 16 caracteres"
     * )
     * @Assert\NotBlank(
     *     message = "El Nickname no puede estar vacío"
     * )
     */
    protected $nick;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Length(
     *     min = 8,
     *     minMessage = "La contraseña debe tener como mínimo 8 caracteres"
     * )
     *
     * @var string
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @Assert\NotBlank(
     *     message = "El email no puede estar vacío"
     * )
     * @Assert\Email(
     *     message = "El email {{ value }} no es válido.",
     *     checkMX = "false"
     * )
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(
     *     message = "El nombre no puede estar vacío"
     * )
     *
     * @var string
     */
    protected $nombre;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(
     *     message = "Los apellidos no pueden estar vacíos"
     * )
     *
     * @var string
     */
    protected $apellidos;

    /**
     * @ORM\Column(type="date", nullable=false)
     *
     * @var /date
     */
    protected $fechaNacimiento;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(
     *     message = "La ciudad no puede estar vacía"
     * )
     *
     * @var string
     */
    protected $ciudad;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $provincia;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $pais;

    /**
     * @ORM\Column(type="string", length=9, nullable=false)
     * @Assert\Length(
     *     min = 9,
     *     max = 9,
     *     exactMessage = "El número de contacto debe tener 9 dígitos"
     * )
     * @Assert\NotBlank(
     *     message = "El número de contacto no puede estar vacío"
     * )
     *
     * @var string
     */
    protected $telefono;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $descripcion;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $imagenPerfil;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $imagenFondo;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     *
     * @var boolean
     */
    protected $estado = true;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var \DateTime
     */
    protected $activacion;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Viaje", mappedBy="conductor")
     *
     * @var Viaje
     */
    protected $viaje;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vehiculo", mappedBy="conductor")
     *
     * @var Vehiculo
     */
    protected $vehiculo;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Notificacion", mappedBy="id_usuario")
     *
     * @var Notificacion
     */
    protected $notificacion;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Mensaje", mappedBy="emisor")
     *
     * @var Mensaje
     */
    protected $emisor;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Mensaje", mappedBy="receptor")
     *
     * @var Mensaje
     */
    protected $receptor;

    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER', 'ROLE_ADMIN');
    }

    public function eraseCredentials()
    {

    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password
        ));
    }

    public function  unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password
            ) = unserialize($serialized);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * @param string $rol
     */
    public function setRol($rol)
    {
        $this->rol = $rol;
    }

    /**
     * @return string
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * @param string $nick
     */
    public function setNick($nick)
    {
        $this->nick = $nick;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * @param string $apellidos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return /date
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * @param /date $fechaNacimiento
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    /**
     * @return string
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * @param string $ciudad
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    }

    /**
     * @return string
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * @param string $provincia
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;
    }

    /**
     * @return string
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * @param string $pais
     */
    public function setPais($pais)
    {
        $this->pais = $pais;
    }

    /**
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return string
     */
    public function getImagenPerfil()
    {
        return $this->imagenPerfil;
    }

    /**
     * @param string $imagenPerfil
     */
    public function setImagenPerfil($imagenPerfil)
    {
        $this->imagenPerfil = $imagenPerfil;
    }

    /**
     * @return string
     */
    public function getImagenFondo()
    {
        return $this->imagenFondo;
    }

    /**
     * @param string $imagenFondo
     */
    public function setImagenFondo($imagenFondo)
    {
        $this->imagenFondo = $imagenFondo;
    }

    /**
     * @return boolean
     */
    public function isEstado()
    {
        return $this->estado;
    }

    /**
     * @param boolean $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return \DateTime
     */
    public function getActivacion()
    {
        return $this->activacion;
    }

    /**
     * @param \DateTime $activacion
     */
    public function setActivacion($activacion)
    {
        $this->activacion = $activacion;
    }

    /**
     * @return Viaje
     */
    public function getViaje()
    {
        return $this->viaje;
    }

    /**
     * @param Viaje $viaje
     */
    public function setViaje($viaje)
    {
        $this->viaje = $viaje;
    }

    /**
     * @return Vehiculo
     */
    public function getVehiculo()
    {
        return $this->vehiculo;
    }

    /**
     * @param Vehiculo $vehiculo
     */
    public function setVehiculo($vehiculo)
    {
        $this->vehiculo = $vehiculo;
    }

    /**
     * @return Notificacion
     */
    public function getNotificacion()
    {
        return $this->notificacion;
    }

    /**
     * @param Notificacion $notificacion
     */
    public function setNotificacion($notificacion)
    {
        $this->notificacion = $notificacion;
    }

    /**
     * @return Mensaje
     */
    public function getEmisor()
    {
        return $this->emisor;
    }

    /**
     * @param Mensaje $emisor
     */
    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;
    }

    /**
     * @return Mensaje
     */
    public function getReceptor()
    {
        return $this->receptor;
    }

    /**
     * @param Mensaje $receptor
     */
    public function setReceptor($receptor)
    {
        $this->receptor = $receptor;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->viaje = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notificacion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->emisor = new \Doctrine\Common\Collections\ArrayCollection();
        $this->receptor = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get estado
     *
     * @return boolean
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Add viaje
     *
     * @param Viaje $viaje
     *
     * @return Usuario
     */
    public function addViaje(Viaje $viaje)
    {
        $this->viaje[] = $viaje;

        return $this;
    }

    /**
     * Remove viaje
     *
     * @param Viaje $viaje
     */
    public function removeViaje(Viaje $viaje)
    {
        $this->viaje->removeElement($viaje);
    }

    /**
     * Add notificacion
     *
     * @param Notificacion $notificacion
     *
     * @return Usuario
     */
    public function addNotificacion(Notificacion $notificacion)
    {
        $this->notificacion[] = $notificacion;

        return $this;
    }

    /**
     * Remove notificacion
     *
     * @param Notificacion $notificacion
     */
    public function removeNotificacion(Notificacion $notificacion)
    {
        $this->notificacion->removeElement($notificacion);
    }

    /**
     * Add emisor
     *
     * @param Mensaje $emisor
     *
     * @return Usuario
     */
    public function addEmisor(Mensaje $emisor)
    {
        $this->emisor[] = $emisor;

        return $this;
    }

    /**
     * Remove emisor
     *
     * @param Mensaje $emisor
     */
    public function removeEmisor(Mensaje $emisor)
    {
        $this->emisor->removeElement($emisor);
    }

    /**
     * Add receptor
     *
     * @param Mensaje $receptor
     *
     * @return Usuario
     */
    public function addReceptor(Mensaje $receptor)
    {
        $this->receptor[] = $receptor;

        return $this;
    }

    /**
     * Remove receptor
     *
     * @param Mensaje $receptor
     */
    public function removeReceptor(Mensaje $receptor)
    {
        $this->receptor->removeElement($receptor);
    }
}
