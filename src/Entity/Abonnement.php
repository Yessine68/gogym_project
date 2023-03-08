<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AbonnementRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('Abonnements')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom est un champ obligatoire")]
    #[Assert\Regex(pattern:"/^[a-zA-Z]+$/", message:"Le nom '{{ value }}' ne doit contenir que des lettres")]
    #[Groups('Abonnements')]
    private ?string $nom_a = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le type d'abonnement est un champ obligatoire")]
    #[Groups('Abonnements')]
    private ?string $type_a = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La description est un champ obligatoire")]
    #[Groups('Abonnements')]
    private ?string $description_a = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"Le prix ne peut pas être nulle")]
    #[Assert\Regex(pattern:"/^[0-9]+$/", message:"Le prix '{{ value }}' ne doit contenir que des chiffres")]
    #[Assert\Positive(message:"Le prix doit être positif")]
    #[Groups('Abonnements')]
    private ?int $prix_a = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"La date est un champ obligatoire")]
    #[Assert\Type('\DateTimeInterface', message: "La date '{{ value }}' n'est pas une date valide.")]
    #[Groups('Abonnements')]
    private ?\DateTimeInterface $debut_a = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"La date est un champ obligatoire")]
    #[Assert\Type('\DateTimeInterface', message: "La date '{{ value }}' n'est pas une date valide.")]
    #[Groups('Abonnements')]
    private ?\DateTimeInterface $fin_a = null;

    #[ORM\ManyToMany(targetEntity: Salle::class, inversedBy: 'abonnements')]
    #[Assert\NotBlank(message:"La salle est un champ obligatoire")]
    #[Groups('Abonnements')]
    private Collection $salle_a;

    public function __construct()
    {
        $this->salle_a = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomA(): ?string
    {
        return $this->nom_a;
    }

    public function setNomA(string $nom_a): self
    {
        $this->nom_a = $nom_a;

        return $this;
    }

    public function getTypeA(): ?string
    {
        return $this->type_a;
    }

    public function setTypeA(string $type_a): self
    {
        $this->type_a = $type_a;

        return $this;
    }

    public function getDescriptionA(): ?string
    {
        return $this->description_a;
    }

    public function setDescriptionA(string $description_a): self
    {
        $this->description_a = $description_a;

        return $this;
    }

    public function getPrixA(): ?int
    {
        return $this->prix_a;
    }

    public function setPrixA(int $prix_a): self
    {
        $this->prix_a = $prix_a;

        return $this;
    }

    public function getDebutA(): ?\DateTimeInterface
    {
        return $this->debut_a;
    }

    public function setDebutA(\DateTimeInterface $debut_a): self
    {
        $this->debut_a = $debut_a;

        return $this;
    }

    public function getFinA(): ?\DateTimeInterface
    {
        return $this->fin_a;
    }

    public function setFinA(\DateTimeInterface $fin_a): self
    {
        $this->fin_a = $fin_a;

        return $this;
    }

    /**
     * @return Collection<int, Salle>
     */
    public function getSalleA(): Collection
    {
        return $this->salle_a;
    }

    public function addSalleA(Salle $salleA): self
    {
        if (!$this->salle_a->contains($salleA)) {
            $this->salle_a->add($salleA);
        }

        return $this;
    }

    public function removeSalleA(Salle $salleA): self
    {
        $this->salle_a->removeElement($salleA);

        return $this;
    }

    public function __toString(): string {
        return $this->nom_a;
    }
}
