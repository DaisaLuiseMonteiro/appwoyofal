<?php

namespace App\Entity;

class LogAchat
{
    private ?int $id = null;
    private ?\DateTime $dateHeure = null;
    private ?string $localisation = null;
    private ?string $adresseIp = null;
    private ?string $statut = null;
    private ?string $numero_compteur = null;  // Fixed: was 'numero_ompteur'
    private ?string $codeRecharge = null;
    private ?float $nbreKwt = null;
    private ?string $messageErreur = null;
    
    public function __construct(?int $id = null)
    {
        $this->id = $id;
        $this->dateHeure = new \DateTime(); // Initialiser avec la date actuelle
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
    
    public function getDateHeure(): ?\DateTime
    {
        return $this->dateHeure;
    }
    
    public function setDateHeure(?\DateTime $dateHeure): self
    {
        $this->dateHeure = $dateHeure;
        return $this;
    }
    
    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }
    
    public function setLocalisation(?string $localisation): self
    {
        $this->localisation = $localisation;
        return $this;
    }
    
    public function getAdresseIp(): ?string
    {
        return $this->adresseIp;
    }
    
    public function setAdresseIp(?string $adresseIp): self
    {
        $this->adresseIp = $adresseIp;
        return $this;
    }
    
    public function getStatut(): ?string
    {
        return $this->statut;
    }
    
    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
    
    public function getNumeroCompteur(): ?string
    {
        return $this->numero_compteur;  // Now matches the property name
    }
    
    public function setNumeroCompteur(?string $numero_compteur): self
    {
        $this->numero_compteur = $numero_compteur;  // Now matches the property name
        return $this;
    }
    
    public function getCodeRecharge(): ?string
    {
        return $this->codeRecharge;
    }
    
    public function setCodeRecharge(?string $codeRecharge): self
    {
        $this->codeRecharge = $codeRecharge;
        return $this;
    }
    
    public function getNbreKwt(): ?float
    {
        return $this->nbreKwt;
    }
    
    public function setNbreKwt(?float $nbreKwt): self
    {
        $this->nbreKwt = $nbreKwt;
        return $this;
    }
    
    public function getMessageErreur(): ?string
    {
        return $this->messageErreur;
    }
    
    public function setMessageErreur(?string $messageErreur): self
    {
        $this->messageErreur = $messageErreur;
        return $this;
    }
}