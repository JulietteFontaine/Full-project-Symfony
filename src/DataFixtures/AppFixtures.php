<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Customer;
use App\Entity\Invoice;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('it_IT');

        $admin = new User;
        $admin->firstName = $faker->firstName;
        $admin->lastName = $faker->lastName;
        $admin->email = "admin@email.com";
        $admin->password = "password";
        $admin->roles = ["ROLE_ADMIN", "ROLE_COMPTABLE"];

        $manager->persist($admin);

        $moderator = new User();
        $moderator->firstName = $faker->firstName;
        $moderator->lastName = $faker->lastName;
        $moderator->email = "moderator@gmail.com";
        $moderator->password = "password";
        $moderator->roles = ["ROLE_MODERATOR"];

        $manager->persist($moderator);

        for ($u = 0; $u < 3; $u++) {
            $user = new User;

            $user->firstName = $faker->firstName();
            $user->lastName = $faker->lastName();
            $user->email = "user$u@mail.com";
            $user->password = "password";

            $manager->persist($user);

            for ($i = 0; $i < mt_rand(5, 10); $i++) {
                $customer = new Customer;

                $customer->firstName = $faker->firstName();
                $customer->lastName = $faker->lastName();
                $customer->email = $faker->email();

                $customer->user = $user;

                $manager->persist($customer);

                for ($j = 0; $j < mt_rand(3, 5); $j++) {
                    $invoice = new Invoice;
                    $invoice->amount = $faker->numberBetween(10000, 100000);
                    $invoice->createdAt = $faker->dateTime('-6 months');
                    $invoice->customer = $customer;

                    $manager->persist($invoice);
                }
            }
        }

        $manager->flush();
    }
}
