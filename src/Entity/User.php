<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_8D93D649F85E0677", columns={"username"})})
 * @ORM\Entity
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=180, nullable=false)
     */
    private $username;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json", nullable=false)
     */
    private $roles;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_unite", type="string", length=30, nullable=true)
     */
    private $nomUnite;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fin_est_membre", type="date", nullable=true)
     */
    private $finEstMembre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_equipe", type="string", length=30, nullable=true)
     */
    private $nomEquipe;

    /**
     * @var string|null
     *
     * @ORM\Column(name="info_equipe", type="string", length=254, nullable=true)
     */
    private $infoEquipe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNomUnite(): ?string
    {
        return $this->nomUnite;
    }

    public function setNomUnite(?string $nomUnite): self
    {
        $this->nomUnite = $nomUnite;

        return $this;
    }

    public function getFinEstMembre(): ?\DateTimeInterface
    {
        return $this->finEstMembre;
    }

    public function setFinEstMembre(?\DateTimeInterface $finEstMembre): self
    {
        $this->finEstMembre = $finEstMembre;

        return $this;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nomEquipe;
    }

    public function setNomEquipe(?string $nomEquipe): self
    {
        $this->nomEquipe = $nomEquipe;

        return $this;
    }

    public function getInfoEquipe(): ?string
    {
        return $this->infoEquipe;
    }

    public function setInfoEquipe(?string $infoEquipe): self
    {
        $this->infoEquipe = $infoEquipe;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getUsername();
    }
}
