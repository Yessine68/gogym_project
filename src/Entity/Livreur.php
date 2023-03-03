<?php

namespace App\Entity;
use App\Repository\LivreurRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LivreurRepository::class)]
class Livreur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $id_livreur = null;

    #[ORM\Column(length: 30)]
    #   /**
    #* @Assert\Length(min=3,max=50)
    #* @ORM\Column(type="string", length=100)
    #*/
    /**
     * @var string
     *
     * @ORM\Column(name="nom_liv", type="string", length=30, nullable=false)
     * @Assert\Length(
     *      min = 3,
     *      max = 8,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\NotBlank
     *
     */
    public ?string $nom_liv = null;

    #[ORM\Column(length: 30)]
      #   /**
    #* @Assert\Length(min=3,max=50)
    #* @ORM\Column(type="string", length=100)
    #*/
    /**
     * @var string
     *
     * @ORM\Column(name="nom_liv", type="string", length=30, nullable=false)
     * @Assert\Length(
     *      min = 3,
     *      max = 8,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\NotBlank
     *
     */
    public ?string $prenom_liv = null;

    #[ORM\Column]
    /**
     * @var integer
     *
     * @ORM\Column(name="num_tel_liv",type="integer",length=30,nullable=false)
     * @Assert\Regex(
     * pattern ="/^[0-9]{8}$/",
     * message ="Le numero de telephone doit etre compose de 8 chiffres"
     * )
     *
     * @Assert\NotBlank
     */
    public ?int $num_tel_liv = null;

    #[ORM\Column]
    private ?bool $disponibilite_liv = null;

    #[ORM\Column(length: 50)]
    private ?string $Region = null;

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="id_livreur")
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity=Livraison::class, mappedBy="id_livreur")
     */
    private $livraisons;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->livraisons = new ArrayCollection();
    }

    public function getId_Livreur(): ?int
    {
        return $this->id_livreur;
    }

    public function getNomLiv(): ?string
    {
        return $this->nom_liv;
    }

    public function setNomLiv(string $nom_liv): self
    {
        $this->nom_liv = $nom_liv;

        return $this;
    }

    public function getPrenomLiv(): ?string
    {
        return $this->prenom_liv;
    }

    public function setPrenomLiv(string $prenom_liv): self
    {
        $this->prenom_liv = $prenom_liv;

        return $this;
    }

    public function getNumTelLiv(): ?int
    {
        return $this->num_tel_liv;
    }

    public function setNumTelLiv(int $num_tel_liv): self
    {
        $this->num_tel_liv = $num_tel_liv;

        return $this;
    }

    public function isDisponibiliteLiv(): ?bool
    {
        return $this->disponibilite_liv;
    }

    public function setDisponibiliteLiv(bool $disponibilite_liv): self
    {
        $this->disponibilite_liv = $disponibilite_liv;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->Region;
    }

    public function setRegion(string $Region): self
    {
        $this->Region = $Region;

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setIdLivreur($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getId_Livreur() === $this) {
                $commande->setId_Livreur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Livraison[]
     */
    public function getLivraisons(): Collection
    {
        return $this->livraisons;
    }

    public function addLivraison(Livraison $livraison): self
    {
        if (!$this->livraisons->contains($livraison)) {
            $this->livraisons[] = $livraison;
            $livraison->setIdLivreur($this);
        }

        return $this;
    }

    public function removeLivraison(Livraison $livraison): self
    {
        if ($this->livraisons->removeElement($livraison)) {
            // set the owning side to null (unless already changed)
            if ($livraison->getIdLivreur() === $this) {
                $livraison->setIdLivreur(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->id_livreur;
    }



}
