<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\Usuario;
use AppBundle\Entity\Seguimiento;


class SeguimientoController extends Controller{
    private $session;

    public function __construct(){
        $this->session = new Session();
    }


    /**
     * @Route("/follow", name="following_follow", methods="POST")
     */
    public function followAction(Request $request){
        $user = $this->getUser();                   //Obtenemos el usuario actual
        $followed_id = $request->get('followed');   //Obtenemos el usuario al que vamos a seguir. 'followed' es una variable recogida por POST

        $em = $this->getDoctrine()->getManager();

        $user_repo = $em->getRepository('AppBundle:Usuario');

        $followed = $user_repo->find($followed_id);

        $following = new Seguimiento();             //Creamos nueva instancia de la entida seguimiento
        $following->setUsuario($user);              //Seteamos el usuario seguido
        $following->setSeguidor($followed);         //Y el usuario seguidor

        $em->persist($following);
        $flush = $em->flush();                      //Para que guarde los cambios

        if($flush == null){     //Si no da fallo
            $status = "Ahora estas siguiendo a este usuario.";
        }else{
            $status = "No se ha podido seguir a este usuario.";
        }

        return new Response($status);
    }


    /**
     * @Route("/unfollow", name="following_unfollow", methods="POST")
     */
    public function unfollowAction(Request $request){
        $user = $this->getUser();                   //Obtenemos el usuario actual
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
            $status = "Has dejado de seguir a este usuario.";
        }else{
            $status = "No se ha podido dejar de seguir a este usuario.";
        }

        return new Response($status);
    }
}