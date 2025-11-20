# Changelog

Toutes les modifications notables de ce projet seront documentées dans ce fichier.

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
