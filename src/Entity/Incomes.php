<?php

namespace App\Entity;

use App\Repository\IncomesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IncomesRepository::class)]
class Incomes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $executedAt = null;

    #[ORM\Column(length: 30)]
    private ?string $motif = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\ManyToOne(inversedBy: 'incomes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Churches $churches = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExecutedAt(): ?\DateTimeImmutable
    {
        return $this->executedAt;
    }

    public function setExecutedAt(\DateTimeImmutable $executedAt): static
    {
        $this->executedAt = $executedAt;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getChurches(): ?Churches
    {
        return $this->churches;
    }

    public function setChurches(?Churches $churches): static
    {
        $this->churches = $churches;

        return $this;
    }
}
