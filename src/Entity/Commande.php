<?php


namespace App\Entity;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #Groups ("post:read")
    public ?int $id_com = null;

    #[ORM\Column]
    #Groups ("post:read")
    public ?bool $etat_com = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #Groups ("post:read")
    public ?\DateTimeInterface $date_com = null;

    #[ORM\Column]
    #Groups ("post:read")
    private ?float $prixtotal = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commandes" , cascade={"remove"})
      *@ORM\JoinColumn(name="id", referencedColumnName="id" , onDelete="CASCADE")
     */
    private $id;
    

    /**
     * @ORM\ManyToOne(targetEntity=Livreur::class, inversedBy="commandes", cascade={ "remove"})
     * @ORM\JoinColumn(name="id_livreur", referencedColumnName="id_livreur" , onDelete="CASCADE")
     */
    private $id_livreur;

    /**
     * @ORM\OneToMany(targetEntity=LigneCommande::class, mappedBy="id_commande")
     */
    private $ligneCommandes;

    public function __construct()
    {
        $this->ligneCommandes = new ArrayCollection();
    }

    /**
     * @return mixed
     */

    public function getIdCom(): ?int
    {
        return $this->id_com;
    }

    public function isEtatCom(): ?bool
    {
        return $this->etat_com;
    }

    /**
     * @return mixed
     */
    public function setEtatCom(bool $etat_com): self
    {
        $this->etat_com = $etat_com;

        return $this;
    }

    public function getDateCom(): ?\DateTimeInterface
    {
        return $this->date_com;
    }

    /**
     * @param mixed $date_com
     */
    public function setDateCom(\DateTimeInterface $date_com): self
    {
        $this->date_com = $date_com;

        return $this;
    }

    public function getPrixtotal(): ?float
    {
        return $this->prixtotal;
    }

    public function setPrixtotal(float $prixtotal): self
    {
        $this->prixtotal = $prixtotal;

        return $this;
    }

    public function getId(): ?User
    {
        return $this->id;
    }

    public function setId(?User $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId_Livreur(): ?Livreur
    {
        return $this->id_livreur;
    }

    public function setId_Livreur(?Livreur $id_livreur): self
    {
        $this->id_livreur = $id_livreur;

        return $this;
    }

    /**
     * @return Collection|LigneCommande[]
     */
    public function getLigneCommandes(): Collection
    {
        return $this->ligneCommandes;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): self
    {
        if (!$this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes[] = $ligneCommande;
            $ligneCommande->setIdCommande($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): self
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getICommande() === $this) {
                $ligneCommande->setIdCommande(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return (string) $this->id_com;
    }
}


