<?php

namespace App\Entity;

use App\Repository\LivraisonServicesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LivraisonServicesRepository::class)]
class LivraisonServices
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateLivraison = null;

    #[ORM\ManyToOne(inversedBy: 'livraisonServices')]
    private ?Services $service = null;

    /**
     * @var Collection<int, Livrer>
     */
    #[ORM\OneToMany(targetEntity: Livrer::class, mappedBy: 'livraison')]
    private Collection $livrers;

    public function __construct()
    {
        $this->livrers = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDateLivraison(): ?\DateTime
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(\DateTime $dateLivraison): static
    {
        $this->dateLivraison = $dateLivraison;

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
     * @return Collection<int, Livrer>
     */
    public function getLivrers(): Collection
    {
        return $this->livrers;
    }

    public function addLivrer(Livrer $livrer): static
    {
        if (!$this->livrers->contains($livrer)) {
            $this->livrers->add($livrer);
            $livrer->setLivraison($this);
        }

        return $this;
    }

    public function removeLivrer(Livrer $livrer): static
    {
        if ($this->livrers->removeElement($livrer)) {
            // set the owning side to null (unless already changed)
            if ($livrer->getLivraison() === $this) {
                $livrer->setLivraison(null);
            }
        }

        return $this;
    }
}
