<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User
{
    public const string SUBSCRIPTION_FREE    = 'free';
    public const string SUBSCRIPTION_PREMIUM = 'premium';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, minMessage: "Phone number must be at least 10 digits")]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [self::SUBSCRIPTION_FREE, self::SUBSCRIPTION_PREMIUM])]
    private ?string $subscriptionType = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'now()'])]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Address $address = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?PaymentInformation $paymentInformation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getSubscriptionType(): ?string
    {
        return $this->subscriptionType;
    }

    public function setSubscriptionType(string $subscriptionType): static
    {
        $this->subscriptionType = $subscriptionType;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        if ($address === null && $this->address !== null) {
            $this->address->setUser(null);
        }
        if ($address !== null && $address->getUser() !== $this) {
            $address->setUser($this);
        }
        $this->address = $address;
        return $this;
    }

    public function getPaymentInformation(): ?PaymentInformation
    {
        return $this->paymentInformation;
    }

    public function setPaymentInformation(?PaymentInformation $paymentInformation): static
    {
        if ($paymentInformation === null && $this->paymentInformation !== null) {
            $this->paymentInformation->setUser(null);
        }
        if ($paymentInformation !== null && $paymentInformation->getUser() !== $this) {
            $paymentInformation->setUser($this);
        }
        $this->paymentInformation = $paymentInformation;
        return $this;
    }

    public function isPremium(): bool
    {
        return $this->subscriptionType === self::SUBSCRIPTION_PREMIUM;
    }
}
