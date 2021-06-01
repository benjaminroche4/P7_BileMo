<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        for($i = 1; $i <=20; $i++){
            $user = new User();
            $user->setFirstname('John')
                ->setLastname('Doe')
                ->setEmail('johndoe@gmail.com')
                ->setCreatedAt(new \DateTime())
                ->setCustomerId($this->getReference(CustomerFixtures::CUSTOMER_REFERENCE));

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return[
            CustomerFixtures::class,
        ];
    }
}
