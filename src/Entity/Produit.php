<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixHt = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixAchat = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codeBarre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codeBarreFournisseur = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockActuel = null;

    #[ORM\Column(nullable: true)]
    private ?int $qteReapprovissement = null;

    #[ORM\Column(nullable: true)]
    private ?int $qteMini = null;

    #[ORM\Column(nullable: true)]
    private ?float $qteVente = null;

    #[ORM\Column(nullable: true)]
    private ?float $qteRebus = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixRevient = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estMatierePremiere = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estStockable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estDisponible = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $unite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $uniteDetail = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbreUnite = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Famille $famille = null;



    /**
     * @var Collection<int, PrixAApplique>
     */
    #[ORM\OneToMany(targetEntity: PrixAApplique::class, mappedBy: 'produit',cascade: ['persist', 'remove'],orphanRemoval: true)]
    private Collection $prixAAppliques;

    /**
     * @var Collection<int, LigneEntreeStock>
     */
    #[ORM\OneToMany(targetEntity: LigneEntreeStock::class, mappedBy: 'produit')]
    private Collection $ligneEntreeStocks;

    #[ORM\OneToOne(mappedBy: 'produit', cascade: ['persist', 'remove'])]
    private ?Stock $stock = null;

    /**
     * @var Collection<int, MouvementStock>
     */
    #[ORM\OneToMany(targetEntity: MouvementStock::class, mappedBy: 'produit')]
    private Collection $mouvementStocks;

    /**
     * @var Collection<int, ImagesProduit>
     */
    #[ORM\OneToMany(targetEntity: ImagesProduit::class, mappedBy: 'produit')]
    private Collection $imagesProduits;

    /**
     * @var Collection<int, LigneSortiesStock>
     */
    #[ORM\OneToMany(targetEntity: LigneSortiesStock::class, mappedBy: 'produit')]
    private Collection $ligneSortiesStocks;

    #[ORM\Column(nullable: true)]
    private ?int $stockAlerte = null;

    /**
     * @var Collection<int, AffectationMateriel>
     */
    #[ORM\OneToMany(targetEntity: AffectationMateriel::class, mappedBy: 'chambres')]
    private Collection $affectationMateriels;

    /**
     * @var Collection<int, LigneInventaire>
     */
    #[ORM\OneToMany(targetEntity: LigneInventaire::class, mappedBy: 'Produit')]
    private Collection $ligneInventaires;



    use ReferenceTraits;
    use CommunTraits;
    public function __construct()
    {
        $this->prixAAppliques = new ArrayCollection();
        $this->ligneEntreeStocks = new ArrayCollection();
        $this->mouvementStocks = new ArrayCollection();
        $this->imagesProduits = new ArrayCollection();
        $this->ligneSortiesStocks = new ArrayCollection();
        $this->affectationMateriels = new ArrayCollection();
        $this->ligneInventaires = new ArrayCollection();
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

    public function getPrixHt(): ?float
    {
        return $this->prixHt;
    }

    public function setPrixHt(?float $prixHt): static
    {
        $this->prixHt = $prixHt;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCodeBarre(): ?string
    {
        return $this->codeBarre;
    }

    public function setCodeBarre(?string $codeBarre): static
    {
        $this->codeBarre = $codeBarre;

        return $this;
    }

    public function getCodeBarreFournisseur(): ?string
    {
        return $this->codeBarreFournisseur;
    }

    public function setCodeBarreFournisseur(?string $codeBarreFournisseur): static
    {
        $this->codeBarreFournisseur = $codeBarreFournisseur;

        return $this;
    }

    public function getStockActuel(): ?int
    {
        return $this->stockActuel;
    }

    public function setStockActuel(?int $stockActuel): static
    {
        $this->stockActuel = $stockActuel;

        return $this;
    }

    public function getQteReapprovissement(): ?int
    {
        return $this->qteReapprovissement;
    }

    public function setQteReapprovissement(?int $qteReapprovissement): static
    {
        $this->qteReapprovissement = $qteReapprovissement;

        return $this;
    }

    public function getQteMini(): ?int
    {
        return $this->qteMini;
    }

    public function setQteMini(?int $qteMini): static
    {
        $this->qteMini = $qteMini;

        return $this;
    }

    public function getQteVente(): ?float
    {
        return $this->qteVente;
    }

    public function setQteVente(?float $qteVente): static
    {
        $this->qteVente = $qteVente;

        return $this;
    }

    public function getQteRebus(): ?float
    {
        return $this->qteRebus;
    }

    public function setQteRebus(?float $qteRebus): static
    {
        $this->qteRebus = $qteRebus;

        return $this;
    }

    public function getPrixRevient(): ?float
    {
        return $this->prixRevient;
    }

    public function setPrixRevient(?float $prixRevient): static
    {
        $this->prixRevient = $prixRevient;

        return $this;
    }

    public function isEstMatierePremiere(): ?bool
    {
        return $this->estMatierePremiere;
    }

    public function setEstMatierePremiere(?bool $estMatierePremiere): static
    {
        $this->estMatierePremiere = $estMatierePremiere;

        return $this;
    }

    public function isEstStockable(): ?bool
    {
        return $this->estStockable;
    }

    public function setEstStockable(?bool $estStockable): static
    {
        $this->estStockable = $estStockable;

        return $this;
    }

    public function isEstDisponible(): ?bool
    {
        return $this->estDisponible;
    }

    public function setEstDisponible(?bool $estDisponible): static
    {
        $this->estDisponible = $estDisponible;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    public function getUniteDetail(): ?string
    {
        return $this->uniteDetail;
    }

    public function setUniteDetail(?string $uniteDetail): static
    {
        $this->uniteDetail = $uniteDetail;

        return $this;
    }

    public function getNbreUnite(): ?int
    {
        return $this->nbreUnite;
    }

    public function setNbreUnite(?int $nbreUnite): static
    {
        $this->nbreUnite = $nbreUnite;

        return $this;
    }

    public function getFamille(): ?Famille
    {
        return $this->famille;
    }

    public function setFamille(?Famille $famille): static
    {
        $this->famille = $famille;

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
            $prixAApplique->setProduit($this);
        }

        return $this;
    }

    public function removePrixAApplique(PrixAApplique $prixAApplique): static
    {
        if ($this->prixAAppliques->removeElement($prixAApplique)) {
            // set the owning side to null (unless already changed)
            if ($prixAApplique->getProduit() === $this) {
                $prixAApplique->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LigneEntreeStock>
     */
    public function getLigneEntreeStocks(): Collection
    {
        return $this->ligneEntreeStocks;
    }

    public function addLigneEntreeStock(LigneEntreeStock $ligneEntreeStock): static
    {
        if (!$this->ligneEntreeStocks->contains($ligneEntreeStock)) {
            $this->ligneEntreeStocks->add($ligneEntreeStock);
            $ligneEntreeStock->setProduit($this);
        }

        return $this;
    }

    public function removeLigneEntreeStock(LigneEntreeStock $ligneEntreeStock): static
    {
        if ($this->ligneEntreeStocks->removeElement($ligneEntreeStock)) {
            // set the owning side to null (unless already changed)
            if ($ligneEntreeStock->getProduit() === $this) {
                $ligneEntreeStock->setProduit(null);
            }
        }

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): static
    {
        // unset the owning side of the relation if necessary
        if ($stock === null && $this->stock !== null) {
            $this->stock->setProduit(null);
        }

        // set the owning side of the relation if necessary
        if ($stock !== null && $stock->getProduit() !== $this) {
            $stock->setProduit($this);
        }

        $this->stock = $stock;

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
            $mouvementStock->setProduit($this);
        }

        return $this;
    }

    public function removeMouvementStock(MouvementStock $mouvementStock): static
    {
        if ($this->mouvementStocks->removeElement($mouvementStock)) {
            // set the owning side to null (unless already changed)
            if ($mouvementStock->getProduit() === $this) {
                $mouvementStock->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ImagesProduit>
     */
    public function getImagesProduits(): Collection
    {
        return $this->imagesProduits;
    }

    public function addImagesProduit(ImagesProduit $imagesProduit): static
    {
        if (!$this->imagesProduits->contains($imagesProduit)) {
            $this->imagesProduits->add($imagesProduit);
            $imagesProduit->setProduit($this);
        }

        return $this;
    }

    public function removeImagesProduit(ImagesProduit $imagesProduit): static
    {
        if ($this->imagesProduits->removeElement($imagesProduit)) {
            // set the owning side to null (unless already changed)
            if ($imagesProduit->getProduit() === $this) {
                $imagesProduit->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LigneSortiesStock>
     */
    public function getLigneSortiesStocks(): Collection
    {
        return $this->ligneSortiesStocks;
    }

    public function addLigneSortiesStock(LigneSortiesStock $ligneSortiesStock): static
    {
        if (!$this->ligneSortiesStocks->contains($ligneSortiesStock)) {
            $this->ligneSortiesStocks->add($ligneSortiesStock);
            $ligneSortiesStock->setProduit($this);
        }

        return $this;
    }

    public function removeLigneSortiesStock(LigneSortiesStock $ligneSortiesStock): static
    {
        if ($this->ligneSortiesStocks->removeElement($ligneSortiesStock)) {
            // set the owning side to null (unless already changed)
            if ($ligneSortiesStock->getProduit() === $this) {
                $ligneSortiesStock->setProduit(null);
            }
        }

        return $this;
    }

    public function getStockAlerte(): ?int
    {
        return $this->stockAlerte;
    }

    public function setStockAlerte(?int $stockAlerte): static
    {
        $this->stockAlerte = $stockAlerte;

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
            $affectationMateriel->setChambres($this);
        }

        return $this;
    }

    public function removeAffectationMateriel(AffectationMateriel $affectationMateriel): static
    {
        if ($this->affectationMateriels->removeElement($affectationMateriel)) {
            // set the owning side to null (unless already changed)
            if ($affectationMateriel->getChambres() === $this) {
                $affectationMateriel->setChambres(null);
            }
        }

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
            $ligneInventaire->setProduit($this);
        }

        return $this;
    }

    public function removeLigneInventaire(LigneInventaire $ligneInventaire): static
    {
        if ($this->ligneInventaires->removeElement($ligneInventaire)) {
            // set the owning side to null (unless already changed)
            if ($ligneInventaire->getProduit() === $this) {
                $ligneInventaire->setProduit(null);
            }
        }

        return $this;
    }

//    public function getImageProduit(): ?string
//    {
//        $url = filter_var($this->imagesProduits, FILTER_VALIDATE_URL);
//        // TODO: Implement __toString() method.
//        if ($url) {
//            return $this->photo;
//        } else {
//            return 'https://' . $_SERVER['SERVER_NAME'] . '/uploads/produit/' . $this->photo;
//
//        }
//    }
}
