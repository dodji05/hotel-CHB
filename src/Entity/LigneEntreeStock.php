<?php

namespace App\Entity;

use App\Repository\LigneEntreeStockRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LigneEntreeStockRepository::class)]
class LigneEntreeStock
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'ligneEntreeStocks')]
    private ?EntreeStock $entreStock = null;

    #[ORM\ManyToOne(inversedBy: 'ligneEntreeStocks')]
    private ?Produit $produit = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantiteGros = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantiteDetails = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEntreStock(): ?EntreeStock
    {
        return $this->entreStock;
    }

    public function setEntreStock(?EntreeStock $entreStock): static
    {
        $this->entreStock = $entreStock;

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

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;

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

//    public function calculerQuantiteTotale(): int
//    {
//        if (!$this->getProduit()) {
//            return 0;
//        }
//
//        $quantiteGros = $this->getQt ?? 0;
//        $quantiteDetails = $this->getQuantiteDetails() ?? 0;
//        $nbreUnite = $this->get() ?? 0;
//
//        return $quantiteGros * $nbreUnite + $quantiteDetails;
//    }
}
