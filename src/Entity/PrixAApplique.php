<?php

namespace App\Entity;

use App\Repository\PrixAAppliqueRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PrixAAppliqueRepository::class)]
class PrixAApplique
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[ORM\Column(nullable: true)]
    private ?int $stock = null;

    #[ORM\Column(nullable: true)]
    private ?int $qteVendu = null;

    #[ORM\Column(nullable: true)]
    private ?int $qteApprovisionne = null;

    #[ORM\Column(nullable: true)]
    private ?int $qteRetourne = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbReportion = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockEnPortion = null;

    #[ORM\ManyToOne(inversedBy: 'prixAAppliques')]
    private ?Services $service = null;

    #[ORM\ManyToOne(inversedBy: 'prixAAppliques')]
    private ?Produit $produit = null;

    /**
     * @var Collection<int, LigneFacture>
     */
    #[ORM\OneToMany(targetEntity: LigneFacture::class, mappedBy: 'produit')]
    private Collection $ligneFactures;

    /**
     * @var Collection<int, LigneDemandeProduitService>
     */
    #[ORM\OneToMany(targetEntity: LigneDemandeProduitService::class, mappedBy: 'produit')]
    private Collection $ligneDemandeProduitServices;

    use ReferenceTraits;
    use CommunTraits;
    public function __construct()
    {
        $this->ligneFactures = new ArrayCollection();
        $this->ligneDemandeProduitServices = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getQteVendu(): ?int
    {
        return $this->qteVendu;
    }

    public function setQteVendu(?int $qteVendu): static
    {
        $this->qteVendu = $qteVendu;

        return $this;
    }

    public function getQteApprovisionne(): ?int
    {
        return $this->qteApprovisionne;
    }

    public function setQteApprovisionne(?int $qteApprovisionne): static
    {
        $this->qteApprovisionne = $qteApprovisionne;

        return $this;
    }

    public function getQteRetourne(): ?int
    {
        return $this->qteRetourne;
    }

    public function setQteRetourne(?int $qteRetourne): static
    {
        $this->qteRetourne = $qteRetourne;

        return $this;
    }

    public function getNbReportion(): ?int
    {
        return $this->nbReportion;
    }

    public function setNbReportion(?int $nbReportion): static
    {
        $this->nbReportion = $nbReportion;

        return $this;
    }

    public function getStockEnPortion(): ?int
    {
        return $this->stockEnPortion;
    }

    public function setStockEnPortion(?int $stockEnPortion): static
    {
        $this->stockEnPortion = $stockEnPortion;

        return $this;
    }

    public function getService(): ?Services
    {
        return $this->service;
    }

    public function setService(?Services $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * @return Collection<int, LigneFacture>
     */
    public function getLigneFactures(): Collection
    {
        return $this->ligneFactures;
    }

    public function addLigneFacture(LigneFacture $ligneFacture): static
    {
        if (!$this->ligneFactures->contains($ligneFacture)) {
            $this->ligneFactures->add($ligneFacture);
            $ligneFacture->setProduit($this);
        }

        return $this;
    }

    public function removeLigneFacture(LigneFacture $ligneFacture): static
    {
        if ($this->ligneFactures->removeElement($ligneFacture)) {
            // set the owning side to null (unless already changed)
            if ($ligneFacture->getProduit() === $this) {
                $ligneFacture->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LigneDemandeProduitService>
     */
    public function getLigneDemandeProduitServices(): Collection
    {
        return $this->ligneDemandeProduitServices;
    }

    public function addLigneDemandeProduitService(LigneDemandeProduitService $ligneDemandeProduitService): static
    {
        if (!$this->ligneDemandeProduitServices->contains($ligneDemandeProduitService)) {
            $this->ligneDemandeProduitServices->add($ligneDemandeProduitService);
            $ligneDemandeProduitService->setProduit($this);
        }

        return $this;
    }

    public function removeLigneDemandeProduitService(LigneDemandeProduitService $ligneDemandeProduitService): static
    {
        if ($this->ligneDemandeProduitServices->removeElement($ligneDemandeProduitService)) {
            // set the owning side to null (unless already changed)
            if ($ligneDemandeProduitService->getProduit() === $this) {
                $ligneDemandeProduitService->setProduit(null);
            }
        }

        return $this;
    }
}
