<?php

namespace AppBundle\Controller;

use AppBundle\Form\RegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Usuario;

class UserController extends Controller{

    /**
     * @Route("/login", name="login")
     */
    public function LoginAction(Request $request){
        return $this->render(':default:login.html.twig', array(
            "titulo" => "Login"
        ));
    }

    /**
     * @Route("/register", name="Register")
     */
    public function RegisterAction(Request $request){

        $user = new Usuario();
        $form = $this->createForm(RegisterType::class, $user);

        return $this->render(':default:register.html.twig', array(
            "form" => $form->createView()
        ));
    }

}