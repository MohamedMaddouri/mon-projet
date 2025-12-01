<?php

namespace App\Entity;

use App\Repository\StatistiqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatistiqueRepository::class)]
class Statistique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $periode = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $chiffreAffaires = null;

    #[ORM\Column]
    private ?int $nombreCommandes = null;

    #[ORM\Column]
    private ?int $nombreReservations = null;

    #[ORM\Column]
    private ?int $nombreClients = null;

    #[ORM\Column]
    private ?int $nombreProduits = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeriode(): ?\DateTimeInterface
    {
        return $this->periode;
    }

    public function setPeriode(\DateTimeInterface $periode): static
    {
        $this->periode = $periode;

        return $this;
    }

    public function getChiffreAffaires(): ?string
    {
        return $this->chiffreAffaires;
    }

    public function setChiffreAffaires(string $chiffreAffaires): static
    {
        $this->chiffreAffaires = $chiffreAffaires;

        return $this;
    }

    public function getNombreCommandes(): ?int
    {
        return $this->nombreCommandes;
    }

    public function setNombreCommandes(int $nombreCommandes): static
    {
        $this->nombreCommandes = $nombreCommandes;

        return $this;
    }

    public function getNombreReservations(): ?int
    {
        return $this->nombreReservations;
    }

    public function setNombreReservations(int $nombreReservations): static
    {
        $this->nombreReservations = $nombreReservations;

        return $this;
    }

    public function getNombreClients(): ?int
    {
        return $this->nombreClients;
    }

    public function setNombreClients(int $nombreClients): static
    {
        $this->nombreClients = $nombreClients;

        return $this;
    }

    public function getNombreProduits(): ?int
    {
        return $this->nombreProduits;
    }

    public function setNombreProduits(int $nombreProduits): static
    {
        $this->nombreProduits = $nombreProduits;

        return $this;
    }
}
