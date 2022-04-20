<?php

namespace App\Validator\Constraints;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class UserPropertiesValidator extends ConstraintValidator
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint): void
    {
        $user = $this->userRepository->findOneBy(['email' => $value]);

        if ($user && $user->getId() !== $this->context->getRoot()->getId()) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}