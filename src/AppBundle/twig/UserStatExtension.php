<?php

namespace AppBundle\twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class UserStatExtension extends \Twig_Extension{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine){
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('user_stats', array($this, 'userStatsFilter'))
        );
    }

    public function userStatsFilter($user){
        $following_repo = $this->doctrine->getRepository('AppBundle:Seguimiento');
        $publication_repo = $this->doctrine->getRepository('AppBundle:Viaje');

        $user_following = $following_repo->findBy(array('usuario' => $user));       //Usuarios que estoy siguiendo
        $followers = $following_repo->findBy(array('seguidor' => $user));           //Usuarios que estoy siguiendo
        $user_publication = $publication_repo->findBy(array('conductor' => $user)); //Viajes de un conductor

        $result = array(
            'siguiendo' => count($user_following),
            'seguidores' => count($followers),
            'publicaciones' => count($user_publication)
        );

        return $result;

    }

    public function getName(){
        return 'user_stats_extension';
    }
}