<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Usuario;
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
        $mensajes = $this->getMensajes($request);


        return $this->render('admin/index.html.twig', [
            'admin' => $usuario,
            'viajes' => $viajes,
            'rutinas' => $rutinas,
            'usuarios' => $usuarios,
            'mensajes' => $mensajes
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
            $usuario->getVehiculo()->setActivo(false);

            $usuario->setEstado(false);
            $em->flush();
            $this->addFlash('estado', 'El usuario se ha eliminado con éxito');
        }
        catch(Exception $e) {
            $this->addFlash('error', 'Hubo algún error. No se ha podido eliminar el usuario');
        }
        return $this->redirectToRoute('administracion');
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