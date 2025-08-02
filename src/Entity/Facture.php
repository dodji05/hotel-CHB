<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use App\Traits\CommunTraits;
use App\Traits\ReferenceTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateFacture = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalHT = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalTTC = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalTVA = null;

    #[ORM\Column(nullable: true)]
    private ?float $remise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceFacture = null;

    #[ORM\Column(nullable: true)]
    private ?float $tauxAIB = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantAIB = null;

    #[ORM\Column(nullable: true)]
    private ?int $compteur = null;

    #[ORM\Column(nullable: true)]
    private ?int $compteurTot = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signature = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $DateMCF = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $MCFNum = null;

    #[ORM\Column(nullable: true)]
    private ?float $netApayer = null;

    #[ORM\Column(nullable: true)]
    private ?bool $normalisee = false;

    #[ORM\Column(nullable: true)]
    private ?bool $annulee = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifAnnulation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceFactureAvoir = null;

    #[ORM\Column(nullable: true)]
    private ?int $compteurAvoir = null;

    #[ORM\Column(nullable: true)]
    private ?int $compteurTotAvoir = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signatureAvoir = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateMCFAvoir = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $qrcode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $qrcodeavoir = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $compteurt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $compteurTavoir = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estCommande = false;

    #[ORM\Column(nullable: true)]
    private ?bool $estPaye = false;

    #[ORM\Column(nullable: true)]
    private ?int $nombreProduits = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceTicket = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?ModeReglement $modeReglement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut = null;

    /**
     * @var Collection<int, LigneFacture>
     */
    #[ORM\OneToMany(targetEntity: LigneFacture::class, mappedBy: 'facture',cascade: ['persist', 'remove'])]
    private Collection $ligneFactures;

    /**
     * @var Collection<int, Consommation>
     */
    #[ORM\OneToMany(targetEntity: Consommation::class, mappedBy: 'sejour')]
    private Collection $consommations;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $datePaiement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nature = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?TableServices $tableservice = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Employe $servante = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observation = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateArrive = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localisation = null;
    use ReferenceTraits;
    use CommunTraits;

    public function __construct()
    {
        $this->ligneFactures = new ArrayCollection();
        $this->consommations = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDateFacture(): ?\DateTime
    {
        return $this->dateFacture;
    }

    public function setDateFacture(?\DateTime $dateFacture): static
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }

    public function getTotalHT(): ?float
    {
        return $this->totalHT;
    }

    public function setTotalHT(?float $totalHT): static
    {
        $this->totalHT = $totalHT;

        return $this;
    }

    public function getTotalTTC(): ?float
    {
        return $this->totalTTC;
    }

    public function setTotalTTC(?float $totalTTC): static
    {
        $this->totalTTC = $totalTTC;

        return $this;
    }

    public function getTotalTVA(): ?float
    {
        return $this->totalTVA;
    }

    public function setTotalTVA(?float $totalTVA): static
    {
        $this->totalTVA = $totalTVA;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(?float $remise): static
    {
        $this->remise = $remise;

        return $this;
    }

    public function getReferenceFacture(): ?string
    {
        return $this->referenceFacture;
    }

    public function setReferenceFacture(?string $referenceFacture): static
    {
        $this->referenceFacture = $referenceFacture;

        return $this;
    }

    public function getTauxAIB(): ?float
    {
        return $this->tauxAIB;
    }

    public function setTauxAIB(?float $tauxAIB): static
    {
        $this->tauxAIB = $tauxAIB;

        return $this;
    }

    public function getMontantAIB(): ?float
    {
        return $this->montantAIB;
    }

    public function setMontantAIB(?float $montantAIB): static
    {
        $this->montantAIB = $montantAIB;

        return $this;
    }

    public function getCompteur(): ?int
    {
        return $this->compteur;
    }

    public function setCompteur(?int $compteur): static
    {
        $this->compteur = $compteur;

        return $this;
    }

    public function getCompteurTot(): ?int
    {
        return $this->compteurTot;
    }

    public function setCompteurTot(?int $compteurTot): static
    {
        $this->compteurTot = $compteurTot;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(?string $signature): static
    {
        $this->signature = $signature;

        return $this;
    }

    public function getDateMCF(): ?\DateTime
    {
        return $this->DateMCF;
    }

    public function setDateMCF(?\DateTime $DateMCF): static
    {
        $this->DateMCF = $DateMCF;

        return $this;
    }

    public function getMCFNum(): ?string
    {
        return $this->MCFNum;
    }

    public function setMCFNum(?string $MCFNum): static
    {
        $this->MCFNum = $MCFNum;

        return $this;
    }

    public function getNetApayer(): ?float
    {
        return $this->netApayer;
    }

    public function setNetApayer(?float $netApayer): static
    {
        $this->netApayer = $netApayer;

        return $this;
    }

    public function isNormalisee(): ?bool
    {
        return $this->normalisee;
    }

    public function setNormalisee(?bool $normalisee): static
    {
        $this->normalisee = $normalisee;

        return $this;
    }

    public function isAnnulee(): ?bool
    {
        return $this->annulee;
    }

    public function setAnnulee(?bool $annulee): static
    {
        $this->annulee = $annulee;

        return $this;
    }

    public function getMotifAnnulation(): ?string
    {
        return $this->motifAnnulation;
    }

    public function setMotifAnnulation(?string $motifAnnulation): static
    {
        $this->motifAnnulation = $motifAnnulation;

        return $this;
    }

    public function getReferenceFactureAvoir(): ?string
    {
        return $this->referenceFactureAvoir;
    }

    public function setReferenceFactureAvoir(?string $referenceFactureAvoir): static
    {
        $this->referenceFactureAvoir = $referenceFactureAvoir;

        return $this;
    }

    public function getCompteurAvoir(): ?int
    {
        return $this->compteurAvoir;
    }

    public function setCompteurAvoir(?int $compteurAvoir): static
    {
        $this->compteurAvoir = $compteurAvoir;

        return $this;
    }

    public function getCompteurTotAvoir(): ?int
    {
        return $this->compteurTotAvoir;
    }

    public function setCompteurTotAvoir(?int $compteurTotAvoir): static
    {
        $this->compteurTotAvoir = $compteurTotAvoir;

        return $this;
    }

    public function getSignatureAvoir(): ?string
    {
        return $this->signatureAvoir;
    }

    public function setSignatureAvoir(?string $signatureAvoir): static
    {
        $this->signatureAvoir = $signatureAvoir;

        return $this;
    }

    public function getDateMCFAvoir(): ?\DateTime
    {
        return $this->dateMCFAvoir;
    }

    public function setDateMCFAvoir(?\DateTime $dateMCFAvoir): static
    {
        $this->dateMCFAvoir = $dateMCFAvoir;

        return $this;
    }

    public function getQrcode(): ?string
    {
        return $this->qrcode;
    }

    public function setQrcode(?string $qrcode): static
    {
        $this->qrcode = $qrcode;

        return $this;
    }

    public function getQrcodeavoir(): ?string
    {
        return $this->qrcodeavoir;
    }

    public function setQrcodeavoir(?string $qrcodeavoir): static
    {
        $this->qrcodeavoir = $qrcodeavoir;

        return $this;
    }

    public function getCompteurt(): ?string
    {
        return $this->compteurt;
    }

    public function setCompteurt(?string $compteurt): static
    {
        $this->compteurt = $compteurt;

        return $this;
    }

    public function getCompteurTavoir(): ?string
    {
        return $this->compteurTavoir;
    }

    public function setCompteurTavoir(?string $compteurTavoir): static
    {
        $this->compteurTavoir = $compteurTavoir;

        return $this;
    }

    public function isEstCommande(): ?bool
    {
        return $this->estCommande;
    }

    public function setEstCommande(?bool $estCommande): static
    {
        $this->estCommande = $estCommande;

        return $this;
    }

    public function isEstPaye(): ?bool
    {
        return $this->estPaye;
    }

    public function setEstPaye(?bool $estPaye): static
    {
        $this->estPaye = $estPaye;

        return $this;
    }

    public function getNombreProduits(): ?int
    {
        return $this->nombreProduits;
    }

    public function setNombreProduits(?int $nombreProduits): static
    {
        $this->nombreProduits = $nombreProduits;

        return $this;
    }

    public function getReferenceTicket(): ?string
    {
        return $this->referenceTicket;
    }

    public function setReferenceTicket(?string $referenceTicket): static
    {
        $this->referenceTicket = $referenceTicket;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getModeReglement(): ?ModeReglement
    {
        return $this->modeReglement;
    }

    public function setModeReglement(?ModeReglement $modeReglement): static
    {
        $this->modeReglement = $modeReglement;

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

    /**
     * @return Collection<int, LigneFacture>
     */
    public function getLigneFactures(): Collection
    {
        return $this->ligneFactures;
    }

    public function addLigneFacture(LigneFacture $ligneFacture): static
    {
        if (!$this->ligneFactures->contains($ligneFacture)) {
            $this->ligneFactures->add($ligneFacture);
            $ligneFacture->setFacture($this);
        }

        return $this;
    }

    public function removeLigneFacture(LigneFacture $ligneFacture): static
    {
        if ($this->ligneFactures->removeElement($ligneFacture)) {
            // set the owning side to null (unless already changed)
            if ($ligneFacture->getFacture() === $this) {
                $ligneFacture->setFacture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Consommation>
     */
    public function getConsommations(): Collection
    {
        return $this->consommations;
    }

    public function addConsommation(Consommation $consommation): static
    {
        if (!$this->consommations->contains($consommation)) {
            $this->consommations->add($consommation);
            $consommation->setSejour($this);
        }

        return $this;
    }

    public function removeConsommation(Consommation $consommation): static
    {
        if ($this->consommations->removeElement($consommation)) {
            // set the owning side to null (unless already changed)
            if ($consommation->getSejour() === $this) {
                $consommation->setSejour(null);
            }
        }

        return $this;
    }

    public function getDatePaiement(): ?\DateTime
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(?\DateTime $datePaiement): static
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(?string $nature): static
    {
        $this->nature = $nature;

        return $this;
    }

    public function getTableservice(): ?TableServices
    {
        return $this->tableservice;
    }

    public function setTableservice(?TableServices $tableservice): static
    {
        $this->tableservice = $tableservice;

        return $this;
    }

    public function getServante(): ?Employe
    {
        return $this->servante;
    }

    public function setServante(?Employe $servante): static
    {
        $this->servante = $servante;

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

    public function getDateArrive(): ?\DateTime
    {
        return $this->dateArrive;
    }

    public function setDateArrive(?\DateTime $dateArrive): static
    {
        $this->dateArrive = $dateArrive;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }
}
