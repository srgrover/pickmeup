<?php

namespace AppBundle\twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class getUserExtension extends \Twig_Extension{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine){
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('get_user', array($this, 'getUserFilter'))
        );
    }

    public function getUserFilter($usuario_id){
        $usuario_repo = $this->doctrine->getRepository('AppBundle:Usuario');
        $usuario = $usuario_repo->findOneBy(array(
            "id" => $usuario_id,
        ));

        if (!empty($usuario) && is_object($usuario)){
            $result = $usuario;
        }else{
            $result = false;
        }

        return $result;
    }

    public function getName(){
        return 'get_user_extension';
    }
}