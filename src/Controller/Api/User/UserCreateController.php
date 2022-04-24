<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserCreateController extends AbstractController
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
        $customer = "";
        
        if ($data->getCustomer()) {
            $customer = $this->customerRepository->findOneBy(['name' => $data->getCustomer()->getName()]);
        }

        if ($user) {
            $this->errors['user_exist'] = 'This user already exists';
        }

        switch (true) {
            case (!$data->getCustomer()):
                $this->errors['customer_empty'] = 'Please add a customer';
                break;
            case (!$customer):
                $this->errors['customer_not_exist'] = 'This customer does not exist';
                break;
        }

        if ($this->errors) {
            return $this->json(['errors' => $this->errors], 400);
        }

        return $data;
    }
}
