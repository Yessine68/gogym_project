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
    private ?int $id_livraison = null;

    #[ORM\Column(length: 255)]
    public ?string $description_livraison = null;

    #[ORM\Column]
    public ?bool $etat_livraison = null;

    #[ORM\Column(length: 255)]
    public ?string $Adresse_livraison = null;

    /**
     * @ORM\ManyToOne(targetEntity=Livreur::class, inversedBy="livraisons")
     * @ORM\JoinColumn(name="id_livreur", referencedColumnName="id_livreur")
     */
    private $id_livreur;


    public function getId_livraison(): ?int
    {
        return $this->id_livraison;
    }

    public function getDescriptionLivraison(): ?string
    {
        return $this->description_livraison;
    }

    public function setDescriptionLivraison(string $description_livraison): self
    {
        $this->description_livraison = $description_livraison;

        return $this;
    }

    public function isEtatLivraison(): ?bool
    {
        return $this->etat_livraison;
    }

    public function setEtatLivraison(bool $etat_livraison): self
    {
        $this->etat_livraison = $etat_livraison;

        return $this;
    }

    public function getIdLivreur(): ?Livreur
    {
        return $this->id_livreur;
    }

    public function setIdLivreur(?Livreur $id_livreur): self
    {
        $this->id_livreur = $id_livreur;

        return $this;
    }


    public function getAdresseLivraison(): ?string
    {
        return $this->Adresse_livraison;
    }

    public function setAdresseLivraison(string $Adresse_livraison): self
    {
        $this->Adresse_livraison = $Adresse_livraison;

        return $this;
    }

    
}
