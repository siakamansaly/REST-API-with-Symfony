<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\TypeProduct;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\TypeProductFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = $manager->getRepository(User::class)->findOneBy(['email' => 'admin@example.fr']);

        $typeProducts = $manager->getRepository(TypeProduct::class)->findAll();
        foreach ($typeProducts as $typeProduct) {
            for ($i = 1; $i < rand(2, 4); $i++) {
                $product = new Product();
                $name = 'Product '.$typeProduct->getName().' '.$i;
                $product->setName($name);
                $product->setCoverImage('https://via.placeholder.com/600/0000FF/808080%20?Text='.$name);
                $product->setPrice(rand(100, 1000));
                $product->setDescription($typeProduct->getName(). ' haut de gamme');
                $product->setTypeProduct($typeProduct);
                $product->setCreatedAt(new \DateTime());
                $product->setReference(uniqid("REF_"));
                $product->setStock(rand(10, 500));
                $product->setUser($user);
                $manager->persist($product);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TypeProductFixtures::class,
            UserFixtures::class,
        ];
    }
}
