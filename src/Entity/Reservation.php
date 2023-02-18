<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_res = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_res = null;

    #[ORM\Column(length: 255)]
    private ?string $type_res = null;

    public function getId_Res(): ?int
    {
        return $this->id_res;
    }

    public function getDateRes(): ?\DateTimeInterface
    {
        return $this->date_res;
    }

    public function setDateRes(\DateTimeInterface $date_res): self
    {
        $this->date_res = $date_res;

        return $this;
    }

    public function getTypeRes(): ?string
    {
        return $this->type_res;
    }

    public function setTypeRes(string $type_res): self
    {
        $this->type_res = $type_res;

        return $this;
    }
}
