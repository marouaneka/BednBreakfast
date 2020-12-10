<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ClFirstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ClFamilyName;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $ClCountry;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="ClientAuth", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="resClient")
     */
    private $reservations;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Room", inversedBy="clientlikes",cascade={"persist"})
     */
    //,orphanRemoval=true
    private $room;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="client")
     */
    private $comments;
    
    public function __toString() {
        return (string) $this->getClFamilyname();
    }

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClFirstName(): ?string
    {
        return $this->ClFirstName;
    }

    public function setClFirstName(?string $ClFirstName): self
    {
        $this->ClFirstName = $ClFirstName;

        return $this;
    }

    public function getClFamilyName(): ?string
    {
        return $this->ClFamilyName;
    }

    public function setClFamilyName(string $ClFamilyName): self
    {
        $this->ClFamilyName = $ClFamilyName;

        return $this;
    }

    public function getClCountry(): ?string
    {
        return $this->ClCountry;
    }

    public function setClCountry(string $ClCountry): self
    {
        $this->ClCountry = $ClCountry;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newClientAuth = $user === null ? null : $this;
        if ($newClientAuth !== $user->getClientAuth()) {
            $user->setClientAuth($newClientAuth);
        }

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setResClient($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getResClient() === $this) {
                $reservation->setResClient(null);
            }
        }

        return $this;
    }
    
     /**
     * @return Collection|Room[]
     */
    public function getRoom(): Collection
    {
        return $this->room;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->room->contains($room)) {
            $this->room[] = $room;
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->room->contains($room)) {
            $this->room->removeElement($room);
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setClient($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getClient() === $this) {
                $comment->setClient(null);
            }
        }

        return $this;
    }
}
