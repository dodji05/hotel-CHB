<?php

namespace App\Entity;

use App\Repository\LigneFactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LigneFactureRepository::class)]
class LigneFacture
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFactures',cascade: ['persist', 'remove'])]
    private ?Facture $facture = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFactures')]
    private ?PrixAApplique $produit = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixVente = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelleProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $depart = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $destination = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifvoyage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nbEnfant = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateEntree = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateSortie = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbrejour = null;

    #[ORM\Column(nullable: true)]
    private ?float $caution = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estimprime = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantLigne = null;

    #[ORM\ManyToOne(inversedBy: 'detailsConsommation')]
    private ?Consommation $consommation = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $heureArrivee = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $heureSortie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateDebutAbonnement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateFinAbonnement = null;





    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): static
    {
        $this->facture = $facture;

        return $this;
    }

    public function getProduit(): ?PrixAApplique
    {
        return $this->produit;
    }

    public function setProduit(?PrixAApplique $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prixVente;
    }

    public function setPrixVente(?float $prixVente): static
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    public function getLibelleProduit(): ?string
    {
        return $this->libelleProduit;
    }

    public function setLibelleProduit(?string $libelleProduit): static
    {
        $this->libelleProduit = $libelleProduit;

        return $this;
    }

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(?string $depart): static
    {
        $this->depart = $depart;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getMotifvoyage(): ?string
    {
        return $this->motifvoyage;
    }

    public function setMotifvoyage(?string $motifvoyage): static
    {
        $this->motifvoyage = $motifvoyage;

        return $this;
    }

    public function getNbEnfant(): ?string
    {
        return $this->nbEnfant;
    }

    public function setNbEnfant(?string $nbEnfant): static
    {
        $this->nbEnfant = $nbEnfant;

        return $this;
    }

    public function getDateEntree(): ?\DateTime
    {
        return $this->dateEntree;
    }

    public function setDateEntree(?\DateTime $dateEntree): static
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    public function getDateSortie(): ?\DateTime
    {
        return $this->dateSortie;
    }

    public function setDateSortie(?\DateTime $dateSortie): static
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getNbrejour(): ?int
    {
        return $this->nbrejour;
    }

    public function setNbrejour(?int $nbrejour): static
    {
        $this->nbrejour = $nbrejour;

        return $this;
    }

    public function getCaution(): ?float
    {
        return $this->caution;
    }

    public function setCaution(?float $caution): static
    {
        $this->caution = $caution;

        return $this;
    }

    public function isEstimprime(): ?bool
    {
        return $this->estimprime;
    }

    public function setEstimprime(?bool $estimprime): static
    {
        $this->estimprime = $estimprime;

        return $this;
    }

    public function getMontantLigne(): ?float
    {
        return $this->montantLigne;
    }

    public function setMontantLigne(?float $montantLigne): static
    {
        $this->montantLigne = $montantLigne;

        return $this;
    }

    public function getConsommation(): ?Consommation
    {
        return $this->consommation;
    }

    public function setConsommation(?Consommation $consommation): static
    {
        $this->consommation = $consommation;

        return $this;
    }

    public function getHeureArrivee(): ?\DateTime
    {
        return $this->heureArrivee;
    }

    public function setHeureArrivee(?\DateTime $heureArrivee): static
    {
        $this->heureArrivee = $heureArrivee;

        return $this;
    }

    public function getHeureSortie(): ?\DateTime
    {
        return $this->heureSortie;
    }

    public function setHeureSortie(?\DateTime $heureSortie): static
    {
        $this->heureSortie = $heureSortie;

        return $this;
    }

    public function getDateDebutAbonnement(): ?\DateTime
    {
        return $this->dateDebutAbonnement;
    }

    public function setDateDebutAbonnement(?\DateTime $dateDebutAbonnement): static
    {
        $this->dateDebutAbonnement = $dateDebutAbonnement;

        return $this;
    }

    public function getDateFinAbonnement(): ?\DateTime
    {
        return $this->dateFinAbonnement;
    }

    public function setDateFinAbonnement(?\DateTime $dateFinAbonnement): static
    {
        $this->dateFinAbonnement = $dateFinAbonnement;

        return $this;
    }


}
