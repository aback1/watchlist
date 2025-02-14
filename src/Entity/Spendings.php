<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SpendingsRepository;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: SpendingsRepository::class)]
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: ['username' => 'exact'])]
#[ORM\Table(name: 'spendings', uniqueConstraints: [
    new ORM\UniqueConstraint(name: "spendings_unique", columns:["month", "username"])
])]




#[UniqueEntity(fields: ["month", "username"], message: "you already have a entry for this month please delete it first to save your entry")]

class Spendings
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $month = "";

    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $username = "";

    #[ORM\Column(length: 10)]
    private int $income = 0;

    #[ORM\Column(length: 10)]
    private int $rentcosts = 0;

    #[ORM\Column(length: 10)]
    private int $sidecosts = 0;

    #[ORM\Column(length: 10)]
    private int $foodanddrinkscosts = 0;

    #[ORM\Column(length: 10)]
    private int $hobbycosts = 0;

    #[ORM\Column(length: 10)]
    private int $savingscosts = 0;

    #[ORM\Column(length: 10)]
    private int $mobilitycosts = 0;

    #[ORM\Column(length: 10)]
    private int $insurancecosts = 0;

    public function getMonth(): string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getIncome(): int
    {
        return $this->income;
    }

    public function setIncome(int $income): static
    {
        $this->income = $income;
        return $this;
    }

    public function getRentcosts(): ?int
    {
        return $this->rentcosts;
    }

    public function setRentcosts(int $rentcosts): static
    {
        $this->rentcosts = $rentcosts;
        return $this;
    }

    public function getSidecosts(): ?int
    {
        return $this->sidecosts;
    }

    public function setSidecosts(int $sidecosts): static
    {
        $this->sidecosts = $sidecosts;
        return $this;
    }

    public function getFoodAndDrinkcosts(): ?int
    {
        return $this->foodanddrinkscosts;
    }

    public function setFoodAndDrinkcosts(int $foodanddrinkscosts): static
    {
        $this->foodanddrinkscosts = $foodanddrinkscosts;
        return $this;
    }

    public function getHobbycosts(): ?int
    {
        return $this->hobbycosts;
    }

    public function setHobbycosts(int $hobbycosts): static
    {
        $this->hobbycosts = $hobbycosts;
        return $this;
    }

    public function getSavingscosts(): ?int
    {
        return $this->savingscosts;
    }

    public function setSavingscosts(int $savingscosts): static
    {
        $this->savingscosts = $savingscosts;
        return $this;
    }

    public function getInsurancecosts(): ?int
    {
        return $this->insurancecosts;
    }

    public function setInsurancecosts(int $insurancecosts): static
    {
        $this->insurancecosts = $insurancecosts;
        return $this;
    }

    public function getMobilitycosts(): ?int
    {
        return $this->mobilitycosts;
    }

    public function setMobilitycosts(int $mobilitycosts): static
    {
        $this->mobilitycosts = $mobilitycosts;
        return $this;
    }

}