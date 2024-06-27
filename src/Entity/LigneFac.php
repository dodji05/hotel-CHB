<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'LigneFac')]
#[ORM\UniqueConstraint(name: 'IDLigneFac', columns: ['IDLigneFac'])]
#[ORM\Index(name: 'WDIDX_LigneFac_date', columns: ['date'])]
#[ORM\Index(name: 'WDIDX_LigneFac_Codefamille', columns: ['Codefamille'])]
#[ORM\Index(name: 'WDIDX_LigneFac_Reference', columns: ['Reference'])]
#[ORM\Index(name: 'WDIDX_LigneFac_IDservice', columns: ['IDservice'])]
#[ORM\Index(name: 'WDIDX_LigneFac_IDLigneCde', columns: ['IDLigneCde'])]
#[ORM\Index(name: 'WDIDX_LigneFac_IDtable', columns: ['IDtable'])]
#[ORM\Index(name: 'WDIDX_LigneFac_OrdreAffichage', columns: ['OrdreAffichage'])]
#[ORM\Index(name: 'WDIDX_LigneFac_IDAnnee', columns: ['IDAnnee'])]
#[ORM\Index(name: 'WDIDX_LigneFac_reffacture', columns: ['reffacture'])]
#[ORM\Index(name: 'WDIDX_LigneFac_TauxTVA', columns: ['TauxTVA'])]
#[ORM\Index(name: 'WDIDX_LigneFac_IDPiece', columns: ['IDPiece'])]
#[ORM\Index(name: 'WDIDX_LigneFac_NumFacture', columns: ['NumFacture'])]
#[ORM\Index(name: 'WDIDX_LigneFac_Codeprix_applique', columns: ['Codeprix_applique'])]
#[ORM\Index(name: 'WDIDX_LigneFac_LibProd', columns: ['LibProd'])]
#[ORM\Index(name: 'WDIDX_LigneFac_CLE_NumFacture_OrdreAffichage', columns: ['NumFacture', 'OrdreAffichage'])]
#[ORM\Index(name: 'WDIDX_LigneFac_IDSOCIETE', columns: ['IDSOCIETE'])]
#[ORM\Index(name: 'WDIDX_LigneFac_ID', columns: ['ID'])]
class LigneFac
{
    #[ORM\Column(name: 'Quantite', type: 'float', precision: 10, scale: 0, nullable: false)]
    private float $quantite = 0.0;

    #[ORM\Column(name: 'tauxRemise', type: 'float', precision: 10, scale: 0, nullable: false)]
    private float $tauxremise = 0.0;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'IDLigneFac', type: 'string', length: 50, nullable: false)]
    private string $idlignefac;

    #[ORM\Column(name: 'TauxTVA', type: 'float', precision: 10, scale: 0, nullable: false)]
    private float $tauxtva;

    #[ORM\Column(name: 'Reference', type: 'string', length: 50, nullable: false)]
    private string $reference;

    #[ORM\Column(name: 'PrixVente', type: 'decimal', precision: 24, scale: 6, nullable: false)]
    private string $prixvente;

    #[ORM\Column(name: 'NumFacture', type: 'string', length: 50, nullable: false)]
    private string $numfacture;

    #[ORM\Column(name: 'IDLigneCde', type: 'bigint', nullable: false)]
    private int $idlignecde;

    #[ORM\Column(name: 'LibProd', type: 'string', length: 40, nullable: false)]
    private string $libprod;

    #[ORM\Column(name: 'OrdreAffichage', type: 'smallint', nullable: false)]
    private int $ordreaffichage = 0;

    #[ORM\Column(name: 'montantLigneAIB', type: 'integer', nullable: false)]
    private int $montantligneaib = 0;

    #[ORM\Column(name: 'tauxAIB', type: 'float', precision: 10, scale: 0, nullable: false)]
    private float $tauxaib = 0.0;

    #[ORM\Column(name: 'montantLigne', type: 'integer', nullable: false)]
    private int $montantligne = 0;

    #[ORM\Column(name: 'MontantLigneTVA', type: 'integer', nullable: false)]
    private int $montantlignetva = 0;

    #[ORM\Column(name: 'IDSOCIETE', type: 'integer', nullable: false)]
    private int $idsociete;

    #[ORM\Column(name: 'IDAnnee', type: 'integer', nullable: false)]
    private int $idannee;

    #[ORM\Column(name: 'AffTVA', type: 'boolean', nullable: false)]
    private bool $afftva = false;

    #[ORM\Column(name: 'montantRemise', type: 'integer', nullable: false)]
    private int $montantremise = 0;

    #[ORM\Column(name: 'ttcHorsRemise', type: 'integer', nullable: false)]
    private int $ttchorsremise = 0;

    #[ORM\Column(name: 'ttcAvecRemise', type: 'integer', nullable: false)]
    private int $ttcavecremise = 0;

    #[ORM\Column(name: 'htAvecRemise', type: 'integer', nullable: false)]
    private int $htavecremise = 0;

    #[ORM\Column(name: 'ligneTTC', type: 'float', precision: 10, scale: 0, nullable: false)]
    private float $lignettc = 0.0;

    #[ORM\Column(name: 'ID', type: 'string', length: 50, nullable: false)]
    private string $id;

    #[ORM\Column(name: 'Description', type: 'text', nullable: false)]
    private string $description;

    #[ORM\Column(name: 'PuHT', type: 'integer', nullable: false)]
    private int $puht = 0;

    #[ORM\Column(name: 'date', type: 'date', nullable: false)]
    private \DateTime $date;


    #[ORM\Column(name: 'Codefamille', type: 'string', length: 10, nullable: false)]
    private string $codefamille;

    #[ORM\Column(name: 'depart', type: 'string', length: 50, nullable: false)]
    private string $depart;

    #[ORM\Column(name: 'destination', type: 'string', length: 50, nullable: false)]
    private string $destination;

    #[ORM\Column(name: 'motifvoyage', type: 'string', length: 50, nullable: false)]
    private string $motifvoyage;

    #[ORM\Column(name: 'nbEnfant', type: 'integer', nullable: false)]
    private int $nbenfant = 0;

    #[ORM\Column(name: 'allanta', type: 'string', length: 100, nullable: false)]
    private string $allanta;

    #[ORM\Column(name: 'Venantde', type: 'string', length: 100, nullable: false)]
    private string $venantde;

    #[ORM\Column(name: 'IDPiece', type: 'bigint', nullable: false)]
    private int $idpiece;

    #[ORM\Column(name: 'numeropiece', type: 'string', length: 50, nullable: false)]
    private string $numeropiece;

    #[ORM\Column(name: 'DateEntree', type: 'date', nullable: false)]
    private \DateTime $dateentree;

    #[ORM\Column(name: 'DateSortie', type: 'date', nullable: false)]
    private \DateTime $datesortie;

    #[ORM\Column(name: 'nbrejoure', type: 'integer', nullable: false)]
    private int $nbrejoure = 0;

    #[ORM\Column(name: 'caution', type: 'integer', nullable: false)]
    private int $caution = 0;

    #[ORM\Column(name: 'IDservice', type: 'integer', nullable: false)]
    private int $idservice = 0;

    #[ORM\Column(name: 'estLivrer', type: 'boolean', nullable: false)]
    private bool $estlivrer = false;

    #[ORM\Column(name: 'Codeprix_applique', type: 'string', length: 50, nullable: false)]
    private string $codeprixApplique;

    #[ORM\Column(name: 'designationprod', type: 'string', length: 50, nullable: false)]
    private string $designationprod;

    #[ORM\Column(name: 'assujjeti', type: 'boolean', nullable: false)]
    private bool $assujjeti = false;

    #[ORM\Column(name: 'IDtable', type: 'integer', nullable: false)]
    private int $idtable;

    #[ORM\ManyToOne(inversedBy: 'ligneFacs')]
    #[ORM\JoinColumn(name: "codeprix_applique", referencedColumnName: "codeprix_applique")]

    private ?PrixAAppliquer $Codeprix_applique = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFacs')]
    #[ORM\JoinColumn(name: "reffacture", referencedColumnName: "reffacture")]

    private ?Facture $reffacture = null;

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getTauxremise(): ?float
    {
        return $this->tauxremise;
    }

    public function setTauxremise(float $tauxremise): static
    {
        $this->tauxremise = $tauxremise;

        return $this;
    }

    public function getIdlignefac(): ?string
    {
        return $this->idlignefac;
    }

    public function getTauxtva(): ?float
    {
        return $this->tauxtva;
    }

    public function setTauxtva(float $tauxtva): static
    {
        $this->tauxtva = $tauxtva;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPrixvente(): ?string
    {
        return $this->prixvente;
    }

    public function setPrixvente(string $prixvente): static
    {
        $this->prixvente = $prixvente;

        return $this;
    }

    public function getNumfacture(): ?string
    {
        return $this->numfacture;
    }

    public function setNumfacture(string $numfacture): static
    {
        $this->numfacture = $numfacture;

        return $this;
    }

    public function getIdlignecde(): ?string
    {
        return $this->idlignecde;
    }

    public function setIdlignecde(string $idlignecde): static
    {
        $this->idlignecde = $idlignecde;

        return $this;
    }

    public function getLibprod(): ?string
    {
        return $this->libprod;
    }

    public function setLibprod(string $libprod): static
    {
        $this->libprod = $libprod;

        return $this;
    }

    public function getOrdreaffichage(): ?int
    {
        return $this->ordreaffichage;
    }

    public function setOrdreaffichage(int $ordreaffichage): static
    {
        $this->ordreaffichage = $ordreaffichage;

        return $this;
    }

    public function getMontantligneaib(): ?int
    {
        return $this->montantligneaib;
    }

    public function setMontantligneaib(int $montantligneaib): static
    {
        $this->montantligneaib = $montantligneaib;

        return $this;
    }

    public function getTauxaib(): ?float
    {
        return $this->tauxaib;
    }

    public function setTauxaib(float $tauxaib): static
    {
        $this->tauxaib = $tauxaib;

        return $this;
    }

    public function getMontantligne(): ?int
    {
        return $this->montantligne;
    }

    public function setMontantligne(int $montantligne): static
    {
        $this->montantligne = $montantligne;

        return $this;
    }

    public function getMontantlignetva(): ?int
    {
        return $this->montantlignetva;
    }

    public function setMontantlignetva(int $montantlignetva): static
    {
        $this->montantlignetva = $montantlignetva;

        return $this;
    }

    public function getIdsociete(): ?int
    {
        return $this->idsociete;
    }

    public function setIdsociete(int $idsociete): static
    {
        $this->idsociete = $idsociete;

        return $this;
    }

    public function getIdannee(): ?int
    {
        return $this->idannee;
    }

    public function setIdannee(int $idannee): static
    {
        $this->idannee = $idannee;

        return $this;
    }

    public function isAfftva(): ?bool
    {
        return $this->afftva;
    }

    public function setAfftva(bool $afftva): static
    {
        $this->afftva = $afftva;

        return $this;
    }

    public function getMontantremise(): ?int
    {
        return $this->montantremise;
    }

    public function setMontantremise(int $montantremise): static
    {
        $this->montantremise = $montantremise;

        return $this;
    }

    public function getTtchorsremise(): ?int
    {
        return $this->ttchorsremise;
    }

    public function setTtchorsremise(int $ttchorsremise): static
    {
        $this->ttchorsremise = $ttchorsremise;

        return $this;
    }

    public function getTtcavecremise(): ?int
    {
        return $this->ttcavecremise;
    }

    public function setTtcavecremise(int $ttcavecremise): static
    {
        $this->ttcavecremise = $ttcavecremise;

        return $this;
    }

    public function getHtavecremise(): ?int
    {
        return $this->htavecremise;
    }

    public function setHtavecremise(int $htavecremise): static
    {
        $this->htavecremise = $htavecremise;

        return $this;
    }

    public function getLignettc(): ?float
    {
        return $this->lignettc;
    }

    public function setLignettc(float $lignettc): static
    {
        $this->lignettc = $lignettc;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

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

    public function getPuht(): ?int
    {
        return $this->puht;
    }

    public function setPuht(int $puht): static
    {
        $this->puht = $puht;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }



    public function getCodefamille(): ?string
    {
        return $this->codefamille;
    }

    public function setCodefamille(string $codefamille): static
    {
        $this->codefamille = $codefamille;

        return $this;
    }

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(string $depart): static
    {
        $this->depart = $depart;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getMotifvoyage(): ?string
    {
        return $this->motifvoyage;
    }

    public function setMotifvoyage(string $motifvoyage): static
    {
        $this->motifvoyage = $motifvoyage;

        return $this;
    }

    public function getNbenfant(): ?int
    {
        return $this->nbenfant;
    }

    public function setNbenfant(int $nbenfant): static
    {
        $this->nbenfant = $nbenfant;

        return $this;
    }

    public function getAllanta(): ?string
    {
        return $this->allanta;
    }

    public function setAllanta(string $allanta): static
    {
        $this->allanta = $allanta;

        return $this;
    }

    public function getVenantde(): ?string
    {
        return $this->venantde;
    }

    public function setVenantde(string $venantde): static
    {
        $this->venantde = $venantde;

        return $this;
    }

    public function getIdpiece(): ?string
    {
        return $this->idpiece;
    }

    public function setIdpiece(string $idpiece): static
    {
        $this->idpiece = $idpiece;

        return $this;
    }

    public function getNumeropiece(): ?string
    {
        return $this->numeropiece;
    }

    public function setNumeropiece(string $numeropiece): static
    {
        $this->numeropiece = $numeropiece;

        return $this;
    }

    public function getDateentree(): ?\DateTimeInterface
    {
        return $this->dateentree;
    }

    public function setDateentree(\DateTimeInterface $dateentree): static
    {
        $this->dateentree = $dateentree;

        return $this;
    }

    public function getDatesortie(): ?\DateTimeInterface
    {
        return $this->datesortie;
    }

    public function setDatesortie(\DateTimeInterface $datesortie): static
    {
        $this->datesortie = $datesortie;

        return $this;
    }

    public function getNbrejoure(): ?int
    {
        return $this->nbrejoure;
    }

    public function setNbrejoure(int $nbrejoure): static
    {
        $this->nbrejoure = $nbrejoure;

        return $this;
    }

    public function getCaution(): ?int
    {
        return $this->caution;
    }

    public function setCaution(int $caution): static
    {
        $this->caution = $caution;

        return $this;
    }

    public function getIdservice(): ?int
    {
        return $this->idservice;
    }

    public function setIdservice(int $idservice): static
    {
        $this->idservice = $idservice;

        return $this;
    }

    public function isEstlivrer(): ?bool
    {
        return $this->estlivrer;
    }

    public function setEstlivrer(bool $estlivrer): static
    {
        $this->estlivrer = $estlivrer;

        return $this;
    }

    public function getCodeprixApplique(): ?string
    {
        return $this->codeprixApplique;
    }

    public function setCodeprixApplique(string $codeprixApplique): static
    {
        $this->codeprixApplique = $codeprixApplique;

        return $this;
    }

    public function getDesignationprod(): ?string
    {
        return $this->designationprod;
    }

    public function setDesignationprod(string $designationprod): static
    {
        $this->designationprod = $designationprod;

        return $this;
    }

    public function isAssujjeti(): ?bool
    {
        return $this->assujjeti;
    }

    public function setAssujjeti(bool $assujjeti): static
    {
        $this->assujjeti = $assujjeti;

        return $this;
    }

    public function getIdtable(): ?int
    {
        return $this->idtable;
    }

    public function setIdtable(int $idtable): static
    {
        $this->idtable = $idtable;

        return $this;
    }

    public function getReffacture(): ?Facture
    {
        return $this->reffacture;
    }

    public function setReffacture(?Facture $reffacture): static
    {
        $this->reffacture = $reffacture;

        return $this;
    }
}
