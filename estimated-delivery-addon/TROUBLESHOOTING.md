# 🔧 Guide de Dépannage

Ce guide vous aide à résoudre les problèmes courants avec le plugin Estimated Delivery Addon.

## ❌ Problème : Les délais ne s'appliquent pas

### Symptôme
Vous avez configuré des délais pour un type ou une catégorie, mais le produit affiche toujours les paramètres généraux.

### Solutions

#### 1. Vérifier l'option "Overwrite" du produit
**Le problème le plus courant !**

1. Éditez le produit dans WordPress
2. Cherchez la metabox **"Estimated Delivery"**
3. **Décochez** l'option "Overwrite general settings"
4. Sauvegardez le produit

**Explication** : Quand "Overwrite" est activé, le produit utilise ses propres paramètres et ignore les réglages de type/catégorie.

#### 2. Vérifier que les valeurs sont bien configurées
1. Allez dans **WooCommerce > Délais par Type/Catégorie**
2. Vérifiez que vous avez entré une valeur dans au moins un champ (pas seulement 0)
3. Cliquez sur **Enregistrer les paramètres**

#### 3. Vider le cache
Si vous utilisez un plugin de cache :
```bash
# Vider le cache WordPress
- WP Super Cache : Options > Supprimer le cache
- W3 Total Cache : Performance > Purge All Caches
- WP Rocket : Vider le cache
```

#### 4. Vérifier l'ordre de priorité
Rappelez-vous l'ordre :
1. Produit (si Overwrite = OUI) ⬅️ **Priorité maximale**
2. Catégorie (si configurée)
3. Type de produit (si configuré)
4. Paramètres généraux

## 🔴 Problème : Incompatibilité WooCommerce

### Symptôme
Message : "WooCommerce a détecté que certaines de vos extensions actives sont incompatibles..."

### Solution
**Ce problème a été corrigé dans la version 1.0.1**

1. Mettez à jour le plugin vers la version 1.0.1 ou supérieure
2. Désactivez puis réactivez le plugin
3. Le message devrait disparaître

**Cause** : Le header "Requires Plugins" utilisé dans la version 1.0.0 n'était pas standard.

## 💥 Problème : Le produit "plante" ou ne s'affiche pas

### Symptôme
La page produit ne s'affiche pas correctement ou affiche une erreur.

### Solutions

#### 1. Vérifier les logs d'erreur
Activez le mode debug de WordPress :

**wp-config.php** :
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Consultez le fichier `/wp-content/debug.log` pour voir les erreurs.

#### 2. Tester sans autres plugins
1. Désactivez **TOUS** les autres plugins sauf :
   - WooCommerce
   - Estimated Delivery for WooCommerce
   - Estimated Delivery Addon
2. Testez si le problème persiste
3. Réactivez les plugins un par un pour identifier le conflit

#### 3. Tester avec un thème par défaut
1. Activez temporairement un thème WordPress par défaut (Twenty Twenty-Three)
2. Testez le produit
3. Si ça fonctionne, le problème vient du thème

## 🔄 Problème : Les délais ne changent pas immédiatement

### Symptôme
Vous modifiez les délais mais l'affichage ne change pas sur le site.

### Solutions

#### 1. Vider tous les caches
```
✓ Cache WordPress
✓ Cache navigateur (Ctrl+F5)
✓ Cache CDN (si applicable)
✓ Cache du serveur
```

#### 2. Vérifier le mode AJAX
Si le plugin principal utilise le mode AJAX/Cache :
1. Allez dans **WooCommerce > Estimated Delivery**
2. L'option "Use AJAX" doit être compatible
3. Testez en mode navigation privée

## 📂 Problème : Menu "Délais par Type/Catégorie" invisible

### Symptôme
Le menu n'apparaît pas sous WooCommerce.

### Solutions

#### 1. Vérifier les droits utilisateur
- Vous devez avoir le rôle **Administrateur**
- Ou avoir la capacité `manage_options`

#### 2. Réactiver le plugin
1. Allez dans **Extensions**
2. Désactivez "Estimated Delivery Addon"
3. Réactivez-le
4. Actualisez la page

#### 3. Vérifier l'installation
Le plugin principal doit être actif **AVANT** l'addon :
1. Vérifiez que "Estimated Delivery for WooCommerce" est activé
2. Activez ensuite l'addon

## ⚠️ Problème : Catégories multiples

### Symptôme
Un produit a plusieurs catégories configurées, mais seule l'une d'elles s'applique.

### Explication
**C'est normal !** Le plugin utilise la **première catégorie configurée** qu'il trouve.

### Solution
Si vous voulez contrôler quelle catégorie est utilisée :
1. Réorganisez les catégories du produit (déplacer la principale en premier)
2. Ou configurez les délais directement sur le produit avec "Overwrite"

## 🔍 Problème : Les jours max ne fonctionnent pas

### Symptôme
Vous avez configuré "Jours" et "Jours Max" mais seul "Jours" s'affiche.

### Vérifications

#### 1. Jours Max > Jours
```
✗ Mauvais :  Jours = 5, Jours Max = 3
✓ Correct :  Jours = 3, Jours Max = 5
```

#### 2. Jours Max > 0
Si Jours Max = 0, la plage est désactivée et seule la date unique s'affiche.

## 🎯 Problème : Type "external" ou "grouped" ne fonctionne pas

### Symptôme
Les produits groupés ou externes n'appliquent pas les délais.

### Raison
Certains thèmes ou plugins peuvent modifier l'affichage de ces types de produits.

### Solution
1. Vérifiez que le plugin principal fonctionne sur ces produits
2. Ajoutez les délais directement sur le produit avec "Overwrite"

## 📊 Vérifications Générales

### Checklist de dépannage
```
□ Plugin Estimated Delivery principal actif ?
□ Plugin Addon actif ?
□ WooCommerce à jour ?
□ WordPress à jour ?
□ Option "Overwrite" DÉCOCHÉE sur le produit ?
□ Au moins une valeur configurée (pas que des 0) ?
□ Cache vidé ?
□ Testé en navigation privée ?
□ Logs d'erreur consultés ?
```

## 🆘 Besoin d'aide supplémentaire ?

Si aucune de ces solutions ne fonctionne :

1. **Activez le mode debug WordPress** (voir ci-dessus)
2. **Consultez les logs** dans `/wp-content/debug.log`
3. **Notez** :
   - Version de WordPress
   - Version de WooCommerce
   - Version du plugin principal
   - Version de l'addon
   - Message d'erreur exact
   - Étapes pour reproduire le problème

## 🔬 Tests Avancés

### Test 1 : Vérifier que les filtres fonctionnent
Ajoutez ce code temporairement dans `functions.php` de votre thème :

```php
add_action('wp_footer', function() {
    if(is_product()) {
        global $product;
        echo '<!-- Product Type: ' . $product->get_type() . ' -->';
    }
});
```

Consultez le code source de la page pour voir le type de produit.

### Test 2 : Vérifier les paramètres sauvegardés
Dans phpMyAdmin :
```sql
SELECT * FROM wp_options WHERE option_name IN ('eda_product_types_settings', 'eda_categories_settings');
```

Vous devriez voir vos configurations sauvegardées.

---

## 📝 Notes Importantes

- ⚡ Les modifications prennent effet **immédiatement** (après vidage du cache)
- 🔒 Les paramètres du plugin ne modifient **jamais** la base de données des produits
- 🔄 Désactiver l'addon restaure automatiquement les paramètres généraux
- 💾 Les paramètres de l'addon sont stockés dans `wp_options`

---

**Dernière mise à jour** : Version 1.0.1 - 2025-11-20
