<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mensaje;
use AppBundle\Entity\Rutina;
use AppBundle\Entity\Vehiculo;
use AppBundle\Entity\Viaje;
use AppBundle\Form\AddRutinaType;
use AppBundle\Form\AddViajeType;
use AppBundle\Form\CocheType;
use AppBundle\Form\fastMensajeType;
use AppBundle\Form\MensajeType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IndexController
 * @package AppBundle\Controller
 * @Security("is_granted('ROLE_USER')")
 */
class IndexController extends Controller{

    /**
     * @Route("/home", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function IndexAction(Request $request){
        $viajes = $this->getViajes($request);
        $rutinas = $this->getRutinas($request);

        if($this->getUser()->isAdmin()){
            return $this->redirectToRoute('administracion');
        }

        return $this->render(':publication:home.html.twig', [
            'viajes' => $viajes,
            'rutinas' => $rutinas
        ]);
    }

    public function getViajes($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $viajes_repo = $em->getRepository('AppBundle:Viaje');
        $following_repo = $em->getRepository('AppBundle:Seguimiento');

        $following = $following_repo->findBy(['usuario' => $user]);

        $following_array = array();
        foreach ($following as $follow){
            $following_array[] = $follow->getSeguidor();
        }

        $query = $viajes_repo->createQueryBuilder('p')
            ->where('p.conductor = (:user_id) OR p.conductor IN (:following)')
            ->andWhere('p.activo = true')
            ->setParameter('user_id', $user->getId())
            ->setParameter('following', $following_array)
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $pagination;
    }

    public function getRutinas($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $viajes_repo = $em->getRepository('AppBundle:Rutina');
        $following_repo = $em->getRepository('AppBundle:Seguimiento');

        $following = $following_repo->findBy(['usuario' => $user]);

        $following_array = array();
        foreach ($following as $follow){
            $following_array[] = $follow->getSeguidor();
        }

        $query = $viajes_repo->createQueryBuilder('p')
            ->where('p.conductor = (:user_id) OR p.conductor IN (:following)')
            ->andWhere('p.activo = true')
            ->setParameter('user_id', $user->getId())
            ->setParameter('following', $following_array)
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $pagination;
    }

    /**
     * @Route("/viaje/ver/{id}", name="ver_viaje")
     * @param Request $request
     * @param Viaje $viaje
     * @return Response
     * @internal param Request $request
     */
    public function verViajeAction(Request $request, Viaje $viaje){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $mensaje = new Mensaje();
        $formulario = $this->createForm(fastMensajeType::class, $mensaje);

        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $mensaje->setEmisor($this->getUser());
            $mensaje->setReceptor($viaje->getConductor());
            $mensaje->setFechaEnviado(new \DateTime('now'));
            $mensaje->setLeido(false);

            $em->persist($mensaje);
            $flush = $em->flush();

            if($flush == null){
                $this->addFlash('estado','El mensaje se ha enviado correctamente');
                return $this->redirectToRoute('mensajes');
            }else{
                $this->addFlash('error','Hubo un problema al enviar el mensaje');
            }
        }

        return $this->render(':Viaje:viaje.html.twig', [
            "formulario" => $formulario->createView(),
            'viaje' => $viaje
        ]);
    }

    /**
    * @Route("/rutina/ver/{id}", name="ver_rutina")
    * @param Rutina $rutina
    * @return Response
    * @internal param Request $request
    */
    public function verRutinaAction(Request $request, Rutina $rutina){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $mensaje = new Mensaje();
        $formulario = $this->createForm(fastMensajeType::class, $mensaje);

        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $mensaje->setEmisor($this->getUser());
            $mensaje->setReceptor($rutina->getConductor());
            $mensaje->setFechaEnviado(new \DateTime('now'));
            $mensaje->setLeido(false);

            $em->persist($mensaje);
            $flush = $em->flush();

            if($flush == null){
                $this->addFlash('estado','El mensaje se ha enviado correctamente');
                return $this->redirectToRoute('mensajes');
            }else{
                $this->addFlash('error','Hubo un problema al enviar el mensaje');
            }
        }


        return $this->render('rutina/rutina.html.twig', [
            "formulario" => $formulario->createView(),
            'rutina' => $rutina
        ]);
    }

    /**
     * @Route("/rutina/editar/{id}", name="editar_rutina")
     * @Route("/rutina/añadir", name="add_rutina")
     * @param Request $request
     * @param Rutina|null $rutina
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addRutinaAction(Request $request, Rutina $rutina = null){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->getUser();
        if ($rutina == null) {
            $rutina = new Rutina();
            $em->persist($rutina);
            $rutina->setConductor($usuario);
            $rutina->setFechaPublicacion(new \DateTime("now"));
        }
        $form = $this->createForm(AddRutinaType::class, $rutina);

        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $flush = $em->flush();

                if ($flush == null) {
                    if (null == $rutina) {
                        $this->addFlash('estado', 'La rutina se ha creado correctamente');
                    } else {
                        $this->addFlash('estado', 'Los cambios se han guardado correctamente');
                    }
                } else {
                    if (null == $rutina) {
                        $this->addFlash('error', 'Error al añadir la rutina');
                    } else {
                        $this->addFlash('error', 'Los cambios no se han guardado correctamente');
                    }
                }
            } else {
                if (null == $rutina) {
                    $this->addFlash('error', 'La rutina no se ha creado porque el formulario no es válido');
                } else {
                    $this->addFlash('error', 'Los cambios no se han guardado porque el formulario no es válido');
                }
            }

            return $this->redirectToRoute('homepage');
        }

        return $this->render(':publication:add_rutina.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/viaje/editar/{id}", name="editar_viaje")
     * @Route("/viaje/añadir", name="add_viaje")
     * @param Request $request
     * @param Viaje|null $viaje
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addViajeAction(Request $request, Viaje $viaje = null){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->getUser();
        if ($viaje == null) {
            $viaje = new Viaje();
            $em->persist($viaje);
            $viaje->setConductor($usuario);
            $viaje->setFechaPublicacion(new \DateTime("now"));
        }
        $form = $this->createForm(AddViajeType::class, $viaje);

        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $flush = $em->flush();

                if ($flush == null) {
                    if (null == $viaje) {
                        $this->addFlash('estado', 'La rutina se ha creado correctamente');
                    } else {
                        $this->addFlash('estado', 'Los cambios se han guardado correctamente');
                    }
                } else {
                    if (null == $viaje) {
                        $this->addFlash('error', 'Error al añadir la rutina');
                    } else {
                        $this->addFlash('error', 'Los cambios no se han guardado correctamente');
                    }
                }
            } else {
                if (null == $viaje) {
                    $this->addFlash('error', 'La rutina no se ha creado porque el formulario no es válido');
                } else {
                    $this->addFlash('error', 'Los cambios no se han guardado porque el formulario no es válido');
                }
            }

            return $this->redirectToRoute('homepage');
        }

        return $this->render(':publication:add_viaje.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("viaje/eliminar/{id}", name="borrar_viaje", methods={"GET"})
     * @param Viaje $viaje
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function borrarViajeAction(Viaje $viaje){
        $usuario = $this->getUser();
        if($viaje->getConductor()->getId() == $usuario->getId() || $this->getUser()->isAdmin()) {
            return $this->render(':Viaje:borrar.html.twig', [
                'viaje' => $viaje
            ]);
        }else{
            $this->addFlash('error', 'No tienes permisos para borrar este viaje');
            return $this->redirectToRoute('homepage');
        }
    }


    /**
     * @Route("viaje/eliminar/{id}", name="confirmar_borrar_viaje", methods={"POST"})
     * @param Viaje $viaje
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function borrarDeVerdadViajeAction(Viaje $viaje){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        if($viaje->getConductor()->getId() == $usuario->getId() || $this->getUser()->isAdmin()){
            try {
                $viaje->setActivo(false);
                $em->flush();
                $this->addFlash('estado', 'Viaje eliminado con éxito');
            }
            catch(Exception $e) {
                $this->addFlash('error', 'No se han podido eliminar el viaje');
            }
        }else{
            $this->addFlash('error', 'No tienes permisos para borrar este viaje');
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("rutina/eliminar/{id}", name="borrar_rutina", methods={"GET"})
     * @param Rutina $rutina
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function borrarRutinaAction(Rutina $rutina){
        $usuario = $this->getUser();
        if($rutina->getConductor()->getId() == $usuario->getId() || $this->getUser()->isAdmin()) {
            return $this->render(':rutina:borrar.html.twig', [
                'rutina' => $rutina
            ]);
        }else{
            $this->addFlash('error', 'No tienes permisos para borrar este viaje');
            return $this->redirectToRoute('homepage');
        }
    }


    /**
     * @Route("rutina/eliminar/{id}", name="confirmar_borrar_rutina", methods={"POST"})
     * @param Rutina $rutina
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function borrarDeVerdadAction(Rutina $rutina){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        if($rutina->getConductor()->getId() == $usuario->getId() || $this->getUser()->isAdmin()){
            try {
                $rutina->setActivo(false);
                $em->flush();
                $this->addFlash('estado', 'Rutina eliminada con éxito');
            }
            catch(Exception $e) {
                $this->addFlash('error', 'No se han podido eliminar la rutina');
            }
        }else{
            $this->addFlash('error', 'No tienes permisos para borrar esta rutina');
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/coche/editar/{id}", name="editar_coche")
     * @Route("/coche/añadir", name="add_coche")
     * @param Request $request
     * @param Vehiculo|null $coche
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @internal param Rutina|null $rutina
     */
    public function addCocheAction(Request $request, Vehiculo $coche = null){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();


        if ($coche == null) {
            $coche = new Vehiculo();
            $em->persist($coche);
            $coche->setConductor($usuario);
        }


        $form = $this->createForm(CocheType::class, $coche);

        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if ($form->isValid()) {
                if($coche->getConductor()->getId() == $usuario->getId() || $this->getUser()->isAdmin()) {
                    try{
                        $flush = $em->flush();

                        if ($flush == null) {
                            if (null == $coche) {
                                $this->addFlash('estado', 'El coche se ha creado correctamente');
                            } else {
                                $this->addFlash('estado', 'Los cambios se han guardado correctamente');
                            }
                        } else {
                            if (null == $coche) {
                                $this->addFlash('error', 'Error al añadir el coche');
                            } else {
                                $this->addFlash('error', 'Los cambios no se han guardado correctamente');
                            }
                        }
                    }catch (Exception $exception){
                        if (null == $coche) {
                            $this->addFlash('error', 'No tienes permisos para agregar este vehículo');
                        } else {
                            $this->addFlash('error', 'No puedes modificar este vehículo');
                        }
                    }
                }

            } else {
                if (null == $coche) {
                    $this->addFlash('error', 'El coche no se ha creado porque el formulario no es válido');
                } else {
                    $this->addFlash('error', 'Los cambios no se han guardado porque el formulario no es válido');
                }
            }

            return $this->redirectToRoute('perfil_usuario');
        }

        return $this->render('coche/coche.html.twig', [
            'formulario' => $form->createView(),
        ]);
    }

    /**
     * @Route("coche/eliminar/{id}", name="borrar_coche", methods={"GET"})
     * @param Vehiculo $coche
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function borrarCocheAction(Vehiculo $coche){
        $usuario = $this->getUser();
        if($coche->getConductor()->getId() == $usuario->getId()) {
            return $this->render(':coche:borrar.html.twig', [
                'coche' => $coche
            ]);
        }else{
            $this->addFlash('error', 'No tienes permisos para borrar este viaje');
            return $this->redirectToRoute('homepage');
        }
    }


    /**
     * @Route("coche/eliminar/{id}", name="confirmar_borrar_coche", methods={"POST"})
     * @param Vehiculo $coche
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function borrarCocheDeVerdadAction(Vehiculo $coche){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        if($coche->getConductor()->getId() == $usuario->getId()){
            try {
                $em->remove($coche);
                $em->flush();
                $this->addFlash('estado', 'Rutina eliminado con éxito');
            }
            catch(Exception $e) {
                $this->addFlash('error', 'No se han podido eliminar la rutina');
            }
        }else{
            $this->addFlash('error', 'No tienes permisos para borrar esta rutina');
        }
        return $this->redirectToRoute('perfil_usuario');
    }

}