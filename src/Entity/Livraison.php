<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_liv = null;

    #[ORM\Column(length: 255)]
    private ?string $description_liv = null;

    #[ORM\Column]
    private ?bool $etat_liv = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_liv = null;

    public function getId_Liv(): ?int
    {
        return $this->id_liv;
    }

    public function getDescriptionLiv(): ?string
    {
        return $this->description_liv;
    }

    public function setDescriptionLiv(string $description_liv): self
    {
        $this->description_liv = $description_liv;

        return $this;
    }

    public function isEtatLiv(): ?bool
    {
        return $this->etat_liv;
    }

    public function setEtatLiv(bool $etat_liv): self
    {
        $this->etat_liv = $etat_liv;

        return $this;
    }

    public function getAdresseLiv(): ?string
    {
        return $this->adresse_liv;
    }

    public function setAdresseLiv(string $adresse_liv): self
    {
        $this->adresse_liv = $adresse_liv;

        return $this;
    }
}
