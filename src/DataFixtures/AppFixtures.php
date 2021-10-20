<?php

namespace App\DataFixtures;

use App\Entity\Place;
use App\Entity\Trip;
use App\Entity\User;
use App\Entity\City;
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
        for ($i = 0; $i < 100; $i++) {
            $ville = new City();
            $ville->setCityName($faker->city());
            $ville->setPostalCode($faker->postcode());
            $manager->persist($ville);
            $villes[] = $ville;
        }

        //lieux
        $lieux = [];
        for ($i = 0; $i < 100; $i++) {
            $lieu = new Place();
            $lieu->setPlaceName($faker->streetName());
            $lieu->setAdresse($faker->address());
            $lieu->setCountry($faker->country());
            $lieu->setCity($faker->randomElement($villes));
            $manager->persist($lieu);
            $lieux[] = $lieu;
        }

        $users = [];
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setUsername($faker->userName());

            $password = $faker->password();

            $user->setPassword($password);
            $user->setLastname($faker->lastName());
            $user->setFirstname($faker->firstName());
            $user->setEmail($faker->email());
            $user->setPhoneNumber($faker->phoneNumber());
            $user->setCity($faker->randomElement($villes));
            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 0; $i < 100; $i++) {
            $dateLimite = $faker->dateTime('now');
            $dateSortie = $faker->dateTime();
            $dateEnd = $faker->dateTimeInInterval($dateLimite,'+ 20 days');
            if ($dateSortie > $dateLimite ){

                $dateLimite = date_create($dateLimite->format('Y-m-d'));

                $sortie = new Trip();
                $sortie->setPlace($faker->randomElement($lieux));
                $sortie->setTripName($faker->catchPhrase());
                $sortie->setDeadlineRegistrationDate($dateLimite);
                $sortie->setTripStartDate($dateSortie);
                $sortie->setDuration($faker->numberBetween(15, 300));
                $sortie->setCapacity($faker->numberBetween(5, 300));
                $sortie->setDescription($faker->catchPhrase());
                $sortie->setState($faker->numberBetween(1, 3));
                $sortie->setOrganizer($faker->randomElement($users));
                $sortie->setEndDate($dateEnd);
                $manager->persist($sortie);
            }
        }

        $manager->flush();
    }

}
