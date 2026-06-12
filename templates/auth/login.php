<?php ob_start(); ?>

<div class="flex justify-center items-center mt-12">
    <div class="w-full max-w-md bg-white rounded-xl shadow-md p-8 border border-gray-100">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-extrabold text-emerald-600 tracking-tight">💊 PharmaFEFO</h2>
            <p class="text-gray-400 text-sm mt-1">Application d'Optimisation des Stocks</p>
        </div>
        
        <h5 class="text-xl font-bold text-gray-700 mb-4">Connexion</h5>

        <?php if (isset($error) && $error): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded text-sm mb-4">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/">
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Adresse Email</label>
                <input type="email" name="email" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" placeholder="exemple@pharma.com" required>
            </div>
            <div class="mb-5">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Mot de passe</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 text-sm mb-4 shadow-sm">
                Se connecter
            </button>
            
            <div class="text-center mt-4">
                <span class="text-sm text-gray-500">Pas encore de compte ?</span>
                <a href="/register" class="text-sm text-emerald-600 hover:text-emerald-700 font-bold ml-1 transition">Créer un compte</a>
            </div>
        </form>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once __DIR__ . '/../layout/base.php'; 
?>