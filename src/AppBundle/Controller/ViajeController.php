<?php

namespace AppBundle\Controller;

use AppBundle\Form\AddRutinaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Form\AddViajeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Viaje;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/publicacion")
 */
class ViajeController extends Controller{

    /**
     * @Route("/viaje", name="ver_viaje")
     * @param Request $request
     * @return Response
     */
    public function viajeAction(Request $request){
        $publications = $this->getPublications($request);

        return $this->render(':viajes:viaje:home.html.twig',array(
            'pagination' => $publications
        ));
    }

    /**
     * @Route("/rutina", name="ver_rutina")
     * @param Request $request
     * @return Response
     */
    public function rutinaAction(Request $request){
        $publications = $this->getPublications($request);

        return $this->render(':viajes:rutina:home.html.twig',array(
            'pagination' => $publications
        ));
    }

    /**
     * @Route("/rutina/editar/{id}", name="editar_rutina")
     * @Route("/rutina/añadir", name="add_rutina")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addRutinaAction(Request $request, Viaje $rutina = null){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        if (null == $rutina) {
            $rutina = new Viaje();
            $em->persist($rutina);
        }
        $form = $this->createForm(AddRutinaType::class, $rutina);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                if (null == $rutina) {
                    $rutina->setTipo(1);
                    $rutina->setConductor($user);
                    $rutina->setFechaPublicacion(new \DateTime("now"));
                }

                $flush = $em->flush();

                if($flush == null){
                    if (null == $rutina) {
                        $this->addFlash('estado', 'La rutina se ha creado correctamente');
                    }else {
                        $this->addFlash('estado', 'Los cambios se han guardado correctamente');
                    }
                    //$status = 'La rutina se ha creado correctamente';
                }else{
                    if (null == $rutina) {
                        $this->addFlash('error', 'Error al añadir la rutina');
                    }else {
                        $this->addFlash('error', 'Los cambios no se han guardado correctamente');
                    }
                    //$status = 'Error al añadir la rutina';
                }
            }else{
                if (null == $rutina) {
                    $this->addFlash('error', 'La rutina no se ha creado porque el formulario no es válido');
                }else {
                    $this->addFlash('error', 'Los cambios no se han guardado porque el formulario no es válido');
                }
                //$status = 'La rutina no se ha creado porque el formulario no es válido';
            }

//            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('homepage');
        }

        return $this->render(':publication:add_rutina.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/viaje/editar/{id}", name="editar_viaje")
     * @Route("/viaje/añadir", name="add_viaje")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addViajeAction(Request $request, Viaje $viaje = null){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if (null == $viaje) {
            $viaje = new Viaje();
            $em->persist($viaje);
        }

        $form = $this->createForm(AddViajeType::class, $viaje);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                if (null == $viaje) {
                    $viaje->setTipo(2);
                    $viaje->setConductor($user);
                    $viaje->setFechaPublicacion(new \DateTime("now"));
                }

                $flush = $em->flush();

                if($flush == null){
                    if (null == $viaje) {
                        $this->addFlash('estado', 'El viaje se ha creado correctamente');
                    }else {
                        $this->addFlash('estado', 'Los cambios se han guardado correctamente');
                    }
                    //$status = 'El viaje se ha creado correctamente';
                }else{
                    if (null == $viaje) {
                        $this->addFlash('error', 'Error al añadir el viaje');
                    }else {
                        $this->addFlash('error', 'Los cambios no se han guardado correctamente');
                    }
                    //$status = 'Error al añadir el viaje';
                }

            }else{
                if (null == $viaje) {
                    $this->addFlash('error', 'El viaje no se ha creado porque el formulario no es válido');
                }else {
                    $this->addFlash('error', 'El viaje no se ha guardado porque el formulario no es válido');
                }
                //$status = 'El viaje no se ha creado porque el formulario no es válido';
            }

            //$this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('homepage');
        }

        return $this->render(':publication:add_viaje.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function getPublications($request){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $viajes_repo = $em->getRepository('AppBundle:Viaje');
        $following_repo = $em->getRepository('AppBundle:Seguimiento');

        /*SELECT * FROM `viaje` WHERE conductor_id = 3 OR conductor_id IN (SELECT seguidor_id FROM `seguimiento` WHERE usuario_id = 3)*/
        $following = $following_repo->findBy(array('usuario' => $user));

        $following_array = array();
        foreach ($following as $follow){
            $following_array[] = $follow->getSeguidor();
        }

        $query = $viajes_repo->createQueryBuilder('p')
            ->where('p.conductor = (:user_id) OR p.conductor IN (:following)')
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
     * @Route("/eliminar/{id}", name="eliminar_viaje")
     * @param null $id
     * @return Response
     * @internal param Request $request
     */
    public function removePublicationAction($id = null){
        $em = $this->getDoctrine()->getManager();
        $publication_repo = $em->getRepository('AppBundle:Viaje');
        $publication = $publication_repo->find($id);

        $user = $this->getUser();

        if($publication->getConductor()->getId() == $user->getId()){
            $em->remove($publication);
            $flush = $em->flush();

            if($flush == null){
                $this->addFlash('estado', 'Tu viaje publicado se ha borrado correctamente');
                //$status = 'Tu viaje publicado se ha borrado correctamente';
            }else{
                $this->addFlash('error', 'Hubo un error al intentar borrar tu viaje publicado');
                //$status = 'Hubo un error al intentar borrar tu viaje publicado';
            }
        }else{
            $this->addFlash('error', 'Hubo un error al intentar borrar tu viaje publicado');
        }

        //return new Response($status);
    }

}