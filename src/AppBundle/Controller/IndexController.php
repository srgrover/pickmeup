<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Form\AddViajeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Viaje;

class IndexController extends Controller{
    private $session;

    public function __construct(){
        $this->session = new Session();
    }


    /**
     * @Route("/home", name="homepage")
     */
    public function IndexAction(Request $request){

        return $this->render(':publication:home.html.twig');
    }


    /**
     * @Route("/rutina/add", name="add_rutina")
     */
    public function addRutinaAction(Request $request){


        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $viaje = new Viaje();
        $form = $this->createForm(AddViajeType::class, $viaje);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                $viaje->setTipo(1);
                $viaje->setConductor($user);
                $viaje->setFechaPublicacion(new \DateTime("now"));

                $em->persist($viaje);
                $flush = $em->flush();

                if($flush == null){
                    $status = 'La rutina se ha creado correctamente';
                }else{
                    $status = 'Error al añadir la rutina';
                }

            }else{
                $status = 'La rutina no se ha creado porque el formulario no es válido';
            }

            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('homepage');
        }

        return $this->render(':publication:add_rutina.html.twig', array(
            'form' => $form->createView()
        ));
    }

}