<?php

namespace App\Entity;

class Tranche
{
    private ?int $id;
    private string $nom;
    private float $min;
    private float $max;
    private float $prixKw;
    private int $ordre;
    
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
    
    public function getMin(): float
    {
        return $this->min;
    }
    
    public function setMin(float $min): self
    {
        $this->min = $min;
        return $this;
    }
    
    public function getMax(): float
    {
        return $this->max;
    }
    
    public function setMax(float $max): self
    {
        $this->max = $max;
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
    
    public function getOrdre(): int
    {
        return $this->ordre;
    }
    
    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;
        return $this;
    }
}