<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get:infos"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get:infos"})
     * @Assert\NotBlank(message="The customerId can't not be blank")
     */
    private $customerId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get:infos"})
     * @Assert\NotBlank(message="The first name can't not be blank")
     * @Assert\Length(min=3, max=50)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get:infos"})
     * @Assert\NotBlank(message="The last name can't not be blank")
     * @Assert\Length(min=3, max=50)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get:infos"})
     * @Assert\NotBlank(message="The email can't not be blank")
     * @Assert\Length(min=3, max=100)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get:infos"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"get:infos"})
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerId(): ?Customer
    {
        return $this->customerId;
    }

    public function setCustomerId(?Customer $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
