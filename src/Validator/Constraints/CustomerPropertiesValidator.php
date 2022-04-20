<?php

namespace App\Validator\Constraints;

use App\Repository\CustomerRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class CustomerPropertiesValidator extends ConstraintValidator
{
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function validate($value, Constraint $constraint): void
    {

        $customer = $this->customerRepository->findOneBy(['name' => $value]);

        if (!$customer) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}