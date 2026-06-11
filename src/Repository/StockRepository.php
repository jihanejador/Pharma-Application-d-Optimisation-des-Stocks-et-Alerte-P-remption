<?php
declare(strict_types=1)

namespace PharmaApp\Repository;

use PDO;
use DateTimeImmutable;
use PharmaApp\Entity\StockBatch;
use PharmaApp\Enum\BatchStatus;

class StockRepository{
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }
    public function findExpiredAndAtriskLots(): array{
        $query = "SELECT l.*, m.nom AS medicament_nom, m.seuil_alerte
        FROM lots l
        INNER JOIN medicaments m ON l.medicament_id = m.id
        ORDER BY l.date_peremption ASC";
    $stmt = $this->pdo->query($query);
    $rows = $stmt->fetchAll();

    $batches = [];
    foreach ($rows as $row){
        $batch = new StockBatch();
        $batch->setId((int)$row->id)
              ->setMedicamentId((int)$row->medicament_id)
              ->setNumeroLot($row->numero_lot)
              ->setQuantite((int)$row->quantite)
              ->setDatePeremption(new DateTimeImmutable($row->date_peremption))
              ->setStatut(BatchStatus::from($row->statut))
              ->setMedicamentNom($row->medicament_nom);
        
        $batches[] = $batch;
    }
    return $batches;
    }
    public function saveBatch(StockBatch $batch): bool {
        $stmt = $this->pdo->prepare("INSERT INTO lots (medicament_id, numero_lot, quantite, date_peremption, statut)
        VALUES (:medicament_id, :numero_lot, :quantite, :date_peremption, :statut)
        ");

        return $stmt->execute([
            ':medicament_id' => $batch->getMedicamentId(),
            ':numero_lot' => $batch->getNumeroLot(),
            ':quantite' => $batch->getQuantite(),
            ':date_peremption' => $batch->getDatePeremption()->format('Y-m-d'),
            ':statut' => $batch->getStatut()->value
        ]);
    }
}