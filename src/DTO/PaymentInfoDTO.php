<?php

namespace App\DTO;

class PaymentInfoDTO
{
    public ?string $cardNumber = null;
    public ?string $expirationDate = null;
    public ?string $cvv = null;
}