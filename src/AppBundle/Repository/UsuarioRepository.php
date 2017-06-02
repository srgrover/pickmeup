<?php
namespace AppBundle\Repository;

class UsuarioRepository extends \Doctrine\ORM\EntityRepository{
    public function getUsuariosSiguiendo($usuario){
        $em = $this->getEntityManager();

        $siguiendo_repo = $em->getRepository('AppBundle:Seguimiento');
        $siguiendo = $siguiendo_repo->findBy(['usuario' => $usuario]);

        $siguiendo_array = array();
        foreach ($siguiendo as $seguir){
            $siguiendo_array[] = $seguir->getSeguidor();
        }

        $usuario_repo = $em->getRepository('AppBundle:Usuario');
        $usuarios = $usuario_repo->createQueryBuilder('u')
            ->where('u.id != :usuario AND u.id IN (:siguiendo)')
            ->setParameter('usuario', $usuario->getId())
            ->setParameter('siguiendo', $siguiendo_array)
            ->orderBy('u.id', 'DESC');

        return $usuarios;
    }
}