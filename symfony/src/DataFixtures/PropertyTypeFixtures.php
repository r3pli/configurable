<?php

namespace App\DataFixtures;

use App\Entity\PropertyType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PropertyTypeFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) :void
    {
        $textType = new PropertyType();
        $textType->setName('Text');
        $manager->persist($textType);

        $selectType = new PropertyType();
        $selectType->setName('Select');
        $manager->persist($selectType);

        $msType = new PropertyType();
        $msType->setName('Multi-Select');
        $manager->persist($msType);

        $manager->flush();
    }
}