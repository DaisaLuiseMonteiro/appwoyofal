<?php

namespace App\Entity;

class Compteur
{
    private ?int $id;
    private string $numero;
    private int $clientId;
    private bool $actif;
    private \DateTime $dateCreation;
    
    public function __construct(?int $id = null)
    {
        $this->id = $id;
        $this->actif = true;
        $this->dateCreation = new \DateTime();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }
    
    public function getNumero(): string
    {
        return $this->numero;
    }
    
    public function setNumero(string $numero): self
    {
        $this->numero = $numero;
        return $this;
    }
    
    public function getClientId(): int
    {
        return $this->clientId;
    }
    
    public function setClientId(int $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }
    
    public function isActif(): bool
    {
        return $this->actif;
    }
    
    public function setActif(bool $actif): self
    {
        $this->actif = $actif;
        return $this;
    }
    
    public function getDateCreation(): \DateTime
    {
        return $this->dateCreation;
    }
    
    public function setDateCreation(\DateTime $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }
}