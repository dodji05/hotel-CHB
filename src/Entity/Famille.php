<?php

namespace App\Entity;

use App\Repository\FamilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FamilleRepository::class)]
#[ORM\Table(name: "famille")]
#[ORM\UniqueConstraint(name: "CodeFamille", columns: ["CodeFamille"])]
class Famille
{

    #[ORM\Id]
    #[ORM\CustomIdGenerator]
    #[ORM\Column(name:"CodeFamille" ,type: "string", length: 50)]
    private ?string $codeFamille;

    #[ORM\Column(name:"libellé", type: "string", length: 50)]
    private $libelle;

    #[ORM\Column(name:"IDSOCIETE" ,type: "integer", nullable: true)]
    private ?int $idSociete;

    #[ORM\Column(name:"IDAnnee" ,type: "integer", nullable: true)]
    private ?int $idAnnee;

    #[ORM\Column(type: "boolean", options: ["default" => 0])]
    private $supprimer;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description;

    #[ORM\Column(name:"numeroS" ,type: "string", length: 50, nullable: true)]
    private ?string $numero;

    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'CodeFamille')]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }


    public function getCodeFamille(): ?string
    {
        return $this->codeFamille;
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

    public function getIdSociete(): ?int
    {
        return $this->idSociete;
    }

    public function setIdSociete(int $idSociete): static
    {
        $this->idSociete = $idSociete;

        return $this;
    }

    public function getIdAnnee(): ?int
    {
        return $this->idAnnee;
    }

    public function setIdAnnee(int $idAnnee): static
    {
        $this->idAnnee = $idAnnee;

        return $this;
    }

    public function isSupprimer(): ?bool
    {
        return $this->supprimer;
    }

    public function setSupprimer(bool $supprimer): static
    {
        $this->supprimer = $supprimer;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }


    public function setCodeFamille(?string $codeFamille): void
    {
        $this->codeFamille = $codeFamille;
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
            $produit->setCodeFamille($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCodeFamille() === $this) {
                $produit->setCodeFamille(null);
            }
        }

        return $this;
    }

}
