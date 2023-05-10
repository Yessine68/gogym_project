<?php

namespace App\Entity;
use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #Groups ("post:read")
    public ?int $id_l_p = null;

    #[ORM\Column]
    #Groups ("post:read")
    private ?int $quantite = null;

    #[ORM\Column]
    #Groups ("post:read")
    private ?float $total = null;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="paniers")
     * @ORM\JoinColumn(name="id", referencedColumnName="id" )
     * @Groups ("post:read")
     */
    private $produit;

    /**
     * @return mixed
     */

    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function __construct()
    {
        $this->produit = new ArrayCollection();
    }
    public function getId_L_P(): ?int
    {
        return $this->id_l_p;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @param mixed $produit
     */
    public function setProduit($produit): void
    {
        $this->produit = $produit;
    }



    
}
