<?php

namespace App\Entity;

class Client
{
    private ?int $id;
    private string $nom;
    private string $prenom;
    private string $telephone;
    private string $adresse;
    
    public function __construct(?int $id = null)
    {
        $this->id = $id;
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
    
    public function getNom(): string
    {
        return $this->nom;
    }
    
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }
    
    public function getPrenom(): string
    {
        return $this->prenom;
    }
    
    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }
    
    public function getTelephone(): string
    {
        return $this->telephone;
    }
    
    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }
    
    public function getAdresse(): string
    {
        return $this->adresse;
    }
    
    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }
    
    public function getNomComplet(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }
}