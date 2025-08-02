<?php

namespace App\Entity;

use App\Repository\SequenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SequenceRepository::class)]
class Sequence
{

    #[ORM\Id]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prefixe = null;

    #[ORM\Column]
    private ?int $derniereSequence = 0;

    public function __construct(string $prefixe)
    {
        $this->prefixe = $prefixe;
    }

    public function getPrefixe(): ?string
    {
        return $this->prefixe;
    }

//    public function setPrefixe(?string $prefixe): static
//    {
//        $this->prefixe = $prefixe;
//
//        return $this;
//    }

    public function getDerniereSequence(): ?int
    {
        return $this->derniereSequence;
    }

    public function setDerniereSequence(int $derniereSequence): static
    {
        $this->derniereSequence = $derniereSequence;

        return $this;
    }
}
