<?php

namespace App\DataFixtures;

use App\Entity\Place;
use App\Entity\Trip;
use App\Entity\User;
use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        //city
        $citys = [];
        for ($i = 0; $i < 100; $i++) {
            $city = new City();
            $city->setCityName($faker->city());
            $city->setPostalCode($faker->postcode());
            $manager->persist($city);
            $citys[] = $city;
        }

        //places
        $places = [];
        for ($i = 0; $i < 100; $i++) {
            $place = new Place();
            $place->setPlaceName($faker->streetName());
            $place->setAddress($faker->address());
            $place->setCountry($faker->country());
            $place->setCity($faker->randomElement($citys));
            $manager->persist($place);
            $places[] = $place;
        }
        //-----------USER----------------
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
            $user->setCity($faker->randomElement($citys));
            $user->setRoles($faker->randomElement([["ROLE_USER"],['ROLE_ORGA']]));
            $manager->persist($user);
            $users[] = $user;
        }


        $user = new User();
        $user->setUsername('user');
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, 'root42'));
        $user->setLastname('userLastName');
        $user->setFirstname('userFirstName');
        $user->setEmail('useremail@gmail.com');
        $user->setPhoneNumber('0632138578');
        $user->setCity($faker->randomElement($citys));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        $users[] = $user;

        $orga = new User();
        $orga->setUsername('orga');
        $orga->setPassword($this->userPasswordHasherInterface->hashPassword($orga, 'root42'));
        $orga->setLastname('orgaLastName');
        $orga->setFirstname('orgaFirstName');
        $orga->setEmail('orgaemail@gmail.com');
        $orga->setPhoneNumber('0632138578');
        $orga->setCity($faker->randomElement($citys));
        $orga->setRoles(["ROLE_ORGA"]);
        $manager->persist($orga);
        $users[] = $orga;

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword($this->userPasswordHasherInterface->hashPassword($admin, 'root42'));
        $admin->setLastname('adminLastName');
        $admin->setFirstname('adminFirstName');
        $admin->setEmail('adminemail@gmail.com');
        $admin->setPhoneNumber('0632138578');
        $admin->setCity($faker->randomElement($citys));
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);
        $users[] = $admin;


        //-------------TRIP------------------------

        for ($i = 0; $i < 200; $i++) {
            $deadlineRegistrationDate = $faker->dateTime('now');
            $tripStartDate = $faker->dateTime();
            $dateEnd = $faker->dateTimeInInterval($deadlineRegistrationDate,'+ 20 days');
            if ($tripStartDate > $deadlineRegistrationDate ){

                $deadlineRegistrationDate = date_create($deadlineRegistrationDate->format('Y-m-d'));

                $trip = new Trip();
                $trip->setPlace($faker->randomElement($places));
                $trip->setTripName($faker->catchPhrase());
                $trip->setDeadlineRegistrationDate($deadlineRegistrationDate);
                $trip->setTripStartDate($tripStartDate);
                $trip->setDuration($faker->numberBetween(15, 300));
                $trip->setCapacity($faker->numberBetween(5, 8));
                $trip->setDescription($faker->catchPhrase());
                $trip->setState($faker->numberBetween(1, 3));
                $trip->setOrganizer($faker->randomElement($users));
                $trip->addUsers($faker->randomElements($users,$faker->randomDigitNotNull()));
                $trip->setEndDate($dateEnd);
                $manager->persist($trip);
            }
        }

        $admin = new User();
        $admin->setUsername('admintest');
        $admin->setPassword('root42');
        $admin->setLastname('adminLastName');
        $admin->setFirstname('adminFirstName');
        $admin->setEmail('adminemail@gmail.com');
        $admin->setPhoneNumber('0632138578');
        $admin->setCity($faker->randomElement($citys));
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->addTrip($trip);
        $manager->persist($admin);
        $users[] = $admin;

        $manager->flush();
    }

}
