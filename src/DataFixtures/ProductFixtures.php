<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <=20; $i++){
            $product = new Product();
            $product->setBrand('Apple')
                ->setColor('Red')
                ->setCreatedAt(new \DateTime())
                ->setDescription('Produit très populaire')
                ->setPrice(599.99)
                ->setModel('Iphone 8');

            $manager->persist($product);
        }

        $manager->flush();
    }
}
