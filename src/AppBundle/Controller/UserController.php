<?php

namespace AppBundle\Controller;

use AppBundle\Form\PassType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Usuario;
use AppBundle\Form\UserType;

class UserController extends Controller{

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
     * @Route("/perfil/editar/{id}", name="editar_a_usuario")
     * @param Request $request
     * @param Usuario|null $usuario
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editarUsuarioAction(Request $request, Usuario $usuario = null){
        if ($usuario == null){
            $usuario = $this->getUser();   //getUser() para recoger los datos de un usuario que ya esta logueado
        }
        $usuario_image = $usuario->getImagenPerfil();
        $usuario_image_f = $usuario->getImagenFondo();
        $form = $this->createForm(UserType::class, $usuario);  //Crea el formulario

        $form->handleRequest($request);    //Informacion de request se guarda aqui

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();

            $usuario_isset = $em->createQueryBuilder()
                ->select('u')
                ->from('AppBundle:Usuario', 'u')
                ->where('u.email = :email AND u.nick = :nick')
                ->setParameter('email', $form->get("email")->getData())
                ->setParameter('nick', $form->get("nick")->getData())
                ->getQuery()
                ->getResult();

            if(count($usuario_isset) == 0 || ($usuario->getEmail() == $usuario_isset[0]->getEmail() && $usuario->getNick() == $usuario_isset[0]->getNick())){
                //Fichero subido
                $imagenPerfil = $form["imagenPerfil"]->getData();

                if(!empty($imagenPerfil) && $imagenPerfil != null){
                    $ext = $imagenPerfil->guessExtension();
                    if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif'){
                        $nombre_imagen = $usuario->getId().time().'.'.$ext;
                        $imagenPerfil->move("uploads/users", $nombre_imagen);

                        $usuario->setImagenPerfil($nombre_imagen);
                    }
                }else{
                    $usuario->setImagenPerfil($usuario_image);
                }

                $imagenFondo = $form["imagenFondo"]->getData();

                if(!empty($imagenFondo) && $imagenFondo != null){
                    $ext = $imagenFondo->guessExtension();
                    if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif'){
                        $nombre_imagen_f = 'wall_'.$usuario->getId().time().'.'.$ext;
                        $imagenFondo->move("uploads/users", $nombre_imagen_f);

                        $usuario->setImagenFondo($nombre_imagen_f);
                    }
                }else{
                    $usuario->setImagenFondo($usuario_image_f);
                }

                $em->persist($usuario);
                $flush = $em->flush();

                if($flush == null){ //No devuelve ningun error
                    $this->addFlash('estado', 'Datos modificados correctamente');
                    if($this->getUser()->isAdmin()){
                        return $this->redirectToRoute('administracion');
                    }else{
                        return $this->redirectToRoute('perfil_usuario');
                    }

                }else{
                    $this->addFlash('error', 'Los datos no se han modificado correctamente FLUSH');
                }
            }else{
                $this->addFlash('error', 'El usuario ya existe !!');
            }
        }

        return $this->render(':user:edit_user.html.twig', [
            "form" => $form->createView()
        ]);
    }


    /**
     * @Security("has_role('ROLE_USER')")
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
            ->where('u.estado = true')
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
     * @Security("has_role('ROLE_USER')")
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
     * @Security("has_role('ROLE_USER')")
     * @Route("/perfil/{nick}", name="perfil_usuario")
     * @param Request $request
     * @param null $nick
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
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
            ->select('v')
            ->from('AppBundle:Viaje', 'v')
            ->where('v.conductor = :usuario')
            ->andWhere('v.activo = true')
            ->orderBy('v.id', 'DESC')
            ->setParameter('usuario', $usuario_id)
            ->getQuery()
            ->getResult();

        $rutinas = $em->createQueryBuilder()
            ->select('r')
            ->from('AppBundle:Rutina', 'r')
            ->where('r.conductor = :usuario')
            ->andWhere('r.activo = true')
            ->orderBy('r.id', 'DESC')
            ->setParameter('usuario', $usuario_id)
            ->getQuery()
            ->getResult();

        $pagina_viaje = $this->get("knp_paginator");
        $pub_viajes = $pagina_viaje->paginate(
            $viajes,
            $request->query->getInt('page', 1),
            5
        );

        $pagina_rutina = $this->get("knp_paginator");
        $pub_rutinas = $pagina_rutina->paginate(
            $rutinas,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render(':user:perfil.html.twig', [
            'usuario' => $usuario,
            'viajes' => $pub_viajes,
            'rutinas' => $pub_rutinas
        ]);
    }


    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/cambiar-contraseña", name="cambiar_pass")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function cambiarPassAction(Request $request){
        $usuario = $this->getUser();

        $form = $this->createForm(PassType::class, $usuario);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            try{
                $claveFormulario = $form->get('nueva')->get('first')->getData();
                if ($claveFormulario) {
                    $clave = $this->get('security.password_encoder')
                        ->encodePassword($usuario, $claveFormulario);
                    $usuario->setPassword($clave);
                }
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('estado','Contraseña cambiada con éxito!');
                return $this->redirectToRoute('perfil_usuario');
            }catch (Exception $exception){
                $this->addFlash('error','Hubo algún problema al actualizar la contraseña');
            }

        }

        return $this->render(':user:cambiar_pass.html.twig', [
            'formulario' => $form->createView(),
            'usuario' => $usuario
        ]);
    }
}