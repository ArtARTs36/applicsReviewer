<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class NoHTMLConstraint
 * @package AppBundle\Validator\Constraints
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class NoHTMLConstraint extends Constraint
{
    public $message = 'This string exists html codes.';
}
