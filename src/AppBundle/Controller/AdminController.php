<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $mensajes = $this->getMensajes($request);


        return $this->render('admin/index.html.twig', [
            'admin' => $usuario,
            'viajes' => $viajes,
            'rutinas' => $rutinas,
            'usuarios' => $usuarios,
            'mensajes' => $mensajes
        ]);
    }

    public function getViajes($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $viajes_repo = $em->getRepository('AppBundle:Viaje');

        $query = $viajes_repo->createQueryBuilder('p')
            ->Where('p.activo = true')
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
            ->Where('p.activo = true')
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

    public function getMensajes($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $mensaje_repo = $em->getRepository('AppBundle:Mensaje');
        $mensajes = $mensaje_repo->findAll();

        $paginador = $this->get('knp_paginator');
        $paginacion = $paginador->paginate(
            $mensajes,
            $request->query->getInt('page', 1),
            5
        );

        return $paginacion;
    }
}