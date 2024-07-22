<?php

namespace App\Entity;

use App\Repository\ImagesProduitsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesProduitsRepository::class)]
class ImagesProduits
{
    #[ORM\Id]
    #[ORM\CustomIdGenerator]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'imagesProduits')]
    #[ORM\Column(name: 'produitID')]
    #[ORM\JoinColumn(name: "ID", referencedColumnName: "ID")]
    private ?Produit $produit = null;

    public function getId(): ?string
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

    public function setId(?string $id): ImagesProduits
    {
        $this->id = $id;
        return $this;
    }
}
