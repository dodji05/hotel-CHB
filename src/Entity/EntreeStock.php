<?php

namespace App\Entity;

use App\Repository\EntreeStockRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EntreeStockRepository::class)]
class EntreeStock
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'entreeStocks')]
    private ?Fournisseur $fournisseur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateEntreStock = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observation = null;

    use ReferenceTraits;
    use CommunTraits;
    /**
     * @var Collection<int, LigneEntreeStock>
     */

    #[ORM\OneToMany(targetEntity: LigneEntreeStock::class, mappedBy: 'entreStock',cascade: ['persist', 'remove'],orphanRemoval: true)]
    private Collection $ligneEntreeStocks;

    public function __construct()
    {
        $this->ligneEntreeStocks = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getDateEntreStock(): ?\DateTime
    {
        return $this->dateEntreStock;
    }

    public function setDateEntreStock(?\DateTime $dateEntreStock): static
    {
        $this->dateEntreStock = $dateEntreStock;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): static
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * @return Collection<int, LigneEntreeStock>
     */
    public function getLigneEntreeStocks(): Collection
    {
        return $this->ligneEntreeStocks;
    }

    public function addLigneEntreeStock(LigneEntreeStock $ligneEntreeStock): static
    {
        if (!$this->ligneEntreeStocks->contains($ligneEntreeStock)) {
            $this->ligneEntreeStocks->add($ligneEntreeStock);
            $ligneEntreeStock->setEntreStock($this);
        }

        return $this;
    }

    public function removeLigneEntreeStock(LigneEntreeStock $ligneEntreeStock): static
    {
        if ($this->ligneEntreeStocks->removeElement($ligneEntreeStock)) {
            // set the owning side to null (unless already changed)
            if ($ligneEntreeStock->getEntreStock() === $this) {
                $ligneEntreeStock->setEntreStock(null);
            }
        }

        return $this;
    }
}
