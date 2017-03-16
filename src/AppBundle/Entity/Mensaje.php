<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 16/03/17
 * Time: 12:47
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */

class Mensaje{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var /datetime
     */
    protected $fechaEnviado;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario", inversedBy="")
     *
     * @var Usuario
     */
    protected $emisor;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario", inversedBy="receptor")
     *
     * @var Usuario
     */
    protected $receptor;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $mensaje;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @var boolean
     */
    protected $leido;

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
     * @return mixed
     */
    public function getFechaEnviado()
    {
        return $this->fechaEnviado;
    }

    /**
     * @param mixed $fechaEnviado
     */
    public function setFechaEnviado($fechaEnviado)
    {
        $this->fechaEnviado = $fechaEnviado;
    }

    /**
     * @return Usuario
     */
    public function getEmisor()
    {
        return $this->emisor;
    }

    /**
     * @param Usuario $emisor
     */
    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;
    }

    /**
     * @return Usuario
     */
    public function getReceptor()
    {
        return $this->receptor;
    }

    /**
     * @param Usuario $receptor
     */
    public function setReceptor($receptor)
    {
        $this->receptor = $receptor;
    }

    /**
     * @return string
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * @param string $mensaje
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    /**
     * @return mixed
     */
    public function getLeido()
    {
        return $this->leido;
    }

    /**
     * @param mixed $leido
     */
    public function setLeido($leido)
    {
        $this->leido = $leido;
    }

}
