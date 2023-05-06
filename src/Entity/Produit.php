<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("produit")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom ne doit pas être vide.')]
    #[Groups("produit")]
    private ?string $nom_Prod = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'LA Description ne doit pas être vide.')]
    #[Groups("produit")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups("produit")]
    private ?string $image = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le prix ne doit pas être vide.')]
    #[Assert\GreaterThan(value: 0, message: 'Le prix doit être supérieur à 0.')]
    #[Groups("produit")]
    private ?float $prix = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La Quantite ne doit pas être vide.')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'La Quantite doit être supérieur ou égal à 1.')]
    #[Groups("produit")]
    private ?int $nbr_Prods = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categorie $categorie = null;

  
   



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProd(): ?string
    {
        return $this->nom_Prod;
    }

    public function setNomProd(string $nom_Prod): self
    {
        $this->nom_Prod = $nom_Prod;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }


    public function getNbrProds(): ?int
    {
        return $this->nbr_Prods;
    }

    public function setNbrProds(int $nbr_Prods): self
    {
        $this->nbr_Prods = $nbr_Prods;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function __toString()
    {
        return $this->nom_Prod.' '.$this->id;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'categorie' => $this->categorie,
            'nom' => $this->nom_Prod,
            'description' => $this->description,
            'image' => $this->image,
            'prix' => $this->prix,
            'nombre' => $this->nbr_Prods
        );
    }

    public function constructor($categorie, $nom, $description, $image, $prix, $nombre)
    {
        $this->categorie = $categorie;
        $this->nom_Prod = $nom;
        $this->description = $description;
        $this->image = $image;
        $this->prix = $prix;
        $this->nbr_Prods = $nombre;
    }
}
