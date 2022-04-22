<?php 
namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private $customerRepository;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, CustomerRepository $customerRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->customerRepository = $customerRepository;
        $this->passwordHasher = $passwordHasher;
    }


    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        // Hash password
        $data->setPassword($this->passwordHasher->hashPassword($data, $data->getPassword()));

        // Set Role User 
        $data->setRoles(['ROLE_USER']);
        
        // Set Customer User
        $customer = $this->customerRepository->findOneBy(['name' => $data->getCustomer()->getName()]);
        $data->setCustomer($customer);

        // Doctrine Persister User
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        // Doctrine Remove User
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }


}