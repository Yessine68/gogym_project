<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_com = null;

    #[ORM\Column]
    private ?bool $etat_com = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_com = null;

    #[ORM\Column]
    private ?float $prixtotal = null;

    public function getId_Com(): ?int
    {
        return $this->id_com;
    }

    public function isEtatCom(): ?bool
    {
        return $this->etat_com;
    }

    public function setEtatCom(bool $etat_com): self
    {
        $this->etat_com = $etat_com;

        return $this;
    }

    public function getDateCom(): ?\DateTimeInterface
    {
        return $this->date_com;
    }

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
}
