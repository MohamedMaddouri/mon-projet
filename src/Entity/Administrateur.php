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

    /**
     * @var Collection<int, Produit>
     */
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'Administrateur')]
    private Collection $produits;

    /**
     * @var Collection<int, Client>
     */
    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'administrateur')]
    private Collection $clientsGeres;

    /**
     * @var Collection<int, Réservation>
     */
    #[ORM\OneToMany(targetEntity: Réservation::class, mappedBy: 'administrateur')]
    private Collection $rServations;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'administrateur')]
    private Collection $commandes;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->clientsGeres = new ArrayCollection();
        $this->rServations = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }


    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getDateEmbauche(): ?\DateTimeInterface
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(\DateTimeInterface $dateEmbauche): static
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setAdministrateur($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getAdministrateur() === $this) {
                $produit->setAdministrateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClientsGeres(): Collection
    {
        return $this->clientsGeres;
    }

    public function addClientsGere(Client $clientsGere): static
    {
        if (!$this->clientsGeres->contains($clientsGere)) {
            $this->clientsGeres->add($clientsGere);
            $clientsGere->setAdministrateur($this);
        }

        return $this;
    }

    public function removeClientsGere(Client $clientsGere): static
    {
        if ($this->clientsGeres->removeElement($clientsGere)) {
            // set the owning side to null (unless already changed)
            if ($clientsGere->getAdministrateur() === $this) {
                $clientsGere->setAdministrateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Réservation>
     */
    public function getRServations(): Collection
    {
        return $this->rServations;
    }

    public function addRServation(Réservation $rServation): static
    {
        if (!$this->rServations->contains($rServation)) {
            $this->rServations->add($rServation);
            $rServation->setAdministrateur($this);
        }

        return $this;
    }

    public function removeRServation(Réservation $rServation): static
    {
        if ($this->rServations->removeElement($rServation)) {
            // set the owning side to null (unless already changed)
            if ($rServation->getAdministrateur() === $this) {
                $rServation->setAdministrateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setAdministrateur($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getAdministrateur() === $this) {
                $commande->setAdministrateur(null);
            }
        }

        return $this;
    }
}
