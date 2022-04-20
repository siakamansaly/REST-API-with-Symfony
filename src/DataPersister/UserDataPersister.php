<?php 
namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

final class UserDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private $customerRepository;

    public function __construct(EntityManagerInterface $entityManager, CustomerRepository $customerRepository)
    {
        $this->entityManager = $entityManager;
        $this->customerRepository = $customerRepository;
    }


    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        
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