<?php

namespace App\Entity;

use App\Repository\CategorieEvenementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieEvenementRepository::class)]
class CategorieEvenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_cat_e = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_cat_e = null;

    public function getId_Cat_E(): ?int
    {
        return $this->id_cat_e;
    }

    public function getNomCatE(): ?string
    {
        return $this->nom_cat_e;
    }

    public function setNomCatE(string $nom_cat_e): self
    {
        $this->nom_cat_e = $nom_cat_e;

        return $this;
    }
}
