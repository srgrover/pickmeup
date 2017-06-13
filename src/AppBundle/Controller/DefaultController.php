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
//                $claveRegistro = $form->get("password")->getData();
//                if ($claveRegistro) {
//                    $clave = $this->get('security.password_encoder')
//                        ->encodePassword($usuario, $claveRegistro);
//                    $usuario->setPassword($clave);
//                }
                $usuario->setPassword('qwertyuiop');
                $usuario->setImagenPerfil(null);
                $usuario->setImagenFondo(null);
                $usuario->setActivacion(new \DateTime("now"));

                $em->persist($usuario);
                $flush = $em->flush();

                if($flush == null){ //No devuelve ningun error
                    $expire = 30;

                    if($usuario->getToken() && $usuario->getTokenValidity() > new \Datetime()){
                        //Mensaje flash de error aqui
                    }else{
                        $token = bin2hex(random_bytes(16));
                        $usuario->setToken($token);

                        $validity = new \DateTime();
                        $validity->add(new \DateInterval('PT'.$expire.'M'));
                        $usuario->setTokenValidity($validity);

                        $message = \Swift_Message::newInstance()
                            ->setSubject('Completa tu registro en PickmeUP!')
                            ->setFrom($this->getParameter('mailer_user'))
                            ->setTo($usuario->getEmail())
                            ->setBody(
                                $this->renderView('email/emailConfirmUser.html.twig',[
                                    'usuario' => $usuario,
                                    'token' => $token
                                ])
                            );

                        $this->get('mailer')->send($message);

                        // guardar token
                        $this->get('doctrine')->getManager()->flush();                    }


                    $this->addFlash('estado', 'Estás a un paso de completar tu registro. Revisa tu email y confirma tu cuenta');
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
     * @Route("/confirmar/{usuario}/{token}", name="confirmar_usuario_pass", methods={"GET", "POST"})
     * @param Request $request
     * @param Usuario $usuarioId
     * @param $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function confirmarAction(Request $request, Usuario $usuarioId, $token){
        /**
         * @var Usuario|null
         */
        $usuario = $this->getDoctrine()->getManager()->getRepository('AppBundle:Usuario')->findOneBy([
            'id' => $usuarioId,
            'token' => $token
        ]);
        if (null === $usuario || ($usuario->getTokenValidity() < new \DateTime())) {
            $this->addFlash('error', '');
            return $this->redirectToRoute('login');
        }
        $data = [
            'password' => '',
            'repeat' => ''
        ];
        $form = $this->createForm('AppBundle\Form\NewPassType', $data);
        $form->handleRequest($request);
        $error = '';
        if ($form->isSubmitted() && $form->isValid()) {
            //codificar la nueva contraseña y asignarla al usuario
            $password = $this->get('security.password_encoder')
                ->encodePassword($usuario, $form->get('newPassword')->get('first')->getData());

            $usuario->setPassword($password)->setToken(null)->setTokenValidity(null);

            $this->getDoctrine()->getManager()->flush();

            // indicar que los cambios se han realizado con éxito y volver a la página de inicio
            $this->addFlash('estado', 'Te has registrado correctamente. Inicia sesión en cualquier momento con tu nueva contraseña');
            return $this->redirectToRoute('login');
        }
        return $this->render(
            'user/restaurar_pass.html.twig', [
                'user' => $usuario,
                'form' => $form->createView(),
                'error' => $error
            ]
        );
    }


    /**
     * @Route("/comprobar", name="comprobar")
     * @Route("/salir", name="salir")
     */
    public function comprobarAction() {
    }
}
