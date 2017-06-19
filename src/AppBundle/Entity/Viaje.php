<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 15/03/17
 * Time: 20:22
 */

namespace AppBundle\Entity;

// Acme/TaskBundle/Entity/Task.php
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\DecimalType;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */

class Viaje{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="date", nullable=false)
     *
     * @var /date
     */
    protected $fechaPublicacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $fechaSalida;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $fechaVuelta;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     * @Assert\NotBlank()
     */
    protected $origen;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     * @Assert\NotBlank()
     */
    protected $destino;

    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var integer
     */
    protected $plazasLibres;

    /**
     * @ORM\Column(type="decimal", nullable=false)
     *
     * @var DecimalType
     * @Assert\NotBlank()
     */
    protected $precio;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario", inversedBy="viajes")
     *
     * @var Usuario
     */
    protected $conductor;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $descripcion = "El usuario no ha añadido mas información sobre el viaje.";

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $flexiblididad = "Justo a tiempo";

    /**
     * @ORM\Column(type="boolean", nullable=false)
     *
     * @var boolean
     */
    protected $maximoAtras = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     *
     * @var boolean
     */
    protected $activo = true;

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
     * @return /date
     */
    public function getFechaPublicacion()
    {
        return $this->fechaPublicacion;
    }

    /**
     * @param /date $fechaPublicacion
     */
    public function setFechaPublicacion($fechaPublicacion)
    {
        $this->fechaPublicacion = $fechaPublicacion;
    }

    /**
     * @return string
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * @param string $origen
     */
    public function setOrigen($origen)
    {
        $this->origen = $origen;
    }

    /**
     * @return string
     */
    public function getDestino()
    {
        return $this->destino;
    }

    /**
     * @param string $destino
     */
    public function setDestino($destino)
    {
        $this->destino = $destino;
    }

    /**
     * @return int
     */
    public function getPlazasLibres()
    {
        return $this->plazasLibres;
    }

    /**
     * @param int $plazasLibres
     */
    public function setPlazasLibres($plazasLibres)
    {
        $this->plazasLibres = $plazasLibres;
    }

    /**
     * @return Usuario
     */
    public function getConductor()
    {
        return $this->conductor;
    }

    /**
     * @param Usuario $conductor
     */
    public function setConductor($conductor)
    {
        $this->conductor = $conductor;
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
     * @return int
     */
    public function getFlexiblididad()
    {
        return $this->flexiblididad;
    }

    /**
     * @param int $flexiblididad
     */
    public function setFlexiblididad($flexiblididad)
    {
        $this->flexiblididad = $flexiblididad;
    }

    /**
     * @return boolean
     */
    public function isMaximoAtras()
    {
        return $this->maximoAtras;
    }

    /**
     * @param boolean $maximoAtras
     */
    public function setMaximoAtras($maximoAtras)
    {
        $this->maximoAtras = $maximoAtras;
    }


    /**
     * Get maximoAtras
     *
     * @return boolean
     */
    public function getMaximoAtras()
    {
        return $this->maximoAtras;
    }

    /**
     * @return \DateTime
     */
    public function getFechaSalida()
    {
        return $this->fechaSalida;
    }

    /**
     * @param \DateTime $fechaSalida
     */
    public function setFechaSalida($fechaSalida)
    {
//        $fecha = explode("/", $fechaSalida);
//        dump($fecha);
        $this->fechaSalida = $fechaSalida;
    }

    /**
     * @return \DateTime
     */
    public function getFechaVuelta()
    {
        return $this->fechaVuelta;
    }

    /**
     * @param \DateTime $fechaVuelta
     */
    public function setFechaVuelta($fechaVuelta)
    {
//        $fecha = explode("/", $fechaVuelta);
        $this->fechaVuelta = $fechaVuelta;
    }

    /**
     * @return mixed
     */
    public function getHoraSalidaIda()
    {
        return $this->horaSalidaIda;
    }

    /**
     * @param mixed $horaSalidaIda
     */
    public function setHoraSalidaIda($horaSalidaIda)
    {
        $this->horaSalidaIda = $horaSalidaIda;
    }

    /**
     * @return mixed
     */
    public function getHoraSalidaVuelta()
    {
        return $this->horaSalidaVuelta;
    }

    /**
     * @param mixed $horaSalidaVuelta
     */
    public function setHoraSalidaVuelta($horaSalidaVuelta)
    {
        $this->horaSalidaVuelta = $horaSalidaVuelta;
    }

    /**
     * @return DecimalType
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * @param int $precio
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    /**
     * @return bool
     */
    public function isActivo()
    {
        return $this->activo;
    }

    /**
     * @param bool $activo
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    }

}
