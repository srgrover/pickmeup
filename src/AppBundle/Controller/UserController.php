<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Route("/Entrar", name="login")
     */
    public function LoginAction(){
        if(is_object($this->getUser())){        //El usuario está logueado
            return $this->redirect('home');
        }

        $autenticationUtils = $this->get('security.authentication_utils');  //Utiles para autenticacion
        $error = $autenticationUtils->getLastAuthenticationError();         //Capturamos el error

        $lastUserName = $autenticationUtils->getLastUsername();             //Capturamos el usuario del error

        return $this->render(':user:login.html.twig', [
            'last_username' => $lastUserName,
            'error' => $error
        ]);
    }

    /**
     * @Route("/Registro", name="Register")
     * @param Request $request
     * @return Response
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

                $query = $em->createQueryBuilder()
                    ->select('u')
                    ->from('AppBundle:Usuario', 'u')
                    ->where('u.email = :email')
                    ->orWhere('u.nick = :nick')
                    ->setParameter('email', $form->get("email")->getData())
                    ->setParameter('nick', $form->get("nick")->getData())
                    ->getQuery();

                $user_isset = $query->getResult();

                if(count($user_isset) == 0){

                    $factory = $this->get("security.encoder_factory");
                    $encoder = $factory->getEncoder($user);

                    $password = $encoder->encodePassword($form->get("password")->getData(), $user->getSalt());

                    $user->setPassword($password);
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

        return $this->render(':user:register.html.twig', [
            "form" => $form->createView()
        ]);
    }


    //Comprobamos si el nick introducido ya está registrado en la base de datos
    public function nickTestAction(Request $request){
        $nick = $request->get("nick");

        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository("AppBundle:Usuario");
        $user_isset = $user_repo->findOneBy(array("nick" => $nick));

        $result = "unused";

        if(count($user_isset) >= 1 && is_object($user_isset)){
            $result = "used";
        }

        return new Response(($result));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/perfil/editar", name="user_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editarUsuarioAction(Request $request){
        $user = $this->getUser();   //getUser() para recoger los datos de un usuario que ya esta logueado
        $user_image = $user->getImagenPerfil();
        $user_image_f = $user->getImagenFondo();
        $form = $this->createForm(UserType::class, $user);  //Crea el formulario

        $form->handleRequest($request);    //Informacion de request se guarda aqui

        if($form->isSubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();

                $user_isset = $em->createQueryBuilder()
                    ->select('u')
                    ->from('AppBundle:Usuario', 'u')
                    ->where('u.email = :email')
                    ->orWhere('u.nick = :nick')
                    ->setParameter('email', $form->get("email")->getData())
                    ->setParameter('nick', $form->get("nick")->getData())
                    ->getQuery()
                    ->getResult();

                if(count($user_isset) == 0 || ($user->getEmail() == $user_isset[0]->getEmail() && $user->getNick() == $user_isset[0]->getNick())){

                    //Fichero subido
                    $imagenPerfil = $form["imagenPerfil"]->getData();

                    if(!empty($imagenPerfil) && $imagenPerfil != null){
                        $ext = $imagenPerfil->guessExtension();
                        if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif'){
                            $nombre_imagen = $user->getId().time().'.'.$ext;
                            $imagenPerfil->move("uploads/users", $nombre_imagen);

                            $user->setImagenPerfil($nombre_imagen);
                        }
                    }else{
                        $user->setImagenPerfil($user_image);
                    }

                    $imagenFondo = $form["imagenFondo"]->getData();

                    if(!empty($imagenFondo) && $imagenFondo != null){
                        $ext = $imagenFondo->guessExtension();
                        if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif'){
                            $nombre_imagen_f = 'wall_'.$user->getId().time().'.'.$ext;
                            $imagenFondo->move("uploads/users", $nombre_imagen_f);

                            $user->setImagenFondo($nombre_imagen_f);
                        }
                    }else{
                        $user->setImagenFondo($user_image_f);
                    }

                    $em->persist($user);
                    $flush = $em->flush();

                    if($flush == null){ //No devuelve ningun error
                        $this->addFlash('estado', 'Datos modificados correctamente');
                    }else{
                        $this->addFlash('error', 'Los datos no se han modificado correctamente FLUSH');
                    }

                }else{
                    $this->addFlash('error', 'El usuario ya existe !!');
                }

            }else{
                $this->addFlash('error', 'Los datos no se han modificado correctamente NO VALIDO');
            }

            return $this->redirect('my-data');
        }

        return $this->render(':user:edit_user.html.twig', [
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/usuarios", name="users")
     * @param Request $request
     * @return Response
     */
    public function usersAction(Request $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->createQueryBuilder()
            ->select('u')
            ->from('AppBundle:Usuario', 'u')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $usuarios,
            $request->query->getInt('page', 1),     //page es la variable de la url
            5                                             //5 usuarios por pagina
        );

        return $this->render(':user:users.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * @Route("/buscar", name="user_search")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function searchAction(Request $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $search = trim($request->query->get("search", null)); //Se recoge el valor de la variable search de la URL

        if($search == null){    //Si la variable search del GET es nula, se redirige a la pagina home
            return $this->redirect($this->generateUrl('homepage'));
        }

        $usuarios = $em->createQueryBuilder()
            ->select('u')
            ->from('AppBundle:Usuario', 'u')
            ->where('u.nombre LIKE :search')
            ->orWhere('u.apellidos LIKE :search')
            ->orWhere('u.nick LIKE :search')
            ->orderBy('u.id', 'ASC')
            ->setParameter('search', "%$search%")
            ->getQuery()
            ->getResult();

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $usuarios,
            $request->query->getInt('page', 1),       //page es la variable de la url
            5                                               //5 usuarios por pagina
        );

        return $this->render(':user:users.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * @Route("/perfil/{nick}", name="perfil_usuario")
     */
    public function perfilAction(Request $request, $nick = null){
        /** @var EntityManager $em*/
        $em = $this->getDoctrine()->getManager();

        if($nick != null){
            $usuario_repo = $em->getRepository("AppBundle:Usuario");
            $usuario = $usuario_repo->findOneBy(array('nick' => $nick));
        }else{
            $usuario = $this->getUser();
        }

        if(empty($usuario) || !is_object($usuario)){
            return $this->redirect($this->generateUrl('homepage'));
        }

        $usuario_id = $usuario->getId();

        $viajes = $em->createQueryBuilder()
            ->select('p')
            ->from('AppBundle:Viaje', 'p')
            ->where('p.conductor = :usuario')
            ->orderBy('p.id', 'DESC')
            ->setParameter('usuario', $usuario_id)
            ->getQuery()
            ->getResult();

//        $dql = "SELECT p FROM AppBundle:Viaje p WHERE p.conductor = $usuario_id ORDER BY p.id DESC";
//        $query = $em->createQuery($dql);

        $paginador = $this->get("knp_paginator");
        $publicaciones = $paginador->paginate(
            $viajes,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render(':user:perfil.html.twig', [
            'usuario' => $usuario,
            'paginacion' => $publicaciones
        ]);
    }
}