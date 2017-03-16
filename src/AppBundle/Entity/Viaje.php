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
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var integer
     */
    protected $plazasLibres;

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
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var integer
     */
    protected $flexiblididad = 1;

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
}
