<?php 
namespace App\DataPersister;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

final class CustomerDataPersister implements DataPersisterInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function supports($data, ?array $context = []): bool
    {
        return $data instanceof Customer;
    }

    public function persist($data, ?array $context = [])
    {
        // Set Date 
        $data->setCreatedAt(new \DateTime());

        // Doctrine Persister Customer
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, ?array $context = [])
    {
        // Doctrine Remove Customer
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }


}