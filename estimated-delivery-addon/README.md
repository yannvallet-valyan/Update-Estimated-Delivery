# Estimated Delivery Addon - Product Types & Categories

Plugin addon pour **Estimated Delivery for WooCommerce** qui permet de définir des délais de livraison spécifiques par type de produit et par catégorie.

## 📋 Prérequis

- WordPress 5.0+
- WooCommerce 3.0+
- Plugin **Estimated Delivery for WooCommerce** installé et activé

## 🚀 Installation

1. Téléchargez le dossier `estimated-delivery-addon`
2. Placez-le dans le répertoire `/wp-content/plugins/`
3. Activez le plugin depuis le menu "Extensions" de WordPress
4. Accédez au menu **WooCommerce > Délais par Type/Catégorie**

## ✨ Fonctionnalités

### Délais par Type de Produit
Configurez des délais spécifiques pour :
- **Produits Simples** (simple)
- **Produits Variables** (variable)
- **Produits Groupés** (grouped)
- **Produits Externes/Affiliés** (external)

### Délais par Catégorie
Configurez des délais spécifiques pour chaque catégorie de produit WooCommerce.

### Options Disponibles
Pour chaque type/catégorie, vous pouvez configurer :
- ✅ **Jours (En stock)** : Délai pour les produits en stock
- ✅ **Jours Max** : Délai maximum (crée une plage)
- ✅ **Jours (Rupture)** : Délai pour les produits en rupture de stock
- ✅ **Jours Max (Rupture)** : Délai maximum en rupture
- ✅ **Jours (Précommande)** : Délai pour les précommandes
- ✅ **Jours Max (Précommande)** : Délai maximum pour précommandes

## 🎯 Ordre de Priorité

Le plugin applique les délais selon cet ordre de priorité :

1. **Paramètres du produit individuel** (si l'option "Overwrite" est activée sur le produit)
2. **Paramètres de la catégorie** (configurés dans ce plugin)
3. **Paramètres du type de produit** (configurés dans ce plugin)
4. **Paramètres généraux** (du plugin Estimated Delivery principal)

## 📖 Exemples d'Utilisation

### Exemple 1 : Produits Volumineux
Créez une catégorie "Produits volumineux" et configurez :
- Jours : 7
- Jours Max : 10

Tous les produits de cette catégorie afficheront : "Livraison estimée entre 7-10 jours"

### Exemple 2 : Produits Variables (Sur Mesure)
Configurez le type "Produit Variable" avec :
- Jours : 5
- Jours Max : 7

Tous les produits variables afficheront un délai de 5-7 jours, sauf s'ils ont une catégorie spécifique configurée.

### Exemple 3 : Produits Importés
Créez une catégorie "Import" et configurez :
- Jours : 14
- Jours Max : 21

Les produits de cette catégorie afficheront : "Livraison estimée entre 14-21 jours"

## 🔧 Configuration

1. Allez dans **WooCommerce > Délais par Type/Catégorie**
2. Configurez les délais pour les types de produits souhaités
3. Configurez les délais pour les catégories souhaitées
4. Cliquez sur **Enregistrer les paramètres**

💡 **Astuce** : Laissez les champs vides ou à 0 pour utiliser les paramètres par défaut.

## 🧩 Intégration avec le Plugin Principal

Ce plugin s'intègre automatiquement avec Estimated Delivery for WooCommerce en utilisant :
- Les filtres WordPress pour modifier les options de délais
- La détection automatique du type et de la catégorie du produit
- Le respect de l'ordre de priorité défini

## 🐛 Dépannage

### Les délais ne s'appliquent pas
1. Vérifiez que le plugin principal est activé
2. Vérifiez que le produit n'a pas l'option "Overwrite" activée
3. Videz le cache si vous utilisez un plugin de cache

### Produits avec plusieurs catégories
Si un produit appartient à plusieurs catégories configurées, la première catégorie trouvée (par ordre de configuration) sera utilisée.

## 📄 Licence

GPLv3 - Même licence que WordPress et le plugin principal

## 🤝 Support

Pour toute question ou problème :
1. Vérifiez d'abord la documentation du plugin principal
2. Vérifiez que votre configuration WooCommerce est correcte
3. Testez avec le thème par défaut pour écarter les conflits de thème

## 🎨 Personnalisation

Ce plugin utilise les mêmes options d'affichage que le plugin principal :
- Position d'affichage
- Format de date
- Icônes
- Style CSS

Toutes ces options sont configurées dans le plugin Estimated Delivery principal.

## 📝 Changelog

### Version 1.0.0
- Première version
- Support des types de produits (simple, variable, grouped, external)
- Support des catégories de produits
- Interface d'administration intuitive
- Intégration complète avec Estimated Delivery for WooCommerce
