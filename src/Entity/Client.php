<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Table(name: 'Client')]
#[ORM\Entity(repositoryClass: FamilleRepository::class)]
#[ORM\UniqueConstraint(name: 'NumClient', columns: ['NumClient'])]

class Client
{
    #[ORM\Column(name: 'Civilite', type: 'string', length: 5, nullable: false)]
    private string $civilite;

    #[ORM\Column(name: 'NumClient', type: 'string', length: 50, nullable: false)]
    #[ORM\Id]
    #[ORM\CustomIdGenerator]
    private ?string $numclient = '0';

    #[ORM\Column(name: 'Societe', type: 'string', length: 40, nullable: false)]
    private string $societe;

    #[ORM\Column(name: 'Adresse', type: 'string', length: 150, nullable: false)]
    private string $adresse;

    #[ORM\Column(name: 'Pays', type: 'string', length: 40, nullable: false)]
    private string $pays;

    #[ORM\Column(name: 'NomClient', type: 'string', length: 70, nullable: false)]
    private string $nomclient;

    #[ORM\Column(name: 'Telephone', type: 'string', length: 20, nullable: false)]
    private string $telephone;

    #[ORM\Column(name: 'Fax', type: 'string', length: 20, nullable: false)]
    private string $fax;

    #[ORM\Column(name: 'Email', type: 'string', length: 40, nullable: false)]
    private string $email;

    #[ORM\Column(name: 'Ville', type: 'string', length: 40, nullable: false)]
    private string $ville;

    #[ORM\Column(name: 'Mobile', type: 'string', length: 20, nullable: false)]
    private string $mobile;

    #[ORM\Column(name: 'Observations', type: 'text', length: 0, nullable: false)]
    private string $observations;

    #[ORM\Column(name: 'Type', type: 'smallint', nullable: false)]
    private int $type = 0;

    #[ORM\Column(name: 'SaisiPar', type: 'string', length: 40, nullable: false)]
    private string $saisipar;

    #[ORM\Column(name: 'AuteurModif', type: 'string', length: 40, nullable: false)]
    private string $auteurmodif;

    #[ORM\Column(name: 'CodePostal', type: 'string', length: 5, nullable: false)]
    private string $codepostal;

    #[ORM\Column(name: 'SaisiLe', type: 'datetime', nullable: false)]
    private \DateTime $saisile;

    #[ORM\Column(name: 'DateModif', type: 'date', nullable: false)]
    private \DateTime $datemodif;

    #[ORM\Column(name: 'ExemptTVA', type: 'boolean', nullable: false)]
    private bool $exempttva = false;

    #[ORM\Column(name: 'LivreMemeAdresse', type: 'boolean', nullable: false)]
    private bool $livrememeadresse = false;

    #[ORM\Column(name: 'FactureMemeAdresse', type: 'boolean', nullable: false)]
    private bool $facturememeadresse = false;

    #[ORM\Column(name: 'Prenom', type: 'string', length: 50, nullable: false)]
    private string $prenom;

    #[ORM\Column(name: 'Photo', type: 'blob', length: 0, nullable: false)]
    private string $photo;

    #[ORM\Column(name: 'siteweb', type: 'string', length: 500, nullable: false)]
    private string $siteweb;

    #[ORM\Column(name: 'nomCompletTel', type: 'string', length: 100, nullable: false)]
    private string $nomcomplettel;

    #[ORM\Column(name: 'ifu', type: 'string', length: 50, nullable: false)]
    private string $ifu;

    #[ORM\Column(name: 'IDSOCIETE', type: 'integer', nullable: false)]
    private int $idsociete;

    #[ORM\Column(name: 'IDAnnee', type: 'integer', nullable: false)]
    private int $idannee;

    #[ORM\Column(name: 'tauxAIB', type: 'float', precision: 10, scale: 0, nullable: false)]
    private float $tauxaib = 0.0;

    #[ORM\Column(name: 'ClientEnAlerte', type: 'boolean', nullable: false)]
    private bool $clientenalerte = false;

    #[ORM\Column(name: 'MessageAlerte', type: 'string', length: 100, nullable: false)]
    private string $messagealerte;

    #[ORM\Column(name: 'LimiteCredit', type: 'integer', nullable: false)]
    private int $limitecredit = 0;

    #[ORM\Column(name: 'SoldeDisponible', type: 'integer', nullable: false)]
    private int $soldedisponible = 0;

    #[ORM\Column(name: 'IDEntreprise_Client', type: 'bigint', nullable: false)]
    private int $identrepriseClient;

    #[ORM\Column(name: 'IDProfession', type: 'bigint', nullable: false)]
    private int $idprofession;

    #[ORM\Column(name: 'IDNationalite', type: 'bigint', nullable: false)]
    private int $idnationalite;

    #[ORM\Column(name: 'NomJeuneFille', type: 'string', length: 50, nullable: false)]
    private string $nomjeunefille;

    #[ORM\Column(name: 'DateNaissance', type: 'date', nullable: false)]
    private \DateTime $datenaissance;

    #[ORM\Column(name: 'LieuNaissance', type: 'string', length: 50, nullable: false)]
    private string $lieunaissance;

    #[ORM\Column(name: 'Domicile', type: 'string', length: 50, nullable: false)]
    private string $domicile;

    #[ORM\Column(name: 'Occupation', type: 'string', length: 50, nullable: false)]
    private string $occupation;

    #[ORM\Column(name: 'tauxfidelite', type: 'integer', nullable: false)]
    private int $tauxfidelite = 0;

    #[ORM\Column(name: 'latitude', type: 'string', length: 50, nullable: false)]
    private string $latitude;

    #[ORM\Column(name: 'longitude', type: 'string', length: 50, nullable: false)]
    private string $longitude;

    
    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'NumClient')]
    private Collection $factures;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setNumClient($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getNumClient() === $this) {
                $facture->setNumClient(null);
            }
        }

        return $this;
    }


}
