<?php

namespace App\DataFixtures;

use App\Entity\TypeProduct;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TypeProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $typeProductNames = ['Smartphone','Classic','Multimedia','Accessory'];

        foreach ($typeProductNames as $typeProductName) {
            $typeProduct = new TypeProduct();
            $typeProduct->setName($typeProductName);
            $manager->persist($typeProduct);
        }
         
        $manager->flush();
    }
}
