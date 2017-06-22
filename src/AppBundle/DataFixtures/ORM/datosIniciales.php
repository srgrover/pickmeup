<?php

namespace AppBundle\DataFixtures\ORM;
use AppBundle\Entity\Usuario;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class datosIniciales extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    public function load(ObjectManager $manager)
    {
        $userAdmin = new Usuario();
        $userAdmin
            ->setNombre('Admin')
            ->setApellidos('Admin')
            ->setNick('Admin')
            ->setEmail('grovebreaks@gmail.com')
            ->setFechaNacimiento(new DateTime('1994-04-11'))
            ->setCiudad('Bailén')
            ->setProvincia('Jaén')
            ->setPais('España')
            ->setTelefono('722390411')
            ->setPassword($this->container->get('security.password_encoder')->encodePassword($userAdmin, 'admin'));
        $manager->persist($userAdmin);
        $manager->flush();
    }
    public function getOrder()
    {
        return 10;
    }
    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}