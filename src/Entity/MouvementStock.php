<?php

namespace App\Entity;

use App\Repository\MouvementStockRepository;
use App\Traits\CommunTraits;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MouvementStockRepository::class)]
class MouvementStock
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeOperation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifOperation = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementStocks')]
    private ?Produit $produit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(nullable: true)]
    private ?float $qte = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateMouvement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceOperation = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantiteAvant = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantiteSortie = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementStocks')]
    private ?Services $service = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nature = null;

    use CommunTraits;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTypeOperation(): ?string
    {
        return $this->typeOperation;
    }

    public function setTypeOperation(?string $typeOperation): static
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

    public function getMotifOperation(): ?string
    {
        return $this->motifOperation;
    }

    public function setMotifOperation(?string $motifOperation): static
    {
        $this->motifOperation = $motifOperation;

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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getQte(): ?float
    {
        return $this->qte;
    }

    public function setQte(?float $qte): static
    {
        $this->qte = $qte;

        return $this;
    }

    public function getDateMouvement(): ?\DateTime
    {
        return $this->dateMouvement;
    }

    public function setDateMouvement(?\DateTime $dateMouvement): static
    {
        $this->dateMouvement = $dateMouvement;

        return $this;
    }

    public function getReferenceOperation(): ?string
    {
        return $this->referenceOperation;
    }

    public function setReferenceOperation(?string $referenceOperation): static
    {
        $this->referenceOperation = $referenceOperation;

        return $this;
    }

    public function getQuantiteAvant(): ?int
    {
        return $this->quantiteAvant;
    }

    public function setQuantiteAvant(?int $quantiteAvant): static
    {
        $this->quantiteAvant = $quantiteAvant;

        return $this;
    }

    public function getQuantiteSortie(): ?int
    {
        return $this->quantiteSortie;
    }

    public function setQuantiteSortie(?int $quantiteSortie): static
    {
        $this->quantiteSortie = $quantiteSortie;

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

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(?string $nature): static
    {
        $this->nature = $nature;

        return $this;
    }
}
