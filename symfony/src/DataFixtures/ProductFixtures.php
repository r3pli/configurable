<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $possibleOwner = $manager->getRepository(User::class)->findAll();

        for ($i = 0; $i < 40; $i++) {
            $product = new Product();
            $product->setName('Product ' . $i)
                ->setPrice(random_int(100,100000));

            $product->setOwner($possibleOwner[random_int(0,count($possibleOwner)-1)]);

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies() :array
    {
        return array(
            UserFixtures::class,
        );
    }
}