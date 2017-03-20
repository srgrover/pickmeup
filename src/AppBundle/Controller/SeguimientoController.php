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
     * @Route("/follow", name="following_follow")
     */
    public function seguimientoAction(Request $request){


        echo "Follow Action";

        die();
    }
}