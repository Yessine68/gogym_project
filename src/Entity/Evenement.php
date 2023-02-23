<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom ne doit pas être vide.')]
    private ?string $nom_e = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La Description ne doit pas être vide.')]
    private ?string $description_e = null;

    #[ORM\Column(length: 255)]
    #[Assert\Date(message: 'La date  doit être de type DD/MM/YY.')]
    private ?string $date_e = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le lieu ne doit pas être vide.')]
    private ?string $lieu_e = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La nombre de participant ne doit pas être vide.')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'La nombre de participant doit être supérieur ou égal à 1.')]
    private ?int $nbr_participants = null;

    #[ORM\Column(length: 1000)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    private ?CategorieEvenement $categorieEvenement = null;

    public function getId(): ?int   
    {
        return $this->id;
    }

    public function getNomE(): ?string
    {
        return $this->nom_e;
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

    public function getCategorieEvenement(): ?CategorieEvenement
    {
        return $this->categorieEvenement;
    }

    public function setCategorieEvenement(?CategorieEvenement $categorieEvenement): self
    {
        $this->categorieEvenement = $categorieEvenement;

        return $this;
    }
    public function __toString()
    {
        return $this->nom_e.' '.$this->id;
    }
}
