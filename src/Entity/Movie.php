<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MovieRepository;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: ["Title" => 'exact'])]
#[ORM\Table(name: 'movies', uniqueConstraints: [
    new ORM\UniqueConstraint(name: "title_unique", columns: ["Title"])
])]


#[UniqueEntity(fields: ["Title", "imdbID"], message: "You already have the selected item in your watchlist!")]

class Movie
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $imdbID = "";

    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $username = "";
    
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $Title = "";
    
    #[ORM\Column(type: 'integer', length: 4)]
    private int $Year = 0;
    
    #[ORM\Column(type: 'string')]
    private string $Poster = "";
    
    #[ORM\Column(type: 'string', length: 7)]
    private string $Type = "";

    #[ORM\Column(type: 'float' , nullable: true)]
    #[Assert\Range(min: 0, max: 5, notInRangeMessage: 'Rating must be between {{ min }} and {{ max }}.')]
    private ?float $Rating = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $Watched = false;
    
    
    public function getImdbID(): string
    {
         return $this->imdbID;
    }

    public function setImdbID(string $imdbID): self
    {
        $this->imdbID = $imdbID;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getYear(): int
    {
        return $this->Year;
    }

    public function setYear(int $Year): self
    {
        $this->Year = $Year;
        return $this;
    }

    public function getPoster(): string
    {
        return $this->Poster;

    }

    public function setPoster(string $Poster): self
    {
        $this->Poster = $Poster;
        return $this;
    }

    public function getType(): string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;
        return $this;
    }

    public function getRating(): float
    {
        return $this->Rating;
    }

    public function setRating(float $Rating): self
    {
        $this->Rating = $Rating;
        return $this;
    }

    public function getWatched(): bool
    {
        return $this->Watched;
    }

    public function setWatched(bool $Watched): self
    {
        $this->Watched = $Watched;
        return $this;
    }






    
}