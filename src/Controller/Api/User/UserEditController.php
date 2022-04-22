<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserEditController extends AbstractController
{
    private $userRepository;
    private $customerRepository;
    private $errors = [];

    public function __construct(UserRepository $userRepository, CustomerRepository $customerRepository)
    {
        $this->userRepository = $userRepository;
        $this->customerRepository = $customerRepository;
    }

    public function __invoke(User $data)
    {
        $user = $this->userRepository->findOneBy(['email' => $data->getEmail()]);

        if ($user && $user->getId() !== $data->getId()) {
            $this->errors['user_exist'] = 'This user already exists';
        }

        if ($this->errors) {
            return $this->json(['errors' => $this->errors], 400);
        }

        return $data;
    }
}
