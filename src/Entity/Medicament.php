<?php
declare(strict_types=1);

namespace PharmaApp\Entity;

class Medicament {
    private ?int $id = null;
    private string $nom;
    private int $seuilAlerte;

    public function getId(): ?int {return $this->id;}
    public function setId(int $id): self{
        $this->id = $id;
        return $this;
    }
    public function getNom(): string{
        return $this->nom;
    }
    public function setNom(string $nom): self {
        $this->nom = $nom;
        return $this;
    }
    public function getAlerte(): int {
        return $this->seuilAlerte;
    }
    public function setAlerte(int $seuilAlerte): self{
        $this->seuilAlerte = $seuilAlerte;
        return $this;
    }
}