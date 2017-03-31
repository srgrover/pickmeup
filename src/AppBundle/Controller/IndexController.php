<?php

namespace AppBundle\Controller;

use AppBundle\Form\AddRutinaType;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Form\AddViajeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Viaje;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller{
    private $session;

    public function __construct(){
        $this->session = new Session();
    }


    /**
     * @Route("/home", name="homepage")
     */
    public function IndexAction(Request $request){
        $publications = $this->getPublications($request);

        return $this->render(':publication:home.html.twig',array(
            'pagination' => $publications
        ));
    }


    /**
     * @Route("/rutina/add", name="add_rutina")
     */
    public function addRutinaAction(Request $request){


        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $rutina = new Viaje();
        $form = $this->createForm(AddRutinaType::class, $rutina);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                $rutina->setTipo(1);
                $rutina->setConductor($user);
                $rutina->setFechaPublicacion(new \DateTime("now"));

                $em->persist($rutina);
                $flush = $em->flush();

                if($flush == null){
                    $status = 'La rutina se ha creado correctamente';
                }else{
                    $status = 'Error al añadir la rutina';
                }

            }else{
                $status = 'La rutina no se ha creado porque el formulario no es válido';
            }

            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('homepage');
        }

        return $this->render(':publication:add_rutina.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/viaje/add", name="add_viaje")
     */
    public function addViajeAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $viaje = new Viaje();
        $form = $this->createForm(AddViajeType::class, $viaje);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                $viaje->setTipo(2);
                $viaje->setConductor($user);
                $viaje->setFechaPublicacion(new \DateTime("now"));

                $em->persist($viaje);
                $flush = $em->flush();

                if($flush == null){
                    $status = 'El viaje se ha creado correctamente';
                }else{
                    $status = 'Error al añadir el viaje';
                }

            }else{
                $status = 'El viaje no se ha creado porque el formulario no es válido';
            }

            $this->session->getFlashBag()->add("status", $status);
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
     * @Route("/publicacion/eliminar/{id}", name="eliminar_viaje")
     */
    public function removePublicationAction(Request $request, $id = null){
        $em = $this->getDoctrine()->getManager();
        $publication_repo = $em->getRepository('AppBundle:Viaje');
        $publication = $publication_repo->find($id);

        $user = $this->getUser();

        if($user->getId() == $publication->getConductor()->getId()){
            $em->remove($publication);
            $flush = $em->flush();

            if($flush == null){
                $status = 'Tu viaje publicado se ha borrado correctamente';
            }else{
                $status = 'Hubo un error al intentar borrar tu viaje publicado';
            }
        }else{
            $status = 'Hubo un error al intentar borrar tu viaje publicado';
        }

        return new Response($status);
    }

}