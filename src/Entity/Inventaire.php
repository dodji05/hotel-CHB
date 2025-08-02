<?php

namespace App\Entity;

use App\Repository\InventaireRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: InventaireRepository::class)]
class Inventaire
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateInventaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateFin = null;

    /**
     * @var Collection<int, LigneInventaire>
     */
    #[ORM\OneToMany(targetEntity: LigneInventaire::class, mappedBy: 'inventaire')]
    private Collection $ligneInventaires;

    use ReferenceTraits;
    use CommunTraits;
    public function __construct()
    {
        $this->ligneInventaires = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDateInventaire(): ?\DateTime
    {
        return $this->dateInventaire;
    }

    public function setDateInventaire(?\DateTime $dateInventaire): static
    {
        $this->dateInventaire = $dateInventaire;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTime $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTime $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection<int, LigneInventaire>
     */
    public function getLigneInventaires(): Collection
    {
        return $this->ligneInventaires;
    }

    public function addLigneInventaire(LigneInventaire $ligneInventaire): static
    {
        if (!$this->ligneInventaires->contains($ligneInventaire)) {
            $this->ligneInventaires->add($ligneInventaire);
            $ligneInventaire->setInventaire($this);
        }

        return $this;
    }

    public function removeLigneInventaire(LigneInventaire $ligneInventaire): static
    {
        if ($this->ligneInventaires->removeElement($ligneInventaire)) {
            // set the owning side to null (unless already changed)
            if ($ligneInventaire->getInventaire() === $this) {
                $ligneInventaire->setInventaire(null);
            }
        }

        return $this;
    }
}
