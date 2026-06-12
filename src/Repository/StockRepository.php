<?php
declare(strict_types=1);

namespace PharmaApp\Repository;

use PDO;
use DateTimeImmutable;
use PharmaApp\Entity\StockBatch;
use PharmaApp\Enum\BatchStatus;

class StockRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    
    public function findExpiredAtRiskLots(?string $filter = null): array {
        $query = "SELECT l.*, m.nom AS medicament_nom, m.seuil_alerte
                  FROM lots l
                  INNER JOIN medicaments m ON l.medicament_id = m.id";
        
        if ($filter === 'rouge') {
            $query .= " WHERE l.statut = 'ACTIF' AND l.date_peremption <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
        }
        
        $query .= " ORDER BY l.date_peremption ASC"; 
        
        $stmt = $this->pdo->query($query);
        $rows = $stmt->fetchAll(\PDO::FETCH_OBJ); 

        $batches = []; 
        foreach ($rows as $row) {
            $batch = new \PharmaApp\Entity\StockBatch();
            $batch->setId((int)$row->id)
                  ->setMedicamentId((int)$row->medicament_id)
                  ->setNumeroLot($row->numero_lot)
                  ->setQuantite((int)$row->quantite)
                  ->setDatePeremption(new \DateTimeImmutable($row->date_peremption))
                  ->setStatut($row->statut) 
                  ->setMedicamentNom($row->medicament_nom);
            
            $batches[] = $batch;
        }
        return $batches;
    }

   
    public function getAllMedicaments(): array {
        $stmt = $this->pdo->query("SELECT id, nom FROM medicaments ORDER BY nom ASC");
        return $stmt->fetchAll();
    }

   
    public function saveBatch(StockBatch $batch): bool {
        $stmt = $this->pdo->prepare("INSERT INTO lots (medicament_id, numero_lot, quantite, date_peremption, statut)
                                     VALUES (:medicament_id, :numero_lot, :quantite, :date_peremption, :statut)");

        return $stmt->execute([
            ':medicament_id'   => $batch->getMedicamentId(),
            ':numero_lot'      => $batch->getNumeroLot(),
            ':quantite'        => $batch->getQuantite(),
            ':date_peremption' => $batch->getDatePeremption()->format('Y-m-d'),
            ':statut'          => $batch->getStatut()->value
        ]);
    }

    
    public function sortirMedicament(int $medicamentId, int $quantiteDemandee): array {
        $stmt = $this->pdo->prepare("SELECT * FROM lots 
                                     WHERE medicament_id = :med_id AND statut = 'ACTIF' AND quantite > 0 
                                     ORDER BY date_peremption ASC");
        $stmt->execute([':med_id' => $medicamentId]);
        $lots = $stmt->fetchAll();

        if (!$lots) {
            return ['success' => false, 'message' => 'Rupture de stock : Aucun lot actif disponible !'];
        }

        $totalDisponible = array_sum(array_column($lots, 'quantite'));
        if ($quantiteDemandee > $totalDisponible) {
            return ['success' => false, 'message' => "Quantité insuffisante ! Disponible total : $totalDisponible boîtes."];
        }

        $restant = $quantiteDemandee;
        $logs = [];

        foreach ($lots as $lot) {
            if ($restant <= 0) break;

            $qteLot = (int)$lot->quantite;
            $lotId = (int)$lot->id;

            if ($qteLot <= $restant) {
                $restant -= $qteLot;
                $logs[] = "Lot [{$lot->numero_lot}] vidé (-{$qteLot})";
                
                $update = $this->pdo->prepare("UPDATE lots SET quantite = 0, statut = 'EXPIRED' WHERE id = :id");
                $update->execute([':id' => $lotId]);
            } else {
                $nouvelleQte = $qteLot - $restant;
                $logs[] = "Lot [{$lot->numero_lot}] déduit (-{$restant})";
                
                $update = $this->pdo->prepare("UPDATE lots SET quantite = :qte WHERE id = :id");
                $update->execute([':qte' => $nouvelleQte, ':id' => $lotId]);
                
                $restant = 0;
            }
        }

        return ['success' => true, 'logs' => $logs];
    }

   
    public function declarerPerime(int $lotId): bool {
        $stmt = $this->pdo->prepare("UPDATE lots SET quantite = 0, statut = 'EXPIRED' WHERE id = :id");
        return $stmt->execute([':id' => $lotId]);
    }

   
    public function getRapportPertesFinancieres(): float {
        $query = "SELECT m.prix_achat 
                  FROM lots l
                  INNER JOIN medicaments m ON l.medicament_id = m.id
                  WHERE l.statut = 'EXPIRED'";
                  
        $stmt = $this->pdo->query($query);
        $lotsPerdus = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$lotsPerdus) {
            return 0.00;
        }

        $totalPerduFinancier = 0.0;
        foreach ($lotsPerdus as $lot) {
            $prixAchat = (float)$lot['prix_achat'];
            
            $quantiteMoyennePerdue = 10; 
            
            $totalPerduFinancier += ($quantiteMoyennePerdue * $prixAchat);
        }

        return $totalPerduFinancier;
    }
    public function saveBatchWithPriceUpdate(StockBatch $batch, float $prixAchat): bool {
        try {
            $this->pdo->beginTransaction();

            $stmt1 = $this->pdo->prepare("INSERT INTO lots (medicament_id, numero_lot, quantite, date_peremption, statut)
                                         VALUES (:medicament_id, :numero_lot, :quantite, :date_peremption, :statut)");
            
            $statutValue = $batch->getStatut();
            if ($statutValue instanceof \PharmaApp\Enum\BatchStatus) {
                $statutValue = $statutValue->value;
            } elseif (is_object($statutValue) && isset($statutValue->value)) {
                $statutValue = $statutValue->value;
            }

            $stmt1->execute([
                ':medicament_id'   => $batch->getMedicamentId(),
                ':numero_lot'      => $batch->getNumeroLot(),
                ':quantite'        => $batch->getQuantite(),
                ':date_peremption' => $batch->getDatePeremption()->format('Y-m-d'),
                ':statut'          => $statutValue 
            ]);

            $stmt2 = $this->pdo->prepare("UPDATE medicaments SET prix_achat = :prix WHERE id = :med_id");
            $stmt2->execute([
                ':prix'   => $prixAchat,
                ':med_id' => $batch->getMedicamentId()
            ]);

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}