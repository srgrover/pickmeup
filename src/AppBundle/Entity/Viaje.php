<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 15/03/17
 * Time: 20:22
 */

namespace AppBundle\Entity;

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
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var integer
     */
    protected $tipo;

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
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $diasRutina;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $origen;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $destino;

    /**
     * @ORM\Column(type="time", nullable=false)
     *
     * @var /time
     */
    protected $horaSalidaIda;

    /**
     * @ORM\Column(type="time", nullable=true)
     *
     * @var /time
     */
    protected $horaSalidaVuelta;

    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var integer
     */
    protected $plazasLibres;

    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var integer
     */
    protected $precio;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario", inversedBy="viaje")
     *
     * @var Usuario
     */
    protected $conductor;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $descripcion = "No hay ninguna descripción para este viaje.";

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
     * @return int
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param int $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
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
        $this->fechaVuelta = $fechaVuelta;
    }

    /**
     * @return string
     */
    public function getDiasRutina()
    {
        return $this->diasRutina;
    }

    /**
     * @param string $diasRutina
     */
    public function setDiasRutina($diasRutina)
    {
        $this->diasRutina = $diasRutina;
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
     * @return int
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
}
