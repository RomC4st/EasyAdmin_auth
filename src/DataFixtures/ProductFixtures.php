<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Product; 


class ProductFixtures extends Fixture
{
    
    public function load(ObjectManager $manager)
    {
        $product1 = new Product();
        $product1->setName('Bois');
        $product1 ->setNumber(5);
        $product1-> setIsActive(true);
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName('Verre');
        $product2 ->setNumber(2);
        $product2-> setIsActive(true);
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setName('Metal');
        $product3 ->setNumber(10);
        $product3-> setIsActive(true);
        $manager->persist($product3);
        $manager->flush();
    }
}