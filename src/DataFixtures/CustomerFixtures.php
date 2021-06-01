<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{
    public const CUSTOMER_REFERENCE = '3791';

    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <=20; $i++){
            $customer = new Customer();
            $customer->setEmail('contact@orange.fr')
                ->setPassword('mypassword')
                ->setCompagny('Orange')
                ->setCreatedAt(new \DateTime());

            $this->setReference(self::CUSTOMER_REFERENCE, $customer);

            $manager->persist($customer);
        }

        $manager->flush();
    }
}
