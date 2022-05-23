<?php 
namespace App\DataPersister;

use DateTime;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ProductDataPersister implements DataPersisterInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function supports($data, array $context = []): bool
    {
        if ($context['resource_class'] !== Product::class) {
            return false;
        }
        return $data instanceof Product;
    }

    public function persist($data, array $context = [])
    {
        if ($context['resource_class'] !== Product::class) {
            return false;
        }
        // Set values
        $date = new \DateTime();
        $data->setCreatedAt($date);
        $data->setReference(uniqid("REF_"));

        // Doctrine Persister Customer
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        if ($context['resource_class'] !== Product::class) {
            return false;
        }
        // Doctrine Remove Customer
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }


}