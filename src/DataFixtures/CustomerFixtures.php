<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <=50; $i++){
            $customer = new Customer();
            $customer->setEmail('contact@orange.fr')
                ->setPassword('mypassword')
                ->setCompagny('Orange')
                ->setCreatedAt(new \DateTime());

            $manager->persist($customer);
        }

        $manager->flush();
    }
}
