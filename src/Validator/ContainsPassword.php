<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsPassword extends Constraint
{
    public $message = 'Le format du mot de passe n\'est pas respecté';
    public $mode = 'strict';
}
