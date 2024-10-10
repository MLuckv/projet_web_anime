<?php

namespace App\Entity;

use App\Repository\RateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RateRepository::class)]
class Rate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'rates')]
    private Collection $user_id;

    /**
     * @var Collection<int, Anime>
     */
    #[ORM\ManyToOne(targetEntity: Anime::class, inversedBy: 'rates')]
    private Collection $anime_id;

    #[ORM\Column]
    private ?int $rating = null;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
        $this->anime_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): static
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id->add($userId);
        }

        return $this;
    }

    public function removeUserId(User $userId): static
    {
        $this->user_id->removeElement($userId);

        return $this;
    }

    /**
     * @return Collection<int, Anime>
     */
    public function getAnimeId(): Collection
    {
        return $this->anime_id;
    }

    public function addAnimeId(Anime $animeId): static
    {
        if (!$this->anime_id->contains($animeId)) {
            $this->anime_id->add($animeId);
        }

        return $this;
    }

    public function removeAnimeId(Anime $animeId): static
    {
        $this->anime_id->removeElement($animeId);

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }
}
