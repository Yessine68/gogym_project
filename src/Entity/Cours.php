<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_cours = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_cours = null;

    #[ORM\Column(length: 255)]
    private ?string $description_cours = null;

    #[ORM\Column]
    private ?int $duree_cours = null;

    public function getId_Cours(): ?int
    {
        return $this->id_cours;
    }

    public function getNomCours(): ?string
    {
        return $this->nom_cours;
    }

    public function setNomCours(string $nom_cours): self
    {
        $this->nom_cours = $nom_cours;

        return $this;
    }

    public function getDescriptionCours(): ?string
    {
        return $this->description_cours;
    }

    public function setDescriptionCours(string $description_cours): self
    {
        $this->description_cours = $description_cours;

        return $this;
    }

    public function getDureeCours(): ?int
    {
        return $this->duree_cours;
    }

    public function setDureeCours(int $duree_cours): self
    {
        $this->duree_cours = $duree_cours;

        return $this;
    }
}
