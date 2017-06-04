<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Rutina;
use AppBundle\Entity\Vehiculo;
use AppBundle\Entity\Viaje;
use AppBundle\Form\AddRutinaType;
use AppBundle\Form\AddViajeType;
use AppBundle\Form\CocheType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminController
 * @package AppBundle\Controller
 * @Security("is_granted('ROLE_ADMIN')")
 */
class IndexController extends Controller{

    /**
     * @Route("/administracion", name="administracion")
     * @param Request $request
     * @return Response
     */
    public function IndexAction(Request $request){
        $viajes = $this->getViajes($request);
        $rutinas = $this->getRutinas($request);

        return $this->render(':publication:home.html.twig', [
            'viajes' => $viajes,
            'rutinas' => $rutinas
        ]);
    }

    public function getViajes($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $viajes_repo = $em->getRepository('AppBundle:Viaje');

        $query = $viajes_repo->createQueryBuilder('p')
            ->Where('p.activo = true')
            ->orderBy('p.fechaPublicacion', 'DESC')
            ->getQuery();

//        $paginator = $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//            $query,
//            $request->query->getInt('page', 1),
//            5
//        );

        return $query;
    }

    public function getRutinas($request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $viajes_repo = $em->getRepository('AppBundle:Rutina');

        $query = $viajes_repo->createQueryBuilder('p')
            ->Where('p.activo = true')
            ->orderBy('p.fechaPublicacion', 'DESC')
            ->getQuery();

//        $paginator = $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//            $query,
//            $request->query->getInt('page', 1),
//            5
//        );

        return $query;
    }
}