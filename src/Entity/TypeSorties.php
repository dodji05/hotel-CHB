<?php

namespace App\Entity;

use App\Repository\TypeSortiesRepository;
use App\Traits\CommunTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeSortiesRepository::class)]
class TypeSorties
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, SortiesStock>
     */
    #[ORM\OneToMany(targetEntity: SortiesStock::class, mappedBy: 'motifSortie')]
    private Collection $sortiesStocks;

    use CommunTraits;

    public function __construct()
    {
        $this->sortiesStocks = new ArrayCollection();
    }

    public function getId(): ?int
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
     * @return Collection<int, SortiesStock>
     */
    public function getSortiesStocks(): Collection
    {
        return $this->sortiesStocks;
    }

    public function addSortiesStock(SortiesStock $sortiesStock): static
    {
        if (!$this->sortiesStocks->contains($sortiesStock)) {
            $this->sortiesStocks->add($sortiesStock);
            $sortiesStock->setMotifSortie($this);
        }

        return $this;
    }

    public function removeSortiesStock(SortiesStock $sortiesStock): static
    {
        if ($this->sortiesStocks->removeElement($sortiesStock)) {
            // set the owning side to null (unless already changed)
            if ($sortiesStock->getMotifSortie() === $this) {
                $sortiesStock->setMotifSortie(null);
            }
        }

        return $this;
    }
}
