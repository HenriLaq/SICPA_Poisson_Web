<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CourbePrevisionnelle
 *
 * @ORM\Table(name="Courbe_Previsionnelle")
 * @ORM\Entity
 */
class CourbePrevisionnelle
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_Courbe_Previsionnelle", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCourbePrevisionnelle;

    /**
     * @var int
     *
     * @ORM\Column(name="id_Lot", type="integer", nullable=false)
     */
    private $idLot;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="Date_Point", type="date", nullable=true)
     */
    private $datePoint;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Temperature", type="float", precision=10, scale=0, nullable=true)
     */
    private $temperature;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Poids_Prevu", type="float", precision=10, scale=0, nullable=true)
     */
    private $poidsPrevu;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Poids_Reel", type="float", precision=10, scale=0, nullable=true)
     */
    private $poidsReel;

    public function getIdCourbePrevisionnelle(): ?int
    {
        return $this->idCourbePrevisionnelle;
    }

    public function getIdLot(): ?int
    {
        return $this->idLot;
    }

    public function setIdLot(int $idLot): self
    {
        $this->idLot = $idLot;

        return $this;
    }

    public function getDatePoint(): ?\DateTimeInterface
    {
        return $this->datePoint;
    }

    public function setDatePoint(?\DateTimeInterface $datePoint): self
    {
        $this->datePoint = $datePoint;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getPoidsPrevu(): ?float
    {
        return $this->poidsPrevu;
    }

    public function setPoidsPrevu(?float $poidsPrevu): self
    {
        $this->poidsPrevu = $poidsPrevu;

        return $this;
    }

    public function getPoidsReel(): ?float
    {
        return $this->poidsReel;
    }

    public function setPoidsReel(?float $poidsReel): self
    {
        $this->poidsReel = $poidsReel;

        return $this;
    }


}
