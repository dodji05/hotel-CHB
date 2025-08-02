<?php

namespace App\Entity;

use App\Repository\LigneDemandeProduitServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LigneDemandeProduitServiceRepository::class)]
class LigneDemandeProduitService
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'ligneDemandeProduitServices')]
    private ?DemandeProduitService $demandeService = null;
    #[ORM\Column(nullable: true)]
    private ?int $quantiteGros = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantiteDetails = null;
    #[ORM\Column(nullable: true)]
    private ?int $quantiteDemande = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantiteServie = null;

    #[ORM\ManyToOne(inversedBy: 'ligneDemandeProduitServices')]
    private ?PrixAApplique $produit = null;

    /**
     * @var Collection<int, Livrer>
     */
    #[ORM\OneToMany(targetEntity: Livrer::class, mappedBy: 'lignedemande')]
    private Collection $livrers;

    public function __construct()
    {
        $this->livrers = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDemandeService(): ?DemandeProduitService
    {
        return $this->demandeService;
    }

    public function setDemandeService(?DemandeProduitService $demandeService): static
    {
        $this->demandeService = $demandeService;

        return $this;
    }

    public function getQuantiteDemande(): ?int
    {
        return $this->quantiteDemande;
    }

    public function setQuantiteDemande(?int $quantiteDemande): static
    {
        $this->quantiteDemande = $quantiteDemande;

        return $this;
    }

    public function getQuantiteServie(): ?int
    {
        return $this->quantiteServie;
    }

    public function setQuantiteServie(?int $quantiteServie): static
    {
        $this->quantiteServie = $quantiteServie;

        return $this;
    }

    public function getProduit(): ?PrixAApplique
    {
        return $this->produit;
    }

    public function setProduit(?PrixAApplique $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * @return Collection<int, Livrer>
     */
    public function getLivrers(): Collection
    {
        return $this->livrers;
    }

    public function addLivrer(Livrer $livrer): static
    {
        if (!$this->livrers->contains($livrer)) {
            $this->livrers->add($livrer);
            $livrer->setLignedemande($this);
        }

        return $this;
    }

    public function removeLivrer(Livrer $livrer): static
    {
        if ($this->livrers->removeElement($livrer)) {
            // set the owning side to null (unless already changed)
            if ($livrer->getLignedemande() === $this) {
                $livrer->setLignedemande(null);
            }
        }

        return $this;
    }
    public function getQuantiteGros(): ?int
    {
        return $this->quantiteGros;
    }

    public function setQuantiteGros(?int $quantiteGros): static
    {
        $this->quantiteGros = $quantiteGros;

        return $this;
    }

    public function getQuantiteDetails(): ?int
    {
        return $this->quantiteDetails;
    }

    public function setQuantiteDetails(?int $quantiteDetails): static
    {
        $this->quantiteDetails = $quantiteDetails;

        return $this;
    }
}
