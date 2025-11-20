# Changelog

Toutes les modifications notables de ce projet seront documentées dans ce fichier.

## [1.0.3] - 2025-11-20

### 🐛 Corrigé
- **Détection des paramètres individuels améliorée** : Vérification stricte avec intval() > 0
- Correction du bug où les produits avec des champs vides ou '0' bloquaient l'addon
- Meilleure logique pour déterminer si un produit a des paramètres personnalisés

### ✨ Ajouté
- **Mode Debug intégré** : Ajoutez `?eda_debug=1` à l'URL pour diagnostiquer les problèmes
- Affichage détaillé de tous les paramètres du produit
- Identification automatique de la raison pour laquelle l'addon ne s'applique pas
- Documentation DEBUG.md complète pour le dépannage

### 🔄 Amélioré
- Vérification de TOUS les champs du produit (days, max_days, outstock, backorders)
- Un produit n'est considéré comme ayant des paramètres personnalisés QUE si au moins un champ > 0
- Meilleure clarté dans la logique de priorité

## [1.0.2] - 2025-11-20

### ✨ Amélioré
- **Détection automatique des paramètres individuels** : Le plugin détecte maintenant si un produit a des paramètres configurés (même sans l'option "Overwrite")
- Plus besoin de modifier manuellement les produits
- Le plugin fonctionne maintenant totalement automatiquement

### 📚 Documentation
- Ajout du guide **QUICKSTART.md** pour un démarrage rapide
- Mise à jour de TROUBLESHOOTING.md avec le nouveau fonctionnement
- Mise à jour de README.md pour refléter la détection automatique

## [1.0.1] - 2025-11-20

### 🐛 Corrigé
- Problème d'incompatibilité WooCommerce causé par le header "Requires Plugins"
- Système de filtres complètement refait pour utiliser `pre_option` au lieu de `option`
- Les délais configurés par type/catégorie remplacent maintenant correctement les paramètres généraux
- Amélioration de la détection du produit actuel (support des pages produits et listes)
- Meilleure gestion du contexte AJAX pour le cache
- Correction du bug qui faisait "planter" l'affichage des produits

### 🔄 Modifié
- Refonte complète de la classe `EDA_Filters`
- Utilisation de `pre_option_{$option}` pour intercepter les options AVANT leur lecture
- Amélioration de la logique de priorisation (Produit > Catégorie > Type > Général)
- Optimisation du chargement des paramètres personnalisés

### 📚 Technique
- Les filtres s'appliquent maintenant au bon moment dans le cycle de vie WordPress
- Meilleure gestion de l'état et du contexte du produit
- Support complet du mode cache/AJAX du plugin principal

## [1.0.0] - 2025-11-20

### ✨ Ajouté
- Première version du plugin
- Interface d'administration sous WooCommerce > Délais par Type/Catégorie
- Support complet des types de produits WooCommerce :
  - Produits Simples
  - Produits Variables
  - Produits Groupés
  - Produits Externes/Affiliés
- Support des catégories de produits WooCommerce
- Configuration des délais pour 6 scénarios par type/catégorie :
  - Délai en stock (jours min et max)
  - Délai en rupture de stock (jours min et max)
  - Délai en précommande (jours min et max)
- Intégration automatique avec Estimated Delivery for WooCommerce
- Système de priorité intelligent :
  1. Paramètres du produit individuel
  2. Paramètres de la catégorie
  3. Paramètres du type de produit
  4. Paramètres généraux
- Interface utilisateur intuitive avec tableaux configurables
- Style CSS personnalisé pour l'administration
- Documentation complète (README.md, INSTALLATION.md)
- Support multilingue (prêt pour la traduction)

### 🎨 Interface
- Design moderne et cohérent avec l'interface WordPress
- Aide contextuelle sur chaque section
- Exemples d'utilisation intégrés
- Messages de succès/erreur clairs

### 🔒 Sécurité
- Vérification des nonces WordPress
- Sanitisation de toutes les entrées utilisateur
- Vérification des capacités utilisateur (manage_options)
- Protection ABSPATH sur tous les fichiers

### 📚 Documentation
- README.md complet avec exemples
- INSTALLATION.md avec guide pas à pas
- CHANGELOG.md pour le suivi des versions
- Commentaires de code en français

### 🧪 Testé avec
- WordPress 5.0+
- WooCommerce 3.0+
- PHP 7.4+
- Estimated Delivery for WooCommerce 1.4.5

---

## Notes de Version Future

### [1.1.0] - Prévu
- [ ] Support des attributs de produits personnalisés
- [ ] Configuration des jours désactivés par type/catégorie
- [ ] Export/Import de configuration
- [ ] Duplication de configuration entre catégories
- [ ] Interface de gestion en masse

### [1.2.0] - Prévu
- [ ] Support des zones de livraison (shipping zones)
- [ ] Délais différents par pays/région
- [ ] Intégration avec les méthodes d'expédition
- [ ] Statistiques et rapports

### Idées pour le Futur
- Interface AJAX pour une sauvegarde instantanée
- Widget dashboard avec résumé de configuration
- Mode debug pour le dépannage
- API REST pour intégrations tierces
- Hooks et filtres pour développeurs

---

## Légende

- ✨ **Ajouté** : Nouvelles fonctionnalités
- 🔄 **Modifié** : Changements dans les fonctionnalités existantes
- 🐛 **Corrigé** : Corrections de bugs
- 🔒 **Sécurité** : Correctifs de sécurité
- ❌ **Supprimé** : Fonctionnalités retirées
- ⚠️ **Déprécié** : Fonctionnalités bientôt retirées

---

## Support de Versions

| Version | Support | Date de fin |
|---------|---------|-------------|
| 1.0.x   | ✅ Actif | N/A         |

---

**Note** : Ce plugin suit le versioning sémantique (SemVer) : MAJOR.MINOR.PATCH
