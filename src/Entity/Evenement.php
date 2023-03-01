<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;





#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("event")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("event")]
    #[Assert\NotBlank(message: 'Le nom ne doit pas être vide.')]
    private ?string $nom_e = null;

    #[ORM\Column(length: 255)]
    #[Groups("event")]
    #[Assert\NotBlank(message: 'La Description ne doit pas être vide.')]
    private ?string $description_e = null;

    #[ORM\Column(length: 255)]
    #[Groups("event")]
    #[Assert\Date(message: 'La date  doit être de type DD/MM/YY.')]
    private ?string $date_e = null;

    #[ORM\Column(length: 255)]
    #[Groups("event")]
    #[Assert\NotBlank(message: 'Le lieu ne doit pas être vide.')]
    private ?string $lieu_e = null;

    #[ORM\Column]
    #[Groups("event")]
    #[Assert\NotBlank(message: 'La nombre de participant ne doit pas être vide.')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'La nombre de participant doit être supérieur ou égal à 1.')]
    private ?int $nbr_participants = null;

    #[ORM\Column(length: 1000)]
    #[Groups("event")]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    private ?CategorieEvenement $categorieEvenement = null;

    #[ORM\OneToMany(mappedBy: 'idEvent', targetEntity: Participate::class)]
    private Collection $participates;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Etat = null;

    public function __construct()
    {
        $this->participates = new ArrayCollection();
    }


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

    /**
     * @return Collection<int, Participate>
     */
    public function getParticipates(): Collection
    {
        return $this->participates;
    }

    public function addParticipate(Participate $participate): self
    {
        if (!$this->participates->contains($participate)) {
            $this->participates->add($participate);
            $participate->setIdEvent($this);
        }

        return $this;
    }

    public function removeParticipate(Participate $participate): self
    {
        if ($this->participates->removeElement($participate)) {
            // set the owning side to null (unless already changed)
            if ($participate->getIdEvent() === $this) {
                $participate->setIdEvent(null);
            }
        }

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(?string $Etat): self
    {
        $this->Etat = $Etat;

        return $this;
    }

  
}
