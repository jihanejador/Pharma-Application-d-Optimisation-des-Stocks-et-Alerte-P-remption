# 💊 PharmaFEFO - Application d'Optimisation des Stocks & Alertes de Péremption

PharmaFEFO est une application web de gestion de stock officinal basée sur la règle stricte du **FEFO (First Expired, First Out)**. Elle permet d'optimiser le cycle de vie des médicaments, d'anticiper les ruptures et de minimiser les pertes financières dues aux produits périmés.

---

## 🚀 Fonctionnalités Clés (Epics & User Stories)

### 📥 1. Réception & Traçabilité (Gestion des Entrées)
* **Enregistrement par Lot :** Entrée des médicaments en spécifiant le numéro de lot, la quantité reçue et la date limite d'utilisation (DLU).
* **Séparation des Données :** Architecture découplée garantissant qu'un même médicament peut avoir plusieurs lots distincts en stock.

### 🚨 2. Système d'Alertes Intelligent
* **Niveaux de Criticité Temporelle :** Code couleur dynamique pour identifier l'état des stocks :
    * 🔴 **Alerte Rouge :** Moins de 30 jours avant péremption.
    * 🟡 **Alerte Orange :** Moins de 90 jours avant péremption.
    * 🟢 **Stable :** Stock sécurisé.
* **Notifications Mensuelles :** Tableau de bord prédictif affichant les produits qui périment le mois prochain.

### 📤 3. Dispensation Intelligente (Sortie FEFO)
* **Décrémentation Automatique :** Lors d'une vente, le système algorithmique choisit et vide automatiquement le lot dont la date de péremption est la plus proche (FEFO Strict), évitant le gaspillage.

### 📊 4. Évaluation des Pertes & Retrait
* **Retrait Virtuel :** Possibilité de retirer ou détruire un lot critique du stock en un clic.
* **Rapport Financier :** Calcul automatique en temps réel de la valeur financière totale perdue.

---

## 🏗️ Architecture Technique & Choix Logiques

L'application est développée selon une architecture **MVC Strict (Modèle-Vue-Contrôleur)** avec une séparation claire des responsabilités (Principes SOLID) :

* **Entities (Modèles Anémiques) :** Représentation pure des données de la base (ex: `StockBatch.php`), encapsulant la logique métier propre à l'objet (`getDaysLeft()`, `expiresNextMonth()`).
* **Repositories (Couche Data Access via PDO) :** Isolation complète des requêtes SQL (ex: `StockRepository.php`). Aucun contrôleur ne communique directement avec la base de données.
* **Controllers :** Orchestration des requêtes, gestion de la session et liaison entre les Repositories et les Vues.
* **Vues (Templates) :** Fichiers PHP isolés utilisant la mise en mémoire tampon (`ob_start()`) injectés dans un Layout principal (`base.php`).

### 🎨 Technologies Utilisées
* **Backend :** PHP 8.0+ (Approche Orientée Objet, Espaces de noms, Typage strict).
* **Database :** MySQL via PHP Data Objects (PDO) avec requêtes préparées pour contrer les injections SQL.
* **Frontend :** **Tailwind CSS** (Intégration fluide, interface moderne et Layout entièrement *Responsive Desktop/Mobile*).

---

## 📂 Structure du Projet

```text
├── public/
│   ├── css/
│   │   └── bootstrap.min.css      # Fallback local
│   └── index.php                  # Point d'entrée unique (Front Controller)
├── src/
│   ├── Controller/                # Logique de contrôle (StockController, AuthController)
│   ├── Entity/                    # Entités Métier (StockBatch, User)
│   ├── Enum/                      # Simulation d'Enums pour PHP 8.0 (BatchStatus)
│   └── Repository/                # Requêtes SQL isolées (StockRepository, UserRepository)
├── templates/
│   ├── auth/                      # Vues de Connexion et Inscription
│   ├── dashboard/                 # Vue principale de gestion du stock
│   └── layout/                    # Template de base (Header, Tailwind Script, CDN)
└── config/                        # Configuration et connexion Database

realise par : Jihane Jador;
