<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $Username = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $Gender = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $Birthday = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $Location = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $Joined = null;

    #[ORM\Column(nullable: true)]
    private ?int $Days_Watched = null;

    #[ORM\Column(nullable: true)]
    private ?int $MeanScore = null;

    #[ORM\Column(nullable: true)]
    private ?int $Watching = null;

    #[ORM\Column(nullable: true)]
    private ?int $Completed = null;

    #[ORM\Column(nullable: true)]
    private ?int $OnHold = null;

    #[ORM\Column(nullable: true)]
    private ?int $Dropped = null;

    #[ORM\Column(nullable: true)]
    private ?int $PlanToWatch = null;

    #[ORM\Column(nullable: true)]
    private ?int $TotalEntries = null;

    #[ORM\Column(nullable: true)]
    private ?int $Rewatched = null;

    #[ORM\Column(nullable: true)]
    private ?int $Episodes = null;

    /**
     * @var Collection<int, Rate>
     */
    #[ORM\ManyToMany(targetEntity: Rate::class, mappedBy: 'user_id')]
    private Collection $rates;

    public function __construct()
    {
        $this->rates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(?string $Username): static
    {
        $this->Username = $Username;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->Gender;
    }

    public function setGender(?string $Gender): static
    {
        $this->Gender = $Gender;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->Birthday;
    }

    public function setBirthday(?string $Birthday): static
    {
        $this->Birthday = $Birthday;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->Location;
    }

    public function setLocation(?string $Location): static
    {
        $this->Location = $Location;

        return $this;
    }

    public function getJoined(): ?string
    {
        return $this->Joined;
    }

    public function setJoined(?string $Joined): static
    {
        $this->Joined = $Joined;

        return $this;
    }

    public function getDaysWatched(): ?int
    {
        return $this->Days_Watched;
    }

    public function setDaysWatched(?int $Days_Watched): static
    {
        $this->Days_Watched = $Days_Watched;

        return $this;
    }

    public function getMeanScore(): ?int
    {
        return $this->MeanScore;
    }

    public function setMeanScore(?int $Mean_Score): static
    {
        $this->MeanScore = $Mean_Score;

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

    public function setOnHold(?int $On_hold): static
    {
        $this->OnHold = $On_hold;

        return $this;
    }

    public function getDropped(): ?int
    {
        return $this->Dropped;
    }

    public function setDropped(?int $Dropped): static
    {
        $this->Dropped = $Dropped;

        return $this;
    }

    public function getPlanToWatch(): ?int
    {
        return $this->PlanToWatch;
    }

    public function setPlanToWatch(?int $Plan_to_watch): static
    {
        $this->PlanToWatch = $Plan_to_watch;

        return $this;
    }

    public function getTotalEntries(): ?int
    {
        return $this->TotalEntries;
    }

    public function setTotalEntries(?int $Total_entries): static
    {
        $this->TotalEntries = $Total_entries;

        return $this;
    }

    public function getRewatched(): ?int
    {
        return $this->Rewatched;
    }

    public function setRewatched(?int $Rewatched): static
    {
        $this->Rewatched = $Rewatched;

        return $this;
    }

    public function getEpisodes(): ?int
    {
        return $this->Episodes;
    }

    public function setEpisodes(?int $Episodes): static
    {
        $this->Episodes = $Episodes;

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
            $rate->addUserId($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): static
    {
        if ($this->rates->removeElement($rate)) {
            $rate->removeUserId($this);
        }

        return $this;
    }
}
