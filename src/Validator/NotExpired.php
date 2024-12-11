<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class NotExpired extends Constraint
{
    public string $message = 'Credit card has been expired';
    public string $mode = 'strict';


}