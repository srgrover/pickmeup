<?php

namespace AppBundle\twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class UserStatExtension extends \Twig_Extension{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine){
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return [
            new \Twig_SimpleFilter('user_stats', [$this, 'userStatsFilter'])
        ];
    }

    public function userStatsFilter($user){
        $following_repo = $this->doctrine->getRepository('AppBundle:Seguimiento');
        $viaje_repo = $this->doctrine->getRepository('AppBundle:Viaje');
        $rutina_repo = $this->doctrine->getRepository('AppBundle:Rutina');

        $user_following = $following_repo->findBy(['usuario' => $user]);       //Usuarios que estoy siguiendo
        $followers = $following_repo->findBy(['seguidor' => $user]);           //Usuarios que me siguen
        $viajes = $viaje_repo->findBy(['conductor' => $user,'activo' => true]);                 //Viajes de un conductor
        $rutinas = $rutina_repo->findBy(['conductor' => $user,'activo' => true]);               //Rutinas de un conductor

        $result = [
            'siguiendo' => count($user_following),
            'seguidores' => count($followers),
            'publicaciones' => count($viajes) + count($rutinas)
        ];

        return $result;

    }

    public function getName(){
        return 'user_stats_extension';
    }
}