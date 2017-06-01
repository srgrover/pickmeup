<?php
namespace AppBundle\Repository;

class UsuarioRepository extends \Doctrine\ORM\EntityRepository{
    public function getUsuariosSiguiendo($usuario){
        $em = $this->getEntityManager();

        $siguiendo_repo = $em->getRepository('AppBundle:Seguimiento');
        $siguiendo = $siguiendo_repo->findBy(['usuario' => $usuario]);

        $siguiendo_array = array();
    }
}