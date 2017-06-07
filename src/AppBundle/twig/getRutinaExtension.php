<?php

namespace AppBundle\twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class getRutinaExtension extends \Twig_Extension{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine){
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('get_rutina', array($this, 'getRutinaFilter'))
        );
    }

    public function getRutinaFilter($rutina_id){
        $rutina_repo = $this->doctrine->getRepository('AppBundle:Rutina');
        $rutina = $rutina_repo->findOneBy(array(
            "id" => $rutina_id,
        ));

        if (!empty($rutina) && is_object($rutina)){
            $result = $rutina;
        }else{
            $result = false;
        }

        return $result;
    }

    public function getName(){
        return 'get_rutina_extension';
    }
}