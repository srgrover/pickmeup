<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 16/03/17
 * Time: 13:32
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */

class Notificacion{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario", inversedBy="notificacion")
     *
     * @var Usuario
     */
    protected $id_usuario;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $tipo;

    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var integer
     */
    protected $tipo_id;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     *
     * @var boolean
     */
    protected $leido;

    /**
     * @ORM\Column(type="date", nullable=false)
     *
     * @var /date
     */
    protected $created_at;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $extra;

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
     * @return Usuario
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * @param Usuario $id_usuario
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return int
     */
    public function getTipoId()
    {
        return $this->tipo_id;
    }

    /**
     * @param int $tipo_id
     */
    public function setTipoId($tipo_id)
    {
        $this->tipo_id = $tipo_id;
    }

    /**
     * @return boolean
     */
    public function isLeido()
    {
        return $this->leido;
    }

    /**
     * @param boolean $leido
     */
    public function setLeido($leido)
    {
        $this->leido = $leido;
    }

    /**
     * @return /date
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param /date $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param string $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

    /**
     * Get leido
     *
     * @return boolean
     */
    public function getLeido()
    {
        return $this->leido;
    }
}
