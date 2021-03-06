<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Seguimiento;

/**
 * Class SeguimientoController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER')")
 */
class SeguimientoController extends Controller{

    /**
     * @Route("/follow", name="following_follow", methods="POST")
     * @param Request $request
     * @return boolean
     */
    public function followAction(Request $request){
        $user = $this->getUser();                        //Obtenemos el usuario actual
        $followed_id = $request->get('followed');   //Obtenemos el usuario al que vamos a seguir. 'followed' es una variable recogida por POST

        $em = $this->getDoctrine()->getManager();

        $user_repo = $em->getRepository('AppBundle:Usuario');

        $followed = $user_repo->find($followed_id);

        $following = new Seguimiento();             //Creamos nueva instancia de la entida seguimiento
        $following->setUsuario($user);              //Seteamos el usuario seguido
        $following->setSeguidor($followed);         //Y el usuario seguidor

        $em->persist($following);
        $flush = $em->flush();                      //Para que guarde los cambios

        if($flush == null){                         //Si no da fallo
            $notificacion = $this->get('app.notificacion_service');
            $notificacion->set($followed, 'follow', $user->getId(),null,null);

            $this->addFlash('estado', 'Ahora estas siguiendo a este usuario.');
        }else{
            $this->addFlash('error', 'No se ha podido seguir a este usuario');
        }

        die();
    }


    /**
     * @Route("/unfollow", name="following_unfollow", methods="POST")
     * @param Request $request
     * @return Response
     */
    public function unfollowAction(Request $request){
        $user = $this->getUser();                        //Obtenemos el usuario actual
        $followed_id = $request->get('followed');   //Obtenemos el usuario al que vamos a seguir. 'followed' es una variable recogida por POST

        $em = $this->getDoctrine()->getManager();

        $following_repo = $em->getRepository('AppBundle:Seguimiento');

        $followed = $following_repo->findOneBy(array(
            "usuario"=> $user,
            "seguidor" => $followed_id
        ));

        $em->remove($followed);
        $flush = $em->flush();  //Para que guarde los cambios

        if($flush == null){     //Si no da fallo
            $this->addFlash('estado', 'Has dejado de seguir a este usuario');
        }else{
            $this->addFlash('error', "No se ha podido dejar de seguir a este usuario.");
        }

        die();
    }


    /**
     * @Route("/siguiendo/{nick}", name="siguiendo_usuarios")
     * @param Request $request
     * @param null $nick
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function siguiendoAction(Request $request, $nick = null){
        $em = $this->getDoctrine()->getManager();

        if($nick != null){
            $usuario_repo = $em->getRepository("AppBundle:Usuario");
            $usuario = $usuario_repo->findOneBy(array('nick' => $nick));
        }else{
            $usuario = $this->getUser();
        }

        if(empty($usuario) || !is_object($usuario)){
            return $this->redirect($this->generateUrl('homepage'));
        }

        $usuario_id = $usuario->getId();

        $siguiendo_consulta = $em->createQueryBuilder()
            ->select('f')
            ->from('AppBundle:Seguimiento', 'f')
            ->where('f.usuario = :usuario')
            ->setParameter('usuario', $usuario_id)
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->getResult();

        $paginador = $this->get("knp_paginator");
        $siguiendo = $paginador->paginate(
            $siguiendo_consulta,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render(':Seguimiento:siguiendo.html.twig', array(
            'tipo' => 'siguiendo',
            'perfil_usuario' => $usuario,
            'paginacion' => $siguiendo
        ));
    }


    /**
     * @Route("/seguidores/{nick}", name="usuarios_seguidores")
     * @param Request $request
     * @param null $nick
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function seguidoresAction(Request $request, $nick = null){
        $em = $this->getDoctrine()->getManager();

        if($nick != null){
            $usuario_repo = $em->getRepository("AppBundle:Usuario");
            $usuario = $usuario_repo->findOneBy(array('nick' => $nick));
        }else{
            $usuario = $this->getUser();
        }

        if(empty($usuario) || !is_object($usuario)){
            return $this->redirect($this->generateUrl('homepage'));
        }

        $usuario_id = $usuario->getId();

        $seguidor_consulta = $em->createQueryBuilder()
            ->select('f')
            ->from('AppBundle:Seguimiento', 'f')
            ->where('f.seguidor = :usuario')
            ->setParameter('usuario', $usuario_id)
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->getResult();

//        $dql = "SELECT f FROM AppBundle:Seguimiento f WHERE f.seguidor = $usuario_id ORDER BY f.id DESC";
//        $query = $em->createQuery($dql);

        $paginador = $this->get("knp_paginator");
        $seguidor = $paginador->paginate(
            $seguidor_consulta,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render(':Seguimiento:siguiendo.html.twig', array(
            'tipo' => 'seguidor',
            'perfil_usuario' => $usuario,
            'paginacion' => $seguidor
        ));
    }
}