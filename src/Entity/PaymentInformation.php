<?php

namespace App\Entity;

use App\Repository\PaymentInformationRepository;
use App\Validator\NotExpired;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PaymentInformationRepository::class)]
class PaymentInformation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Luhn]
    private ?string $cardNumber = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^(0[1-9]|1[0-2])\/([0-9]{2})$/', message: 'Expiration date must be in MM/YY format')]
    #[NotExpired]
    private ?string $expirationDate = null;

    #[ORM\Column(length: 3)]
    #[Assert\NotBlank]
    #[Assert\Length(exactly: 3)]
    #[Assert\Regex(pattern: '/^[0-9]{3}$/', message: 'CVV must be exactly 3 digits')]
    private ?string $cvv = null;

    #[ORM\OneToOne(inversedBy: 'paymentInformation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): static
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(string $expirationDate): static
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(string $cvv): static
    {
        $this->cvv = $cvv;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getObfuscatedCardNumber(): string
    {
        return str_repeat('*', strlen($this->cardNumber) - 4) . substr($this->cardNumber, -4);
    }
}
