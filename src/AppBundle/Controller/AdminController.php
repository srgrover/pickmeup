<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Rutina;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Viaje;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminController
 * @package AppBundle\Controller
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends Controller{

    /**
     * @Route("/administracion", name="administracion")
     * @param Request $request
     * @return Response
     */
    public function IndexAction(Request $request){
        $usuario = $this->getUser();
        $viajes = $this->getViajes($request);
        $rutinas = $this->getRutinas($request);
        $usuarios = $this->getUsuarios($request);

        $total_usuarios = $this->getTotalUsuarios($request);
        $total_viajes = $this->getTotalViajes($request);
        $total_rutinas = $this->getTotalRutinas($request);
        $total_mensajes = $this->getTotalMensajes($request);
        $notif_admin = $this->getAllNotificaciones($request);
        $mensajes_admin = $this->getMensajesAdmin($request);

        return $this->render('admin/index.html.twig', [
            'admin' => $usuario,
            'viajes' => $viajes,
            'rutinas' => $rutinas,
            'usuarios' => $usuarios,
            'total_usuarios' => $total_usuarios,
            'total_viajes' => $total_viajes,
            'total_rutinas' => $total_rutinas,
            'total_mensajes' => $total_mensajes,
            'mensajes_admin' => $mensajes_admin,
            'notif_admin' => $notif_admin
        ]);
    }

    /**
     * @Route("/eliminar/usuario/{id}", name="borrar_usuario", methods={"GET"})
     * @param Usuario $usuario
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function borrarUsuarioAction(Usuario $usuario)
    {
        return $this->render(':admin:borrarUsuario.html.twig', [
            'usuario' => $usuario
        ]);
    }

    /**
     * @Route("/eliminar/usuario/{id}", name="confirmar_borrar_usuario", methods={"POST"})
     * @param Usuario $usuario
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function borrarDeVerdadAction(Usuario $usuario){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        try {
            foreach($usuario->getViajes() as $viaje) {
                $viaje->setActivo(false);
            }
            foreach($usuario->getRutinas() as $rutina) {
                $rutina->setActivo(false);
            }
            if ($usuario->getVehiculo()){
                $usuario->getVehiculo()->setActivo(false);
            }

            $usuario->setEstado(false);
            $em->flush();
            $this->addFlash('estado', 'El usuario se ha eliminado con éxito');
        }
        catch(Exception $e) {
            $this->addFlash('error', 'Hubo algún error. No se ha podido eliminar el usuario');
        }

        return $this->redirectToRoute('administracion');
    }

    /**
     * @Route("/activar/usuario/{id}", name="activar_usuario", methods={"GET"})
     * @param Usuario $usuario
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activarUsuarioAction(Usuario $usuario){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        try {
            foreach($usuario->getViajes() as $viaje) {
                $viaje->setActivo(true);
            }
            foreach($usuario->getRutinas() as $rutina) {
                $rutina->setActivo(true);
            }
            if ($usuario->getVehiculo()){
                $usuario->getVehiculo()->setActivo(true);
            }

            $usuario->setEstado(true);
            $em->flush();
            $this->addFlash('estado', 'El usuario se ha activado con éxito');
        }
        catch(Exception $e) {
            $this->addFlash('error', 'Hubo algún error. No se ha podido activar el usuario');
        }

        return $this->redirectToRoute('administracion');
    }

    /**
     * @Route("/activar/viaje/{id}", name="activar_viaje", methods={"GET"})
     * @param Viaje $viaje
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activarViajeAction(Viaje $viaje){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        try {
            $viaje->setActivo(true);
            $em->flush();
            $this->addFlash('estado', 'El viaje se ha activado con éxito');
        }
        catch(Exception $e) {
            $this->addFlash('error', 'Hubo algún error. No se ha podido activar el viaje');
        }

        return $this->redirectToRoute('administracion');
    }

    /**
     * @Route("/activar/rutina/{id}", name="activar_rutina", methods={"GET"})
     * @param Rutina $rutina
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activarRutinaAction(Rutina $rutina){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        try {
            $rutina->setActivo(true);
            $em->flush();
            $this->addFlash('estado', 'La rutina se ha activado con éxito');
        }
        catch(Exception $e) {
            $this->addFlash('error', 'Hubo algún error. No se ha podido activar la rutina');
        }

        return $this->redirectToRoute('administracion');
    }

    public function getViajes($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $viajes_repo = $em->getRepository('AppBundle:Viaje');

        $query = $viajes_repo->createQueryBuilder('p')
            ->orderBy('p.fechaPublicacion', 'DESC')
            ->getQuery();

        $paginador = $this->get('knp_paginator');
        $paginacion = $paginador->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $paginacion;
    }

    public function getRutinas($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $viajes_repo = $em->getRepository('AppBundle:Rutina');

        $query = $viajes_repo->createQueryBuilder('p')
            ->orderBy('p.fechaPublicacion', 'DESC')
            ->getQuery();

        $paginador = $this->get('knp_paginator');
        $paginacion = $paginador->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $paginacion;
    }

    public function getUsuarios($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $usuarios_repo = $em->getRepository('AppBundle:Usuario');
        $usuarios = $usuarios_repo->findAll();

        $paginador = $this->get('knp_paginator');
        $paginacion = $paginador->paginate(
            $usuarios,
            $request->query->getInt('page', 1),
            5
        );

        return $paginacion;
    }

    public function getTotalUsuarios($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $total_usuarios = $em->createQueryBuilder()
            ->select('COUNT(u)')
            ->from('AppBundle:Usuario', 'u')
            ->getQuery()
            ->getSingleScalarResult();

        return $total_usuarios;
    }

    public function getTotalViajes($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $total_viajes = $em->createQueryBuilder()
            ->select('COUNT(v)')
            ->from('AppBundle:Viaje', 'v')
            ->getQuery()
            ->getSingleScalarResult();

        return $total_viajes;
    }

    public function getTotalRutinas($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $total_rutinas = $em->createQueryBuilder()
            ->select('COUNT(r)')
            ->from('AppBundle:Rutina', 'r')
            ->getQuery()
            ->getSingleScalarResult();

        return $total_rutinas;
    }

    public function getTotalMensajes($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $total_mensajes = $em->createQueryBuilder()
            ->select('COUNT(m)')
            ->from('AppBundle:Mensaje', 'm')
            ->getQuery()
            ->getSingleScalarResult();

        return $total_mensajes;
    }

    public function getAllNotificaciones($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $notificaciones = $em->createQueryBuilder()
            ->select('n')
            ->from('AppBundle:Notificacion', 'n')
            ->orderBy('n.created_at', 'ASC')
            ->getQuery()
            ->getResult();

        $paginador = $this->get('knp_paginator');
        $paginacion = $paginador->paginate(
            $notificaciones,
            $request->query->getInt('page', 1),
            5
        );

        return $paginacion;
    }

    public function getMensajesAdmin($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $mensajes_repo = $em->getRepository('AppBundle:Mensaje');
        $mensajes = $mensajes_repo->createQueryBuilder('m')
            ->where('m.receptor = :admin')
            ->setParameter('admin', $this->getUser())
            ->orderBy('m.fecha_enviado', 'ASC')
            ->getQuery();

        $paginador = $this->get('knp_paginator');
        $paginacion = $paginador->paginate(
            $mensajes,
            $request->query->getInt('page', 1),
            5
        );

        return $paginacion;
    }


}