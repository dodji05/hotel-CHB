<?php

namespace App\Entity;

use App\Repository\TypeMaterielRepository;
use App\Traits\CommunTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TypeMaterielRepository::class)]
class TypeMateriel
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Materiel>
     */
    #[ORM\OneToMany(targetEntity: Materiel::class, mappedBy: 'typeMateriel')]
    private Collection $materiels;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'child')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $child;

    use CommunTraits;

    public function __construct()
    {
        $this->materiels = new ArrayCollection();
        $this->child = new ArrayCollection();
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

    /**
     * @return Collection<int, Materiel>
     */
    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiel $materiel): static
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels->add($materiel);
            $materiel->setTypeMateriel($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): static
    {
        if ($this->materiels->removeElement($materiel)) {
            // set the owning side to null (unless already changed)
            if ($materiel->getTypeMateriel() === $this) {
                $materiel->setTypeMateriel(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChild(): Collection
    {
        return $this->child;
    }

    public function addChild(self $child): static
    {
        if (!$this->child->contains($child)) {
            $this->child->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->child->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }
}
