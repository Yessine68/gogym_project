<?php

namespace App\Entity;

use App\Repository\CategorieEvenementRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: CategorieEvenementRepository::class)]
class CategorieEvenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de Categorie ne doit pas être vide.')]
    private ?string $nom_cat_e = null;

    #[ORM\OneToMany(mappedBy: 'categorieEvenement', targetEntity: Evenement::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCatE(): ?string 
    {
        return $this->nom_cat_e;
    }

    public function setNomCatE(string $nom_cat_e): self
    {
           $this->nom_cat_e = $nom_cat_e;    
           return $this ;
    }
    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->setCategorieEvenement($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getCategorieEvenement() === $this) {
                $evenement->setCategorieEvenement(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom_cat_e.' '.$this->id;
    }
}
