<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller{

    /**
     * @Route("/login", name="login")
     */
    public function LoginAction(Request $request){
        echo "Acción Login";
        die();
    }

}