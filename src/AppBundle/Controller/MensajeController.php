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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @internal param Usuario|null $usuario
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

        $paginador = $this->get('knp_paginator');
        $paginacion = $paginador->paginate(
            $mensajes_privados,
            $request->query->getInt('page', 1),
            5
        );
        $this->marcarLeido($em, $usuario);

        return $this->render(':Mensajes:index.html.twig', [
            "formulario" => $formulario->createView(),
            'mensajes' => $paginacion
        ]);
    }

    /**
     * @Route("/mensajes/enviados", name="mensajes_enviados")
     * @param Request $request
     * @return Response
     */
    public function enviadosAction(Request $request){
        $mensajes_privados = $this->getMensajesPrivados($request, 'enviado'); //Mensajes enviados

        dump($mensajes_privados);

        $paginador = $this->get('knp_paginator');
        $paginacion = $paginador->paginate(
            $mensajes_privados,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('Mensajes/enviados.html.twig', [
            "mensajes" => $paginacion
        ]);
    }

    public function getMensajesPrivados($request, $tipo = null){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();

        $mensajes = $em->createQueryBuilder()
            ->select('m')
            ->from('AppBundle:Mensaje', 'm');
            if($tipo == 'enviado'){
                $mensajes->where('m.emisor = :usuario');
            }else{
                $mensajes->where('m.receptor = :usuario');
            }
        $mensajes->orderBy('m.fecha_enviado', 'DESC')
            ->setParameter('usuario', $usuario->getId())
            ->getQuery()
            ->getResult();

            dump($mensajes);

        return $mensajes;
    }

    /**
     * @Route("/mensajes/no-leidos", name="mensajes_no_leidos")
     * @return Response
     * @internal param Request $request
     */
    public function noLeidosAction(){
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


    private function marcarLeido($em, $usuario){
        /** @var EntityManager $em */

        $respuesta = false;
        $mensaje_repo = $em->getRepository('AppBundle:Mensaje');
        $mensajes = $mensaje_repo->findBy([
            'receptor' => $usuario,
            'leido' => false
        ]);

        foreach ($mensajes as $msg){
            $msg->setLeido(true);
            $em->persist($msg);
        }

        $flush = $em->flush();
        if($flush == null){
            $respuesta = true;
        }

        return $respuesta;
    }

}
