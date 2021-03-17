<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="userAdress is required")
     */
    private $userAdress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $checkoutDate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="price is required")
     * @Assert\GreaterThan(0)
     */
    private $userPhone;

    /**
     * @ORM\Column(type="boolean", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zipcode;

    /**
     * @ORM\OneToMany(targetEntity=OrderDetail::class, mappedBy="Orderr")
     */
    private $orderdetail;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalPrice;

    public function __construct()
    {
        $this->orderdetail = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }




    public function getUserAdress(): ?string
    {
        return $this->userAdress;
    }

    public function setUserAdress(string $userAdress): self
    {
        $this->userAdress = $userAdress;

        return $this;
    }

    public function getCheckoutDate(): ?string
    {
        return $this->checkoutDate;
    }

    public function setCheckoutDate(string $checkoutDate): self
    {
        $this->checkoutDate = $checkoutDate;

        return $this;
    }

    public function getUserPhone(): ?int
    {
        return $this->userPhone;
    }

    public function setUserPhone(int $userPhone): self
    {
        $this->userPhone = $userPhone;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function addOrderdetail(OrderDetail $orderdetail): self
    {
        if (!$this->orderdetail->contains($orderdetail)) {
            $this->orderdetail[] = $orderdetail;
            $orderdetail->setOrderr($this);
        }

        return $this;
    }

    public function removeOrderdetail(OrderDetail $orderdetail): self
    {
        if ($this->orderdetail->removeElement($orderdetail)) {
            // set the owning side to null (unless already changed)
            if ($orderdetail->getOrderr() === $this) {
                $orderdetail->setOrderr(null);
            }
        }

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }
    /**
     * @return Collection|OrderDetail[]
     */
    public function getOrderdetail(): Collection
    {
        return $this->orderdetail;
    }
}
