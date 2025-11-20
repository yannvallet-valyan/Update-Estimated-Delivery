# 🐛 Mode Debug - Estimated Delivery Addon

## Comment Activer le Mode Debug

Le plugin inclut un mode debug qui affiche toutes les informations sur le produit et les paramètres appliqués.

### Étape 1 : Accéder au Produit en Mode Debug

Ajoutez `?eda_debug=1` à la fin de l'URL de votre produit :

**Exemple :**
```
Avant : https://votre-site.com/produit/certificat-cadeau/
Après  : https://votre-site.com/produit/certificat-cadeau/?eda_debug=1
```

### Étape 2 : Analyser les Informations

Une fois sur la page, descendez en bas. Vous verrez un encadré rouge avec toutes les informations :

#### 📦 Produit
- ID, Nom, Type, État du stock

#### 📂 Catégories
- Liste des catégories du produit
- ✓ = La catégorie est configurée dans l'addon
- ✗ = La catégorie n'est pas configurée

#### 🏷️ Type de Produit
- ✓ = Le type est configuré dans l'addon
- ✗ = Le type n'est pas configuré

#### ⚙️ Paramètres du Produit
- Tous les champs de la metabox "Estimated Delivery"
- Si un champ a une valeur > 0, le produit utilise ses propres paramètres

#### 🎯 Paramètres Appliqués
- **✓ ADDON APPLIQUÉ** (fond vert) = Les délais de l'addon sont utilisés
- **✗ ADDON NON APPLIQUÉ** (fond rouge) = L'addon n'est pas appliqué + raison

## 🔍 Diagnostiquer le Problème

### Cas 1 : "Addon Non Appliqué - Le produit a Overwrite activé"

**Solution :**
1. Éditez le produit
2. Cherchez la metabox "Estimated Delivery"
3. Décochez "Overwrite general settings"
4. Sauvegardez

### Cas 2 : "Addon Non Appliqué - Le produit a des paramètres individuels"

**Solution :**
1. Éditez le produit
2. Cherchez la metabox "Estimated Delivery"
3. Videz TOUS les champs (Days, Max Days, etc.)
4. Sauvegardez

### Cas 3 : "Addon Non Appliqué - Aucune configuration trouvée"

**Solution :**
1. Vérifiez que la catégorie du produit est configurée dans l'addon
2. OU vérifiez que le type de produit est configuré
3. Allez dans **WooCommerce > Délais par Type/Catégorie**
4. Configurez les délais pour la catégorie ou le type
5. Enregistrez

### Cas 4 : "Addon Appliqué" mais mauvais délais affichés

**Solution :**
1. Vérifiez les valeurs dans la section "Paramètres Appliqués"
2. Comparez avec vos configurations dans l'addon
3. Videz le cache si vous utilisez un plugin de cache
4. Testez en navigation privée

## 📋 Exemple d'Utilisation

### Produit Certificat qui Bug

**Problème :** Le produit "Certificat Cadeau" n'affiche pas les bons délais

**Diagnostic :**

1. Accédez à : `https://votre-site.com/produit/certificat-cadeau/?eda_debug=1`

2. Regardez la section "📂 Catégories" :
   ```
   Certificat (ID: 15) ✓ Configuré dans l'addon
   Jours: 1
   Max Jours: 2
   ```

3. Regardez la section "⚙️ Paramètres du Produit" :
   ```
   Overwrite: Non
   Days: 5 ← PROBLÈME ICI
   Max Days: vide
   ```

4. Regardez "🎯 Paramètres Appliqués" :
   ```
   ✗ ADDON NON APPLIQUÉ
   Raison: Le produit a des paramètres individuels configurés
   ```

**Solution :**
- Le produit a `Days: 5` configuré individuellement
- Éditer le produit → Vider le champ "Days for Delivery" → Sauvegarder
- L'addon appliquera alors les délais de la catégorie (1-2 jours)

## 🛠️ Solutions Rapides

### Forcer l'Application de l'Addon

Pour qu'un produit utilise les paramètres de type/catégorie, assurez-vous que :

1. ☐ Option "Overwrite" est DÉCOCHÉE
2. ☐ Tous les champs sont VIDES dans la metabox du produit
3. ☐ La catégorie OU le type est configuré dans l'addon
4. ☐ Le cache est vidé

### Vérifier la Configuration de l'Addon

1. Allez dans **WooCommerce > Délais par Type/Catégorie**
2. Vérifiez que vous avez configuré au moins une valeur
3. Cliquez sur "Enregistrer les paramètres"

## 📸 Capture d'Écran du Debug

Le mode debug affiche un encadré comme ceci :

```
┌─────────────────────────────────────────┐
│ 🐛 EDA DEBUG MODE                       │
├─────────────────────────────────────────┤
│ 📦 Produit                               │
│ • ID: 123                                │
│ • Nom: Certificat Cadeau                │
│ • Type: simple                           │
│                                          │
│ 📂 Catégories                            │
│ • Certificat (ID: 15) ✓ Configuré       │
│                                          │
│ 🎯 Paramètres Appliqués                 │
│ ✓ ADDON APPLIQUÉ                        │
│ Array(                                   │
│   [days] => 1                            │
│   [max_days] => 2                        │
│ )                                        │
└─────────────────────────────────────────┘
```

## ⚠️ Important

- Le mode debug est **réservé aux administrateurs**
- Il ne fonctionne que sur les **pages produits**
- Pour le désactiver, enlevez `?eda_debug=1` de l'URL
- N'utilisez PAS ce mode en production (peut ralentir la page)

## 🆘 Besoin d'Aide Supplémentaire ?

Si le mode debug ne vous aide pas à résoudre le problème :

1. Prenez une **capture d'écran** de l'encadré de debug
2. Notez l'URL du produit
3. Consultez **TROUBLESHOOTING.md** pour plus de solutions
4. Vérifiez que vous utilisez la dernière version du plugin

---

**Version du Plugin :** 1.0.3+
**Dernière mise à jour :** 2025-11-20
