<?php

namespace App\Entity;

use App\Repository\PieceRepository;
use App\Traits\CommunTraits;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PieceRepository::class)]
class Piece
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $delevirePar = null;
    #[Assert\LessThan(propertyPath: 'dateExpiration',message:'Cette doit date doit être inférieure à {{ compared_value }}')]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateDelivrance = null;

    #[Assert\GreaterThan(propertyPath: 'dateDelivrance',
        message : 'La date fin ou date expiration ne peut pas être inferieur à la date de delivrance
{{ compared_value }}')]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateExpiration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagepiece = null;

    #[ORM\ManyToOne(inversedBy: 'pieces')]
    private ?TypePiece $typePiece = null;

    #[ORM\OneToOne(mappedBy: 'piece', cascade: ['persist', 'remove'])]
    private ?Client $client = null;

    use CommunTraits;
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDelevirePar(): ?string
    {
        return $this->delevirePar;
    }

    public function setDelevirePar(?string $delevirePar): static
    {
        $this->delevirePar = $delevirePar;

        return $this;
    }

    public function getDateDelivrance(): ?\DateTime
    {
        return $this->dateDelivrance;
    }

    public function setDateDelivrance(?\DateTime $dateDelivrance): static
    {
        $this->dateDelivrance = $dateDelivrance;

        return $this;
    }

    public function getDateExpiration(): ?\DateTime
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(?\DateTime $dateExpiration): static
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }

    public function getImagepiece(): ?string
    {
        return $this->imagepiece;
    }

    public function setImagepiece(?string $imagepiece): static
    {
        $this->imagepiece = $imagepiece;

        return $this;
    }

    public function getTypePiece(): ?TypePiece
    {
        return $this->typePiece;
    }

    public function setTypePiece(?TypePiece $typePiece): static
    {
        $this->typePiece = $typePiece;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        // unset the owning side of the relation if necessary
        if ($client === null && $this->client !== null) {
            $this->client->setPiece(null);
        }

        // set the owning side of the relation if necessary
        if ($client !== null && $client->getPiece() !== $this) {
            $client->setPiece($this);
        }

        $this->client = $client;

        return $this;
    }
}
