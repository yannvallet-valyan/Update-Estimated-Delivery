# 📦 Comment Afficher la Metabox "Estimated Delivery"

## Problème
Vous ne voyez pas la metabox "Estimated Delivery" dans vos produits.

## Solutions

### Solution 1 : Afficher depuis les Options d'Écran

1. **Éditez un produit** dans WordPress
2. En haut à droite, cliquez sur **"Options de l'écran"** (onglet qui se déplie)
3. Cherchez la case **"Estimated Delivery"**
4. **Cochez-la**
5. La metabox devrait maintenant apparaître en bas de page

![Options d'écran](https://i.imgur.com/example.png)

### Solution 2 : Vérifier que le Plugin Principal est Actif

1. Allez dans **Extensions > Extensions installées**
2. Cherchez **"Estimated Delivery for WooCommerce"**
3. Vérifiez qu'il est **activé** (bleu)
4. Si non, activez-le

### Solution 3 : Réactiver le Plugin Principal

Parfois, réactiver le plugin résout le problème :

1. **Extensions > Extensions installées**
2. **Désactivez** "Estimated Delivery for WooCommerce"
3. Attendez 2 secondes
4. **Réactivez-le**
5. Éditez un produit et vérifiez

## 🔍 Mode Debug - Alternative Simple

**Si vous ne trouvez toujours pas la metabox**, utilisez le mode debug pour voir si vos produits ont quand même des paramètres configurés :

### Sur votre produit problématique, ajoutez `?eda_debug=1` :

```
https://votre-site.com/produit/certificat/?eda_debug=1
```

Le mode debug vous montrera dans la section **"⚙️ Paramètres du Produit"** :

```
• Overwrite: Non
• Days: vide         ← Si tout est vide, PARFAIT !
• Max Days: vide
• Days Outstock: vide
• etc.
```

### Si TOUS les champs sont vides :

✅ **PARFAIT !** L'addon devrait s'appliquer automatiquement

Vérifiez juste que dans **"🎯 Paramètres Appliqués"** vous voyez :

```
✓ ADDON APPLIQUÉ
Array(
  [days] => 1
  [max_days] => 2
)
```

### Si des champs ont des valeurs (ex: Days: 5) :

❌ **C'est le problème !** Le produit a des paramètres cachés

**Solution :** Vous devrez les supprimer manuellement en base de données OU afficher la metabox pour les vider.

## 🗄️ Solution Avancée : Supprimer en Base de Données

Si vous ne pouvez vraiment pas afficher la metabox, vous pouvez supprimer les métadonnées directement :

### Via phpMyAdmin :

```sql
-- Remplacez 123 par l'ID de votre produit
DELETE FROM wp_postmeta
WHERE post_id = 123
AND meta_key IN (
    '_edw_days',
    '_edw_max_days',
    '_edw_days_outstock',
    '_edw_max_days_outstock',
    '_edw_days_backorders',
    '_edw_max_days_backorders',
    '_edw_overwrite',
    '_edw_mode',
    '_edw_disabled_days'
);
```

⚠️ **Attention** : Faites une sauvegarde avant !

### Via WP-CLI (si disponible) :

```bash
# Remplacez 123 par l'ID de votre produit
wp post meta delete 123 _edw_days
wp post meta delete 123 _edw_max_days
wp post meta delete 123 _edw_days_outstock
wp post meta delete 123 _edw_max_days_outstock
wp post meta delete 123 _edw_days_backorders
wp post meta delete 123 _edw_max_days_backorders
wp post meta delete 123 _edw_overwrite
wp post meta delete 123 _edw_mode
wp post meta delete 123 _edw_disabled_days
```

## ✅ Vérification Finale

Après avoir vidé les paramètres :

1. Allez sur la page du produit avec `?eda_debug=1`
2. Vérifiez que **"🎯 Paramètres Appliqués"** montre :
   ```
   ✓ ADDON APPLIQUÉ
   ```
3. Vérifiez que les bons délais s'affichent

## 🆘 Toujours Bloqué ?

Si aucune solution ne fonctionne :

1. **Prenez une capture d'écran** du mode debug
2. **Notez** :
   - L'URL du produit
   - Le type de produit (simple, variable, etc.)
   - La catégorie du produit
3. **Vérifiez** que vous avez bien configuré des délais dans **WooCommerce > Délais par Type/Catégorie**

---

**Astuce** : Le plus simple est d'utiliser le **mode debug** (`?eda_debug=1`) qui vous dira exactement où est le problème !
