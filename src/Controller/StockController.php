<?php
declare(strict_types=1);

namespace PharmaApp\Controller;

use PharmaApp\Repository\StockRepository;
use PharmaApp\Entity\StockBatch;

class StockController {
    private StockRepository $stockRepository;

    public function __construct(StockRepository $stockRepository) {
        $this->stockRepository = $stockRepository;
    }

    
    public function index(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $filter = $_GET['filter'] ?? null;
        $batches = $this->stockRepository->findExpiredAtRiskLots($filter);
        $medicaments = $this->stockRepository->getAllMedicaments();
        $pertesFinancieres = $this->stockRepository->getRapportPertesFinancieres();

        require_once __DIR__ . '/../../templates/dashboard/index.php';
    }

   
    public function ajouterLot(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $medicamentId = (int)$_POST['medicament_id'];
            $numeroLot = $_POST['numero_lot'];
            $quantite = (int)$_POST['quantite'];
            $datePeremption = $_POST['date_peremption'];
            
            $prixAchat = (float)$_POST['prix_achat']; 

            $batch = new StockBatch();
            $batch->setMedicamentId($medicamentId)
                  ->setNumeroLot($numeroLot)
                  ->setQuantite($quantite)
                  ->setDatePeremption(new \DateTimeImmutable($datePeremption))
                  ->setStatut('ACTIF');

            $success = $this->stockRepository->saveBatchWithPriceUpdate($batch, $prixAchat);

            if ($success) {
                $_SESSION['success_message'] = "Lot enregistré et prix mis à jour !";
            } else {
                $_SESSION['error_message'] = "Erreur lors de l'enregistrement.";
            }

            header('Location: /dashboard');
            exit;
        }
    }

   
    public function retirer(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $lotId = (int)($_GET['id'] ?? 0);
        if ($lotId > 0) {
            $this->stockRepository->declarerPerime($lotId);
        }

        header('Location: /dashboard');
        exit;
    }
}