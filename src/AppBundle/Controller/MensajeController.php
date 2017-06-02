<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mensaje;
use AppBundle\Form\MensajeType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("has_role('ROLE_USER')")
 */
class MensajeController extends Controller{
    /**
     * @Route("/mensajes", name="mensajes")
     */
    public function indexAction(Request $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();

        $mensaje = new Mensaje();
        $formulario = $this->createForm(MensajeType::class, $mensaje,[
            'empty_data' => $usuario
        ]);

        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $mensaje->setEmisor($usuario);
            $mensaje->setFechaEnviado(new \DateTime('now'));
            $mensaje->setLeido(false);

            $em->persist($mensaje);
            $flush = $em->flush();

            if($flush == null){
                $this->addFlash('estado','El mensaje se ha enviado correctamente');
                return $this->redirectToRoute('mensajes');
            }else{
                $this->addFlash('error','Hubo un problema al enviar el mensaje');
            }
        }

        $mensajes_privados = $this->getMensajesPrivados($request); //Mensajes recibidos

        return $this->render(':Mensajes:index.html.twig', [
            "formulario" => $formulario->createView(),
            'mensajes' => $mensajes_privados
        ]);
    }

    /**
     * @Route("/mensajes/enviados", name="mensajes_enviados")
     */
    public function enviadosAction(Request $request){
        $mensajes_privados = $this->getMensajesPrivados($request, 'enviado'); //Mensajes enviados

        return $this->render(':Mensajes:enviados.html.twig', [
            "mensajes" => $mensajes_privados
        ]);
    }

    public function getMensajesPrivados($request, $tipo = null){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->getUser();

        if($tipo == 'enviado'){
            $query = $em->createQueryBuilder()
                ->select('m')
                ->from('AppBundle:Mensaje', 'm')
                ->where('m.emisor = :usuario')
                ->orderBy('m.fechaEnviado', 'DESC')
                ->setParameter('usuario', $usuario->getId())
                ->getQuery();
        }else{
            $query = $em->createQueryBuilder()
                ->select('m')
                ->from('AppBundle:Mensaje', 'm')
                ->where('m.receptor = :usuario')
                ->orderBy('m.fechaEnviado', 'DESC')
                ->setParameter('usuario', $usuario->getId())
                ->getQuery();
        }

        $paginador = $this->get('knp_paginator');
        $paginacion = $paginador->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $paginacion;
    }

    /**
     * @Route("/mensajes/no-leidos", name="mensajes_no_leidos")
     */
    public function noLeidosAction(Request $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->getUser();

        $mensaje_repo = $em->getRepository('AppBundle:Mensaje');
        $contar_no_leidos = count($mensaje_repo->findBy([
            'receptor' => $usuario,
            'leido' => false
        ]));

        return new Response($contar_no_leidos);
    }
}
