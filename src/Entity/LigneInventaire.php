<?php

namespace App\Entity;

use App\Repository\LigneInventaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LigneInventaireRepository::class)]
class LigneInventaire
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'ligneInventaires')]
    private ?Inventaire $inventaire = null;

    #[ORM\ManyToOne(inversedBy: 'ligneInventaires')]
    private ?Produit $Produit = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockVirtuelle = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockPhysque = null;

    #[ORM\Column(nullable: true)]
    private ?int $ecart = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelleProduit = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getInventaire(): ?Inventaire
    {
        return $this->inventaire;
    }

    public function setInventaire(?Inventaire $inventaire): static
    {
        $this->inventaire = $inventaire;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->Produit;
    }

    public function setProduit(?Produit $Produit): static
    {
        $this->Produit = $Produit;

        return $this;
    }

    public function getStockVirtuelle(): ?int
    {
        return $this->stockVirtuelle;
    }

    public function setStockVirtuelle(?int $stockVirtuelle): static
    {
        $this->stockVirtuelle = $stockVirtuelle;

        return $this;
    }

    public function getStockPhysque(): ?int
    {
        return $this->stockPhysque;
    }

    public function setStockPhysque(?int $stockPhysque): static
    {
        $this->stockPhysque = $stockPhysque;

        return $this;
    }

    public function getEcart(): ?int
    {
        return $this->ecart;
    }

    public function setEcart(?int $ecart): static
    {
        $this->ecart = $ecart;

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
}
