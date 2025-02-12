<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MovieRepository;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: ["title" => 'exact'])]
#[ORM\Table(name: 'movies', uniqueConstraints: [
    new ORM\UniqueConstraint(name: "title_unique", columns: ["title"])
])]


#[UniqueEntity(fields: ["title", "imdbID"], message: "You already have the selected item in your watchlist!")]

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

    #[ORM\Column(type: 'float', length: 5, nullable: true)]
    private float $rating = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $watched = false;
    
    
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






    
}