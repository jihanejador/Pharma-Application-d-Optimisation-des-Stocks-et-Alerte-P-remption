<?php ob_start(); ?>

<div class="flex justify-center items-center mt-8">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-md p-8 border border-gray-100">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-extrabold text-emerald-600 tracking-tight">💊 Inscription</h2>
            <p class="text-gray-400 text-sm mt-1">Créer un compte pour l'équipe officinale</p>
        </div>

        <?php if (isset($error) && $error): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded text-sm mb-4">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success) && $success): ?>
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-3 rounded text-sm mb-4">
                <?= $success; ?> <a href="/" class="font-bold underline hover:text-emerald-900">Se connecter ici</a>
            </div>
        <?php endif; ?>

        <form method="POST" action="/register">
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nom complet</label>
                <input type="text" name="nom" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" placeholder="Dr. Achraf" required>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Adresse Email</label>
                <input type="email" name="email" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" placeholder="achraf@pharma.com" required>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Mot de passe</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" placeholder="••••••••" required>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Rôle du membre</label>
                <select name="role" class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                    <option value="preparateur">Préparateur en pharmacie</option>
                    <option value="titulaire">Pharmacien titulaire</option>
                    <option value="admin">Administrateur système</option>
                </select>
            </div>
            
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 text-sm mb-4 shadow-sm">
                Créer le compte
            </button>
            
            <div class="text-center">
                <span class="text-sm text-gray-500">Vous avez déjà un compte ?</span>
                <a href="/" class="text-sm text-emerald-600 hover:text-emerald-700 font-bold ml-1 transition">Se connecter</a>
            </div>
        </form>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once __DIR__ . '/../layout/base.php'; 
?>