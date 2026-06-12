<?php
declare(strict_types=1);

namespace PharmaApp\Entity;

use DateTimeImmutable;
use PharmaApp\Enum\BatchStatus;

class StockBatch {
    private ?int $id = null;
    private int $medicamentId;
    private string $numeroLot;
    private int $quantite;
    private DateTimeImmutable $datePeremption;
    private BatchStatus $statut;
    private ?string $medicamentNom = null;

    public function getId(): ?int {
        return $this->id;
    }
    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }
    
    public function getMedicamentId(): int {
        return $this->medicamentId;
    }
    public function setMedicamentId(int $medicamentId): self {
        $this->medicamentId = $medicamentId;
        return $this;
    }

    public function getNumeroLot(): string {
        return $this->numeroLot;
    }
    public function setNumeroLot(string $numeroLot): self {
        $this->numeroLot = $numeroLot;
        return $this;
    }

    public function getQuantite(): int {
        return $this->quantite;
    }
    public function setQuantite(int $quantite): self {
        $this->quantite = $quantite;
        return $this;
    }

    public function getDatePeremption(): DateTimeImmutable {
        return $this->datePeremption;
    }
    public function setDatePeremption(DateTimeImmutable $datePeremption): self {
        $this->datePeremption = $datePeremption;
        return $this;
    }

    
    public function setStatut($statut): self {
        if (is_string($statut)) {
            $this->statut = BatchStatus::from($statut);
        } else {
            $this->statut = $statut;
        }
        return $this;
    }

   
    public function getStatut(): BatchStatus {
        if (is_string($this->statut)) {
            return BatchStatus::from($this->statut);
        }
        return $this->statut;
    }

    public function getMedicamentNom(): ?string {
        return $this->medicamentNom; 
    }
    public function setMedicamentNom(?string $nom): self { 
        $this->medicamentNom = $nom;
        return $this; 
    }

    public function getDaysLeft(): int {
        $now = new DateTimeImmutable('today');
        $interval = $now->diff($this->datePeremption);
        return (int)$interval->format('%r%a');
    }

    public function getCriticiteColor(): string {
    if ($this->statut->value === BatchStatus::EXPIRED || $this->quantite <= 0) {
        return 'secondary';
    }
    
    $days = $this->getDaysLeft();
    if ($days <= 30) return 'danger';  //alert rouge  
    if ($days <= 90) return 'warning';  //alert orange
    
    return 'success'; 
}

    public function expiresNextMonth(): bool {
        if ($this->statut === BatchStatus::EXPIRED || $this->getQuantite() <= 0) {
            return false;
        }
        $days = $this->getDaysLeft();
        return ($days > 0 && $days <= 30);
    }
}