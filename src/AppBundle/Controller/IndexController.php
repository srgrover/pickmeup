<?php

namespace AppBundle\Controller;

use AppBundle\Form\AddViajeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Viaje;

class IndexController extends Controller{

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
        $viaje = new Viaje();
        $form = $this->createForm(AddViajeType::class, $viaje);



        return $this->render(':publication:add_rutina.html.twig', array(
            'form' => $form->createView()
        ));
    }

}