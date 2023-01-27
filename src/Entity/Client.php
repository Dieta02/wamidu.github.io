<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Type(type: 'alpha', message: 'Vous ne devez entrer que des lettres !')]
    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[Assert\Type(type: 'alpha', message: 'Vous ne devez entrer que des lettres !')]
    #[ORM\Column(length: 100)]
    private ?string $surname = null;

    //#[Assert\Unique(message: 'Cette adresse email à déja été enregistré')]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 8)]
    private ?string $codeUser = null;

    //#[Assert\Length(min: 4, max: 4)]
    #[ORM\Column(nullable: true)]
    private ?int $codePin = null;

    //#[Assert\Length(min: 4, max: 4)]
    #[ORM\Column]
    private ?int $ticket = null;


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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

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

    public function getCodeUser(): ?string
    {
        return $this->codeUser;
    }

    public function setCodeUser(string $codeUser): self
    {
        $this->codeUser = $codeUser;

        return $this;
    }

    public function getCodePin(): ?int
    {
        return $this->codePin;
    }

    public function setCodePin(?int $codePin): self
    {
        $this->codePin = $codePin;

        return $this;
    }

    public function getTicket(): ?int
    {
        return $this->ticket;
    }

    public function setTicket(?int $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }
}