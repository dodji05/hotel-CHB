<?php

namespace App\Entity;

use App\Repository\ConsommationRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ConsommationRepository::class)]
class Consommation
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    use ReferenceTraits;

    #[ORM\Column(nullable: true)]
    private ?float $montant = null;

    #[ORM\Column(nullable: true)]
    private ?float $remise = null;

    #[ORM\ManyToOne(inversedBy: 'consommations')]
    private ?Facture $sejour = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateComsommation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estPaye = null;

    /**
     * @var Collection<int, LigneFacture>
     */
    #[ORM\OneToMany(targetEntity: LigneFacture::class, mappedBy: 'consommation')]
    private Collection $detailsConsommation;

    #[ORM\ManyToOne(inversedBy: 'consommations')]
    private ?ModeReglement $MmodeReglement = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?TableServices $tableservice = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Employe $servante = null;
    public function __construct()
    {
        $this->detailsConsommation = new ArrayCollection();
    }


    use CommunTraits;
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(?float $remise): static
    {
        $this->remise = $remise;

        return $this;
    }

    public function getSejour(): ?Facture
    {
        return $this->sejour;
    }

    public function setSejour(?Facture $sejour): static
    {
        $this->sejour = $sejour;

        return $this;
    }

    public function getDateComsommation(): ?\DateTime
    {
        return $this->dateComsommation;
    }

    public function setDateComsommation(?\DateTime $dateComsommation): static
    {
        $this->dateComsommation = $dateComsommation;

        return $this;
    }

    public function isEstPaye(): ?bool
    {
        return $this->estPaye;
    }

    public function setEstPaye(?bool $estPaye): static
    {
        $this->estPaye = $estPaye;

        return $this;
    }

    /**
     * @return Collection<int, LigneFacture>
     */
    public function getDetailsConsommation(): Collection
    {
        return $this->detailsConsommation;
    }

    public function addDetailsConsommation(LigneFacture $detailsConsommation): static
    {
        if (!$this->detailsConsommation->contains($detailsConsommation)) {
            $this->detailsConsommation->add($detailsConsommation);
            $detailsConsommation->setConsommation($this);
        }

        return $this;
    }

    public function removeDetailsConsommation(LigneFacture $detailsConsommation): static
    {
        if ($this->detailsConsommation->removeElement($detailsConsommation)) {
            // set the owning side to null (unless already changed)
            if ($detailsConsommation->getConsommation() === $this) {
                $detailsConsommation->setConsommation(null);
            }
        }

        return $this;
    }

    public function getMmodeReglement(): ?ModeReglement
    {
        return $this->MmodeReglement;
    }

    public function setMmodeReglement(?ModeReglement $MmodeReglement): static
    {
        $this->MmodeReglement = $MmodeReglement;

        return $this;
    }
    public function getTableservice(): ?TableServices
    {
        return $this->tableservice;
    }

    public function setTableservice(?TableServices $tableservice): static
    {
        $this->tableservice = $tableservice;

        return $this;
    }

    public function getServante(): ?Employe
    {
        return $this->servante;
    }

    public function setServante(?Employe $servante): static
    {
        $this->servante = $servante;

        return $this;
    }
}
