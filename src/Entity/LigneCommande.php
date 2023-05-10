<?php

namespace App\Entity;

use App\Repository\LigneCommandeRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneCommandeRepository::class)]
class LigneCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #Groups ("post:read")
    private ?int $id_l_p = null;

    #[ORM\Column]
    #Groups ("post:read")
    public ?int $qte_dem = null;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="ligneCommandes" ,cascade={"remove"})
     *  @ORM\JoinColumn(name="id_commande", referencedColumnName="id_com" ,onDelete="CASCADE")
     * @Groups ("post:read")
     */
    private $id_commande;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="ligneCommandes" ,cascade={ "remove"})
     *  @ORM\JoinColumn(name="id", referencedColumnName="id" ,onDelete="CASCADE")
     * @Groups ("post:read")
     */
    private $id;

    public function getId_L_P(): ?int
    {
        return $this->id_l_p;
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

    public function getIdCommande(): ?Commande
    {
        return $this->id_commande;
    }

    public function setIdCommande(?Commande $id_commande): self
    {
        $this->id_commande = $id_commande;

        return $this;
    }

    public function getId(): ?Produit
    {
        return $this->id;
    }

    public function setId(?Produit $id): self
    {
        $this->id = $id;

        return $this;
    }
}
