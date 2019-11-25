<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class NoLinksConstraint
 * @package AppBundle\Validator\Constraints
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class NoLinksConstraint extends Constraint
{
    public $message = 'The string "{{ string }}" exists links.';
}
