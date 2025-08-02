<?php

namespace App\Entity;

use App\Repository\ImagesProduitsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesProduitsRepository::class)]
class ImagesProduits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;


//    #[ORM\Column(name: 'produit_id')]
    #[ORM\JoinColumn(name: "produit_id", referencedColumnName: "ID")]
    #[ORM\ManyToOne(inversedBy: 'imagesProduits')]
    private ?Produit $produit = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getImageProduit(): ?string
    {
        $url = filter_var($this->url, FILTER_VALIDATE_URL);
        // TODO: Implement __toString() method.
        if ($url) {
            return $this->url;
        } else {
            return 'https://' . $_SERVER['SERVER_NAME'] . '/uploads/produit/' . $this->url;

        }
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function __toString(): string
    {
        $url = filter_var($this->url, FILTER_VALIDATE_URL);
        // TODO: Implement __toString() method.
        if ($url) {
            return $this->url;
        } else {
//            return 'https://' . $_SERVER['SERVER_NAME'] . '/uploads/produit/' . $this->url;
                return 'https://gestion.complexehotelierlabonte.com/uploads/produit/'. $this->url;
        }
    }

    public function getImage(): ?string
    {
        $url = filter_var($this->url, FILTER_VALIDATE_URL);
        // TODO: Implement __toString() method.
        if ($url) {
            return $this->url;
        } else {
            return 'https://gestion.complexehotelierlabonte.com/uploads/produit/'. $this->url;

        }
    }

}
