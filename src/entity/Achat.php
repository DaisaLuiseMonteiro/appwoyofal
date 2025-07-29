<?php

namespace App\Entity;

class Achat
{
    private ?int $id;
    private string $reference;
    private string $codeRecharge;
    private string $numeroCompteur;
    private float $montant;
    private float $nbreKwt;
    private string $tranche;
    private float $prixKw;
    private \DateTime $dateAchat;
    private string $statut;
    private string $clientNom;
    
    public function __construct(?int $id = null)
    {
        $this->id = $id;
        $this->dateAchat = new \DateTime();
        $this->statut = 'success';
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
    
    public function getReference(): string
    {
        return $this->reference;
    }
    
    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }
    
    public function getCodeRecharge(): string
    {
        return $this->codeRecharge;
    }
    
    public function setCodeRecharge(string $codeRecharge): self
    {
        $this->codeRecharge = $codeRecharge;
        return $this;
    }
    
    public function getNumeroCompteur(): string
    {
        return $this->numeroCompteur;
    }
    
    public function setNumeroCompteur(string $numeroCompteur): self
    {
        $this->numeroCompteur = $numeroCompteur;
        return $this;
    }
    
    public function getMontant(): float
    {
        return $this->montant;
    }
    
    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }
    
    public function getNbreKwt(): float
    {
        return $this->nbreKwt;
    }
    
    public function setNbreKwt(float $nbreKwt): self
    {
        $this->nbreKwt = $nbreKwt;
        return $this;
    }
    
    public function getTranche(): string
    {
        return $this->tranche;
    }
    
    public function setTranche(string $tranche): self
    {
        $this->tranche = $tranche;
        return $this;
    }
    
    public function getPrixKw(): float
    {
        return $this->prixKw;
    }
    
    public function setPrixKw(float $prixKw): self
    {
        $this->prixKw = $prixKw;
        return $this;
    }
    
    public function getDateAchat(): \DateTime
    {
        return $this->dateAchat;
    }
    
    public function setDateAchat(\DateTime $dateAchat): self
    {
        $this->dateAchat = $dateAchat;
        return $this;
    }
    
    public function getStatut(): string
    {
        return $this->statut;
    }
    
    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
    
    public function getClientNom(): string
    {
        return $this->clientNom;
    }
    
    public function setClientNom(string $clientNom): self
    {
        $this->clientNom = $clientNom;
        return $this;
    }
}