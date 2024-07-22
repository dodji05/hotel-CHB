<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
#[ORM\Table(name: 'facture')]
#[ORM\UniqueConstraint(name: 'reffacture', columns: ['reffacture'])]
#[ORM\Index(name: 'WDIDX_facture_date', columns: ['date'])]
#[ORM\Index(name: 'WDIDX_facture_Code', columns: ['Code'])]
#[ORM\Index(name: 'WDIDX_facture_DateFacture', columns: ['DateFacture'])]
#[ORM\Index(name: 'WDIDX_facture_loginUtilisateur', columns: ['loginUtilisateur'])]
#[ORM\Index(name: 'WDIDX_facture_NumBordereau', columns: ['NumBordereau'])]
#[ORM\Index(name: 'WDIDX_facture_IDAdresseFacturation', columns: ['IDAdresseFacturation'])]
#[ORM\Index(name: 'WDIDX_facture_IDAnnee', columns: ['IDAnnee'])]
#[ORM\Index(name: 'WDIDX_facture_IDtable', columns: ['IDtable'])]
#[ORM\Index(name: 'WDIDX_facture_Acquittee', columns: ['Acquittee'])]
#[ORM\Index(name: 'WDIDX_facture_IDTYPEFACTURE', columns: ['IDTYPEFACTURE'])]
#[ORM\Index(name: 'WDIDX_facture_NumCommande', columns: ['NumCommande'])]
#[ORM\Index(name: 'WDIDX_facture_IDBANQUE', columns: ['IDBANQUE'])]
#[ORM\Index(name: 'WDIDX_facture_IDBordereau', columns: ['IDBordereau'])]
#[ORM\Index(name: 'WDIDX_facture_IDSource', columns: ['IDSource'])]
#[ORM\Index(name: 'WDIDX_facture_NumFacture', columns: ['NumFacture'])]
#[ORM\Index(name: 'WDIDX_facture_NumAgent', columns: ['NumAgent'])]
#[ORM\Index(name: 'WDIDX_facture_NomClient', columns: ['NomClient'])]
#[ORM\Index(name: 'WDIDX_facture_NumClient', columns: ['NumClient'])]
#[ORM\Index(name: 'WDIDX_facture_IDSOCIETE', columns: ['IDSOCIETE'])]
#[ORM\Index(name: 'WDIDX_facture_ModifierPar', columns: ['ModifierPar'])]
#[ORM\Index(name: 'WDIDX_facture_IDModeReglement', columns: ['IDModeReglement'])]
#[ORM\Index(name: 'WDIDX_facture_Acquitteenormalisee', columns: ['Acquittee', 'normalisee'])]
#[ORM\Index(name: 'WDIDX_facture_SaisiPar', columns: ['SaisiPar'])]
#[ORM\Index(name: 'WDIDX_facture_normalisee', columns: ['normalisee'])]
#[ORM\Index(name: 'WDIDX_facture_DateFactureannulee', columns: ['DateFacture', 'annulee'])]
#[ORM\Index(name: 'WDIDX_facture_numOrdreFacture', columns: ['numOrdreFacture'])]
#[ORM\Index(name: 'WDIDX_facture_annulee', columns: ['annulee'])]
#[ORM\Index(name: 'WDIDX_facture_IDAdresseLivraison', columns: ['IDAdresseLivraison'])]
#[ORM\Index(name: 'WDIDX_facture_referenceTicket', columns: ['referenceTicket'])]
class Facture
{

 //   #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'NumFacture', nullable: false)]
    private string $numfacture;

    #[ORM\Column(name: 'DateFacture', type: 'date', nullable: false)]
    private \DateTime $datefacture;

    #[ORM\Column(name: 'TotalHT', type: 'decimal', precision: 24, scale: 6, nullable: false)]
    private string $totalht;

    #[ORM\Column(name: 'TotalTTC', type: 'decimal', precision: 24, scale: 6, nullable: false)]
    private string $totalttc;

    #[ORM\Column(name: 'IDAdresseFacturation', type: 'bigint', nullable: false)]
    private int $idadressefacturation;

    #[ORM\Column(name: 'TotalTVA', type: 'decimal', precision: 24, scale: 6, nullable: false)]
    private string $totaltva;

    #[ORM\Column(name: 'Acquittee', type: 'boolean', nullable: false)]
    private bool $acquittee = false;

    #[ORM\Column(name: 'Remise', type: 'decimal', precision: 24, scale: 6, nullable: false)]
    private string $remise;

    #[ORM\Column(name: 'SaisiPar', type: 'string', length: 40, nullable: false)]
    private string $saisipar;

    #[ORM\Column(name: 'SaisiLe', type: 'datetime', nullable: false)]
    private \DateTime $saisile;

    #[ORM\Column(name: 'Observations', type: 'text', nullable: false)]
    private string $observations;

    #[ORM\Column(name: 'NumCommande', type: 'bigint', nullable: false)]
    private int $numcommande;

    #[ORM\Column(name: 'TotalFraisPort', type: 'decimal', precision: 24, scale: 6, nullable: false, options: ['default' => '0.000000'])]
    private string $totalfraisport = '0.000000';

    #[ORM\Column(name: 'numOrdreFacture', type: 'string', length: 50, nullable: false)]
    private string $numordrefacture;

    #[ORM\Column(name: 'tauxRemise', type: 'integer', nullable: false)]
    private int $tauxremise = 0;

    #[ORM\Column(name: 'IDBordereau', type: 'bigint', nullable: false)]
    private int $idbordereau;

    #[ORM\Id]
    #[ORM\Column(name: 'reffacture', type: 'string', length: 50, nullable: false)]
    #[ORM\CustomIdGenerator]
    private string $reffacture;

    #[ORM\Column(name: 'tauxAIB', type: 'float', precision: 10, scale: 0, nullable: false)]
    private float $tauxaib = 0.0;

    #[ORM\Column(name: 'IDAdresseLivraison', type: 'bigint', nullable: false)]
    private int $idadresselivraison = 0;

    #[ORM\Column(name: 'MontantAIB', type: 'integer', nullable: false)]
    private int $montantaib = 0;

    #[ORM\Column(name: 'adresseLivraison', type: 'string', length: 50, nullable: false)]
    private string $adresselivraison;

    #[ORM\Column(name: 'adresseFacturation', type: 'string', length: 50, nullable: false)]
    private string $adressefacturation;

    #[ORM\Column(name: 'echeancierEtabli', type: 'boolean', nullable: false)]
    private bool $echeancieretabli = false;

    #[ORM\Column(name: 'date', type: 'date', nullable: false)]
    private \DateTime $date;

    #[ORM\Column(name: 'tvaEtat', type: 'float', precision: 10, scale: 0, nullable: false)]
    private float $tvaetat = 0.0;

    #[ORM\Column(name: 'txtvaEtat', type: 'integer', nullable: false)]
    private int $txtvaetat = 0;

    #[ORM\Column(name: 'NumAgent', type: 'bigint', nullable: false)]
    private int $numagent = 0;

    #[ORM\Column(name: 'loginUtilisateur', type: 'string', length: 50, nullable: false)]
    private string $loginutilisateur;

    #[ORM\Column(name: 'IDSOCIETE', type: 'integer', nullable: false)]
    private int $idsociete;

    #[ORM\Column(name: 'IDAnnee', type: 'integer', nullable: false)]
    private int $idannee;

    #[ORM\Column(name: 'IDTYPEFACTURE', type: 'bigint', nullable: false)]
    private int $idtypefacture;

    #[ORM\Column(name: 'Compteur', type: 'integer', nullable: false)]
    private int $compteur = 0;

    #[ORM\Column(name: 'Compteur_tot', type: 'integer', nullable: false)]
    private int $compteurTot = 0;

    #[ORM\Column(name: 'Signature', type: 'string', length: 50, nullable: false)]
    private string $signature;

    #[ORM\Column(name: 'DateMCF', type: 'datetime', nullable: false)]
    private \DateTime $datemcf;

    #[ORM\Column(name: 'MCFNum', type: 'string', length: 50, nullable: false)]
    private string $mcfnum;

    #[ORM\Column(name: 'NetApayer', type: 'integer', nullable: false)]
    private int $netapayer = 0;

    #[ORM\Column(name: 'estPaye', nullable: true)]
    private ?bool $estPaye = false;

    #[ORM\Column(name: 'estCommander',nullable: true)]
    private ?bool $estCommander = null;

    #[ORM\Column(name: 'normalisee', type: 'boolean', nullable: false)]
    private bool $normalisee = false;

    #[ORM\Column(name: 'MontantPercu', type: 'integer', nullable: false)]
    private int $montantpercu = 0;

    #[ORM\Column(name: 'referenceTicket', type: 'bigint', nullable: false)]
    private int $referenceticket = 0;

    #[ORM\Column(name: 'Annulee', type: 'boolean', nullable: false)]
    private bool $annulee = false;

    #[ORM\Column(name: 'IDModeReglement', type: 'bigint', nullable: true)]
    private int $idmodereglement = 0;

    #[ORM\Column(name: 'ModifierPar', type: 'string', length: 50, nullable: false)]
    private string $modifierpar;

    #[ORM\Column(name: 'IDBANQUE', type: 'bigint', nullable: false)]
    private int $idbanque = 0;

    #[ORM\Column(name: 'IDSource', type: 'bigint', nullable: false)]
    private int $idsource = 0;

    #[ORM\Column(name: 'Code', type: 'bigint', nullable: false)]
    private int $code = 0;

    #[ORM\Column(name: 'NomClient', type: 'string', length: 50, nullable: false)]
    private string $nomclient;

    #[ORM\Column(name: 'NumBordereau', type: 'string', length: 50, nullable: false)]
    private string $numbordereau;



    #[ORM\OneToMany(targetEntity: LigneFac::class, mappedBy: 'reffacture')]
    private Collection $ligneFacs;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[ORM\JoinColumn(name: "NumClient", referencedColumnName: "NumClient")]
    private ?Client $NumClient = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[ORM\JoinColumn(name: "IDtable", referencedColumnName: "IDtable")]
    private ?Table $IDtable = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut = null;

    public function __construct()
    {
        $this->ligneFacs = new ArrayCollection();
    }

    public function getNumfacture(): ?string
    {
        return $this->numfacture;
    }

    public function getDatefacture(): ?\DateTimeInterface
    {
        return $this->datefacture;
    }

    public function setDatefacture(\DateTimeInterface $datefacture): static
    {
        $this->datefacture = $datefacture;

        return $this;
    }

    public function getTotalht(): ?string
    {
        return $this->totalht;
    }

    public function setTotalht(string $totalht): static
    {
        $this->totalht = $totalht;

        return $this;
    }

    public function getTotalttc(): ?string
    {
        return $this->totalttc;
    }

    public function setTotalttc(string $totalttc): static
    {
        $this->totalttc = $totalttc;

        return $this;
    }

    public function getIdadressefacturation(): ?string
    {
        return $this->idadressefacturation;
    }

    public function setIdadressefacturation(string $idadressefacturation): static
    {
        $this->idadressefacturation = $idadressefacturation;

        return $this;
    }

    public function getTotaltva(): ?string
    {
        return $this->totaltva;
    }

    public function setTotaltva(string $totaltva): static
    {
        $this->totaltva = $totaltva;

        return $this;
    }

    public function isAcquittee(): ?bool
    {
        return $this->acquittee;
    }

    public function setAcquittee(?bool $acquittee): static
    {
        $this->acquittee = $acquittee;

        return $this;
    }

    public function getRemise(): ?string
    {
        return $this->remise;
    }

    public function setRemise(string $remise): static
    {
        $this->remise = $remise;

        return $this;
    }

    public function getSaisipar(): ?string
    {
        return $this->saisipar;
    }

    public function setSaisipar(string $saisipar): static
    {
        $this->saisipar = $saisipar;

        return $this;
    }

    public function getSaisile(): ?\DateTimeInterface
    {
        return $this->saisile;
    }

    public function setSaisile(\DateTimeInterface $saisile): static
    {
        $this->saisile = $saisile;

        return $this;
    }

    public function getObseations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(string $observations): static
    {
        $this->observations = $observations;

        return $this;
    }

    public function getNumcommande(): ?string
    {
        return $this->numcommande;
    }

    public function setNumcommande(string $numcommande): static
    {
        $this->numcommande = $numcommande;

        return $this;
    }

    public function getTotalfraisport(): ?string
    {
        return $this->totalfraisport;
    }

    public function setTotalfraisport(string $totalfraisport): static
    {
        $this->totalfraisport = $totalfraisport;

        return $this;
    }

    public function getNumordrefacture(): ?string
    {
        return $this->numordrefacture;
    }

    public function setNumordrefacture(string $numordrefacture): static
    {
        $this->numordrefacture = $numordrefacture;

        return $this;
    }

    public function getTauxremise(): ?int
    {
        return $this->tauxremise;
    }

    public function setTauxremise(int $tauxremise): static
    {
        $this->tauxremise = $tauxremise;

        return $this;
    }

    public function getIdbordereau(): ?string
    {
        return $this->idbordereau;
    }

    public function setIdbordereau(string $idbordereau): static
    {
        $this->idbordereau = $idbordereau;

        return $this;
    }

    public function getReffacture(): ?string
    {
        return $this->reffacture;
    }

    public function setReffacture(string $reffacture): static
    {
        $this->reffacture = $reffacture;

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

    public function getIdadresselivraison(): ?string
    {
        return $this->idadresselivraison;
    }

    public function setIdadresselivraison(string $idadresselivraison): static
    {
        $this->idadresselivraison = $idadresselivraison;

        return $this;
    }

    public function getMontantaib(): ?int
    {
        return $this->montantaib;
    }

    public function setMontantaib(int $montantaib): static
    {
        $this->montantaib = $montantaib;

        return $this;
    }

    public function getAdresselivraison(): ?string
    {
        return $this->adresselivraison;
    }

    public function setAdresselivraison(string $adresselivraison): static
    {
        $this->adresselivraison = $adresselivraison;

        return $this;
    }

    public function getAdressefacturation(): ?string
    {
        return $this->adressefacturation;
    }

    public function setAdressefacturation(string $adressefacturation): static
    {
        $this->adressefacturation = $adressefacturation;

        return $this;
    }

    public function isEcheancieretabli(): ?bool
    {
        return $this->echeancieretabli;
    }

    public function setEcheancieretabli(bool $echeancieretabli): static
    {
        $this->echeancieretabli = $echeancieretabli;

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

    public function getTvaetat(): ?float
    {
        return $this->tvaetat;
    }

    public function setTvaetat(float $tvaetat): static
    {
        $this->tvaetat = $tvaetat;

        return $this;
    }

    public function getTxtvaetat(): ?int
    {
        return $this->txtvaetat;
    }

    public function setTxtvaetat(int $txtvaetat): static
    {
        $this->txtvaetat = $txtvaetat;

        return $this;
    }

    public function getNumagent(): ?string
    {
        return $this->numagent;
    }

    public function setNumagent(string $numagent): static
    {
        $this->numagent = $numagent;

        return $this;
    }

    public function getLoginutilisateur(): ?string
    {
        return $this->loginutilisateur;
    }

    public function setLoginutilisateur(string $loginutilisateur): static
    {
        $this->loginutilisateur = $loginutilisateur;

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

    public function getIdtypefacture(): ?string
    {
        return $this->idtypefacture;
    }

    public function setIdtypefacture(string $idtypefacture): static
    {
        $this->idtypefacture = $idtypefacture;

        return $this;
    }

    public function getCompteur(): ?int
    {
        return $this->compteur;
    }

    public function setCompteur(int $compteur): static
    {
        $this->compteur = $compteur;

        return $this;
    }

    public function getCompteurTot(): ?int
    {
        return $this->compteurTot;
    }

    public function setCompteurTot(int $compteurTot): static
    {
        $this->compteurTot = $compteurTot;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(string $signature): static
    {
        $this->signature = $signature;

        return $this;
    }

    public function getDatemcf(): ?\DateTimeInterface
    {
        return $this->datemcf;
    }

    public function setDatemcf(\DateTimeInterface $datemcf): static
    {
        $this->datemcf = $datemcf;

        return $this;
    }

    public function getMcfnum(): ?string
    {
        return $this->mcfnum;
    }

    public function setMcfnum(string $mcfnum): static
    {
        $this->mcfnum = $mcfnum;

        return $this;
    }

    public function getNetapayer(): ?int
    {
        return $this->netapayer;
    }

    public function setNetapayer(int $netapayer): static
    {
        $this->netapayer = $netapayer;

        return $this;
    }

    public function isNormalisee(): ?bool
    {
        return $this->normalisee;
    }

    public function setNormalisee(bool $normalisee): static
    {
        $this->normalisee = $normalisee;

        return $this;
    }

    public function getMontantpercu(): ?int
    {
        return $this->montantpercu;
    }

    public function setMontantpercu(int $montantpercu): static
    {
        $this->montantpercu = $montantpercu;

        return $this;
    }

    public function getReferenceticket(): ?string
    {
        return $this->referenceticket;
    }

    public function setReferenceticket(string $referenceticket): static
    {
        $this->referenceticket = $referenceticket;

        return $this;
    }

    public function isAnnulee(): ?bool
    {
        return $this->annulee;
    }

    public function setAnnulee(bool $annulee): static
    {
        $this->annulee = $annulee;

        return $this;
    }

    public function getIdmodereglement(): ?string
    {
        return $this->idmodereglement;
    }

    public function setIdmodereglement(?string $idmodereglement): static
    {
        $this->idmodereglement = $idmodereglement;

        return $this;
    }

    public function getModifierpar(): ?string
    {
        return $this->modifierpar;
    }

    public function setModifierpar(string $modifierpar): static
    {
        $this->modifierpar = $modifierpar;

        return $this;
    }

    public function getIdbanque(): ?string
    {
        return $this->idbanque;
    }

    public function setIdbanque(string $idbanque): static
    {
        $this->idbanque = $idbanque;

        return $this;
    }

    public function getIdsource(): ?string
    {
        return $this->idsource;
    }

    public function setIdsource(string $idsource): static
    {
        $this->idsource = $idsource;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getNomclient(): ?string
    {
        return $this->nomclient;
    }

    public function setNomclient(?string $nomclient): static
    {
        $this->nomclient = $nomclient;

        return $this;
    }

    public function getNumbordereau(): ?string
    {
        return $this->numbordereau;
    }

    public function setNumbordereau(string $numbordereau): static
    {
        $this->numbordereau = $numbordereau;

        return $this;
    }



    /**
     * @return Collection<int, LigneFac>
     */
    public function getLigneFacs(): Collection
    {
        return $this->ligneFacs;
    }

    public function addLigneFac(LigneFac $ligneFac): static
    {
        if (!$this->ligneFacs->contains($ligneFac)) {
            $this->ligneFacs->add($ligneFac);
            $ligneFac->setReffacture($this);
        }

        return $this;
    }

    public function removeLigneFac(LigneFac $ligneFac): static
    {
        if ($this->ligneFacs->removeElement($ligneFac)) {
            // set the owning side to null (unless already changed)
            if ($ligneFac->getReffacture() === $this) {
                $ligneFac->setReffacture(null);
            }
        }

        return $this;
    }

    public function getNumClient(): ?Client
    {
        return $this->NumClient;
    }

    public function setNumClient(?Client $NumClient): static
    {
        $this->NumClient = $NumClient;

        return $this;
    }

    public function setNumfacture(?string $numfacture): static
    {
        $this->numfacture = $numfacture;
        return $this;
    }

    public function setEstPaye(?bool $estPaye): static
    {
        $this->estPaye = $estPaye;
        return $this;
    }

    public function getEstPaye(): ?bool
    {
        return $this->estPaye;
    }

    public function setEstCommander(?bool $estCommander): static
    {
        $this->estCommander = $estCommander;
        return $this;
    }

    public function getEstCommander(): ?bool
    {
        return $this->estCommander;
    }

    public function getIDtable(): ?Table
    {
        return $this->IDtable;
    }

    public function setIDtable(?Table $IDtable): static
    {
        $this->IDtable = $IDtable;

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
}
