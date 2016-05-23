<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Book;
use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadBooks extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var Category $novels */
        $novels = $this->getReference('novels');

        $book1 = (new Book())
            ->setName('Hamlet')
            ->setAuthor('Shakespeare')
            ->setDescription('A reference.')
            ->setCategory($novels)
            ->setPath('fixtures/cv_remi_andrieux.pdf')
        ;

        $book2 = (new Book())
            ->setName('Romeo and Juliet')
            ->setAuthor('Shakespeare')
            ->setDescription('An other reference.')
            ->setCategory($novels)
            ->setPath('fixtures/cv_remi_andrieux.pdf')
        ;

        $manager->persist($book1);
        $manager->persist($book2);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
