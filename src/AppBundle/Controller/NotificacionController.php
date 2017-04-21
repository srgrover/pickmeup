<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

class NotificacionController extends Controller
{
    /**
     * @Route("/notificaciones", name="notificaciones")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user_id = $user->getId();

        $dql = "SELECT n FROM AppBundle:Notificacion n WHERE n.id_usuario = $user_id ORDER BY n.id DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $notificacion = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),           //page es la variable de la url
            5                                       //5 usuarios por pagina
        );

        return $this->render('notificacion/notificacion.html.twig', [
            'user' => $user,
            'paginador' => $notificacion
        ]);
    }
}
