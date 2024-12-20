<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotExpiredValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $date = \DateTime::createFromFormat('m/y', $value)
            ->add(\DateInterval::createFromDateString('1 month'));
        if ($date < new \DateTime()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}