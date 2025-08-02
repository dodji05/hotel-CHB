<?php

namespace App\Entity;

use App\Repository\FamilleRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FamilleRepository::class)]

class Famille
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroS =null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?bool $supprimer = false;
    use ReferenceTraits;
    use CommunTraits;

    /**
     * @var Collection<int, Produit>
     */
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'famille')]
    private Collection $produits;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $urlimage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;
    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function isSupprimer(): bool
    {
        return $this->supprimer;
    }

    public function setSupprimer(bool $supprimer): self
    {
        $this->supprimer = $supprimer;
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

    public function getNumeroS(): ?string
    {
        return $this->numeroS;
    }

    public function setNumeroS(?string $numeroS): static
    {
        $this->numeroS = $numeroS;
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

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setFamille($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getFamille() === $this) {
                $produit->setFamille(null);
            }
        }

        return $this;
    }

    public function getUrlimage(): ?string
    {
        return $this->urlimage;
    }

    public function setUrlimage(?string $urlimage): static
    {
        $this->urlimage = $urlimage;

        return $this;
    }

    public function getImages()
    {

        $url = filter_var($this->urlimage, FILTER_VALIDATE_URL);
        // TODO: Implement __toString() method.
        if ($url) {
            return $this->urlimage;
        } else {
            return 'https://' . $_SERVER['SERVER_NAME'] . '/uploads/famille/' . $this->urlimage;

        }

    }
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
