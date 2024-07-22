<?php

namespace App\Entity;

use App\Repository\PrixAAppliquerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrixAAppliquerRepository::class)]
#[ORM\Table(name: 'Prix_A_Appliquer')]
#[ORM\UniqueConstraint(name: 'codeprix_applique', columns: ['codeprix_applique'])]
//#[ORM\Table(
//    name: 'Prix_A_Appliquer',
//    uniqueConstraints: [#[ORM\UniqueConstraint(name: 'codeprix_applique', columns: ['codeprix_applique'])]],
//    indexes: [
//        #[ORM\Index(name: 'WDIDX_Prix_A_Appliquer_codeservices', columns: ['codeservices'])],
//        #[ORM\Index(name: 'WDIDX_Prix_A_Appliquer_codeservice_ID', columns: ['codeservices', 'ID'])],
//        #[ORM\Index(name: 'WDIDX_Prix_A_Appliquer_ID', columns: ['ID'])]
//    ]
//)]
class PrixAAppliquer
{
    #[ORM\Column(name: 'codeprix_applique', type: 'string', length: 50, nullable: false)]
    #[ORM\Id]
    #[ORM\CustomIdGenerator]
    private ?string $codeprixApplique ;

    #[ORM\Column(name: 'codeservices', type: 'string', length: 50, nullable: false)]
    private string $codeservices ;


    #[ORM\Column(name: 'prix', type: 'integer', nullable: false)]
    private int $prix = 0;

    #[ORM\Column(name: 'stock', type: 'integer', nullable: false)]
    private int $stock = 0;

    #[ORM\Column(name: 'qteVendu', type: 'integer', nullable: false)]
    private int $qtevendu = 0;

    #[ORM\Column(name: 'qteapprovisionne', type: 'integer', nullable: false)]
    private int $qteapprovisionne = 0;

    #[ORM\Column(name: 'qteretourne', type: 'integer', nullable: false)]
    private int $qteretourne = 0;

    #[ORM\ManyToOne(inversedBy: 'prixAAppliquers')]
    #[ORM\JoinColumn(name: "ID", referencedColumnName: "ID")]
    private ?Produit $ID = null;

    #[ORM\ManyToOne(inversedBy: 'prixAAppliquers')]
    #[ORM\JoinColumn(name: "codeservices", referencedColumnName: "codeservices")]
    private ?Services $codeservice = null;

    #[ORM\OneToMany(targetEntity: LigneFac::class, mappedBy: 'codeprixApplique', cascade: ['persist'])]
    private Collection $ligneFacs;

    public function __construct()
    {
        $this->ligneFacs = new ArrayCollection();
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

    public function getCodeservices(): ?string
    {
        return $this->codeservices;
    }

    public function setCodeservices(string $codeservices): static
    {
        $this->codeservices = $codeservices;

        return $this;
    }

    public function getID(): ?Produit
    {
        return $this->ID;
    }

    public function setId(?Produit $id): static
    {
        $this->ID = $id;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getQtevendu(): ?int
    {
        return $this->qtevendu;
    }

    public function setQtevendu(int $qtevendu): static
    {
        $this->qtevendu = $qtevendu;

        return $this;
    }

    public function getQteapprovisionne(): ?int
    {
        return $this->qteapprovisionne;
    }

    public function setQteapprovisionne(int $qteapprovisionne): static
    {
        $this->qteapprovisionne = $qteapprovisionne;

        return $this;
    }

    public function getQteretourne(): ?int
    {
        return $this->qteretourne;
    }

    public function setQteretourne(int $qteretourne): static
    {
        $this->qteretourne = $qteretourne;

        return $this;
    }

    public function getCodeservice(): ?Services
    {
        return $this->codeservice;
    }

    public function setCodeservice(?Services $codeservice): static
    {
        $this->codeservice = $codeservice;

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
            $ligneFac->setCodeprixApplique($this);
        }

        return $this;
    }

    public function removeLigneFac(LigneFac $ligneFac): static
    {
        if ($this->ligneFacs->removeElement($ligneFac)) {
            // set the owning side to null (unless already changed)
            if ($ligneFac->getCodeprixApplique() === $this) {
                $ligneFac->setCodeprixApplique(null);
            }
        }

        return $this;
    }
}
