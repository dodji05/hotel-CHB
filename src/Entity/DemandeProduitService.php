<?php

namespace App\Entity;


use App\Repository\DemandeProduitServiceRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DemandeProduitServiceRepository::class)]
class DemandeProduitService
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column( nullable: true)]
    private ?string $demandeur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observation = null;

    #[ORM\ManyToOne(inversedBy: 'demandeProduitServices',)]
    private ?Services $service = null;

    /**
     * @var Collection<int, LigneDemandeProduitService>
     */
    #[ORM\OneToMany(targetEntity: LigneDemandeProduitService::class, mappedBy: 'demandeService',cascade: ['persist', 'remove'],orphanRemoval: true)]
    private Collection $ligneDemandeProduitServices;

    #[ORM\Column(nullable: true)]
    private ?bool $isTermine = false;

    use ReferenceTraits;
    use CommunTraits;
    public function __construct()
    {
        $this->ligneDemandeProduitServices = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getDemandeur(): ?string
    {
        return $this->demandeur;
    }

    public function setDemandeur(?string $demandeur): self
    {
        $this->demandeur = $demandeur;
        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): self
    {
        $this->observation = $observation;
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

    /**
     * @return Collection<int, LigneDemandeProduitService>
     */
    public function getLigneDemandeProduitServices(): Collection
    {
        return $this->ligneDemandeProduitServices;
    }

    public function addLigneDemandeProduitService(LigneDemandeProduitService $ligneDemandeProduitService): static
    {
        if (!$this->ligneDemandeProduitServices->contains($ligneDemandeProduitService)) {
            $this->ligneDemandeProduitServices->add($ligneDemandeProduitService);
            $ligneDemandeProduitService->setDemandeService($this);
        }

        return $this;
    }

    public function removeLigneDemandeProduitService(LigneDemandeProduitService $ligneDemandeProduitService): static
    {
        if ($this->ligneDemandeProduitServices->removeElement($ligneDemandeProduitService)) {
            // set the owning side to null (unless already changed)
            if ($ligneDemandeProduitService->getDemandeService() === $this) {
                $ligneDemandeProduitService->setDemandeService(null);
            }
        }

        return $this;
    }

    public function isTermine(): ?bool
    {
        return $this->isTermine;
    }

    public function setIsTermine(?bool $isTermine): static
    {
        $this->isTermine = $isTermine;

        return $this;
    }

}
