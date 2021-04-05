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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Name is required")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Date Debut is required")
     * @Assert\GreaterThan("today")
     */
    private $dateDebut;
    
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Date Fin is required")
     * @Assert\GreaterThan(propertyPath="dateDebut")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Lieu is required")
     */
    private $lieu;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Description is required")
     */
    private $description;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $nbParticipants;
    
    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Prix is required")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez charger une image")
     * @Assert\File(mimeTypes={ "image/png","image/jpeg"})
     */
    private $img;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePub;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(
     *    message = "The url '{{ value }}' is not a valid url",
     *    protocols = {"http", "https", "ftp"}
     * )
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="event", orphanRemoval=true)
     */
    private $participations;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieEvent::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Category;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=CommentEvent::class, mappedBy="Event")
     */
    private $commentEvents;
    

    public function __construct()
    {
        $this->participations = new ArrayCollection();
        $this->date = new ArrayCollection();
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


    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getNbParticipants(): ?int
    {
        return $this->nbParticipants;
    }

    public function setNbParticipants(int $nbParticipants): self
    {
        $this->nbParticipants = $nbParticipants;

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

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Participation[]
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations[] = $participation;
            $participation->setEvent($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getEvent() === $this) {
                $participation->setEvent(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?CategorieEvent
    {
        return $this->Category;
    }

    public function setCategory(?CategorieEvent $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function getUser(): ?Utilisateur
    {
        return $this->user;
    }

    public function setUser(?Utilisateur $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|CommentEvent[]
     */
    public function getCommentsEvents(): Collection
    {
        return $this->commentEvents;
    }

    public function addCommentEvents(CommentEvent $commentEvents): self
    {
        if (!$this->commentEvents->contains($commentEvents)) {
            $this->commentEvents[] = $commentEvents;
            $commentEvents->setEvent($this);
        }

        return $this;
    }

    public function removeCommentEvents(CommentEvent $commentEvents): self
    {
        if ($this->commentEvents->removeElement($commentEvents)) {
            // set the owning side to null (unless already changed)
            if ($commentEvents->getEvent() === $this) {
                $commentEvents->setEvent(null);
            }
        }
        return $this;
    }

    
}

