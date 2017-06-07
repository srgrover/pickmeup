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

class Semana{
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
    protected $dia;

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
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * @param string $dia
     */
    public function setDia($dia)
    {
        $this->dia = $dia;
    }

}
