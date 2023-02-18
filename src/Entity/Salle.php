<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_s = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Il faut saisie un nom")]
    private ?string $nom_s = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Il faut saisie un adresse")]
    private ?string $adresse_s = null;

    #[ORM\Column(length: 255)]
    private ?string $ville_s = null;

    #[ORM\Column]
    #[Assert\Positive(message:"Le perimetre de la salle doit etre positive")]
    private ?float $perimetre_s = null;

    public function getId_S(): ?int
    {
        return $this->id_s;
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
}
