<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tabless")]
#[ORM\Index(name: "WDIDX_table_Libelle", columns: ["Libelle"])]
#[ORM\Index(name: "WDIDX_table_codeservices", columns: ["codeservices"])]
#[ORM\Index(name: "WDIDX_table_numerotable", columns: ["numerotable"])]
#[ORM\Index(name: "WDIDX_table_IDSOCIETE", columns: ["IDSOCIETE"])]
class Table
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "IDtable", type: "integer", nullable: false)]
    private int $idtable;

    #[ORM\Column(name: "numerotable", type: "string", length: 50, nullable: false)]
    private string $numerotable = '0';

    #[ORM\Column(name: "Libelle", type: "string", length: 50, nullable: false)]
    private string $libelle;

    #[ORM\Column(name: "IDSOCIETE", type: "integer", nullable: false)]
    private int $idsociete;

    #[ORM\Column(name: "supprimer", type: "boolean", nullable: false)]
    private bool $supprimer = false;

    #[ORM\ManyToOne(inversedBy: 'tables')]
    #[ORM\JoinColumn(name: "codeservices", referencedColumnName: "codeservices")]
    #[ORM\Column(name: "codeservices")]
    private ?Services $codeservices = null;

    /**
     * @var Collection<int, Facture>
     */
    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'IDtable')]
    private Collection $factures;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
    }

//    #[ORM\Column(name: "codeservices", type: "string", length: 50, nullable: false)]
//    private string $codeservices;

    // getters and setters

    public function getCodeservices(): ?Services
    {
        return $this->codeservices;
    }

    public function setCodeservices(?Services $codeservices): static
    {
        $this->codeservices = $codeservices;

        return $this;
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
            $facture->setIDtable($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getIDtable() === $this) {
                $facture->setIDtable(null);
            }
        }

        return $this;
    }
}

