<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'stock', cascade: ['persist', 'remove'])]
    private ?Produit $produit = null;

    #[ORM\Column(nullable: true)]
    private ?int $qteEnStock = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $auteurModif = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateModif = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQteEnStock(): ?int
    {
        return $this->qteEnStock;
    }

    public function setQteEnStock(?int $qteEnStock): static
    {
        $this->qteEnStock = $qteEnStock;

        return $this;
    }

    public function getAuteurModif(): ?string
    {
        return $this->auteurModif;
    }

    public function setAuteurModif(?string $auteurModif): static
    {
        $this->auteurModif = $auteurModif;

        return $this;
    }

    public function getDateModif(): ?\DateTime
    {
        return $this->dateModif;
    }

    public function setDateModif(?\DateTime $dateModif): static
    {
        $this->dateModif = $dateModif;

        return $this;
    }
}
