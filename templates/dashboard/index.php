<script src="https://cdn.tailwindcss.com"></script>
<?php ob_start(); ?>

<div class="space-y-4 mb-6">
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg shadow-sm">
        <h5 class="text-blue-800 font-bold flex items-center gap-2 text-base">
            <span>📢 Notifications Alertes Mensuelles :</span>
        </h5>
        <ul class="mt-2 space-y-1 text-sm text-blue-700 list-disc list-inside">
            <?php 
            $hasNotif = false;
            foreach ($batches as $b) {
                if ($b->expiresNextMonth()) {
                    $hasNotif = true;
                    echo "<li>Le produit <strong class='font-semibold'>{$b->getMedicamentNom()}</strong> (Lot: <code class='bg-blue-100 px-1 rounded text-xs'>{$b->getNumeroLot()}</code>) va périmer le mois prochain !</li>";
                }
            }
            if (!$hasNotif) echo "<li>Aucune alerte critique pour le mois prochain. Stock stable.</li>";
            ?>
        </ul>
    </div>
</div>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100 gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-extrabold text-gray-800">💊 Espace de Gestion Intelligente FEFO</h2>
        <p class="text-gray-400 text-xs mt-1">Session connectée en MVC Strict (Fonctions Isolées)</p>
    </div>
    <div class="flex items-center gap-3 bg-gray-50 px-4 py-2 rounded-lg border border-gray-200 text-sm">
        <span class="text-gray-600">Bienvenue, <strong class="text-gray-900 font-bold"><?= htmlspecialchars($_SESSION['user_nom']); ?></strong></span>
        <span class="bg-emerald-600 text-white text-xs font-semibold px-2 py-0.5 rounded uppercase tracking-wider"><?= htmlspecialchars($_SESSION['user_role']); ?></span>
        <a href="/logout" class="bg-red-500 hover:bg-red-600 text-white text-xs font-medium py-1 px-3 rounded transition ml-2 shadow-sm">Déconnexion</a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <h5 class="font-bold text-emerald-600 text-base mb-3 flex items-center gap-1">📥 Réception de Commande (Entrée)</h5>
        <hr class="border-gray-100 mb-4">
        <form method="POST" action="/dashboard/ajouter" class="space-y-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Sélectionner le Médicament</label>
                <select name="medicament_id" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <?php foreach($medicaments as $m): ?>
                        <option value="<?= $m->id; ?>"><?= htmlspecialchars($m->nom); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Numéro de Lot</label>
                <input type="text" name="numero_lot" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Quantité Reçue</label>
                <input type="number" name="quantite" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" min="1" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Date de Péremption (DLU)</label>
                <input type="date" name="date_peremption" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Prix d'achat du médicament (DH)</label>
                <input type="number" name="prix_achat" step="0.01" min="0" placeholder="Ex: 45.50" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
            </div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-1.5 px-4 rounded-lg text-xs shadow-sm transition">
                Enregistrer l'entrée
            </button>
        </form>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <h5 class="font-bold text-blue-600 text-base mb-3 flex items-center gap-1">📤 Dispensation & Vente (Sortie FEFO)</h5>
        <hr class="border-gray-100 mb-4">
        <form method="POST" action="/dashboard/vendre" class="space-y-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Médicament à délivrer</label>
                <select name="medicament_id" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <?php foreach($medicaments as $m): ?>
                        <option value="<?= $m->id; ?>"><?= htmlspecialchars($m->nom); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Quantité Demandée</label>
                <input type="number" name="quantite_vente" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" min="1" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-4 rounded-lg text-xs shadow-sm transition mt-4">
                Décrémenter (Règle FEFO)
            </button>
        </form>
    </div>

    <div class="bg-gray-900 text-white p-5 rounded-xl shadow-md flex flex-col justify-between">
        <div>
            <h5 class="font-bold text-amber-400 text-base mb-2">📊 Rapport Financier Mensuel</h5>
            <hr class="border-gray-800 mb-4">
            <p class="text-xs text-gray-400 leading-relaxed">Valeur financière totale du stock perdu (Périmé / À détruire) :</p>
            <h2 class="text-center text-3xl font-black text-amber-400 my-4 tracking-tight"><?= number_format($totalPertes, 2); ?> DH</h2>
        </div>
        <div class="text-center text-gray-500 text-[11px] border-t border-gray-800 pt-2">
            Calculé sur la formule : Quantité × Prix d'achat
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <h5 class="font-bold text-gray-800 text-lg">📦 Suivi et File d'attente Réelle FEFO</h5>
        <div class="flex gap-2">
            <a href="/dashboard" class="border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-medium py-1.5 px-3 rounded-lg transition shadow-sm">Tous les lots</a>
            <a href="/dashboard?filter=rouge" class="bg-red-600 hover:bg-red-700 text-white text-xs font-medium py-1.5 px-3 rounded-lg transition shadow-sm">🚨 Filtrer Alerte Rouge</a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-100">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-100">
                    <th class="p-3">Médicament</th>
                    <th class="p-3">Numéro de Lot</th>
                    <th class="p-3">Quantité Virtuelle</th>
                    <th class="p-3">Date d'Expiration</th>
                    <th class="p-3">Niveau de Criticité</th>
                    <th class="p-3">Action (Admin)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                <?php if (empty($batches)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-gray-400 py-8">Aucun lot trouvé avec ces critères.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($batches as $batch): ?>
                        <?php 
                        $color = $batch->getCriticiteColor(); 
                        $rowBg = 'bg-white hover:bg-gray-50';
                        $badgeStyle = '';
                        
                        if ($color === 'danger') {
                            $rowBg = 'bg-red-50/50 hover:bg-red-50';
                            $badgeStyle = 'bg-red-100 text-red-800 border border-red-200';
                        } elseif ($color === 'warning') {
                            $rowBg = 'bg-amber-50/50 hover:bg-amber-50';
                            $badgeStyle = 'bg-amber-100 text-amber-800 border border-amber-200';
                        } elseif ($color === 'success') {
                            $badgeStyle = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
                        } else {
                            $rowBg = 'bg-gray-50 text-gray-400';
                            $badgeStyle = 'bg-gray-200 text-gray-600';
                        }
                        ?>
                        <tr class="<?= $rowBg; ?> transition duration-150">
                            <td class="p-3 font-bold text-gray-900"><?= htmlspecialchars($batch->getMedicamentNom() ?? 'Inconnu'); ?></td>
                            <td class="p-3"><code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs font-mono text-gray-600"><?= htmlspecialchars($batch->getNumeroLot()); ?></code></td>
                            <td class="p-3 font-medium"><?= $batch->getQuantite(); ?> boîtes</td>
                            <td class="p-3"><?= $batch->getDatePeremption()->format('d/m/Y'); ?></td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-xs font-medium inline-block <?= $badgeStyle; ?>">
                                    <?php if ($color === 'danger'): ?>
                                        Rouge : &lt; 30 Jours
                                    <?php elseif ($color === 'warning'): ?>
                                        Orange : &lt; 90 Jours
                                    <?php elseif ($color === 'success'): ?>
                                        Vert : Stable
                                    <?php else: ?>
                                        Détruit / Périmé
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td class="p-3">
                                <?php if ($batch->getStatut()->value !== 'EXPIRED' && $batch->getQuantite() > 0): ?>
                                    <a href="/dashboard/perimer?id=<?= $batch->getId(); ?>" 
                                       onclick="return confirm('Voulez-vous vraiment retirer ce lot du stock ?');"
                                       class="bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold py-1 px-2.5 rounded transition shadow-sm">
                                        Retirer
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-400 italic text-xs">Aucune action</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>