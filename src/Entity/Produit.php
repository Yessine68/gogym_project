<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_p = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_p = null;

    #[ORM\Column]
    private ?float $prix_p = null;

    #[ORM\Column(length: 255)]
    private ?string $qte_stock = null;

    #[ORM\Column(length: 255)]
    private ?string $description_p = null;

    #[ORM\Column(length: 255)]
    private ?string $cat_p = null;

    public function getId_P(): ?int
    {
        return $this->id_p;
    }

    public function getNomP(): ?string
    {
        return $this->nom_p;
    }

    public function setNomP(string $nom_p): self
    {
        $this->nom_p = $nom_p;

        return $this;
    }

    public function getPrixP(): ?float
    {
        return $this->prix_p;
    }

    public function setPrixP(float $prix_p): self
    {
        $this->prix_p = $prix_p;

        return $this;
    }

    public function getQteStock(): ?string
    {
        return $this->qte_stock;
    }

    public function setQteStock(string $qte_stock): self
    {
        $this->qte_stock = $qte_stock;

        return $this;
    }

    public function getDescriptionP(): ?string
    {
        return $this->description_p;
    }

    public function setDescriptionP(string $description_p): self
    {
        $this->description_p = $description_p;

        return $this;
    }

    public function getCatP(): ?string
    {
        return $this->cat_p;
    }

    public function setCatP(string $cat_p): self
    {
        $this->cat_p = $cat_p;

        return $this;
    }
}
