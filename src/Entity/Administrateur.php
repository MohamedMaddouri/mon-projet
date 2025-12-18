<?php

namespace App\Entity;

use App\Repository\AdministrateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdministrateurRepository::class)]
class Administrateur extends Utilisateur
{
    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEmbauche = null;

    // Produits gérés
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'administrateur')]
    private Collection $produits;

    // Clients gérés
    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'administrateur')]
    private Collection $clientsGeres;

    // Réservations gérées
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'administrateur')]
    private Collection $reservations;

    // Commandes gérées
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'administrateur')]
    private Collection $commandes;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->clientsGeres = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    // ROLE & DATE
    public function getRole(): ?string { return $this->role; }
    public function setRole(string $role): static { $this->role = $role; return $this; }

    public function getDateEmbauche(): ?\DateTimeInterface { return $this->dateEmbauche; }
    public function setDateEmbauche(\DateTimeInterface $dateEmbauche): static { $this->dateEmbauche = $dateEmbauche; return $this; }

    // PRODUITS
    public function getProduits(): Collection { return $this->produits; }
    public function addProduit(Produit $produit): static {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setAdministrateur($this);
        }
        return $this;
    }
    public function removeProduit(Produit $produit): static {
        if ($this->produits->removeElement($produit)) {
            if ($produit->getAdministrateur() === $this) $produit->setAdministrateur(null);
        }
        return $this;
    }

    // CLIENTS
    public function getClientsGeres(): Collection { return $this->clientsGeres; }
    public function addClientGere(Client $client): static {
        if (!$this->clientsGeres->contains($client)) {
            $this->clientsGeres->add($client);
            $client->setAdministrateur($this);
        }
        return $this;
    }
    public function removeClientGere(Client $client): static {
        if ($this->clientsGeres->removeElement($client)) {
            if ($client->getAdministrateur() === $this) $client->setAdministrateur(null);
        }
        return $this;
    }

    // RESERVATIONS
    public function getReservations(): Collection { return $this->reservations; }
    public function addReservation(Reservation $reservation): static {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setAdministrateur($this);
        }
        return $this;
    }
    public function removeReservation(Reservation $reservation): static {
        if ($this->reservations->removeElement($reservation)) {
            if ($reservation->getAdministrateur() === $this) $reservation->setAdministrateur(null);
        }
        return $this;
    }

    // COMMANDES
    public function getCommandes(): Collection { return $this->commandes; }
    public function addCommande(Commande $commande): static {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setAdministrateur($this);
        }
        return $this;
    }
    public function removeCommande(Commande $commande): static {
        if ($this->commandes->removeElement($commande)) {
            if ($commande->getAdministrateur() === $this) $commande->setAdministrateur(null);
        }
        return $this;
    }
}
