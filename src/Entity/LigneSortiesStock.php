<?php

namespace App\Entity;

use App\Repository\LigneSortiesStockRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LigneSortiesStockRepository::class)]
class LigneSortiesStock
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelleProduit = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite = null;

    #[ORM\Column(nullable: true)]
    private ?float $PAU = null;

    #[ORM\ManyToOne(inversedBy: 'ligneSortiesStocks')]
    private ?SortiesStock $sortieStock = null;

    #[ORM\ManyToOne(inversedBy: 'ligneSortiesStocks')]
    private ?Produit $produit = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getReferenceProduit(): ?string
    {
        return $this->referenceProduit;
    }

    public function setReferenceProduit(?string $referenceProduit): static
    {
        $this->referenceProduit = $referenceProduit;

        return $this;
    }

    public function getLibelleProduit(): ?string
    {
        return $this->libelleProduit;
    }

    public function setLibelleProduit(?string $libelleProduit): static
    {
        $this->libelleProduit = $libelleProduit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPAU(): ?float
    {
        return $this->PAU;
    }

    public function setPAU(?float $PAU): static
    {
        $this->PAU = $PAU;

        return $this;
    }

    public function getSortieStock(): ?SortiesStock
    {
        return $this->sortieStock;
    }

    public function setSortieStock(?SortiesStock $sortieStock): static
    {
        $this->sortieStock = $sortieStock;

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
}
