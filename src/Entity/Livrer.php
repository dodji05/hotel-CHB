<?php

namespace App\Entity;

use App\Repository\LivrerRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LivrerRepository::class)]
class Livrer
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'livrers')]
    private ?LigneDemandeProduitService $lignedemande = null;

    #[ORM\Column(nullable: true)]
    private ?int $quaniteLivree = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockFinalEconoma = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockDebuEconomat = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockDebut = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockFinal = null;

    #[ORM\ManyToOne(inversedBy: 'livrers')]
    private ?LivraisonServices $livraison = null;

    use CommunTraits;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getLignedemande(): ?LigneDemandeProduitService
    {
        return $this->lignedemande;
    }

    public function setLignedemande(?LigneDemandeProduitService $lignedemande): static
    {
        $this->lignedemande = $lignedemande;

        return $this;
    }

    public function getQuaniteLivree(): ?int
    {
        return $this->quaniteLivree;
    }

    public function setQuaniteLivree(?int $quaniteLivree): static
    {
        $this->quaniteLivree = $quaniteLivree;

        return $this;
    }

    public function getStockFinalEconoma(): ?int
    {
        return $this->stockFinalEconoma;
    }

    public function setStockFinalEconoma(?int $stockFinalEconoma): static
    {
        $this->stockFinalEconoma = $stockFinalEconoma;

        return $this;
    }

    public function getStockDebuEconomat(): ?int
    {
        return $this->stockDebuEconomat;
    }

    public function setStockDebuEconomat(?int $stockDebuEconomat): static
    {
        $this->stockDebuEconomat = $stockDebuEconomat;

        return $this;
    }

    public function getStockDebut(): ?int
    {
        return $this->stockDebut;
    }

    public function setStockDebut(?int $stockDebut): static
    {
        $this->stockDebut = $stockDebut;

        return $this;
    }

    public function getStockFinal(): ?int
    {
        return $this->stockFinal;
    }

    public function setStockFinal(?int $stockFinal): static
    {
        $this->stockFinal = $stockFinal;

        return $this;
    }

    public function getLivraison(): ?LivraisonServices
    {
        return $this->livraison;
    }

    public function setLivraison(?LivraisonServices $livraison): static
    {
        $this->livraison = $livraison;

        return $this;
    }
}
