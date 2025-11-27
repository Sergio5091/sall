# 🗺️ Guide OpenStreetMap pour votre application

## 📋 **Table des matières**
1. [Installation](#installation)
2. [Utilisation de base](#utilisation-de-base)
3. [Fonctionnalités avancées](#fonctionnalités-avancées)
4. [API OpenStreetMap](#api-openstreetmap)
5. [Exemples pratiques](#exemples-pratiques)

---

## 🚀 **Installation**

### **Déjà configuré dans votre projet** ✅

Dans `resources/js/Components/GoogleMap.vue` :
```javascript
const USE_OPENSTREETMAP = true; // Force OpenStreetMap
```

### **Chargement automatique** :
```javascript
// CSS Leaflet
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

// JS Leaflet  
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

---

## 🎯 **Utilisation de base**

### **1. Afficher une carte**
```vue
<GoogleMap 
  :initial-lat="14.6928"  // Dakar
  :initial-lng="-17.4467"
  height="500px"
  :readonly="false"
  @location-selected="handleLocationSelected"
/>
```

### **2. Pointage manuel**
- **Clic sur la carte** → Place le marqueur
- **Glisser le marqueur** → Ajuste la position
- **Coordonnées affichées** → En dessous de la carte

### **3. Sauvegarder les coordonnées**
```javascript
const handleLocationSelected = (location) => {
  console.log('Nouvelles coordonnées:', location);
  // { lat: 14.6928, lng: -17.4467 }
  
  // Sauvegarder automatiquement
  const form = useForm({
    latitude: location.lat,
    longitude: location.lng,
    // ... autres champs
  });
  
  form.put('/promoter/venues');
};
```

---

## 🚀 **Fonctionnalités avancées**

### **1. Recherche d'adresse** ✨ **NOUVEAU**
```javascript
// Recherche automatique avec Nominatim
const searchOpenStreetMap = async (query) => {
  const response = await fetch(
    `https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=5`
  );
  return response.json();
};
```

**Utilisation** :
1. Tapez une adresse dans le champ de recherche
2. Choisissez parmi les suggestions
3. La carte se centre automatiquement
4. Le marqueur se place à l'adresse

### **2. Marqueur personnalisé**
```javascript
const customIcon = L.divIcon({
  html: '<div style="background-color: #3B82F6; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white;"></div>',
  iconSize: [30, 30],
  iconAnchor: [15, 15]
});
```

### **3. Contrôles de la carte**
```javascript
// Zoom contrôles
map.value.setZoom(16);

// Centrer sur une position
map.value.setView([lat, lng], 15);

// Bounds (zone visible)
map.value.fitBounds([[lat1, lng1], [lat2, lng2]]);
```

---

## 🌐 **API OpenStreetMap**

### **1. Nominatim API** (Recherche/Geocoding)
```javascript
// Recherche d'adresse
GET https://nominatim.openstreetmap.org/search?format=json&q=dakar

// Reverse geocoding (coordonnées → adresse)
GET https://nominatim.openstreetmap.org/reverse?format=json&lat=14.6928&lon=-17.4467
```

### **2. Limites d'utilisation**
- ** Gratuit** : ~1 requête/seconde
- ** Pas de clé API** requise
- ** Usage personnel** autorisé
- ** Usage commercial** nécessite accord

### **3. Bonnes pratiques**
```javascript
// Ajouter un User-Agent obligatoire
const headers = {
  'User-Agent': 'VotreApp/1.0 (contact@votreapp.com)'
};

// Rate limiting
const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));
await delay(1000); // Attendre 1 seconde entre les requêtes
```

---

## 💡 **Exemples pratiques**

### **1. Création de salle avec adresse**
```vue
<!-- CreateSalle.vue -->
<template>
  <div>
    <!-- Formulaire d'adresse -->
    <input v-model="adresse" placeholder="123 Rue du Gaming, Dakar">
    
    <!-- Carte pour pointage -->
    <GoogleMap 
      :initial-lat="coordinates.lat"
      :initial-lng="coordinates.lng"
      @location-selected="updateCoordinates"
    />
    
    <!-- Affichage des coordonnées -->
    <p>Latitude: {{ coordinates.lat }}</p>
    <p>Longitude: {{ coordinates.lng }}</p>
  </div>
</template>
```

### **2. Recherche depuis le formulaire**
```javascript
// Quand l'utilisateur tape une adresse
const searchAddress = async () => {
  try {
    const results = await searchOpenStreetMap(formData.adresse);
    if (results.length > 0) {
      const first = results[0];
      updateLocation(parseFloat(first.lat), parseFloat(first.lon));
    }
  } catch (error) {
    console.error('Erreur de recherche:', error);
  }
};
```

### **3. Validation des coordonnées**
```javascript
const validateCoordinates = (lat, lng) => {
  // Vérifier si les coordonnées sont valides
  if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
    return false;
  }
  
  // Vérifier si dans une zone géographique (ex: Sénégal)
  if (lat < 12 || lat > 17 || lng < -18 || lng > -11) {
    return false;
  }
  
  return true;
};
```

---

## 🎮 **Guide d'utilisation pour l'utilisateur**

### **Étape 1 : Recherche (optionnelle)**
1. Dans le champ "Rechercher une adresse..."
2. Tapez : "Plateau, Dakar"
3. Sélectionnez le résultat dans la liste
4. La carte se centre automatiquement

### **Étape 2 : Pointage précis**
1. **Cliquez** sur la carte à l'emplacement exact
2. **Glissez** le marqueur bleu pour ajuster
3. **Zoom** avec la molette pour plus de précision

### **Étape 3 : Vérification**
1. Vérifiez les coordonnées affichées
2. Le marqueur doit être sur votre bâtiment
3. Cliquez sur "Enregistrer" pour sauvegarder

---

## 🛠️ **Personnalisation**

### **Changer le style de carte**
```javascript
// Différents fournisseurs de tuiles
const tileLayers = {
  // Standard OpenStreetMap
  osm: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
  
  // Carte douce (moins de détails)
  carto: 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png',
  
  // Satellite (nécessite une clé)
  satellite: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
};

L.tileLayer(tileLayers.osm, {
  attribution: '© OpenStreetMap contributors'
}).addTo(map);
```

### **Ajouter des contrôles personnalisés**
```javascript
// Bouton de localisation
L.control.locate({
  position: 'topleft',
  strings: {
    title: "Me localiser"
  }
}).addTo(map);

// Échelle
L.control.scale().addTo(map);
```

---

## 📊 **Comparaison OpenStreetMap vs Google Maps**

| Caractéristique | OpenStreetMap | Google Maps |
|---|---|---|
| **Coût** | ✅ Gratuit | 💰 Payant |
| **Clé API** | ❌ Non requise | ✅ Obligatoire |
| **Recherche** | ✅ Nominatim | ✅ Places API |
| **Satellite** | ❌ Limité | ✅ Complet |
| **Traffic** | ❌ Non | ✅ Oui |
| **Street View** | ❌ Non | ✅ Oui |
| **Personnalisation** | ✅ Totale | ⚠️ Limitée |

---

## 🚨 **Limites et solutions**

### **Limites d'OpenStreetMap**
- **Rate limiting** : 1 requête/seconde
- **Pas de satellite** gratuit
- **Mise à jour** communautaire

### **Solutions**
```javascript
// Cache les résultats de recherche
const searchCache = new Map();

// Debounce sur la recherche
const debouncedSearch = debounce(searchOpenStreetMap, 300);

// Fallback sur Google Maps si nécessaire
const fallbackToGoogle = () => {
  USE_OPENSTREETMAP = false;
  loadGoogleMaps();
};
```

---

## 🎉 **Conclusion**

OpenStreetMap est **parfait** pour votre application de pointage de salle :

✅ **100% gratuit** - Pas de facturation  
✅ **Fonctionnel** - Pointage, recherche, zoom  
✅ **Personnalisable** - Marqueurs, styles, contrôles  
✅ **Sans clé API** - Installation immédiate  
✅ **Communautaire** - Mises à jour régulières  

**Pour votre besoin de pointage de salle, OpenStreetMap est le choix idéal !** 🎯

---

## 📞 **Support et ressources**

- **Documentation officielle** : https://leafletjs.com/
- **API Nominatim** : https://nominatim.openstreetmap.org/
- **Exemples** : https://leafletjs.com/examples/
- **Plugins** : https://leafletjs.com/plugins.html

---

*Ce guide est mis à jour régulièrement. Dernière mise à jour : Novembre 2024*
