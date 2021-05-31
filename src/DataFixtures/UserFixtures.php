<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <=50; $i++){
            $user = new User();
            $user->setFirstname('John')
                ->setLastname('Doe')
                ->setEmail('johndoe@gmail.com')
                ->setCreatedAt(new \DateTime())
                ->setCustomerId($manager->find(Customer::class, 40));
        }

        $manager->flush();
    }
}
