<?php

namespace App\Entity;

use App\Repository\AbonnementsalleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AbonnementsalleRepository::class)]
class Abonnementsalle implements \JsonSerializable
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: "idSalle")]
    private ?int $idSalle = null;

    #[ORM\Column(name: "idAbonnement")]
    private ?int $idAbonnement = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getIdSalle(): ?int
    {
        return $this->idSalle;
    }

    /**
     * @param int|null $idSalle
     */
    public function setIdSalle(?int $idSalle): void
    {
        $this->idSalle = $idSalle;
    }

    /**
     * @return int|null
     */
    public function getIdAbonnement(): ?int
    {
        return $this->idAbonnement;
    }

    /**
     * @param int|null $idAbonnement
     */
    public function setIdAbonnement(?int $idAbonnement): void
    {
        $this->idAbonnement = $idAbonnement;
    }



    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'salle' => $this->idSalle,
            'abonnement' => $this->idAbonnement,
        );
    }
}
