<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategories extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('Novels');
        $this->setReference('novels', $category);

        $manager->persist($category);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
