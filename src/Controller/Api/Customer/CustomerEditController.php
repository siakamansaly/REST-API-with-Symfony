<?php

namespace App\Controller\Api\Customer;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerEditController extends AbstractController
{
    private $customerRepository;
    private $errors = [];

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function __invoke(Customer $data)
    {
        $customer = $this->customerRepository->findOneBy(['name' => $data->getName()]);
       
        if ($customer && $customer->getId() !== $data->getId()) {
            $this->errors['customer_exist'] = 'This customer already exists';
            return $this->json(['errors' => $this->errors], 422);
        }
        return $data;
    }
}
