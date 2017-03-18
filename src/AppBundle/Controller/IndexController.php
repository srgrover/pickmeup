<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller{

    /**
     * @Route("/home", name="homepage")
     */
    public function IndexAction(Request $request){


        return $this->render(':publication:home.html.twig');
    }

}