<?php

namespace App\Entity;

use App\Repository\ParticipationTRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipationTRepository::class)
 */
class ParticipationT
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tournoi::class, inversedBy="participationTs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournoi;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="participationTs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userT;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournoi(): ?Tournoi
    {
        return $this->tournoi;
    }

    public function setTournoi(?Tournoi $tournoi): self
    {
        $this->tournoi = $tournoi;

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
