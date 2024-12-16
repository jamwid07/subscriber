<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StateRequiredValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint): void
    {
        if ((null === $value->getStateProvince() || '' === $value->getStateProvince()) || !in_array($value->getCountry(), ['US', 'CA', 'GB', 'AU', 'SP']) ) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}