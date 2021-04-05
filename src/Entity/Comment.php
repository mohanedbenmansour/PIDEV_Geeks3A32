<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="contenue", type="string", length=200, nullable=false)
     */
    private $contenue;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $idUser;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $idpost;



    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="date", length=200, nullable=false)
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    /**
     * @param string $contenue
     */
    public function setContenue(string $contenue): void
    {
        $this->contenue = $contenue;
    }

    /**
     * @return mixed
     */
    public function getIdUser() : ?Utilisateur
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser): void
    {
        $this->idUser = $idUser;
    }

    /**
     * @return mixed
     */
    public function getIdpost() :?Post
    {
        return $this->idpost;
    }

    /**
     * @param mixed $idpost
     */
    public function setIdpost($idpost): void
    {
        $this->idpost = $idpost;
    }




    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }




}
