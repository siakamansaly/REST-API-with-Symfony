<?php 
namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private $passwordHasher;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
    }


    public function supports($data, array $context = []): bool
    {
        if ($context['resource_class'] !== User::class) {
            return false;
        }
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        if ($context['resource_class'] !== User::class) {
            return false;
        }
        // Hash password
        $data->setPassword($this->passwordHasher->hashPassword($data, $data->getPassword()));

        // Set Role User 
        $data->setRoles(['ROLE_USER']);
        
        // Set Customer User
        $userCurrent = $this->security->getUser()->getUserIdentifier();
        
        $customer = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $userCurrent]);
        $data->setCustomer($customer->getCustomer());
        // Doctrine Persister User
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        if ($context['resource_class'] !== User::class) {
            return false;
        }
        // Doctrine Remove User
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }


}