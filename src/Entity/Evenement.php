<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_e = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_e = null;

    #[ORM\Column(length: 255)]
    private ?string $description_e = null;

    #[ORM\Column(length: 255)]
    private ?string $cat_e = null;

    #[ORM\Column(length: 255)]
    private ?string $date_e = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu_e = null;

    #[ORM\Column]
    private ?int $nbr_participants = null;

    public function getId_E(): ?int
    {
        return $this->id_e;
    }

    public function getNomE(): ?string
    {
        return $this->nom_e;
    }

    public function setNomE(string $nom_e): self
    {
        $this->nom_e = $nom_e;

        return $this;
    }

    public function getDescriptionE(): ?string
    {
        return $this->description_e;
    }

    public function setDescriptionE(string $description_e): self
    {
        $this->description_e = $description_e;

        return $this;
    }

    public function getCatE(): ?string
    {
        return $this->cat_e;
    }

    public function setCatE(string $cat_e): self
    {
        $this->cat_e = $cat_e;

        return $this;
    }

    public function getDateE(): ?string
    {
        return $this->date_e;
    }

    public function setDateE(string $date_e): self
    {
        $this->date_e = $date_e;

        return $this;
    }

    public function getLieuE(): ?string
    {
        return $this->lieu_e;
    }

    public function setLieuE(string $lieu_e): self
    {
        $this->lieu_e = $lieu_e;

        return $this;
    }

    public function getNbrParticipants(): ?int
    {
        return $this->nbr_participants;
    }

    public function setNbrParticipants(int $nbr_participants): self
    {
        $this->nbr_participants = $nbr_participants;

        return $this;
    }
}
