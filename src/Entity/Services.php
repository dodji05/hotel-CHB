<?php

namespace App\Entity;

use App\Repository\ServicesRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ServicesRepository::class)]
class Services
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?bool $featured = null;

    /**
     * @var Collection<int, PrixAApplique>
     */
    #[ORM\OneToMany(targetEntity: PrixAApplique::class, mappedBy: 'service')]
    private Collection $prixAAppliques;

    /**
     * @var Collection<int, DemandeProduitService>
     */
    #[ORM\OneToMany(targetEntity: DemandeProduitService::class, mappedBy: 'service')]
    private Collection $demandeProduitServices;

    /**
     * @var Collection<int, LivraisonServices>
     */
    #[ORM\OneToMany(targetEntity: LivraisonServices::class, mappedBy: 'service')]
    private Collection $livraisonServices;

    /**
     * @var Collection<int, TableServices>
     */
    #[ORM\OneToMany(targetEntity: TableServices::class, mappedBy: 'services')]
    private Collection $tableServices;

    /**
     * @var Collection<int, AffectationMateriel>
     */
    #[ORM\OneToMany(targetEntity: AffectationMateriel::class, mappedBy: 'service')]
    private Collection $affectationMateriels;

    /**
     * @var Collection<int, Employe>
     */
    #[ORM\OneToMany(targetEntity: Employe::class, mappedBy: 'service')]
    private Collection $employes;

    /**
     * @var Collection<int, MouvementStock>
     */
    #[ORM\OneToMany(targetEntity: MouvementStock::class, mappedBy: 'service')]
    private Collection $mouvementStocks;

    /**
     * @var Collection<int, SortiesStock>
     */
    #[ORM\OneToMany(targetEntity: SortiesStock::class, mappedBy: 'service')]
    private Collection $sortiesStocks;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ImageService = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    use ReferenceTraits;
    use CommunTraits;
    public function __construct()
    {
        $this->prixAAppliques = new ArrayCollection();
        $this->demandeProduitServices = new ArrayCollection();
        $this->livraisonServices = new ArrayCollection();
        $this->tableServices = new ArrayCollection();
        $this->affectationMateriels = new ArrayCollection();
        $this->employes = new ArrayCollection();
        $this->mouvementStocks = new ArrayCollection();
        $this->sortiesStocks = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(?bool $featured): static
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * @return Collection<int, PrixAApplique>
     */
    public function getPrixAAppliques(): Collection
    {
        return $this->prixAAppliques;
    }

    public function addPrixAApplique(PrixAApplique $prixAApplique): static
    {
        if (!$this->prixAAppliques->contains($prixAApplique)) {
            $this->prixAAppliques->add($prixAApplique);
            $prixAApplique->setService($this);
        }

        return $this;
    }

    public function removePrixAApplique(PrixAApplique $prixAApplique): static
    {
        if ($this->prixAAppliques->removeElement($prixAApplique)) {
            // set the owning side to null (unless already changed)
            if ($prixAApplique->getService() === $this) {
                $prixAApplique->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DemandeProduitService>
     */
    public function getDemandeProduitServices(): Collection
    {
        return $this->demandeProduitServices;
    }

    public function addDemandeProduitService(DemandeProduitService $demandeProduitService): static
    {
        if (!$this->demandeProduitServices->contains($demandeProduitService)) {
            $this->demandeProduitServices->add($demandeProduitService);
            $demandeProduitService->setService($this);
        }

        return $this;
    }

    public function removeDemandeProduitService(DemandeProduitService $demandeProduitService): static
    {
        if ($this->demandeProduitServices->removeElement($demandeProduitService)) {
            // set the owning side to null (unless already changed)
            if ($demandeProduitService->getService() === $this) {
                $demandeProduitService->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LivraisonServices>
     */
    public function getLivraisonServices(): Collection
    {
        return $this->livraisonServices;
    }

    public function addLivraisonService(LivraisonServices $livraisonService): static
    {
        if (!$this->livraisonServices->contains($livraisonService)) {
            $this->livraisonServices->add($livraisonService);
            $livraisonService->setService($this);
        }

        return $this;
    }

    public function removeLivraisonService(LivraisonServices $livraisonService): static
    {
        if ($this->livraisonServices->removeElement($livraisonService)) {
            // set the owning side to null (unless already changed)
            if ($livraisonService->getService() === $this) {
                $livraisonService->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TableServices>
     */
    public function getTableServices(): Collection
    {
        return $this->tableServices;
    }

    public function addTableService(TableServices $tableService): static
    {
        if (!$this->tableServices->contains($tableService)) {
            $this->tableServices->add($tableService);
            $tableService->setServices($this);
        }

        return $this;
    }

    public function removeTableService(TableServices $tableService): static
    {
        if ($this->tableServices->removeElement($tableService)) {
            // set the owning side to null (unless already changed)
            if ($tableService->getServices() === $this) {
                $tableService->setServices(null);
            }
        }

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
            $affectationMateriel->setService($this);
        }

        return $this;
    }

    public function removeAffectationMateriel(AffectationMateriel $affectationMateriel): static
    {
        if ($this->affectationMateriels->removeElement($affectationMateriel)) {
            // set the owning side to null (unless already changed)
            if ($affectationMateriel->getService() === $this) {
                $affectationMateriel->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Employe>
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Employe $employe): static
    {
        if (!$this->employes->contains($employe)) {
            $this->employes->add($employe);
            $employe->setService($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): static
    {
        if ($this->employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getService() === $this) {
                $employe->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MouvementStock>
     */
    public function getMouvementStocks(): Collection
    {
        return $this->mouvementStocks;
    }

    public function addMouvementStock(MouvementStock $mouvementStock): static
    {
        if (!$this->mouvementStocks->contains($mouvementStock)) {
            $this->mouvementStocks->add($mouvementStock);
            $mouvementStock->setService($this);
        }

        return $this;
    }

    public function removeMouvementStock(MouvementStock $mouvementStock): static
    {
        if ($this->mouvementStocks->removeElement($mouvementStock)) {
            // set the owning side to null (unless already changed)
            if ($mouvementStock->getService() === $this) {
                $mouvementStock->setService(null);
            }
        }

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
            $sortiesStock->setService($this);
        }

        return $this;
    }

    public function removeSortiesStock(SortiesStock $sortiesStock): static
    {
        if ($this->sortiesStocks->removeElement($sortiesStock)) {
            // set the owning side to null (unless already changed)
            if ($sortiesStock->getService() === $this) {
                $sortiesStock->setService(null);
            }
        }

        return $this;
    }

    public function getImageService(): ?string
    {
        return $this->ImageService;
    }

    public function setImageService(?string $ImageService): static
    {
        $this->ImageService = $ImageService;

        return $this;
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
