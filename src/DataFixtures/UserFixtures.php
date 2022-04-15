<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Customer;
use App\DataFixtures\CustomerFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $client = $manager->getRepository(Customer::class)->findOneBy(['name' => 'BileMo']);
        $user = new User();
        $user->setFirstname('admin');
        $user->setLastname('admin');
        $user->setEmail('admin@example.fr');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setCustomer($client);
        $password = $this->hasher->hashPassword($user, 'password');
        $user->setPassword($password);
        $manager->persist($user);

        $client = $manager->getRepository(Customer::class)->findOneBy(['name' => 'Customer1']);
        $user = new User();
        $user->setFirstname('Client');
        $user->setLastname('Customer');
        $user->setEmail('customer@example.fr');
        $user->setRoles(['ROLE_CUSTOMER']);
        $user->setCustomer($client);
        $password = $this->hasher->hashPassword($user, 'password');
        $user->setPassword($password);
        $manager->persist($user);

        for ($i = 1; $i < 11; $i++) {
            $user = new User();
            $user->setFirstname('User');
            $user->setLastname($i);
            $user->setEmail('user'.$i.'@example.fr');
            $user->setRoles(['ROLE_USER']);
            $user->setCustomer($client);
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CustomerFixtures::class,
        ];
    }
}
