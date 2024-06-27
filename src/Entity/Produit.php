<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "produit", uniqueConstraints: [new ORM\UniqueConstraint(name: "ID", columns: ["ID"])], indexes: [
//    new ORM\Index(name: "WDIDX_produit_QteReappro", columns: ["QteReappro"]),
//    new ORM\Index(name: "WDIDX_produit_PTVA_Marge", columns: ["PTVA_Marge"]),
//    new ORM\Index(name: "WDIDX_produit_TauxTVA", columns: ["TauxTVA"]),
//    new ORM\Index(name: "WDIDX_produit_Reference", columns: ["Reference"]),
//    new ORM\Index(name: "WDIDX_produit_CodeBarre", columns: ["CodeBarre"]),
//    new ORM\Index(name: "WDIDX_produit_CodeFamille", columns: ["CodeFamille"]),
//    new ORM\Index(name: "WDIDX_produit_LibProd", columns: ["LibProd"]),
//    new ORM\Index(name: "WDIDX_produit_IDAnnee", columns: ["IDAnnee"]),
//    new ORM\Index(name: "WDIDX_produit_LibFamille", columns: ["LibFamille"]),
//    new ORM\Index(name: "WDIDX_produit_QteMini", columns: ["QteMini"]),
//    new ORM\Index(name: "WDIDX_produit_PTVA_HT", columns: ["PTVA_HT"]),
//    new ORM\Index(name: "WDIDX_produit_GenCode", columns: ["GenCode"]),
//    new ORM\Index(name: "WDIDX_produit_SaisiPar", columns: ["SaisiPar"]),
//    new ORM\Index(name: "WDIDX_produit_proFamille", columns: ["proFamille"]),
//    new ORM\Index(name: "WDIDX_produit_IDSOCIETE", columns: ["IDSOCIETE"])
])]
class Produit
{

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File(
        maxSize: '1024k',
        maxSizeMessage: "La taille de l'image ne doit pas exceder 1 MO",
        extensions: ['jpg','jpeg','png'],
        extensionsMessage: 'Veuillez télécharger une image valide au format *.png, *.jpg, *.jpeg'
    )]
    private ?string $photo = null;

    #[ORM\Column(name: "PrixHT", type: "float", nullable: true, options: ["default" => "0.000000"])]
    private float $prixht;

    #[ORM\Column(name: "QteReappro", type: "integer", nullable: true)]
    private int $qtereappro = 0;

    #[ORM\Column(name: "QteMini", type: "integer", nullable: true)]
    private int $qtemini = 0;

    #[ORM\Column(name: "TauxTVA", type: "float", precision: 10, scale: 0, nullable: true)]
    private float $tauxtva = 0.0;

    #[ORM\Column(name: "GenCode", type: "string", length: 40, nullable: true)]
    private string $gencode;

    #[ORM\Column(name: "CodeBarre", type: "string", length: 40, nullable: false)]
    private string $codebarre;

    #[ORM\Column(name: "SaisiPar", type: "string", length: 40, nullable: false)]
    private string $saisipar;

    #[ORM\Column(name: "SaisiLe", type: "datetime", nullable: false)]
    private \DateTime $saisile;

    #[ORM\Column(name: "Observations", type: "text", nullable: false)]
    private string $observations;

    #[ORM\Column(name: "AIB", type: "float", precision: 10, scale: 0, nullable: false)]
    private float $aib = 0.0;

    #[ORM\Column(name: "stockActuel", type: "integer", nullable: false)]
    private int $stockactuel = 0;

    #[ORM\Id]
    #[ORM\CustomIdGenerator]
    #[ORM\Column(name: "ID", type: "string", nullable: false)]
    private string $id;

    #[ORM\Column(name: "proFamille", type: "string", length: 100, nullable: false)]
    private string $profamille;

    #[ORM\Column(name: "LibProd", type: "string", nullable: false)]
    private string $libprod;

    #[ORM\Column(name: "Description", type: "text", nullable: true)]
    private string $description;

    #[ORM\Column(name: "assujettiTVA", type: "boolean", nullable: false)]
    private bool $assujettitva = false;

    #[ORM\Column(name: "assujettiAIB", type: "boolean", nullable: false)]
    private bool $assujettiaib = false;

    #[ORM\Column(name: "IDSOCIETE", type: "integer", nullable: false)]
    private int $idsociete;

    #[ORM\Column(name: "IDAnnee", type: "integer", nullable: false)]
    private int $idannee;

    #[ORM\Column(name: "referenceInexistante", type: "boolean", nullable: false)]
    private bool $referenceinexistante = false;

    #[ORM\Column(name: "codebare", type: "integer", nullable: true)]
    private ?int $codebare;

    #[ORM\Column(name: "QTEAPPRO", type: "integer", nullable: false)]
    private int $qteappro = 0;

    #[ORM\Column(name: "QteVente", type: "integer", nullable: false)]
    private int $qtevente = 0;

    #[ORM\Column(name: "QteRebus", type: "string", length: 50, nullable: false)]
    private string $qterebus;

    #[ORM\Column(name: "LibFamille", type: "string", length: 75, nullable: false)]
    private string $libfamille;

    #[ORM\Column(name: "LibProdV", type: "string", length: 50, nullable: false)]
    private string $libprodv;

    #[ORM\Column(name: "PrixRevient", type: "integer", nullable: true)]
    private int $prixrevient = 0;

    #[ORM\Column(name: "Marge", type: "float", precision: 10, scale: 0, nullable: true)]
    private float $marge = 0.0;

    #[ORM\Column(name: "NIM_Facture_Preuve", type: "string", length: 50, nullable: true)]
    private string $nimFacturePreuve;

    #[ORM\Column(name: "Signature_Facure_Preuve", type: "string", length: 50, nullable: true)]
    private string $signatureFacurePreuve;

    #[ORM\Column(name: "PTVA_Marge", type: "boolean", nullable: false)]
    private bool $ptvaMarge = false;

    #[ORM\Column(name: "PTVA_HT", type: "boolean", nullable: false)]
    private bool $ptvaHt = false;

    #[ORM\Column(name: "estMatieresPremiere", type: "boolean", nullable: false)]
    private bool $estmatierespremiere = false;

    #[ORM\Column(name: "estStockable", type: "boolean", nullable: false)]
    private bool $eststockable = false;

    #[ORM\Column(name: "estdisponible", type: "boolean", nullable: false)]
    private bool $estdisponible = false;

   
    #[ORM\CustomIdGenerator]
    #[ORM\Column(name: "Reference", type: "string", length: 50, nullable: false)]
    private string $reference;

#[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(name: "CodeFamille", referencedColumnName: "CodeFamille")]
//    #[ORM\JoinColumns([new ORM\JoinColumn(name: "CodeFamille", referencedColumnName: "CodeFamille")])]
    private ?Famille $CodeFamille = null;

    #[ORM\OneToMany(targetEntity: PrixAAppliquer::class, mappedBy: 'ID')]
    private Collection $prixAAppliquers;

    public function __construct()
    {
        $this->prixAAppliquers = new ArrayCollection();
    }

//    #[ORM\ManyToOne(targetEntity: "Famille")]
//    #[ORM\JoinColumns([new ORM\JoinColumn(name: "CodeFamille", referencedColumnName: "CodeFamille")])]
//    private Famille $codefamille;

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPrixht(): ?float
    {
        return $this->prixht;
    }

    public function setPrixht(float $prixht): static
    {
        $this->prixht = $prixht;

        return $this;
    }

    public function getQtereappro(): ?int
    {
        return $this->qtereappro;
    }

    public function setQtereappro(int $qtereappro): static
    {
        $this->qtereappro = $qtereappro;

        return $this;
    }

    public function getQtemini(): ?int
    {
        return $this->qtemini;
    }

    public function setQtemini(int $qtemini): static
    {
        $this->qtemini = $qtemini;

        return $this;
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

    public function getGencode(): ?string
    {
        return $this->gencode;
    }

    public function setGencode(string $gencode): static
    {
        $this->gencode = $gencode;

        return $this;
    }

    public function getCodebarre(): ?string
    {
        return $this->codebarre;
    }

    public function setCodebarre(?string $codebarre): static
    {
        $this->codebarre = $codebarre;

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

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(string $observations): static
    {
        $this->observations = $observations;

        return $this;
    }

    public function getAib(): ?float
    {
        return $this->aib;
    }

    public function setAib(float $aib): static
    {
        $this->aib = $aib;

        return $this;
    }

    public function getStockactuel(): ?int
    {
        return $this->stockactuel;
    }

    public function setStockactuel(int $stockactuel): static
    {
        $this->stockactuel = $stockactuel;

        return $this;
    }



    public function getProfamille(): ?string
    {
        return $this->profamille;
    }

    public function setProfamille(string $profamille): static
    {
        $this->profamille = $profamille;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function isAssujettitva(): ?bool
    {
        return $this->assujettitva;
    }

    public function setAssujettitva(bool $assujettitva): static
    {
        $this->assujettitva = $assujettitva;

        return $this;
    }

    public function isAssujettiaib(): ?bool
    {
        return $this->assujettiaib;
    }

    public function setAssujettiaib(bool $assujettiaib): static
    {
        $this->assujettiaib = $assujettiaib;

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

    public function isReferenceinexistante(): ?bool
    {
        return $this->referenceinexistante;
    }

    public function setReferenceinexistante(bool $referenceinexistante): static
    {
        $this->referenceinexistante = $referenceinexistante;

        return $this;
    }

    public function getCodebare(): ?int
    {
        return $this->codebare;
    }

    public function setCodebare(int $codebare): static
    {
        $this->codebare = $codebare;

        return $this;
    }

    public function getQteappro(): ?int
    {
        return $this->qteappro;
    }

    public function setQteappro(int $qteappro): static
    {
        $this->qteappro = $qteappro;

        return $this;
    }

    public function getQtevente(): ?int
    {
        return $this->qtevente;
    }

    public function setQtevente(int $qtevente): static
    {
        $this->qtevente = $qtevente;

        return $this;
    }

    public function getQterebus(): ?string
    {
        return $this->qterebus;
    }

    public function setQterebus(string $qterebus): static
    {
        $this->qterebus = $qterebus;

        return $this;
    }

    public function getLibfamille(): ?string
    {
        return $this->libfamille;
    }

    public function setLibfamille(string $libfamille): static
    {
        $this->libfamille = $libfamille;

        return $this;
    }

    public function getLibprodv(): ?string
    {
        return $this->libprodv;
    }

    public function setLibprodv(string $libprodv): static
    {
        $this->libprodv = $libprodv;

        return $this;
    }

    public function getPrixrevient(): ?int
    {
        return $this->prixrevient;
    }

    public function setPrixrevient(int $prixrevient): static
    {
        $this->prixrevient = $prixrevient;

        return $this;
    }

    public function getMarge(): ?float
    {
        return $this->marge;
    }

    public function setMarge(float $marge): static
    {
        $this->marge = $marge;

        return $this;
    }

    public function getNimFacturePreuve(): ?string
    {
        return $this->nimFacturePreuve;
    }

    public function setNimFacturePreuve(string $nimFacturePreuve): static
    {
        $this->nimFacturePreuve = $nimFacturePreuve;

        return $this;
    }

    public function getSignatureFacurePreuve(): ?string
    {
        return $this->signatureFacurePreuve;
    }

    public function setSignatureFacurePreuve(string $signatureFacurePreuve): static
    {
        $this->signatureFacurePreuve = $signatureFacurePreuve;

        return $this;
    }

    public function isPtvaMarge(): ?bool
    {
        return $this->ptvaMarge;
    }

    public function setPtvaMarge(bool $ptvaMarge): static
    {
        $this->ptvaMarge = $ptvaMarge;

        return $this;
    }

    public function isPtvaHt(): ?bool
    {
        return $this->ptvaHt;
    }

    public function setPtvaHt(bool $ptvaHt): static
    {
        $this->ptvaHt = $ptvaHt;

        return $this;
    }

    public function isEstmatierespremiere(): ?bool
    {
        return $this->estmatierespremiere;
    }

    public function setEstmatierespremiere(bool $estmatierespremiere): static
    {
        $this->estmatierespremiere = $estmatierespremiere;

        return $this;
    }

    public function isEststockable(): ?bool
    {
        return $this->eststockable;
    }

    public function setEststockable(bool $eststockable): static
    {
        $this->eststockable = $eststockable;

        return $this;
    }

    public function isEstdisponible(): ?bool
    {
        return $this->estdisponible;
    }

    public function setEstdisponible(bool $estdisponible): static
    {
        $this->estdisponible = $estdisponible;

        return $this;
    }


    public function getCodeFamille(): ?Famille
    {
        return $this->CodeFamille;
    }

    public function setCodeFamille(?Famille $CodeFamille): static
    {
        $this->CodeFamille = $CodeFamille;

        return $this;
    }

    /**
     * @return Collection<int, PrixAAppliquer>
     */
    public function getPrixAAppliquers(): Collection
    {
        return $this->prixAAppliquers;
    }

    public function addPrixAAppliquer(PrixAAppliquer $prixAAppliquer): static
    {
        if (!$this->prixAAppliquers->contains($prixAAppliquer)) {
            $this->prixAAppliquers->add($prixAAppliquer);
            $prixAAppliquer->setID($this);
        }

        return $this;
    }

    public function removePrixAAppliquer(PrixAAppliquer $prixAAppliquer): static
    {
        if ($this->prixAAppliquers->removeElement($prixAAppliquer)) {
            // set the owning side to null (unless already changed)
            if ($prixAAppliquer->getID() === $this) {
                $prixAAppliquer->setID(null);
            }
        }

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

   

    public function getImageProduit(): ?string
    {
        $url = filter_var($this->photo, FILTER_VALIDATE_URL);
        // TODO: Implement __toString() method.
        if ($url) {
            return $this->photo;
        } else {
            return 'http://' . $_SERVER['SERVER_NAME'] . '/uploads/produit/' . $this->photo;
            
        }
    }

}
