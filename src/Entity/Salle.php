<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom de la salle est obligatoire")]
    #[Assert\Regex(pattern:"/^[a-zA-Z]+$/", message:"Le nom de la salle '{{ value }}' ne doit contenir que des lettres")]
    private ?string $nom_s = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"Le numéro de téléphone ne peut pas être nulle")]
    #[Assert\Length(min:8, minMessage:"Le numéro de téléphone doit contenir '{{ limit }}' numéro", max:8, maxMessage:"Le numéro de téléphone doit contenir '{{ limit }}' numéro")]
    #[Assert\Regex(pattern:"/^[0-9]+$/", message:"Le numéro de téléphone '{{ value }}' ne doit contenir que des chiffres")]
    private ?int $tel_s = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"L'adresse mail est obligatoire")]
    #[Assert\Email(message:"L'adresse mail '{{ value }}' n'est pas un email valide ")]
    private ?string $email_s = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"L'adresse est obligatoire")]
    private ?string $adresse_s = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La ville est obligatoire")]
    #[Assert\Regex(pattern:"/^[a-zA-Z]+$/", message:"La ville '{{ value }}' ne doit contenir que des lettres")]
    private ?string $ville_s = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"Le perimetre de la salle ne peut pas être nulle")]
    #[Assert\Positive(message:"Le perimetre de la salle doit être positif")]
    #[Assert\Regex(pattern:"/^[0-9]+$/", message:"Le perimetre de la salle '{{ value }}' ne doit contenir que des chiffres")]
    private ?float $perimetre_s = null;

    #[ORM\ManyToMany(targetEntity: Abonnement::class, mappedBy: 'salle_a')]
    #[Assert\NotBlank(message:"L'abonnement est obligatoire")]
    private Collection $abonnements;

    public function __construct()
    {
        $this->abonnements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomS(): ?string
    {
        return $this->nom_s;
    }

    public function setNomS(string $nom_s): self
    {
        $this->nom_s = $nom_s;

        return $this;
    }

    public function getTelS(): ?int
    {
        return $this->tel_s;
    }

    public function setTelS(int $tel_s): self
    {
        $this->tel_s = $tel_s;

        return $this;
    }

    public function getEmailS(): ?string
    {
        return $this->email_s;
    }

    public function setEmailS(string $email_s): self
    {
        $this->email_s = $email_s;

        return $this;
    }

    public function getAdresseS(): ?string
    {
        return $this->adresse_s;
    }

    public function setAdresseS(string $adresse_s): self
    {
        $this->adresse_s = $adresse_s;

        return $this;
    }

    public function getVilleS(): ?string
    {
        return $this->ville_s;
    }

    public function setVilleS(string $ville_s): self
    {
        $this->ville_s = $ville_s;

        return $this;
    }

    public function getPerimetreS(): ?float
    {
        return $this->perimetre_s;
    }

    public function setPerimetreS(float $perimetre_s): self
    {
        $this->perimetre_s = $perimetre_s;

        return $this;
    }

    /**
     * @return Collection<int, Abonnement>
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(Abonnement $abonnement): self
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements->add($abonnement);
            $abonnement->addSalleA($this);
        }

        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->abonnements->removeElement($abonnement)) {
            $abonnement->removeSalleA($this);
        }

        return $this;
    }

    public function __toString(): string {
        return $this->nom_s;
    }
    
}
