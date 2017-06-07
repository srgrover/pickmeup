<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 18/04/17
 * Time: 18:38
 */

namespace AppBundle\Services;

use AppBundle\Entity\Notificacion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Class NotificacionService
 * @package AppBundle\Services
 * @Security("has_role('ROLE_USER')")
 */
class NotificacionService{

    public $manager;

    public function __construct($manager){      //Entity manager de Doctrine para trabajar con entidades
        $this->manager = $manager;
    }

    //Guarda una notificación en la base de datos
    public function set($usuario, $tipo, $tipoId, $ruta_id, $tipo_ruta, $extra = null){
        $status = false;
        $em = $this->manager;

        $notificacion = new Notificacion();
        $notificacion->setIdUsuario($usuario);
        $notificacion->setTipo($tipo);
        $notificacion->setTipoId($tipoId);
        $notificacion->setRutaId($ruta_id);
        $notificacion->setTipoRuta($tipo_ruta);
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

    public function leer($usuario){
        $respuesta = false;
        $em = $this->manager;

        $notificaciones = $em->getRepository("AppBundle:Notificacion")
            ->findBy(['id_usuario' => $usuario]);

        foreach($notificaciones as $notificacion){
            $notificacion->setLeido(true);

            $em->persist($notificacion);
        }

        $flush = $em->flush();

        if ($flush == null){
            $respuesta = true;
        }

        return $respuesta;
    }
}