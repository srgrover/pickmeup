<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Usuario;
use AppBundle\Form\RegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="raiz")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('login');
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
        if(is_object($this->getUser()) && !$this->getUser()->isAdmin()){        //El usuario está logueado
            return $this->redirect('home');
        }

        $usuario = new Usuario();
        $form = $this->createForm(RegisterType::class, $usuario);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();

            $query = $em->createQueryBuilder()
                ->select('u')
                ->from('AppBundle:Usuario', 'u')
                ->where('u.email = :email')
                ->orWhere('u.nick = :nick')
                ->setParameter('email', $form->get("email")->getData())
                ->setParameter('nick', $form->get("nick")->getData())
                ->getQuery();

            $usuario_isset = $query->getResult();

            if(count($usuario_isset) == 0){
                $claveRegistro = $form->get("password")->getData();
                if ($claveRegistro) {
                    $clave = $this->get('security.password_encoder')
                        ->encodePassword($usuario, $claveRegistro);
                    $usuario->setPassword($clave);
                }

                $usuario->setImagenPerfil(null);
                $usuario->setImagenFondo(null);
                $usuario->setActivacion(new \DateTime("now"));

                $em->persist($usuario);
                $flush = $em->flush();

                if($flush == null){ //No devuelve ningun error
                    $this->addFlash('estado', 'Te has registrado correctamente');

                    return $this->redirectToRoute("login");
                }else{
                    $this->addFlash('error', 'No te has registrado correctamente');
                }

            }else{
                $this->addFlash('error', 'Este usuario ya existe');
            }
        }

        return $this->render(':user:register.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/comprobar", name="comprobar")
     * @Route("/salir", name="salir")
     */
    public function comprobarAction() {
    }
}
