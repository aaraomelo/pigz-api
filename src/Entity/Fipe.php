<?php

namespace App\Entity;

use App\Repository\FipeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FipeRepository::class)]
class Fipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Fipe Code is required')]
    private ?string $fipe_code = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Name is required')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Brand is required')]
    private ?string $brand = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Year is required')]
    private ?int $year = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Fuel is required')]
    private ?string $fuel = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Price is required')]
    private ?int $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFipeCode(): ?string
    {
        return $this->fipe_code;
    }

    public function setFipeCode(string $fipe_code): self
    {
        $this->fipe_code = $fipe_code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getFuel(): ?string
    {
        return $this->fuel;
    }

    public function setFuel(string $fuel): self
    {
        $this->fuel = $fuel;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
