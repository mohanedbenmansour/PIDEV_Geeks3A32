<?php

namespace App\Entity;

use App\Entity\Participation;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePub;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Lieu is required")
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Description is required")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\NotBlank(message="Date Event is required")
     */
    private $dateEvent;

    /**
     * @ORM\Column(type="integer")
     */
    private $userID;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(
     *    message = "The url '{{ value }}' is not a valid url",
     *    protocols = {"http", "https", "ftp"}
     * )
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="objectId")
     */
    private $participation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Name is required")
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Prix is required")GreaterThanOrEqual
     * @Assert\GreaterThanOrEqual(0)
     */
    private $prix;

    public function __construct()
    {
        $this->participation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setID(int $ID): self
    {
        $this->ID = $ID;

        return $this;
    }

    public function getDatePub(): ?\DateTimeInterface
    {
        return $this->datePub;
    }

    public function setDatePub(\DateTimeInterface $datePub): self
    {
        $this->datePub = $datePub;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

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

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(?\DateTimeInterface $dateEvent): self
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getUserID(): ?int
    {
        return $this->userID;
    }

    public function setUserID(int $userID): self
    {
        $this->userID = $userID;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection|Participation[]
     */
    public function getParticipation(): Collection
    {
        return $this->participation;
    }

    public function addParticipation(): self
    {
            /*$participation= new Participation();
            $participation->setObjectId($this->id);
            $participation->setType(1);
            $participation->setDate(new \DateTime());
            $participation->setUserId($uid);*/
        if (!$this->participation->contains($participation)) {
            $this->participation[] = $participation;
            $participation->setObjectId($this->id); 
            
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participation->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getObjectId() === $this) {
                $participation->setObjectId(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    
}
