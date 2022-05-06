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
        // Add admin user
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

        // Add customer users
        $customers = $manager->getRepository(Customer::class)->findAll();
        foreach ($customers as $customer) {
            if ($customer->getName() !== 'BileMo') {
                $user = new User();
                $user->setFirstname('Client');
                $user->setLastname($customer->getName());
                $user->setEmail(strtolower($customer->getName()).'@example.fr');
                $user->setRoles(['ROLE_CUSTOMER']);
                $user->setCustomer($customer);
                $password = $this->hasher->hashPassword($user, 'password');
                $user->setPassword($password);
                $manager->persist($user);
            }
        }

        // Add users
        foreach ($customers as $customer) {
            if ($customer->getName() !== 'BileMo') {
                for ($i = 1; $i < rand(2, 15); $i++) {
                    $user = new User();
                    $user->setFirstname('User'.$i);
                    $user->setLastname($customer->getName());
                    $user->setEmail('user'.$i.'@'.strtolower($customer->getName()).'.fr');
                    $user->setRoles(['ROLE_USER']);
                    $user->setCustomer($customer);
                    $password = $this->hasher->hashPassword($user, 'password');
                    $user->setPassword($password);
                    $manager->persist($user);
                }
            }
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
