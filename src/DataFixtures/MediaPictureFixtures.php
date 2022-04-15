<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\MediaPicture;
use App\DataFixtures\ProductFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MediaPictureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $products = $manager->getRepository(Product::class)->findAll();

        foreach ($products as $product) {
            for ($i = 1; $i < rand(2, 4); $i++) {
                $MediaPicture = new MediaPicture();
                $MediaPicture->setName('https://via.placeholder.com/600/0000FF/808080%20');
                $MediaPicture->setProduct($product);
                $manager->persist($MediaPicture);
            }
        }
         
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class,
        ];
    }
}
