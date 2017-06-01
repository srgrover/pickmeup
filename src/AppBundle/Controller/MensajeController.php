<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mensaje;
use AppBundle\Form\MensajeType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;

class MensajeController extends Controller{
    /**
     * @Route("/mensajes", name="mensajes")
     */
    public function indexAction(){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();

        $mensaje = new Mensaje();
        $formulario = $this->createForm(MensajeType::class, $mensaje,[
            'empty_data' => $usuario
        ]);

        return $this->render(':Mensajes:index.html.twig', [
            "formulario" => $formulario->createView()
        ]);
    }
}
