# 🚀 Guide de Démarrage Rapide

## Installation et Configuration Simplifiée

### Étape 1 : Installer le Plugin

1. Copiez le dossier `estimated-delivery-addon` dans `/wp-content/plugins/`
2. Activez le plugin depuis **Extensions > Extensions installées**
3. Vous verrez le message de confirmation

### Étape 2 : Configurer les Délais

Allez dans **WooCommerce > Délais par Type/Catégorie**

#### Option A : Par Type de Produit

**Exemple : Tous les produits simples en 3-5 jours**

1. Trouvez la ligne "Produit Simple"
2. Configurez :
   - Jours (En stock) : `3`
   - Jours Max : `5`
3. Cliquez sur **Enregistrer**

✅ **Résultat** : Tous les produits simples afficheront "Livraison estimée entre 3-5 jours"

#### Option B : Par Catégorie

**Exemple : Catégorie "Électronique" en 2-4 jours**

1. Trouvez la ligne de votre catégorie "Électronique"
2. Configurez :
   - Jours (En stock) : `2`
   - Jours Max : `4`
3. Cliquez sur **Enregistrer**

✅ **Résultat** : Tous les produits de cette catégorie afficheront "Livraison estimée entre 2-4 jours"

### Étape 3 : C'est Tout ! 🎉

**Le plugin fonctionne automatiquement**. Vous n'avez **RIEN à modifier** sur vos produits.

---

## 🎯 Comment ça Fonctionne

### Ordre de Priorité Automatique

Le système applique les délais dans cet ordre :

```
1. Produit avec paramètres individuels     ← Priorité MAXIMALE
   (si vous avez configuré des jours dans la metabox du produit)

2. Catégorie du produit                    ← ADDON (automatique)
   (configuré dans WooCommerce > Délais par Type/Catégorie)

3. Type de produit                         ← ADDON (automatique)
   (configuré dans WooCommerce > Délais par Type/Catégorie)

4. Paramètres généraux                     ← Plugin principal
   (configuré dans WooCommerce > Estimated Delivery)
```

### 💡 Quand les Paramètres de l'Addon S'appliquent

✅ **S'appliquent automatiquement** quand :
- Le produit n'a PAS de délais configurés individuellement
- Vous avez configuré des délais pour le type ou la catégorie

❌ **Ne s'appliquent PAS** quand :
- Le produit a ses propres délais configurés dans sa metabox "Estimated Delivery"
- (Le produit individuel a toujours la priorité absolue)

---

## 📖 Exemples Pratiques

### Exemple 1 : Boutique de Meubles

**Besoin** : Meubles volumineux = 7-10 jours

**Configuration** :
1. Créez une catégorie "Meubles"
2. **WooCommerce > Délais par Type/Catégorie**
3. Ligne "Meubles" : Jours = `7`, Jours Max = `10`
4. **Enregistrer**

**Résultat** : Tous les produits dans "Meubles" affichent 7-10 jours

### Exemple 2 : Produits Sur Mesure

**Besoin** : Produits variables (configurables) = 5-7 jours

**Configuration** :
1. **WooCommerce > Délais par Type/Catégorie**
2. Ligne "Produit Variable" : Jours = `5`, Jours Max = `7`
3. **Enregistrer**

**Résultat** : Tous les produits variables affichent 5-7 jours

### Exemple 3 : Mix de Règles

**Besoin** :
- Électronique = 2-3 jours
- Sauf iPhone (produit spécifique) = 1 jour

**Configuration** :
1. Catégorie "Électronique" : Jours = `2`, Jours Max = `3`
2. Sur le produit "iPhone" : Ouvrir l'onglet "Estimated Delivery"
3. Configurer : Jours = `1`

**Résultat** :
- Tous les produits électroniques : 2-3 jours
- iPhone : 1 jour (priorité individuelle)

---

## ✅ Checklist de Vérification

Après installation, vérifiez que :

- [ ] Plugin activé avec succès
- [ ] Menu "Délais par Type/Catégorie" visible sous WooCommerce
- [ ] Au moins un type ou catégorie configuré
- [ ] Valeurs enregistrées (bouton "Enregistrer" cliqué)
- [ ] Cache vidé si vous utilisez un plugin de cache
- [ ] Test sur un produit réel effectué

---

## 🔍 Vérifier que ça Fonctionne

### Test Simple

1. Configurez un délai pour "Produit Simple" : Jours = `5`
2. Allez sur un produit simple de votre boutique
3. Vérifiez que "Livraison estimée dans 5 jours" s'affiche

### Si ça ne Fonctionne Pas

**Vérification 1** : Le produit a-t-il ses propres paramètres ?
```
Éditez le produit → Cherchez la metabox "Estimated Delivery"
Si des valeurs sont configurées → Le produit utilise ses propres paramètres
Solution : Laissez les champs vides pour utiliser l'addon
```

**Vérification 2** : Cache à vider ?
```
Testez en navigation privée (Ctrl+Shift+N ou Ctrl+Shift+P)
Si ça fonctionne → Videz le cache de votre site
```

**Vérification 3** : Valeurs bien enregistrées ?
```
WooCommerce → Délais par Type/Catégorie
Vérifiez que vos valeurs sont présentes
Cliquez à nouveau sur Enregistrer
```

---

## 🎓 Questions Fréquentes

### Q : Dois-je modifier mes produits existants ?
**R : NON !** Le plugin fonctionne automatiquement. Vous ne touchez aux produits que si vous voulez des délais spécifiques pour certains produits.

### Q : Que se passe-t-il si je désactive l'addon ?
**R :** Les paramètres généraux du plugin principal reprennent le dessus automatiquement.

### Q : Puis-je avoir des délais différents par catégorie ET par type ?
**R :** Oui ! La catégorie a priorité sur le type. Si un produit est dans une catégorie configurée, il utilisera les délais de la catégorie.

### Q : Comment configurer un produit spécifique différemment ?
**R :** Éditez le produit, allez dans la metabox "Estimated Delivery" et configurez les délais. Le produit individuel a toujours la priorité absolue.

### Q : Les délais s'appliquent-ils aussi en rupture de stock ?
**R :** Oui ! Vous pouvez configurer des délais différents pour :
- En stock
- Rupture de stock
- Précommande

---

## 🚀 Fonctionnalités Avancées

### Délais Différents selon le Stock

Configurez pour chaque type/catégorie :

- **Jours (En stock)** : Délai quand le produit est disponible
- **Jours (Rupture)** : Délai quand le produit est en rupture
- **Jours (Précommande)** : Délai pour les précommandes

**Exemple** :
```
Catégorie "Import"
- En stock : 5-7 jours
- Rupture : 14-21 jours
```

### Plages de Jours

**Une seule date** : Jours = `5`, Jours Max = `0`
→ "Livraison estimée dans 5 jours"

**Plage de dates** : Jours = `3`, Jours Max = `7`
→ "Livraison estimée entre 3-7 jours"

---

## 📞 Besoin d'Aide ?

Consultez les autres fichiers de documentation :

- **TROUBLESHOOTING.md** : Solutions aux problèmes courants
- **README.md** : Documentation complète
- **INSTALLATION.md** : Guide d'installation détaillé

---

**Profitez du plugin !** 🎉
