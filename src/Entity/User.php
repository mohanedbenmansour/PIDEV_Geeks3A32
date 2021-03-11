<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom_u;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Prenom_u;

    /**
     * @ORM\ManyToMany(targetEntity=Tournoi::class, mappedBy="Participant")
     * @ORM\JoinColumn(nullable=false)

     */
    private $tournois;

    public function __construct()
    {
        $this->tournois = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomU(): ?string
    {
        return $this->Nom_u;
    }

    public function setNomU(string $Nom_u): self
    {
        $this->Nom_u = $Nom_u;

        return $this;
    }

    public function getPrenomU(): ?string
    {
        return $this->Prenom_u;
    }

    public function setPrenomU(string $Prenom_u): self
    {
        $this->Prenom_u = $Prenom_u;

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
            $tournoi->addParticipant($this);
        }

        return $this;
    }

    public function removeTournoi(Tournoi $tournoi): self
    {
        if ($this->tournois->removeElement($tournoi)) {
            $tournoi->removeParticipant($this);
        }

        return $this;
    }
    public function __toString(){
        // to show the name of the Category in the select
        return $this->User;
        // to show the id of the Category in the select
        // return $this->id;
    }



}
