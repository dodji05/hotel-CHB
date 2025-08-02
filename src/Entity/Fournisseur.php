<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeFournisseur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenoms = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $denomination = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pays = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $IFU = null;

    /**
     * @var Collection<int, EntreeStock>
     */
    #[ORM\OneToMany(targetEntity: EntreeStock::class, mappedBy: 'fournisseur')]
    private Collection $entreeStocks;

    use ReferenceTraits;
    use CommunTraits;

    public function __construct()
    {
        $this->entreeStocks = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTypeFournisseur(): ?string
    {
        return $this->typeFournisseur;
    }

    public function setTypeFournisseur(?string $typeFournisseur): static
    {
        $this->typeFournisseur = $typeFournisseur;

        return $this;
    }

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(?string $prenoms): static
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    public function getDenomination(): ?string
    {
        return $this->denomination;
    }

    public function setDenomination(?string $denomination): static
    {
        $this->denomination = $denomination;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

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

    public function getIFU(): ?string
    {
        return $this->IFU;
    }

    public function setIFU(?string $IFU): static
    {
        $this->IFU = $IFU;

        return $this;
    }

    /**
     * @return Collection<int, EntreeStock>
     */
    public function getEntreeStocks(): Collection
    {
        return $this->entreeStocks;
    }

    public function addEntreeStock(EntreeStock $entreeStock): static
    {
        if (!$this->entreeStocks->contains($entreeStock)) {
            $this->entreeStocks->add($entreeStock);
            $entreeStock->setFournisseur($this);
        }

        return $this;
    }

    public function removeEntreeStock(EntreeStock $entreeStock): static
    {
        if ($this->entreeStocks->removeElement($entreeStock)) {
            // set the owning side to null (unless already changed)
            if ($entreeStock->getFournisseur() === $this) {
                $entreeStock->setFournisseur(null);
            }
        }

        return $this;
    }
}
