<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom est un champ obligatoire")]
    #[Assert\Regex(pattern:"/^[a-zA-Z]+$/", message:"Le nom '{{ value }}' ne doit contenir que des lettres")]
    private ?string $Nom = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"La duree ne peut pas être nulle")]
    #[Assert\Regex(pattern:"/^[0-9]+$/", message:"La duree '{{ value }}' ne doit contenir que des chiffres")]
    #[Assert\Positive(message:"La duree doit être positif")]
    private ?int $duree = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"L'intensite est un champ obligatoire")]
    #[Assert\Regex(pattern:"/^[a-zA-Z]+$/", message:"L'intensite' '{{ value }}' ne doit contenir que des lettres")]
    private ?string $intensite = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le bienfaits est un champ obligatoire")]
    private ?string $bienfaits = null;

    #[ORM\OneToMany(mappedBy: 'cours', targetEntity: Reservation::class)]
    // #[Assert\NotBlank(message:"La reservation est un champ obligatoire")]
    private Collection $reservation_c;

    public function __construct()
    {
        $this->reservation_c = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getIntensite(): ?string
    {
        return $this->intensite;
    }

    public function setIntensite(string $intensite): self
    {
        $this->intensite = $intensite;

        return $this;
    }

    public function getBienfaits(): ?string
    {
        return $this->bienfaits;
    }

    public function setBienfaits(string $bienfaits): self
    {
        $this->bienfaits = $bienfaits;

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getReservationC(): Collection
    {
        return $this->reservation_c;
    }

    public function addReservationC(reservation $reservationC): self
    {
        if (!$this->reservation_c->contains($reservationC)) {
            $this->reservation_c->add($reservationC);
            $reservationC->setCours($this);
        }

        return $this;
    }

    public function removeReservationC(reservation $reservationC): self
    {
        if ($this->reservation_c->removeElement($reservationC)) {
            // set the owning side to null (unless already changed)
            if ($reservationC->getCours() === $this) {
                $reservationC->setCours(null);
            }
        }

        return $this;
    }

    public function __toString(): string {
        return $this->Nom;
    }
}
