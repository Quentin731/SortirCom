<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        //ville
        $villes = [];
        for ($i = 0; $i < 50; $i++) {
            $ville = new Ville();
            $ville->setLibelle($faker->city());
            $ville->setCodePostal($faker->postcode());
            $manager->persist($ville);
            $villes[] = $ville;
        }

        //lieux
        $lieux = [];
        for ($i = 0; $i < 50; $i++) {
            $lieu = new Lieu();
            $lieu->setNom($faker->streetName());
            $lieu->setAdresse($faker->address());
            $lieu->setPays($faker->country());
            $lieu->setVille($faker->randomElement($villes));
            $manager->persist($lieu);
            $lieux[] = $lieu;
        }

        $users = [];
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setUsername($faker->userName());

            $password = $faker->password();

            $user->setPassword($password);
            $user->setName($faker->lastName());
            $user->setFirstname($faker->firstName());
            $user->setEmail($faker->email());
            $user->setPhoneNumber($faker->phoneNumber());
            $user->setVille($faker->randomElement($villes));
            $manager->persist($user);
            $users[] = $user;
        }

        $manager->flush();
    }

}