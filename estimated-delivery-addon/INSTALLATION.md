# 📦 Guide d'Installation - Estimated Delivery Addon

## Installation Rapide

### Étape 1 : Vérification des Prérequis
Avant d'installer ce plugin, assurez-vous que :
- ✅ WordPress est installé (version 5.0 ou supérieure)
- ✅ WooCommerce est installé et activé (version 3.0 ou supérieure)
- ✅ Le plugin **Estimated Delivery for WooCommerce** est installé et activé

### Étape 2 : Installation du Plugin

#### Option A : Installation Manuelle (Recommandé)
1. Téléchargez ou copiez le dossier `estimated-delivery-addon`
2. Placez-le dans `/wp-content/plugins/` de votre installation WordPress
3. Connectez-vous à l'administration WordPress
4. Allez dans **Extensions > Extensions installées**
5. Trouvez **Estimated Delivery Addon - Product Types & Categories**
6. Cliquez sur **Activer**

#### Option B : Installation via ZIP
1. Compressez le dossier `estimated-delivery-addon` en fichier ZIP
2. Dans WordPress, allez dans **Extensions > Ajouter**
3. Cliquez sur **Téléverser une extension**
4. Sélectionnez le fichier ZIP et cliquez sur **Installer**
5. Cliquez sur **Activer**

### Étape 3 : Configuration Initiale

1. Une fois activé, allez dans **WooCommerce > Délais par Type/Catégorie**
2. Vous verrez deux sections principales :
   - 🏷️ **Délais par Type de Produit**
   - 📂 **Délais par Catégorie de Produit**

### Étape 4 : Configuration des Délais

#### Pour les Types de Produits
1. Trouvez le type de produit que vous souhaitez configurer
2. Entrez les valeurs dans les colonnes :
   - **Jours (En stock)** : Nombre de jours pour la livraison
   - **Jours Max** : Nombre maximum de jours (0 = désactivé)
   - **Jours (Rupture)** : Délai si le produit est en rupture
   - **Jours Max (Rupture)** : Délai maximum en rupture
   - **Jours (Précommande)** : Délai pour les précommandes
   - **Jours Max (Précommande)** : Délai maximum pour précommandes

#### Pour les Catégories
1. Trouvez la catégorie que vous souhaitez configurer
2. Entrez les mêmes types de valeurs que pour les types de produits

### Étape 5 : Enregistrer
Cliquez sur **Enregistrer les paramètres** en bas de la page.

## 🎯 Exemples de Configuration

### Exemple 1 : Produits en Précommande
**Besoin** : Les produits variables nécessitent 3-5 jours de fabrication

**Configuration** :
- Type de produit : **Variable**
- Jours (En stock) : `3`
- Jours Max : `5`

### Exemple 2 : Catégorie "Électronique"
**Besoin** : Les produits électroniques prennent 2-4 jours

**Configuration** :
- Catégorie : **Électronique**
- Jours (En stock) : `2`
- Jours Max : `4`

### Exemple 3 : Produits Volumineux en Rupture
**Besoin** : Les produits volumineux en rupture prennent 10-14 jours

**Configuration** :
- Catégorie : **Produits Volumineux**
- Jours (Rupture) : `10`
- Jours Max (Rupture) : `14`

## ✅ Vérification de l'Installation

### Test 1 : Vérifier le Menu
1. Connectez-vous à l'administration WordPress
2. Vérifiez que **WooCommerce > Délais par Type/Catégorie** est visible
3. Cliquez dessus pour accéder à la page de configuration

### Test 2 : Vérifier l'Application des Délais
1. Configurez un délai pour un type ou une catégorie
2. Créez ou ouvrez un produit de ce type/catégorie
3. Visitez la page du produit sur le frontend
4. Vérifiez que le délai configuré s'affiche

## 🔧 Résolution de Problèmes

### Le plugin ne s'active pas
**Problème** : Message d'erreur lors de l'activation

**Solution** :
1. Vérifiez que le plugin **Estimated Delivery for WooCommerce** est installé
2. Activez le plugin principal avant ce plugin addon
3. Vérifiez les logs d'erreur PHP

### Le menu n'apparaît pas
**Problème** : Pas de menu "Délais par Type/Catégorie" sous WooCommerce

**Solution** :
1. Désactivez et réactivez le plugin
2. Videz le cache WordPress si vous utilisez un plugin de cache
3. Vérifiez que vous avez les droits d'administration

### Les délais ne s'appliquent pas
**Problème** : Les délais configurés n'apparaissent pas sur les produits

**Solution** :
1. Vérifiez que le produit n'a pas l'option "Overwrite" activée individuellement
2. Videz le cache du site
3. Vérifiez l'ordre de priorité (voir README.md)
4. Assurez-vous que les champs ne sont pas vides ou à 0

### Conflit avec le plugin principal
**Problème** : Les deux plugins ne fonctionnent pas ensemble

**Solution** :
1. Mettez à jour le plugin principal à la dernière version
2. Vérifiez qu'il n'y a pas d'autres plugins de délai de livraison activés
3. Testez avec un thème par défaut (Twenty Twenty-One par exemple)

## 📞 Support Technique

Si vous rencontrez des problèmes non listés ici :

1. Vérifiez d'abord le fichier **README.md** pour plus d'informations
2. Consultez les logs d'erreur WordPress (wp-content/debug.log)
3. Désactivez temporairement les autres plugins pour identifier les conflits
4. Testez avec un thème WordPress par défaut

## 🎓 Ressources Supplémentaires

- **Documentation WooCommerce** : https://docs.woocommerce.com/
- **Codex WordPress** : https://codex.wordpress.org/
- **Plugin principal** : Estimated Delivery for WooCommerce

## 📋 Checklist Post-Installation

- [ ] Plugin activé avec succès
- [ ] Menu visible dans WooCommerce
- [ ] Délais configurés pour au moins un type ou une catégorie
- [ ] Test sur un produit réel effectué
- [ ] Affichage frontend vérifié
- [ ] Documentation lue et comprise

Félicitations ! Votre plugin est maintenant installé et configuré. 🎉
