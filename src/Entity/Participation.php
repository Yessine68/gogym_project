<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_part = null;

    #[ORM\Column]
    private ?int $verif_code = null;

    public function getId_Part(): ?int
    {
        return $this->id_part;
    }

    public function getVerifCode(): ?int
    {
        return $this->verif_code;
    }

    public function setVerifCode(int $verif_code): self
    {
        $this->verif_code = $verif_code;

        return $this;
    }
}
