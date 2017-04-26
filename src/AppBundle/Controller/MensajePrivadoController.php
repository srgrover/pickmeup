<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Mensaje;
use Symfony\Component\Routing\Annotation\Route;

class MensajePrivadoController extends Controller{

    private $session;

    public function __construct(){
        $this->session = new Session();
    }


    /**
     * @Route("/mensajes", name="mensajes")
     */
    public function indexAction(){


        return $this->render(':Mensajes:index.html.twig', [
            "titulo" => "Mensajes"
        ]);
    }
}
