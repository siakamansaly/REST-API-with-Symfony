<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $customers = ['BileMo','Customer1','Lorem','Ipsum'];

        foreach ($customers as $customer) {
            $client = new Customer();
            $client->setName($customer);
            $client->setCreatedAt(new DateTime());
            $manager->persist($client);
        }
         
        $manager->flush();
    }
}
