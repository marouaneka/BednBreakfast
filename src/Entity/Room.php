<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 * @Vich\Uploadable
 */
class Room
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $capacity;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $superficy;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Owner", inversedBy="room",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Region", inversedBy="room",cascade={"persist"})
     * 
     */
    private $regions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="resRoom")
     */
    private $reservations;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Client", mappedBy="room",cascade={"persist"})
     *
     */
    private $clientlikes;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="room")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @Vich\UploadableField(mapping="room", fileNameProperty="imageName")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $imageUpdatesAt;


    public function __toString() {
        return (string) $this->getSummary();
    }
    
    public function __construct()
    {
        $this->regions = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->imageUpdatesAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getSuperficy(): ?float
    {
        return $this->superficy;
    }

    public function setSuperficy(?float $superficy): self
    {
        $this->superficy = $superficy;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function setOwner(?Owner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Region[]
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function addRegion(Region $region): self
    {
        if (!$this->regions->contains($region)) {
            $this->regions[] = $region;
            $region->addRoom($this);
        }

        return $this;
    }

    public function removeRegion(Region $region): self
    {
        if ($this->regions->contains($region)) {
            $this->regions->removeElement($region);
            $region->removeRoom($this);
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
            $reservation->setResRoom($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getResRoom() === $this) {
                $reservation->setResRoom(null);
            }
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
            $comment->setRoom($this);
        }
        
        return $this;
    }
    
    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getRoom() === $this) {
                $comment->setResRoom(null);
            }
        }
        
        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageUpdatesAt(): ?\DateTimeInterface
    {
        return $this->imageUpdatesAt;
    }

    public function setImageUpdatesAt(?\DateTimeInterface $imageUpdatesAt): self
    {
        $this->imageUpdatesAt = $imageUpdatesAt;

        return $this;
    }

    /**
    * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
    * of 'UploadedFile' is injected into this setter to trigger the update. If this
    * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
        // It is required that at least one field changes if you are using doctrine
        // otherwise the event listeners won't be called and the file is lost
        $this->imageUpdatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
}
