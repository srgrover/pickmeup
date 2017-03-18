<?php

namespace AppBundle\Controller;

use AppBundle\Form\RegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Usuario;



class UserController extends Controller{
    private $session;

    public function __construct(){
        $this->session = new Session();
    }

    /**
     * @Route("/login", name="login")
     */
    public function LoginAction(Request $request){
        if(is_object($this->getUser())){        //El usuario está logueado
            return $this->redirect('home');
        }

        $autenticationUtils = $this->get('security.authentication_utils');  //Utiles para autenticacion
        $error = $autenticationUtils->getLastAuthenticationError();         //Capturamos el error

        $lastUserName = $autenticationUtils->getLastUsername();             //Capturamos el usuario del error

        return $this->render(':user:login.html.twig', array(
            'last_username' => $lastUserName,
            'error' => $error
        ));
    }

    /**
     * @Route("/register", name="Register")
     */
    public function RegisterAction(Request $request){
        if(is_object($this->getUser())){        //El usuario está logueado
            return $this->redirect('home');
        }

        $user = new Usuario();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                //$user_repo = $em->getRepository("AppBundle:Usuario");

                $query = $em->createQuery('SELECT u FROM AppBundle:Usuario u WHERE u.email = :email OR u.nick = :nick')
                    ->setParameter('email', $form->get("email")->getData())
                    ->setParameter('nick', $form->get("username")->getData());

                $user_isset = $query->getResult();

                if(count($user_isset) == 0){

                    $factory = $this->get("security.encoder_factory");
                    $encoder = $factory->getEncoder($user);

                    $password = $encoder->encodePassword($form->get("password")->getData(), $user->getSalt());

                    $user->setPassword($password);
                    $user->setRol("ROLE_USER");
                    $user->setImagenPerfil(null);
                    $user->setImagenFondo(null);
                    $user->setActivacion(new \DateTime("now"));

                    $em->persist($user);
                    $flush = $em->flush();

                    if($flush == null){ //No devuelve ningun error
                        $status = "Te has registrado correctamente";

                        $this->session->getFlashBag()->add("status", $status);
                        return $this->redirect("login");
                    }else{
                        $status = "No te has registrado correctamente";
                    }

                }else{
                    $status = "El usuario ya existe !!";
                }

            }else{
                $status = "No te has registrado correctamente !!";
            }

            $this->session->getFlashBag()->add("status", $status);
        }

        return $this->render(':user:register.html.twig', array(
            "form" => $form->createView()
        ));
    }


    //Comprobamos si el nick introducido ya está registrado en la base de datos
    public function nickTestAction(Request $request){
        $nick = $request->get("nick");

        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository("AppBundle:Usuario");
        $user_isset = $user_repo->findOneBy(array("nick" => $nick));

        $result = "used";

        if(count($user_isset) >= 1 && is_object($user_isset)){
            $result = "used";
        }else{
            $result = "unused";
        }

        return new Response(($result));
    }


    //Comprobamos si el email introducido ya está registrado en la base de datos
    public function emailTestAction(Request $request){
        $email = $request->get("email");

        $em = $this->getDoctrine()->getManager();
        $email_repo = $em->getRepository("AppBundle:Usuario");
        $email_isset = $email_repo->findOneBy(array("email" => $email));

        $result = "used";

        if(count($email_isset) >= 1 && is_object($email_isset)){
            $result = "used";
        }else{
            $result = "unused";
        }

        return new Response(($result));
    }


    /**
     * @Route("/my-data", name="user_edit")
     */
    public function aditUserAction(Request $request){
//        $email = $request->get("email");
//
//        $em = $this->getDoctrine()->getManager();
//        $email_repo = $em->getRepository("AppBundle:Usuario");
//        $email_isset = $email_repo->findOneBy(array("email" => $email));
//
//        $result = "used";
//
//        if(count($email_isset) >= 1 && is_object($email_isset)){
//            $result = "used";
//        }else{
//            $result = "unused";
//        }

        return $this->render(':user:edit_user.html.twig', array(

        ));
    }

}