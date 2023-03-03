<?php

namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_a = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Il faut saisie un libelle")]
    private ?string $libelle_a = null;

    #[ORM\Column(length: 255)]
    private ?string $type_a = null;

    #[ORM\Column(length: 255)]
    private ?string $description_a = null;

    #[ORM\Column]
    private ?int $prix_a = null;

    #[ORM\Column]
    private ?int $duree_a = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $debut_a = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fin_a = null;

    public function getId_A(): ?int
    {
        return $this->id_a ;
    }

    public function getLibelleA(): ?string
    {
        return $this->libelle_a;
    }

    public function setLibelleA(string $libelle_a): self
    {
        $this->libelle_a = $libelle_a;

        return $this;
    }

    public function getTypeA(): ?string
    {
        return $this->type_a;
    }

    public function setTypeA(string $type_a): self
    {
        $this->type_a = $type_a;

        return $this;
    }

    public function getDescriptionA(): ?string
    {
        return $this->description_a;
    }

    public function setDescriptionA(string $description_a): self
    {
        $this->description_a = $description_a;

        return $this;
    }

    public function getPrixA(): ?int
    {
        return $this->prix_a;
    }

    public function setPrixA(int $prix_a): self
    {
        $this->prix_a = $prix_a;

        return $this;
    }

    public function getDureeA(): ?int
    {
        return $this->duree_a;
    }

    public function setDureeA(int $duree_a): self
    {
        $this->duree_a = $duree_a;

        return $this;
    }

    public function getDebutA(): ?\DateTimeInterface
    {
        return $this->debut_a;
    }

    public function setDebutA(\DateTimeInterface $debut_a): self
    {
        $this->debut_a = $debut_a;

        return $this;
    }

    public function getFinA(): ?\DateTimeInterface
    {
        return $this->fin_a;
    }

    public function setFinA(\DateTimeInterface $fin_a): self
    {
        $this->fin_a = $fin_a;

        return $this;
    }
}
