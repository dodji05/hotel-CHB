<?php

namespace App\Entity;

use App\Repository\SortiesStockRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SortiesStockRepository::class)]
class SortiesStock
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    use ReferenceTraits;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateSortie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $magasin = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalTTC = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detailMotif = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observations = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $beneficiaire = null;

    /**
     * @var Collection<int, LigneSortiesStock>
     */
    #[ORM\OneToMany(targetEntity: LigneSortiesStock::class, mappedBy: 'sortieStock',cascade: ['persist', 'remove'],orphanRemoval: true)]
    private Collection $ligneSortiesStocks;

    #[ORM\ManyToOne(inversedBy: 'sortiesStocks')]
    private ?TypeSorties $motifSortie = null;

    #[ORM\ManyToOne(inversedBy: 'sortiesStocks')]
    private ?Services $service = null;

    use CommunTraits;
    public function __construct()
    {
        $this->ligneSortiesStocks = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDateSortie(): ?\DateTime
    {
        return $this->dateSortie;
    }

    public function setDateSortie(?\DateTime $dateSortie): static
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getMagasin(): ?string
    {
        return $this->magasin;
    }

    public function setMagasin(?string $magasin): static
    {
        $this->magasin = $magasin;

        return $this;
    }

    public function getTotalTTC(): ?float
    {
        return $this->totalTTC;
    }

    public function setTotalTTC(?float $totalTTC): static
    {
        $this->totalTTC = $totalTTC;

        return $this;
    }

    public function getDetailMotif(): ?string
    {
        return $this->detailMotif;
    }

    public function setDetailMotif(?string $detailMotif): static
    {
        $this->detailMotif = $detailMotif;

        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations): static
    {
        $this->observations = $observations;

        return $this;
    }

    public function getBeneficiaire(): ?string
    {
        return $this->beneficiaire;
    }

    public function setBeneficiaire(?string $beneficiaire): static
    {
        $this->beneficiaire = $beneficiaire;

        return $this;
    }

    /**
     * @return Collection<int, LigneSortiesStock>
     */
    public function getLigneSortiesStocks(): Collection
    {
        return $this->ligneSortiesStocks;
    }

    public function addLigneSortiesStock(LigneSortiesStock $ligneSortiesStock): static
    {
        if (!$this->ligneSortiesStocks->contains($ligneSortiesStock)) {
            $this->ligneSortiesStocks->add($ligneSortiesStock);
            $ligneSortiesStock->setSortieStock($this);
        }

        return $this;
    }

    public function removeLigneSortiesStock(LigneSortiesStock $ligneSortiesStock): static
    {
        if ($this->ligneSortiesStocks->removeElement($ligneSortiesStock)) {
            // set the owning side to null (unless already changed)
            if ($ligneSortiesStock->getSortieStock() === $this) {
                $ligneSortiesStock->setSortieStock(null);
            }
        }

        return $this;
    }

    public function getMotifSortie(): ?TypeSorties
    {
        return $this->motifSortie;
    }

    public function setMotifSortie(?TypeSorties $motifSortie): static
    {
        $this->motifSortie = $motifSortie;

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
}
