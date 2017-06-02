<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mensaje;
use AppBundle\Form\MensajeType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("has_role('ROLE_USER')")
 */
class MensajeController extends Controller{
    /**
     * @Route("/mensajes", name="mensajes")
     */
    public function indexAction(Request $request){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();

        $mensaje = new Mensaje();
        $formulario = $this->createForm(MensajeType::class, $mensaje,[
            'empty_data' => $usuario
        ]);

        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $mensaje->setEmisor($usuario);
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

        return $this->render(':Mensajes:index.html.twig', [
            "formulario" => $formulario->createView()
        ]);
    }
}
