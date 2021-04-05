<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photocover;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookprofil;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitchProfil;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $youtubeChannel;

    /**
     * @ORM\OneToMany(targetEntity=ParticipationT::class, mappedBy="userT")
     */
    private $participationTs;

    /**
     * @ORM\OneToMany(targetEntity=Tournoi::class, mappedBy="userT")
     */
    private $tournois;

    public function __construct()
    {
        $this->participationTs = new ArrayCollection();
        $this->tournois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPhotocover(): ?string
    {
        return $this->photocover;
    }

    public function setPhotocover(?string $photocover): self
    {
        $this->photocover = $photocover;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getFacebookprofil(): ?string
    {
        return $this->facebookprofil;
    }

    public function setFacebookprofil(?string $facebookprofil): self
    {
        $this->facebookprofil = $facebookprofil;

        return $this;
    }

    public function getTwitchProfil(): ?string
    {
        return $this->twitchProfil;
    }

    public function setTwitchProfil(?string $twitchProfil): self
    {
        $this->twitchProfil = $twitchProfil;

        return $this;
    }

    public function getYoutubeChannel(): ?string
    {
        return $this->youtubeChannel;
    }

    public function setYoutubeChannel(?string $youtubeChannel): self
    {
        $this->youtubeChannel = $youtubeChannel;

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
            $participationT->setUserT($this);
        }

        return $this;
    }

    public function removeParticipationT(ParticipationT $participationT): self
    {
        if ($this->participationTs->removeElement($participationT)) {
            // set the owning side to null (unless already changed)
            if ($participationT->getUserT() === $this) {
                $participationT->setUserT(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tournoi[]
     */
    public function getTournois(): Collection
    {
        return $this->tournois;
    }

    public function addTournoi(Tournoi $tournoi): self
    {
        if (!$this->tournois->contains($tournoi)) {
            $this->tournois[] = $tournoi;
            $tournoi->setUserT($this);
        }

        return $this;
    }

    public function removeTournoi(Tournoi $tournoi): self
    {
        if ($this->tournois->removeElement($tournoi)) {
            // set the owning side to null (unless already changed)
            if ($tournoi->getUserT() === $this) {
                $tournoi->setUserT(null);
            }
        }

        return $this;
    }
}
