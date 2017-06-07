<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Rutina;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Viaje;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificacionController extends Controller{
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
            ->orderBy('n.created_at', 'DESC')
            ->getQuery()
            ->getResult();

        $paginator = $this->get('knp_paginator');
        $notifications = $paginator->paginate(
            $notificaciones,
            $request->query->getInt('page', 1),
            5
        );

        sleep(1);
        $notificacion = $this->get('app.notificacion_service');
        $notificacion->leer($usuario_id);

        return $this->render('notificacion/notificacion.html.twig', [
            'user' => $usuario,
            'paginador' => $notifications
        ]);
    }


    /**
     * @Route("/notificaciones/all", name="all_notificaciones")
     * @param Request $request
     * @return Response
     */
    public function allNotificacionAction(Request $request){
        if (!$this->getUser()->isAdmin()){
            return $this->redirectToRoute('homepage');
        }

        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $usuario_id = $usuario->getId();

        $notificaciones = $em->createQueryBuilder()
            ->select('n')
            ->from('AppBundle:Notificacion', 'n')
            ->orderBy('n.created_at', 'DESC')
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

    /**
     * @Route("/peticion/viaje/{conductor}/{usuario}/{viaje}", name="peticion_viaje")
     */
    public function peticionViajeAction(Usuario $conductor, Usuario $usuario, Viaje $viaje){
        try{
            $notificacion = $this->get('app.notificacion_service');
            $notificacion->set($conductor, 'peticion', $usuario->getId(), $viaje->getId(), 'viaje');
            $this->addFlash('estado', 'La petición se ha mandado al conductor. Espera la respuesta');
        }catch (Exception $exception){
            $this->addFlash('error', 'Hubo algún problema al procesar la petición');
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/peticion/rutina/{conductor}/{usuario}/{rutina}", name="peticion_rutina")
     * @param Usuario $conductor
     * @param Usuario $usuario
     * @param Viaje $rutina
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function peticionRutinaAction(Usuario $conductor, Usuario $usuario, Viaje $rutina){
        try{
            $notificacion = $this->get('app.notificacion_service');
            $notificacion->set($conductor, 'peticion', $usuario->getId(), $rutina->getId(), 'rutina');
            $this->addFlash('estado', 'La petición se ha mandado al conductor. Espera la respuesta');
        }catch (Exception $exception){
            $this->addFlash('error', 'Hubo algún problema al procesar la petición');
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("viaje/aceptar/{conductor}/{usuario}/{viaje}", name="confirmar_plaza_viaje")
     * @param Usuario $conductor
     * @param Usuario $usuario
     * @param Viaje $viaje
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmarViajeAction(Usuario $conductor, Usuario $usuario, Viaje $viaje){
        try{
            $viaje->setPlazasLibres($viaje->getPlazasLibres()-1);
            $notificacion = $this->get('app.notificacion_service');
            $notificacion->set($usuario, 'Vaceptado', $conductor->getId(), $viaje->getId(), 'viaje');
            $this->addFlash('estado', 'La respuesta se ha mandado al usuario');
        }catch (Exception $exception){
            $this->addFlash('error', 'Hubo algún problema al procesar la petición');
        }
        return $this->redirectToRoute('notificaciones');
    }

    /**
     * @Route("rutina/aceptar/{conductor}/{usuario}/{viaje}", name="confirmar_plaza_rutina")
     * @param Usuario $conductor
     * @param Usuario $usuario
     * @param Rutina $viaje
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmarRutinaAction(Usuario $conductor, Usuario $usuario, Rutina $viaje){
        try{
            $viaje->setPlazasLibres($viaje->getPlazasLibres()-1);
            $notificacion = $this->get('app.notificacion_service');
            $notificacion->set($usuario, 'Raceptado', $conductor->getId(), $viaje->getId(), 'rutina');
            $this->addFlash('estado', 'La respuesta se ha mandado al usuario');
        }catch (Exception $exception){
            $this->addFlash('error', 'Hubo algún problema al procesar la petición');
        }
        return $this->redirectToRoute('notificaciones');
    }
}
