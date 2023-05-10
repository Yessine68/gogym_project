<?php

namespace App\Entity;

use App\Repository\ParticipateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipateRepository::class)]
class Participate implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'participates')]
    private ?User $idUser = null;

    #[ORM\ManyToOne(inversedBy: 'participates')]
    private ?Evenement $idEvent = null;

    #[ORM\Column(nullable: true)]
    private ?int $VerificationCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdEvent(): ?Evenement
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Evenement $idEvent): self
    {
        $this->idEvent = $idEvent;

        return $this;
    }

    public function getVerificationCode(): ?int
    {
        return $this->VerificationCode;
    }

    public function setVerificationCode(?int $VerificationCode): self
    {
        $this->VerificationCode = $VerificationCode;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'user' => $this->idUser,
            'evenement' => $this->idEvent
        );
    }

    public function constructor($user, $evenement)
    {
        $this->idUser = $user;
        $this->idEvent = $evenement;
    }
}