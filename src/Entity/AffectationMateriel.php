<?php

namespace App\Entity;

use App\Repository\AffectationMaterielRepository;
use App\Traits\CommunTraits;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AffectationMaterielRepository::class)]
class AffectationMateriel
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'affectationMateriels')]
    private ?Materiel $materiel = null;

    #[ORM\ManyToOne(inversedBy: 'affectationMateriels')]
    private ?Services $service = null;

    #[ORM\ManyToOne(inversedBy: 'affectationMateriels')]
    private ?Produit $chambres = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateAffectation = null;

    use CommunTraits;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getMateriel(): ?Materiel
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiel $materiel): static
    {
        $this->materiel = $materiel;

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

    public function getChambres(): ?Produit
    {
        return $this->chambres;
    }

    public function setChambres(?Produit $chambres): static
    {
        $this->chambres = $chambres;

        return $this;
    }

    public function getDateAffectation(): ?\DateTime
    {
        return $this->dateAffectation;
    }

    public function setDateAffectation(\DateTime $dateAffectation): static
    {
        $this->dateAffectation = $dateAffectation;

        return $this;
    }
}
