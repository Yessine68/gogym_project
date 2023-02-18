<?php

namespace App\Entity;

use App\Repository\CategorieProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieProduitRepository::class)]
class CategorieProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_cat_p = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_cat_p = null;

    public function getId_Cat_P(): ?int
    {
        return $this->id_cat_p;
    }

    public function getNomCatP(): ?string
    {
        return $this->nom_cat_p;
    }

    public function setNomCatP(string $nom_cat_p): self
    {
        $this->nom_cat_p = $nom_cat_p;

        return $this;
    }
}
