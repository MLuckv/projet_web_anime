<?php

namespace App\Entity;

use App\Repository\AnimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimeRepository::class)]
class Anime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $Nom = null;

    #[ORM\Column(nullable: true)]
    private ?float $Score = null;

    #[ORM\Column(length: 125, nullable: true)]
    private ?string $Genre = null;

    #[ORM\Column(length: 114, nullable: true)]
    private ?string $EnglishName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Synopsis = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $Type = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $Episode = null;

    #[ORM\Column(length: 28, nullable: true)]
    private ?string $Aired = null;

    #[ORM\Column(length: 11, nullable: true)]
    private ?string $Premiered = null;

    #[ORM\Column(length: 375, nullable: true)]
    private ?string $Producers = null;

    #[ORM\Column(length: 69, nullable: true)]
    private ?string $Licensors = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Studios = null;

    #[ORM\Column(length: 13, nullable: true)]
    private ?string $Source = null;

    #[ORM\Column(length: 21, nullable: true)]
    private ?string $Duration = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $Rating = null;

    #[ORM\Column(nullable: true)]
    private ?int $Ranked = null;

    #[ORM\Column(nullable: true)]
    private ?int $Popularity = null;

    #[ORM\Column(nullable: true)]
    private ?int $Members = null;

    #[ORM\Column(nullable: true)]
    private ?int $Favorites = null;

    #[ORM\Column(nullable: true)]
    private ?int $Watching = null;

    #[ORM\Column(nullable: true)]
    private ?int $Completed = null;

    #[ORM\Column(nullable: true)]
    private ?int $OnHold = null;

    #[ORM\Column(nullable: true)]
    private ?int $Droped = null;

    /**
     * @var Collection<int, Rate>
     */
    #[ORM\OneToMany(targetEntity: Rate::class, mappedBy: 'anime')]
    private Collection $rates;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ImageUrl = null;

    public function __construct()
    {
        $this->rates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(?string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->Score;
    }

    public function setScore(?float $Score): static
    {
        $this->Score = $Score;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->Genre;
    }

    public function setGenre(?string $Genre): static
    {
        $this->Genre = $Genre;

        return $this;
    }

    public function getEnglishName(): ?string
    {
        return $this->EnglishName;
    }

    public function setEnglishName(?string $English_name): static
    {
        $this->EnglishName = $English_name;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->Synopsis;
    }

    public function setSynopsis(?string $Synopsis): static
    {
        $this->Synopsis = $Synopsis;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(?string $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getEpisode(): ?int
    {
        return $this->Episode;
    }

    public function setEpisode(?int $Episode): static
    {
        $this->Episode = $Episode;

        return $this;
    }

    public function getAired(): ?string
    {
        return $this->Aired;
    }

    public function setAired(?string $Aired): static
    {
        $this->Aired = $Aired;

        return $this;
    }

    public function getPremiered(): ?string
    {
        return $this->Premiered;
    }

    public function setPremiered(?string $Premiered): static
    {
        $this->Premiered = $Premiered;

        return $this;
    }

    public function getProducers(): ?string
    {
        return $this->Producers;
    }

    public function setProducers(?string $Producers): static
    {
        $this->Producers = $Producers;

        return $this;
    }

    public function getLicensors(): ?string
    {
        return $this->Licensors;
    }

    public function setLicensors(string $Licensors): static
    {
        $this->Licensors = $Licensors;

        return $this;
    }

    public function getStudios(): ?string
    {
        return $this->Studios;
    }

    public function setStudios(?string $Studios): static
    {
        $this->Studios = $Studios;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->Source;
    }

    public function setSource(?string $Source): static
    {
        $this->Source = $Source;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->Duration;
    }

    public function setDuration(?string $Duration): static
    {
        $this->Duration = $Duration;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->Rating;
    }

    public function setRating(?string $Rating): static
    {
        $this->Rating = $Rating;

        return $this;
    }

    public function getRanked(): ?int
    {
        return $this->Ranked;
    }

    public function setRanked(?int $Ranked): static
    {
        $this->Ranked = $Ranked;

        return $this;
    }

    public function getPopularity(): ?int
    {
        return $this->Popularity;
    }

    public function setPopularity(?int $Popularity): static
    {
        $this->Popularity = $Popularity;

        return $this;
    }

    public function getMembers(): ?int
    {
        return $this->Members;
    }

    public function setMembers(?int $Members): static
    {
        $this->Members = $Members;

        return $this;
    }

    public function getFavorites(): ?int
    {
        return $this->Favorites;
    }

    public function setFavorites(?int $Favorites): static
    {
        $this->Favorites = $Favorites;

        return $this;
    }

    public function getWatching(): ?int
    {
        return $this->Watching;
    }

    public function setWatching(?int $Watching): static
    {
        $this->Watching = $Watching;

        return $this;
    }

    public function getCompleted(): ?int
    {
        return $this->Completed;
    }

    public function setCompleted(?int $Completed): static
    {
        $this->Completed = $Completed;

        return $this;
    }

    public function getOnHold(): ?int
    {
        return $this->OnHold;
    }

    public function setOnHold(?int $On_Hold): static
    {
        $this->OnHold = $On_Hold;

        return $this;
    }

    public function getDroped(): ?int
    {
        return $this->Droped;
    }

    public function setDroped(?int $Droped): static
    {
        $this->Droped = $Droped;

        return $this;
    }

    /**
     * @return Collection<int, Rate>
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): static
    {
        if (!$this->rates->contains($rate)) {
            $this->rates->add($rate);
            $rate->addAnimeId($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): static
    {
        if ($this->rates->removeElement($rate)) {
            $rate->removeAnimeId($this);
        }

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->ImageUrl;
    }

    public function setImageUrl(?string $ImageUrl): static
    {
        $this->ImageUrl = $ImageUrl;

        return $this;
    }
}
