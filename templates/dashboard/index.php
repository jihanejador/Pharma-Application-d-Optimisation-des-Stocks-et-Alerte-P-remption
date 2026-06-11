<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="text-dark font-weight-bold">💊 Tableau de Bord FEFO</h2>
        <p class="text-muted small">Suivi d'optimisation des stocks et alertes péremption</p>
    </div>
    <div class="bg-white p-2 rounded shadow-sm border">
        <span class="small text-secondary">Utilisateur : </span>
        <strong><?= htmlspecialchars($_SESSION['user_nom']); ?></strong> 
        <span class="badge bg-info text-capitalize"><?= htmlspecialchars($_SESSION['user_role']); ?></span>
        <a href="/logout" class="btn btn-outline-danger btn-sm ms-3">Déconnexion</a>
    </div>
</div>

<div class="card p-4 shadow-sm bg-white border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Médicament</th>
                    <th>Numéro de Lot</th>
                    <th>Quantité En Stock</th>
                    <th>Date d'Expiration</th>
                    <th>Criticité Seuil</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lots)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Aucun lot de médicament enregistré pour le moment.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lots as $batch): ?>
                        <?php 
                        // تـشغيل الـدالة الـذكية من داخل الـEntity لحساب الـخطورة (مثلاً العتبة هي 30 يوم)
                        $isCritical = $batch->isCritical(30); 
                        ?>
                        <tr class="<?= $isCritical ? 'table-danger' : ''; ?>">
                            <td><strong><?= htmlspecialchars($batch->getMedicamentNom() ?? 'Inconnu'); ?></strong></td>
                            <td><code><?= htmlspecialchars($batch->getNumeroLot()); ?></code></td>
                            <td><?= htmlspecialchars((string)$batch->getQuantite()); ?> boîtes</td>
                            <td><?= htmlspecialchars($batch->getDatePeremption()->format('d/m/Y')); ?></td>
                            <td>
                                <?php if ($isCritical): ?>
                                    <span class="badge bg-danger">🚨 ALERTE CRITIQUE</span>
                                <?php else: ?>
                                    <span class="badge bg-success">✓ Stock Stable</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?= htmlspecialchars($batch->getStatut()->value); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once __DIR__ . '/../layout/base.php'; 
?>