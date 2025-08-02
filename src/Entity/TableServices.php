<?php

namespace App\Entity;

use App\Repository\TableServicesRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TableServicesRepository::class)]
class TableServices
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroTable = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'tableServices')]
    private ?Services $services = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, Facture>
     */
    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'tableservice')]
    private Collection $factures;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
    }

    use ReferenceTraits;
    use CommunTraits;
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getNumeroTable(): ?string
    {
        return $this->numeroTable;
    }

    public function setNumeroTable(?string $numeroTable): static
    {
        $this->numeroTable = $numeroTable;

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

    public function getServices(): ?Services
    {
        return $this->services;
    }

    public function setServices(?Services $services): static
    {
        $this->services = $services;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

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
            $facture->setTableservice($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getTableservice() === $this) {
                $facture->setTableservice(null);
            }
        }

        return $this;
    }
}
