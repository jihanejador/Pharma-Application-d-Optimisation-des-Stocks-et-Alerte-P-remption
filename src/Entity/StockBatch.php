<?php
declare(strict_types=1);

namespace PharmaApp\Entity;

use DateTimeImmutable;
use PharmaApp\Enum\BatchStatus;

class StockBatch{
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
    
    public function getMedicamentId(): int{
        return $this->medicament;
    }
    public Function setMedicamentId(int $medicamentId): self {
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

    public function getQuantite(): int{
        return $ths->quantite;
    }
    public function setQuantite(int $quantite): self{
        $this->quantite = $quantite;
        return $this;
    }

    public function getDatePeremption(): DateTimeImmutable{
        return $this->datePeremption;
    }
    public function setDatePeremption(DateTimeImmutable $datePeremption): self{
        $this->datePeremption = $datePeremption;
        return $this;
    }

    public function getStatut(): BatchStatus { 
        return $this->statut; 
    }
    public function setStatut(BatchStatus $statut): self {
        $this->statut = $statut; 
        return $this;
    }

    public function getMedicamentNom(): ?string {
        return $this->medicamentNom; 
    }
    public function setMedicamentNom(?string $nom): self { 
        $this->medicamentNom = $nom;
        return $this; 
    }
    public function isCritical(int $seuilAlerteDays): bool{
        $now = new DateTimeImmutable('now');
        $interval = $now->diff($this->datePermption);
        $daysLeft = (int)$interval->format('%r%a');
        return $daysLeft <= $seuilAlerteDays;
    }
}