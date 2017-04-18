<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 18/04/17
 * Time: 18:38
 */

namespace AppBundle\Services;

use AppBundle\Entity\Notificacion;


class NotificacionService{

    public $manager;

    public function __construct($manager){      //Entity manager de Doctrine para trabajar con entidades
        $this->manager = $manager;
    }

    //Guarda una notificación en la base de datos
    public function set($usuario, $tipo, $tipoId, $extra = null){
        $status = false;
        $em = $this->manager;

        $notificacion = new Notificacion();
        $notificacion->setIdUsuario($usuario);
        $notificacion->setTipo($tipo);
        $notificacion->setTipoId($tipoId);
        $notificacion->setLeido(false);
        $notificacion->setCreatedAt(new \DateTime("now"));
        $notificacion->setExtra($extra);

        $em->persist($notificacion);

        $flush = $em->flush();

        if($flush == null){
            $status = true;
        }

        return $status;
    }
}