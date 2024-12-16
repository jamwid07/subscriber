<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $addressLine1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $addressLine2 = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $city = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    private ?string $postalCode = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\When(
        expression: "this.getCountry() in ['US', 'CA', 'GB', 'AU', 'SP']",
        constraints: [
            new Assert\NotBlank(message: 'State is required for this country')
        ]
    )]
    private ?string $stateProvince = null;

    #[ORM\Column(length: 2)]
    #[Assert\NotBlank]
    #[Assert\Country]
    private ?string $country = null;

    #[ORM\OneToOne(inversedBy: 'address')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function setAddressLine1(string $addressLine1): static
    {
        $this->addressLine1 = $addressLine1;
        return $this;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function setAddressLine2(?string $addressLine2): static
    {
        $this->addressLine2 = $addressLine2;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getStateProvince(): ?string
    {
        return $this->stateProvince;
    }

    public function setStateProvince(?string $stateProvince): static
    {
        $this->stateProvince = $stateProvince;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;
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
}
