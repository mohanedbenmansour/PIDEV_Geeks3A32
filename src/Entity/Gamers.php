<?php

namespace App\Entity;

use App\Repository\GamersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GamersRepository::class)
 */
class Gamers
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $playerPhoto;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gamerTwitch;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gamerFacebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gamerTeam;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;

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

    public function getPlayerPhoto(): ?string
    {
        return $this->playerPhoto;
    }

    public function setPlayerPhoto(?string $playerPhoto): self
    {
        $this->playerPhoto = $playerPhoto;

        return $this;
    }

    public function getGamerTwitch(): ?string
    {
        return $this->gamerTwitch;
    }

    public function setGamerTwitch(string $gamerTwitch): self
    {
        $this->gamerTwitch = $gamerTwitch;

        return $this;
    }

    public function getGamerFacebook(): ?string
    {
        return $this->gamerFacebook;
    }

    public function setGamerFacebook(string $gamerFacebook): self
    {
        $this->gamerFacebook = $gamerFacebook;

        return $this;
    }

    public function getGamerTeam(): ?string
    {
        return $this->gamerTeam;
    }

    public function setGamerTeam(?string $gamerTeam): self
    {
        $this->gamerTeam = $gamerTeam;

        return $this;
    }
}
