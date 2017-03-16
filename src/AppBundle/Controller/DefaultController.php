<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository("AppBundle:Usuario");

        $user = $user_repo->find(1);

        echo "Bienvenido ".$user->getNombre()." ".$user->getApellidos();

        //var_dump($user);
        die();

        // replace this example code with whatever you need
        return $this->render(':default:index.html.twig');
    }
}
