<?php

namespace App\Controller\Api\Customer;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerCreateController extends AbstractController
{
    private $customerRepository;
    private $errors = [];

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function __invoke(Customer $data)
    {
        if ($this->customerRepository->findOneBy(['name' => $data->getName()])) {
            $this->errors['customer_exist'] = 'This customer already exists';
            return $this->json(['errors' => $this->errors], 400);
        }
        return $data;
    }
}
