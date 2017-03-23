<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\Usuario;
use AppBundle\Form\RegisterType;
use AppBundle\Form\UserType;



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
                    ->setParameter('nick', $form->get("nick")->getData());

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

    /**
     * @Route("/my-data", name="user_edit")
     */
    public function aditUserAction(Request $request){
        $user = $this->getUser();   //getUser() para recoger los datos de un usuario que ya esta logueado
        $user_image = $user->getImagenPerfil();
        $form = $this->createForm(UserType::class, $user);  //Crea el formulario

        $form->handleRequest($request);    //Informacion de request se guarda aqui

        if($form->isSubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();

                $query = $em->createQuery('SELECT u FROM AppBundle:Usuario u WHERE u.email = :email OR u.nick = :nick')
                    ->setParameter('email', $form->get("email")->getData())
                    ->setParameter('nick', $form->get("nick")->getData());

                $user_isset = $query->getResult();

                if(count($user_isset) == 0 || ($user->getEmail() == $user_isset[0]->getEmail() && $user->getNick() == $user_isset[0]->getNick())){

                    //Fichero subido
                    $file = $form["imagenPerfil"]->getData();

                    if(!empty($file) && $file != null){
                        $ext = $file->guessExtension();
                        if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif'){
                            $file_name = $user->getId().time().'.'.$ext;
                            $file->move("uploads/users", $file_name);

                            $user->setImagenPerfil($file_name);
                        }
                    }else{
                        $user->setImagenPerfil($user_image);
                    }

                    $em->persist($user);
                    $flush = $em->flush();

                    if($flush == null){ //No devuelve ningun error
                        $status = "Datos modificados correctamente";
                    }else{
                        $status = "Los datos no se han modificado correctamente";
                    }

                }else{
                    $status = "El usuario ya existe !!";
                }

            }else{
                $status = "No se han actualizado los datos correctamente !!";
            }

            $this->session->getFlashBag()->add("status", $status);
            return $this->redirect('my-data');
        }

        return $this->render(':user:edit_user.html.twig', array(
            "form" => $form->createView()
        ));
    }


    /**
     * @Route("/users", name="users")
     */
    public function usersAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT u FROM AppBundle:Usuario u ORDER BY u.id ASC";

        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),     //page es la variable de la url
            5                                       //5 usuarios por pagina
        );

        return $this->render(':user:users.html.twig', array(
            'pagination' => $pagination
        ));
    }


    /**
     * @Route("/search", name="user_search")
     */
    public function searchAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $search = trim($request->query->get("search", null)); //Se recoge el valor de la variable search de la URL

        if($search == null){    //Si la variable search del GET es nula, se redirige a la pagina home
            return $this->redirect($this->generateUrl('homepage'));
        }

        $dql = "SELECT u FROM AppBundle:Usuario u " .
            "WHERE u.nombre LIKE :search OR u.apellidos LIKE :search " .
            "OR u.nick LIKE :search ORDER BY u.id ASC";

        $query = $em->createQuery($dql)->setParameter('search', "%$search%");   //añadimos parametro search que sea "lo que sea" + search + "lo que sea"

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),     //page es la variable de la url
            5                                       //5 usuarios por pagina
        );

        return $this->render(':user:users.html.twig', array(
            'pagination' => $pagination
        ));
    }
}