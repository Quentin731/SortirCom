<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Sortie;
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
        for ($i = 0; $i < 100; $i++) {
            $ville = new Ville();
            $ville->setLibelle($faker->city());
            $ville->setCodePostal($faker->postcode());
            $manager->persist($ville);
            $villes[] = $ville;
        }

        //lieux
        $lieux = [];
        for ($i = 0; $i < 100; $i++) {
            $lieu = new Lieu();
            $lieu->setNom($faker->streetName());
            $lieu->setAdresse($faker->address());
            $lieu->setPays($faker->country());
            $lieu->setVille($faker->randomElement($villes));
            $manager->persist($lieu);
            $lieux[] = $lieu;
        }

        $users = [];
        for ($i = 0; $i < 100; $i++) {
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

        for ($i = 0; $i < 100; $i++) {
            $dateLimite = $faker->dateTime('now');
            $dateSortie = $faker->dateTime();
            $dateEnd = $faker->dateTimeInInterval($dateLimite,'+ 20 days');
            if ($dateSortie > $dateLimite ){

                $dateLimite = date_create($dateLimite->format('Y-m-d'));

                $sortie = new Sortie();
                $sortie->setLieu($faker->randomElement($lieux));
                $sortie->setNom($faker->catchPhrase());
                $sortie->setDateLimite($dateLimite);
                $sortie->setDateSortie($dateSortie);
                $sortie->setDuree($faker->numberBetween(15, 300));
                $sortie->setNombrePlace($faker->numberBetween(5, 300));
                $sortie->setDescription($faker->catchPhrase());
                $sortie->setState($faker->numberBetween(1, 3));
                $sortie->setOrganisateur($faker->randomElement($users));
                $sortie->setEndDate($dateEnd);
                $manager->persist($sortie);
            }
        }

        $manager->flush();
    }

}
