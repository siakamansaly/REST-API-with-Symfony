<?php
// api/src/Validator/Constraints/MinimalProperties.php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserProperties extends Constraint
{
    public $message = 'This user is already registered';
}