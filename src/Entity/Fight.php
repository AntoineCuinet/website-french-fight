<?php

namespace App\Entity;

use App\Repository\FightRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FightRepository::class)]
class Fight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fighterA = null;

    #[ORM\Column(length: 255)]
    private ?string $fighterB = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null; // MMA, Boxe, JJB, etc.

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $result = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(nullable: true)]
    private ?int $fighterAAge = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $fighterAHeight = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $fighterAWeight = null;

    #[ORM\Column(nullable: true)]
    private ?int $fighterBAge = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $fighterBHeight = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $fighterBWeight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $broadcaster = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $weightClass = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isTitleFight = null;

    #[ORM\Column(nullable: true)]
    private ?int $rounds = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eventName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFighterA(): ?string
    {
        return $this->fighterA;
    }

    public function setFighterA(string $fighterA): static
    {
        $this->fighterA = $fighterA;

        return $this;
    }

    public function getFighterB(): ?string
    {
        return $this->fighterB;
    }

    public function setFighterB(string $fighterB): static
    {
        $this->fighterB = $fighterB;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): static
    {
        $this->result = $result;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getFighterAAge(): ?int
    {
        return $this->fighterAAge;
    }

    public function setFighterAAge(?int $fighterAAge): static
    {
        $this->fighterAAge = $fighterAAge;

        return $this;
    }

    public function getFighterAHeight(): ?string
    {
        return $this->fighterAHeight;
    }

    public function setFighterAHeight(?string $fighterAHeight): static
    {
        $this->fighterAHeight = $fighterAHeight;

        return $this;
    }

    public function getFighterAWeight(): ?string
    {
        return $this->fighterAWeight;
    }

    public function setFighterAWeight(?string $fighterAWeight): static
    {
        $this->fighterAWeight = $fighterAWeight;

        return $this;
    }

    public function getFighterBAge(): ?int
    {
        return $this->fighterBAge;
    }

    public function setFighterBAge(?int $fighterBAge): static
    {
        $this->fighterBAge = $fighterBAge;

        return $this;
    }

    public function getFighterBHeight(): ?string
    {
        return $this->fighterBHeight;
    }

    public function setFighterBHeight(?string $fighterBHeight): static
    {
        $this->fighterBHeight = $fighterBHeight;

        return $this;
    }

    public function getFighterBWeight(): ?string
    {
        return $this->fighterBWeight;
    }

    public function setFighterBWeight(?string $fighterBWeight): static
    {
        $this->fighterBWeight = $fighterBWeight;

        return $this;
    }

    public function getBroadcaster(): ?string
    {
        return $this->broadcaster;
    }

    public function setBroadcaster(?string $broadcaster): static
    {
        $this->broadcaster = $broadcaster;

        return $this;
    }

    public function getWeightClass(): ?string
    {
        return $this->weightClass;
    }

    public function setWeightClass(?string $weightClass): static
    {
        $this->weightClass = $weightClass;

        return $this;
    }

    public function isTitleFight(): ?bool
    {
        return $this->isTitleFight;
    }

    public function setIsTitleFight(?bool $isTitleFight): static
    {
        $this->isTitleFight = $isTitleFight;

        return $this;
    }

    public function getRounds(): ?int
    {
        return $this->rounds;
    }

    public function setRounds(?int $rounds): static
    {
        $this->rounds = $rounds;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(?string $eventName): static
    {
        $this->eventName = $eventName;

        return $this;
    }
}