<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "services")]
#[ORM\UniqueConstraint(name: "codeservices", columns: ["codeservices"])]
class Services
{
//    #[ORM\Id]
//    #[ORM\Column(type: "bigint")]
//    private $idService;

    #[ORM\Id]
   #[ORM\CustomIdGenerator]
    #[ORM\Column(name:"codeservices" ,type: "string", length: 50)]
    private ?string $codeService;

    #[ORM\Column(name:"libe" ,type: "string", length: 255)]
    private ?string $libelle;



    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(targetEntity: PrixAAppliquer::class, mappedBy: 'codeservice')]
    private Collection $prixAAppliquers;

    public function __construct()
    {
        $this->prixAAppliquers = new ArrayCollection();
    }


    // Getters and Setters

//    public function getIdService(): ?string
//    {
//        return $this->idService;
//    }
//
    public function getCodeService(): ?string
    {
        return $this->codeService;
    }

    public function setCodeService(string $codeService): static
    {
        $this->codeService = $codeService;

        return $this;
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


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

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
            $prixAAppliquer->setCodeservice($this);
        }

        return $this;
    }

    public function removePrixAAppliquer(PrixAAppliquer $prixAAppliquer): static
    {
        if ($this->prixAAppliquers->removeElement($prixAAppliquer)) {
            // set the owning side to null (unless already changed)
            if ($prixAAppliquer->getCodeservice() === $this) {
                $prixAAppliquer->setCodeservice(null);
            }
        }

        return $this;
    }

    public function getImageService(): ?string
    {
        return 'http://' . $_SERVER['SERVER_NAME'] . '/uploads/services/' . $this->image;
    }

}
