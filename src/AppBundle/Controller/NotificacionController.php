<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificacionController extends Controller
{
    /**
     * @Route("/notificaciones", name="notificaciones")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $usuario_id = $usuario->getId();

        $notificaciones = $em->createQueryBuilder()
            ->select('n')
            ->from('AppBundle:Notificacion', 'n')
            ->where('n.id_usuario = :usuario')
            ->setParameter('usuario', $usuario_id)
            ->orderBy('n.created_at')
            ->getQuery()
            ->getResult();

        $paginator = $this->get('knp_paginator');
        $notifications = $paginator->paginate(
            $notificaciones,
            $request->query->getInt('page', 1),           //page es la variable de la url
            5                                                   //5 usuarios por pagina
        );

        $notificacion = $this->get('app.notificacion_service');
        $notificacion->leer($usuario_id);

        return $this->render('notificacion/notificacion.html.twig', [
            'user' => $usuario,
            'paginador' => $notifications
        ]);
    }

    /**
     * @Route("/notificaciones/count", name="notificaciones_count")
     */
    public function countNotificationsAction(){
        $em = $this->getDoctrine()->getManager();
        $notificacion_repo = $em->getRepository("AppBundle:Notificacion");
        $notificaciones = $notificacion_repo->findBy([
           'id_usuario' => $this->getUser(),
            'leido' => false
        ]);

        return new Response(count($notificaciones));
    }

}
