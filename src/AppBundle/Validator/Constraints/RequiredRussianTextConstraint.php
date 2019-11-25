<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class RequiredRussianTextConstraint
 * @package AppBundle\Validator\Constraints
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class RequiredRussianTextConstraint extends Constraint
{
    public $message = 'Warning.';
}
