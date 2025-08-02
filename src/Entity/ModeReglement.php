<?php

namespace App\Entity;

use App\Repository\ModeReglementRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ModeReglementRepository::class)]
class ModeReglement
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bank = null;
    use ReferenceTraits;
    use CommunTraits;
    /**
     * @var Collection<int, Facture>
     */
    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'modeReglement')]
    private Collection $factures;

    /**
     * @var Collection<int, Consommation>
     */
    #[ORM\OneToMany(targetEntity: Consommation::class, mappedBy: 'MmodeReglement')]
    private Collection $consommations;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
        $this->consommations = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function isBank(): ?bool
    {
        return $this->bank;
    }

    public function setBank(?bool $bank): static
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setModeReglement($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getModeReglement() === $this) {
                $facture->setModeReglement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Consommation>
     */
    public function getConsommations(): Collection
    {
        return $this->consommations;
    }

    public function addConsommation(Consommation $consommation): static
    {
        if (!$this->consommations->contains($consommation)) {
            $this->consommations->add($consommation);
            $consommation->setMmodeReglement($this);
        }

        return $this;
    }

    public function removeConsommation(Consommation $consommation): static
    {
        if ($this->consommations->removeElement($consommation)) {
            // set the owning side to null (unless already changed)
            if ($consommation->getMmodeReglement() === $this) {
                $consommation->setMmodeReglement(null);
            }
        }

        return $this;
    }
}
