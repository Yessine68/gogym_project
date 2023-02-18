<?php

namespace App\Entity;

use App\Repository\LigneCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneCommandeRepository::class)]
class LigneCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_ligne = null;

    #[ORM\Column]
    private ?int $qte_dem = null;

    public function getId_Ligne(): ?int
    {
        return $this->id_ligne;
    }

    public function getQteDem(): ?int
    {
        return $this->qte_dem;
    }

    public function setQteDem(int $qte_dem): self
    {
        $this->qte_dem = $qte_dem;

        return $this;
    }
}
