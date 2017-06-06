<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 16/03/17
 * Time: 12:57
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */

class Vehiculo{
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
    protected $marca;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $modelo;

    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var integer
     */
    protected $plazas;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Usuario", inversedBy="vehiculo")
     *
     * @var Usuario
     */
    protected $conductor;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $color;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @var boolean
     */
    protected $activo;

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
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * @param string $marca
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;
    }

    /**
     * @return string
     */
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * @param string $modelo
     */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * @return int
     */
    public function getPlazas()
    {
        return $this->plazas;
    }

    /**
     * @param int $plazas
     */
    public function setPlazas($plazas)
    {
        $this->plazas = $plazas;
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
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
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
