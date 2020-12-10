<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $DateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $DateFin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="reservations")
     */
    private $resClient;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="reservations")
     */
    private $resRoom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->DateDebut;
    }

    public function setDateDebut(\DateTimeInterface $DateDebut): self
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->DateFin;
    }

    public function setDateFin(\DateTimeInterface $DateFin): self
    {
        $this->DateFin = $DateFin;

        return $this;
    }

    public function getResClient(): ?Client
    {
        return $this->resClient;
    }

    public function setResClient(?Client $resClient): self
    {
        $this->resClient = $resClient;

        return $this;
    }

    public function getResRoom(): ?Room
    {
        return $this->resRoom;
    }

    public function setResRoom(?Room $resRoom): self
    {
        $this->resRoom = $resRoom;

        return $this;
    }
}
