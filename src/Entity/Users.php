<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Users")]
class Users
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 50)]
    private $login;

    #[ORM\Column(type: "string", length: 255)]
    private $password;

    #[ORM\Column(type: "string", length: 50)]
    private $nom;

    #[ORM\Column(type: "string", length: 50)]
    private $prenom;

    #[ORM\Column(type: "string", length: 50)]
    private $email;

    #[ORM\Column(type: "integer")]
    private $idRole;

    #[ORM\Column(type: "integer")]
    private $idSociete;

    #[ORM\Column(type: "integer")]
    private $idAnnee;

    #[ORM\Column(type: "boolean", options: ["default" => 0])]
    private $supprimer;

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getIdRole(): ?int
    {
        return $this->idRole;
    }

    public function setIdRole(int $idRole): static
    {
        $this->idRole = $idRole;

        return $this;
    }

    public function getIdSociete(): ?int
    {
        return $this->idSociete;
    }

    public function setIdSociete(int $idSociete): static
    {
        $this->idSociete = $idSociete;

        return $this;
    }

    public function getIdAnnee(): ?int
    {
        return $this->idAnnee;
    }

    public function setIdAnnee(int $idAnnee): static
    {
        $this->idAnnee = $idAnnee;

        return $this;
    }

    public function isSupprimer(): ?bool
    {
        return $this->supprimer;
    }

    public function setSupprimer(bool $supprimer): static
    {
        $this->supprimer = $supprimer;

        return $this;
    }
}
