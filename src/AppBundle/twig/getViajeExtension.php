<?php

namespace AppBundle\twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class getViajeExtension extends \Twig_Extension{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine){
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('get_viaje', array($this, 'getViajeFilter'))
        );
    }

    public function getViajeFilter($viaje_id){
        $viaje_repo = $this->doctrine->getRepository('AppBundle:Viaje');
        $viaje = $viaje_repo->findOneBy([
            "id" => $viaje_id,
        ]);

        if (!empty($viaje) && is_object($viaje)){
            $result = $viaje;
        }else{
            $result = false;
        }

        return $result;
    }

    public function getName(){
        return 'get_viaje_extension';
    }
}