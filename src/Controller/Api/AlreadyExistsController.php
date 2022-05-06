<?php

namespace App\Controller\Api;

use App\Service\ErrorFormatterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlreadyExistsController extends AbstractController
{
    private $repository;
    private $details;
    private $errorService;
    private $violations = [];

    public function __construct(EntityManagerInterface $repository, Security $security, ErrorFormatterService $errorService)
    {
        $this->repository = $repository;
        $this->security = $security;
        $this->errorService = $errorService;
    }

    public function __invoke($data = null, Request $request)
    {
        switch (true) {
            case $data instanceof \App\Entity\User:
                $this->alreadyExistsEmail($data, $request->getMethod(), '\App\Entity\User');
                break;
            case $data instanceof \App\Entity\Product:
                $this->alreadyExistsName($data, $request->getMethod(), '\App\Entity\Product');
                break;
            case $data instanceof \App\Entity\Customer:
                $this->alreadyExistsName($data, $request->getMethod(), '\App\Entity\Customer');
                break;
        }

        if ($this->violations) {
            return $this->json($this->errorService->ErrorPersist($this->violations, $this->details), 422);
        }
        return $data;
    }

    public function alreadyExistsEmail($data, string $method, string $class):void
    {
        $nameProperty = strtolower(str_replace('\App\Entity\\', '', $class));
        switch ($method) {
            case 'POST':
                $user = $this->repository->getRepository($class)->findOneBy(['email' => $data->getEmail()]);
                if ($user !== null) {
                    $this->addViolationMessage($nameProperty, 'email');
                }
                break;
            case 'PATCH':
                $user = $this->repository->getRepository($class)->findOneBy(['email' => $data->getEmail()]);
                if ($user && $user->getId() !== $data->getId()) {
                    $this->addViolationMessage($nameProperty, 'email');
                }
                break;
        }
    }

    public function alreadyExistsName($data, string $method, string $class):void
    {
        $nameProperty = strtolower(str_replace('\App\Entity\\', '', $class));
        switch ($method) {
            case 'POST':
                $user = $this->repository->getRepository($class)->findOneBy(['name' => $data->getName()]);
                if ($user !== null) {
                    $this->addViolationMessage($nameProperty, 'name');
                }
                break;
            case 'PATCH':
                $user = $this->repository->getRepository($class)->findOneBy(['name' => $data->getName()]);
                if ($user && $user->getId() !== $data->getId()) {
                    $this->addViolationMessage($nameProperty, 'name');
                }
                break;
        }
    }

    public function addViolationMessage(string $entity, string $property_path='name'):void
    {
        $message = 'This '.$entity.' already exists.';
        $this->details.= $this->errorService->addDetailError($this->details, $property_path, $message);
        $this->violations[] = $this->errorService->addViolationError($property_path, $message);
    }
}
