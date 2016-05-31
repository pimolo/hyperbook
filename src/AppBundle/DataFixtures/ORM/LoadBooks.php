<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Book;
use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadBooks extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        /** @var Category $novels */
        $novels = $this->getReference('novels');

        $book1 = (new Book())
            ->setName('Hamlet')
            ->setAuthor('Shakespeare')
            ->setDescription('A reference.')
            ->setCategory($novels)
            ->setPath('cv_remi_andrieux.pdf')
        ;

        $book2 = (new Book())
            ->setName('Romeo and Juliet')
            ->setAuthor('Shakespeare')
            ->setDescription('An other reference.')
            ->setCategory($novels)
            ->setPath('cv_remi_andrieux.pdf')
        ;
        $manipulator = $this->container->get('fos_user.util.user_manipulator');

        $admin = $manipulator->create('admin', 'password', 'admin@hyperbook.com', true, false)->addRole('ROLE_ADMIN');
        $user  = $manipulator->create('user', 'password', 'user@hyperbook.com', true, false)->addRole('ROLE_USER');

        $manager->persist($book1);
        $manager->persist($book2);
        $manager->persist($admin);
        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
