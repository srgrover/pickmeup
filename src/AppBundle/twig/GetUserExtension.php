<?php

namespace AppBundle\twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class FollowingExtension extends \Twig_Extension{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine){
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('following', array($this, 'followingFilter'))
        );
    }

    public function followingFilter($user, $followed){
        $following_repo = $this->doctrine->getRepository('AppBundle:Seguimiento');
        $user_following = $following_repo->findOneBy(array(
            "usuario" => $user,
            "seguidor" => $followed
        ));

        if (!empty($user_following) && is_object($user_following)){
            $result = true;
        }else{
            $result = false;
        }

        return $result;
    }

    public function getName(){
        return 'following_extension';
    }
}