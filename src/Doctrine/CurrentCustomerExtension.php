<?php

namespace App\Doctrine;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;

class CurrentCustomerExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;
    private $entityManager;
    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, ?QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
    {
        $this->addWhere($resourceClass, $queryBuilder);
    }

    public function applyToItem(QueryBuilder $queryBuilder, ?QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, $operationName = null, array $context = [])
    {
        $this->addWhere($resourceClass, $queryBuilder);
    }

    private function addWhere(string $resourceClass, QueryBuilder $queryBuilder)
    {
        if($resourceClass === User::class && $this->security->getUser()->getRoles()[0] === 'ROLE_CUSTOMER') {
            $customer = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $this->security->getUser()->getUserIdentifier()]);
            $alias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere($alias . '.customer = :customerId');
            $queryBuilder->setParameter('customerId', $customer->getCustomer());
        }
    }
}