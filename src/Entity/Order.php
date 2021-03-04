<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\Column(type="text")
     */
    private $orderDetail;

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

    public function getOrderDetail(): ?string
    {
        return $this->orderDetail;
    }

    public function setOrderDetail(string $orderDetail): self
    {
        $this->orderDetail = $orderDetail;

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
}
