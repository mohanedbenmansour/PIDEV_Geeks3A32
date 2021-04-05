<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=TournoiRepository::class)
 */
class Tournoi
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("tournois")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Champ olbigatoire")
     * @Groups("tournois")
     */
    private $Nom;

    /**
     * @ORM\Column(type="text", length=500)
     * @Assert\NotBlank(message="Champ olbigatoire")
     * @Groups("tournois")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Champ olbigatoire")
     * @Groups("tournois")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)

     * @Groups("tournois")
     */
    private $image;

    /**
     * @ORM\Column(type="date")
     * @Groups("tournois")
     */
    private $date_tournoi;

    /**
     * @ORM\Column(type="date")
     * @Groups("tournois")
     */
    private $date_publication;

    /**
     * @ORM\Column(type="integer")
     */
    private $Nb_max;



    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="tournois")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Lien_youtube;

    /**
     * @ORM\Column(type="integer")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=ParticipationT::class, mappedBy="tournoi")
     */
    private $participationTs;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="tournois")
     */
    private $userT;







    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateTournoi(): ?\DateTimeInterface
    {
        return $this->date_tournoi;
    }

    public function setDateTournoi(\DateTimeInterface $date_tournoi): self
    {
        $this->date_tournoi = $date_tournoi;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function getNbMax(): ?int
    {
        return $this->Nb_max;
    }

    public function setNbMax(int $Nb_max): self
    {
        $this->Nb_max = $Nb_max;

        return $this;
    }
    public function __construct()
    {
        $this->date_publication = new \DateTime('now');
        $this->Nom_user = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->active=1;
        $this->user = new ArrayCollection();
        $this->Participants = new ArrayCollection();
        $this->Participant = new ArrayCollection();
        $this->participationTournois = new ArrayCollection();
        $this->participationTs = new ArrayCollection();
    }


    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }


    public function getLienYoutube(): ?string
    {
        return $this->Lien_youtube;
    }

    public function setLienYoutube(string $Lien_youtube): self
    {
        $this->Lien_youtube = $Lien_youtube;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|ParticipationT[]
     */
    public function getParticipationTs(): Collection
    {
        return $this->participationTs;
    }

    public function addParticipationT(ParticipationT $participationT): self
    {
        if (!$this->participationTs->contains($participationT)) {
            $this->participationTs[] = $participationT;
            $participationT->setTournoi($this);
        }

        return $this;
    }

    public function removeParticipationT(ParticipationT $participationT): self
    {
        if ($this->participationTs->removeElement($participationT)) {
            // set the owning side to null (unless already changed)
            if ($participationT->getTournoi() === $this) {
                $participationT->setTournoi(null);
            }
        }

        return $this;
    }

    public function getUserT(): ?Utilisateur
    {
        return $this->userT;
    }

    public function setUserT(?Utilisateur $userT): self
    {
        $this->userT = $userT;

        return $this;
    }











}
