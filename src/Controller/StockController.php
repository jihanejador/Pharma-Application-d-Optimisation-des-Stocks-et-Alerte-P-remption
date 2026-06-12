<?php
declare(strict_types=1);

namespace PharmaApp\Controller;

use PharmaApp\Repository\StockRepository;
use PharmaApp\Entity\StockBatch;
use PharmaApp\Enum\BatchStatus;
use DateTimeImmutable;

class StockController {
    private StockRepository $stockRepository;

    public function __construct(StockRepository $stockRepository) {
        $this->stockRepository = $stockRepository;
    }
    
   
    public function dashboard(): void {
        $this->verifierSession();

        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        
        unset($_SESSION['error'], $_SESSION['success']);

        $filter = $_GET['filter'] ?? null;

        $lots = $this->stockRepository->findExpiredAtRiskLots($filter);
        $medicaments = $this->stockRepository->getAllMedicaments();
        $totalPertes = $this->stockRepository->getRapportPertesFinancieres();

        $title = "Tableau de bord - PharmaFEFO";
        require_once __DIR__ . '/../../templates/dashboard/index.php';
    }

   
    public function ajouterLot(): void {
        $this->verifierSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $medicamentId = (int)$_POST['medicament_id'];
            $numeroLot = $_POST['numero_lot'];
            $quantite = (int)$_POST['quantite'];
            $datePeremption = $_POST['date_peremption'];
    
            $prixAchat = (float)$_POST['prix_achat']; 

            $batch = new \PharmaApp\Entity\StockBatch();
            $batch->setMedicamentId($medicamentId)
                  ->setNumeroLot($numeroLot)
                  ->setQuantite($quantite)
                  ->setDatePeremption(new \DateTimeImmutable($datePeremption))
                  ->setStatut('ACTIF');

            $success = $this->stockRepository->saveBatchWithPriceUpdate($batch, $prixAchat);

            if ($success) {
                $_SESSION['success_message'] = "Lot enregistré et prix du médicament mis à jour !";
            }
    
            header('Location: /dashboard');
            exit;
    }

    
    public function vendreMedicament(): void {
        $this->verifierSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $medId = (int)$_POST['medicament_id'];
            $qteAchetee = (int)$_POST['quantite_vente'];

            $result = $this->stockRepository->sortirMedicament($medId, $qteAchetee);
            
            if ($result['success']) {
                $_SESSION['success'] = "Dispensation validée (Règle FEFO appliquée) : " . implode(', ', $result['logs']);
            } else {
                $_SESSION['error'] = $result['message'];
            }
        }
        header('Location: /dashboard');
        exit;
    }

    
    public function retirerDuStock(): void {
        $this->verifierSession();

        $lotId = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if ($lotId) {
            $this->stockRepository->declarerPerime($lotId);
            $_SESSION['success'] = "Le lot a été retiré du stock virtuel (Statut: EXPIRED) et envoyé au circuit de destruction.";
        } else {
            $_SESSION['error'] = "Identifiant du lot introuvable.";
        }

        header('Location: /dashboard');
        exit;
    }

   
    private function verifierSession(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
    }
}