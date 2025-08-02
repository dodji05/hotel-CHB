<?php

namespace App\Entity;

use App\Enum\StatutMateriel;
use App\Repository\MaterielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroSerie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateAcquiton = null;

    #[ORM\Column(length: 255, nullable: true)]
//    #[Assert\Unique(message: 'le code de codification doit être unique.')]
    private ?string $code = null;

//    #[ORM\Column(length: 255, nullable: true)]
    #[ORM\Column(enumType: StatutMateriel::class)]
    #[Assert\NotNull(message: 'L\'état est obligatoire')]
    private ?StatutMateriel $etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixAchat = null;

    #[ORM\ManyToOne(inversedBy: 'materiels')]
    private ?TypeMateriel $typeMateriel = null;

    /**
     * @var Collection<int, AffectationMateriel>
     */
    #[ORM\OneToMany(targetEntity: AffectationMateriel::class, mappedBy: 'materiel')]
    private Collection $affectationMateriels;

    public function __construct()
    {
        $this->affectationMateriels = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNumeroSerie(): ?string
    {
        return $this->numeroSerie;
    }

    public function setNumeroSerie(?string $numeroSerie): static
    {
        $this->numeroSerie = $numeroSerie;

        return $this;
    }

    public function getDateAcquiton(): ?\DateTime
    {
        return $this->dateAcquiton;
    }

    public function setDateAcquiton(?\DateTime $dateAcquiton): static
    {
        $this->dateAcquiton = $dateAcquiton;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getEtat(): ?StatutMateriel
    {
        return $this->etat;
    }

    public function setEtat(?StatutMateriel $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixAchat(): ?float
    {
        return $this->prixAchat;
    }

    public function setPrixAchat(?float $prixAchat): static
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    public function getTypeMateriel(): ?TypeMateriel
    {
        return $this->typeMateriel;
    }

    public function setTypeMateriel(?TypeMateriel $typeMateriel): static
    {
        $this->typeMateriel = $typeMateriel;

        return $this;
    }

    /**
     * @return Collection<int, AffectationMateriel>
     */
    public function getAffectationMateriels(): Collection
    {
        return $this->affectationMateriels;
    }

    public function addAffectationMateriel(AffectationMateriel $affectationMateriel): static
    {
        if (!$this->affectationMateriels->contains($affectationMateriel)) {
            $this->affectationMateriels->add($affectationMateriel);
            $affectationMateriel->setMateriel($this);
        }

        return $this;
    }

    public function removeAffectationMateriel(AffectationMateriel $affectationMateriel): static
    {
        if ($this->affectationMateriels->removeElement($affectationMateriel)) {
            // set the owning side to null (unless already changed)
            if ($affectationMateriel->getMateriel() === $this) {
                $affectationMateriel->setMateriel(null);
            }
        }

        return $this;
    }
}
