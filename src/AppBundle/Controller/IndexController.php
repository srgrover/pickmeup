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
use DateTime;
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
     * @Route("/inicio", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function IndexAction(Request $request){
        if (!$this->getUser()->getEstado()){
            $this->addFlash('error','Tu cuenta se encuentra deshabilitada en este momento');
            return $this->redirectToRoute('salir');
        }

        if($this->getUser()->isAdmin()){
            return $this->redirectToRoute('administracion');
        }

        $viajes = $this->getViajes($request);
        $rutinas = $this->getRutinas($request);

        return $this->render(':publication:home.html.twig', [
            'viajes' => $viajes,
            'rutinas' => $rutinas
        ]);
    }

    /**
     * @Route("/buscar/rutas", name="buscar_rutas")
     * @param Request $request
     * @return Response
     */
    public function buscarAction(Request $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $origen = trim($request->query->get("origen", null));
        $destino = trim($request->query->get("destino", null));


        if($origen == null){
            $viajes = $em->createQueryBuilder()
                ->select('v')
                ->from('AppBundle:Viaje', 'v')
                ->where('v.destino LIKE :search')
                ->andWhere('v.activo = true')
                ->orderBy('v.fechaPublicacion', 'ASC')
                ->setParameter('search', "%$destino%")
                ->getQuery()
                ->getResult();

            $rutinas = $em->createQueryBuilder()
                ->select('r')
                ->from('AppBundle:Rutina', 'r')
                ->where('r.destino LIKE :search')
                ->andWhere('r.activo = true')
                ->orderBy('r.fechaPublicacion', 'ASC')
                ->setParameter('search', "%$destino%")
                ->getQuery()
                ->getResult();
        }elseif($destino == null){
            $this->addFlash('error', 'Al menos debes introducir un destino');
            return $this->redirectToRoute('homepage');
        }else{
            $viajes = $em->createQueryBuilder()
                ->select('v')
                ->from('AppBundle:Viaje', 'v')
                ->where('v.origen LIKE :origen')
                ->andWhere('v.destino LIKE :destino')
                ->andWhere('v.activo = true')
                ->orderBy('v.fechaPublicacion', 'ASC')
                ->setParameter('origen', "%$origen%")
                ->setParameter('destino', "%$destino%")
                ->getQuery()
                ->getResult();

            $rutinas = $em->createQueryBuilder()
                ->select('r')
                ->from('AppBundle:Rutina', 'r')
                ->where('r.origen LIKE :origen')
                ->andWhere('r.destino LIKE :destino')
                ->andWhere('r.activo = true')
                ->orderBy('r.fechaPublicacion', 'ASC')
                ->setParameter('origen', "%$origen%")
                ->setParameter('destino', "%$destino%")
                ->getQuery()
                ->getResult();
        }

        $paginador_viajes = $this->get('knp_paginator');

        $viajes_pag = $paginador_viajes->paginate(
            $viajes,
            $request->query->getInt('page', 1),       //page es la variable de la url
            5                                               //5 usuarios por pagina
        );

        $paginador_rutinas = $this->get('knp_paginator');

        $rutinas_pag = $paginador_rutinas->paginate(
            $rutinas,
            $request->query->getInt('page', 1),       //page es la variable de la url
            5                                               //5 usuarios por pagina
        );

        return $this->render(':publication:buscar.html.twig', [
            'viajes' => $viajes_pag,
            'rutinas' => $rutinas_pag
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
            ->andWhere('p.plazasLibres > 0')
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
            ->andWhere('p.plazasLibres > 0')
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

        //Mandar un mensaje rápido al conductor
        $mensaje = new Mensaje();
        $formulario = $this->createForm(fastMensajeType::class, $mensaje);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $mensaje->setEmisor($this->getUser());
            $mensaje->setReceptor($viaje->getConductor());
            $mensaje->setFechaEnviado(new DateTime('now'));
            $mensaje->setLeido(false);

            $em->persist($mensaje);
            $flush = $em->flush();

            if($flush == null){
                $this->addFlash('estado','El mensaje se ha enviado correctamente');
                return $this->redirect('/viaje/ver/'.$viaje->getId());
            }else{
                $this->addFlash('error','Hubo un problema al enviar el mensaje');
            }
        }

        //Edad del conductor a partir de su fecha de nacimiento
        $fecha = $viaje->getConductor()->getFechaNacimiento();
        $cumpleanos = new DateTime($fecha->format("Y-m-d"));
        $hoy = new DateTime();
        $annos = $hoy->diff($cumpleanos);
        $edad = $annos->y;

        //Cuenta los mensajes que hemos enviado al conductor de la ruta
        $cont_mens = $em->getRepository('AppBundle:Mensaje')->findBy([
            'emisor' => $this->getUser(),
            'receptor' => $viaje->getConductor()
        ]);

        return $this->render(':Viaje:viaje.html.twig', [
            "formulario" => $formulario->createView(),
            'viaje' => $viaje,
            'cont_mensa' => $cont_mens,
            'edad' => $edad
        ]);
    }

    /**
     * @Route("/rutina/ver/{id}", name="ver_rutina")
     * @param Request $request
     * @param Rutina $rutina
     * @return Response
     * @internal param Request $request
     */
    public function verRutinaAction(Request $request, Rutina $rutina){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        //Mandar un mensaje al condutor
        $mensaje = new Mensaje();
        $formulario = $this->createForm(fastMensajeType::class, $mensaje);

        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $mensaje->setEmisor($this->getUser());
            $mensaje->setReceptor($rutina->getConductor());
            $mensaje->setFechaEnviado(new DateTime('now'));
            $mensaje->setLeido(false);

            $em->persist($mensaje);
            $flush = $em->flush();

            if($flush == null){
                $this->addFlash('estado','El mensaje se ha enviado correctamente');
                return $this->redirect('/rutina/ver/'.$rutina->getId());
            }else{
                $this->addFlash('error','Hubo un problema al enviar el mensaje');
            }
        }

        //Edad del conductor a partir de su fecha de nacimiento
        $fecha = $rutina->getConductor()->getFechaNacimiento();
        $cumpleanos = new DateTime($fecha->format("Y-m-d"));
        $hoy = new DateTime();
        $annos = $hoy->diff($cumpleanos);
        $edad = $annos->y;

        //Cuenta los mensajes que hemos enviado al conductor de la ruta
        $cont_mens = $em->getRepository('AppBundle:Mensaje')->findBy([
            'emisor' => $this->getUser(),
            'receptor' => $rutina->getConductor()
        ]);

        return $this->render('rutina/rutina.html.twig', [
            "formulario" => $formulario->createView(),
            'rutina' => $rutina,
            'cont_mens' => $cont_mens,
            'edad' => $edad
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
            $rutina->setFechaPublicacion(new DateTime("now"));
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
                    return $this->redirect('/rutina/ver/'.$rutina->getId());
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

        dump(new DateTime("2017-06-30"));

        $usuario = $this->getUser();
        if ($viaje == null) {
            $viaje = new Viaje();
            $em->persist($viaje);
            $viaje->setConductor($usuario);
            $viaje->setFechaPublicacion(new DateTime("now"));
        }
        $form = $this->createForm(AddViajeType::class, $viaje);

        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $flush = $em->flush();

                if ($flush == null) {
                    if (null == $viaje) {
                        $this->addFlash('estado', 'El viaje se ha creado correctamente');
                    } else {
                        $this->addFlash('estado', 'Los cambios se han guardado correctamente');
                    }
                    return $this->redirect('/viaje/ver/'.$viaje->getId());
                } else {
                    if (null == $viaje) {
                        $this->addFlash('error', 'Error al añadir el viaje');
                    } else {
                        $this->addFlash('error', 'Los cambios no se han guardado correctamente');
                    }
                }
            }
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

    /**
     * @Route("viaje/añadir/plaza/{id}", name="add_plaza_viaje")
     * @param Viaje $viaje
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function añadirPlazaViajeAction(Viaje $viaje){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        try{
            if((int)$viaje->getPlazasLibres() < 4){
                $viaje->setPlazasLibres((int)$viaje->getPlazasLibres()+1);
                $em->flush();
                $this->addFlash('estado', 'Se ha añadido una plaza a tu viaje');
            }else{
                $this->addFlash('error', 'Ya tienes las 4 plazas libres. Recuerda que solo se puede llevar un máximo de 4 pasajeros y el conductor.');
            }
        }catch (Exception $exception){
            $this->addFlash('error', 'Hubo algún problema al añadir la plaza');
        }
        return $this->redirect('/viaje/ver/'.$viaje->getId());
    }

    /**
     * @Route("rutina/añadir/plaza/{id}", name="add_plaza_rutina")
     * @param Rutina $rutina
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function añadirPlazaRutinaAction(Rutina $rutina){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        try{
            if((int)$rutina->getPlazasLibres() < 4){
                $rutina->setPlazasLibres((int)$rutina->getPlazasLibres()+1);
                $em->flush();
                $this->addFlash('estado', 'Se ha añadido una plaza a tu rutina');
            }else{
                $this->addFlash('error', 'Ya tienes las 4 plazas libres. Recuerda que solo se puede llevar un máximo de 4 pasajeros y el conductor.');
            }
        }catch (Exception $exception){
            $this->addFlash('error', 'Hubo algún problema al añadir la plaza');
        }
        return $this->redirect('/rutina/ver/'.$rutina->getId());
    }
}