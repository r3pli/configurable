<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) :void
    {
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setName($this->generateRandomName());

            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return string|null
     */
    private function generateRandomName(): ?string
    {
        $firstNames = array(
            'John',
            'Herbert',
            'Sven',
            'Otto',
            'Andrea',
            'Silvia',
            'Hugo',
            'Max',
            'Sebastian',
            'Anne',
            'Frank',
            'Eva',
        );

        $lastNames = array(
            'Doe',
            'Meier',
            'Müller',
            'Schmidt',
            'Frank',
            'Mustermann',
            'Musterfrau',
            'Spitzer',
            'Walther',
            'West',
            'Holywood',
        );

        try {
            return $firstNames[random_int(0, count($firstNames) - 1)] . ' ' . $lastNames[random_int(0, count($lastNames) - 1)];
        } catch (\Exception $e) {
            //TODO: handle exception here
            return '';
        }
    }
}