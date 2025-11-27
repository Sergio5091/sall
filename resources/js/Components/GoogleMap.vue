<script setup>
import { ref, onMounted, onUnmounted, defineProps, defineEmits } from 'vue';

const props = defineProps({
  initialLat: {
    type: Number,
    default: 14.6928 // Par défaut: Dakar
  },
  initialLng: {
    type: Number,
    default: -17.4467
  },
  height: {
    type: String,
    default: '400px'
  },
  readonly: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['location-selected']);

const mapContainer = ref(null);
const map = ref(null);
const marker = ref(null);
const selectedLocation = ref({
  lat: props.initialLat,
  lng: props.initialLng
});
const isLoading = ref(true);
const hasError = ref(false);
const errorMessage = ref('');

// Clé Google Maps API (optionnelle - utilise OpenStreetMap en secours)
const API_KEY = 'AIzaSyC5ZH8Ysj0RhqMJEBbQgub-yUbtKX77_z4';
const USE_OPENSTREETMAP = true; // Forcer OpenStreetMap par défaut

// Nettoyer les callbacks précédents
const cleanup = () => {
  if (window.initMap) {
    delete window.initMap;
  }
  if (window.googleMapsError) {
    delete window.googleMapsError;
  }
};

onMounted(() => {
  cleanup();
  if (USE_OPENSTREETMAP) {
    initOpenStreetMap();
  } else {
    loadGoogleMaps();
  }
});

onUnmounted(() => {
  cleanup();
});

const initOpenStreetMap = () => {
  isLoading.value = true;
  hasError.value = false;
  errorMessage.value = '';

  console.log('Tentative de chargement OpenStreetMap...');

  // Vérifier si Leaflet est déjà chargé
  if (window.L) {
    console.log('Leaflet déjà chargé, initialisation...');
    initializeOpenStreetMap();
    return;
  }

  console.log('Chargement des dépendances Leaflet...');

  // Charger Leaflet CSS
  const leafletCSS = document.createElement('link');
  leafletCSS.rel = 'stylesheet';
  leafletCSS.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
  leafletCSS.onload = () => console.log('CSS Leaflet chargé');
  leafletCSS.onerror = () => console.error('Erreur chargement CSS Leaflet');
  document.head.appendChild(leafletCSS);

  // Charger Leaflet JS
  const script = document.createElement('script');
  script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
  script.onload = () => {
    console.log('JS Leaflet chargé avec succès');
    // Attendre un peu pour que Leaflet soit complètement initialisé
    setTimeout(() => {
      if (window.L) {
        initializeOpenStreetMap();
      } else {
        console.error('Leaflet non disponible après chargement');
        hasError.value = true;
        errorMessage.value = 'Erreur lors du chargement de Leaflet.';
        isLoading.value = false;
      }
    }, 500);
  };
  script.onerror = (error) => {
    console.error('Erreur de chargement Leaflet JS:', error);
    hasError.value = true;
    errorMessage.value = 'Impossible de charger OpenStreetMap (Leaflet).';
    isLoading.value = false;
  };
  
  // Timeout plus long pour éviter les faux négatifs
  const timeout = setTimeout(() => {
    if (isLoading.value) {
      console.log('Timeout OpenStreetMap, vérification de Leaflet...');
      if (window.L) {
        console.log('Leaflet disponible malgré timeout, initialisation...');
        initializeOpenStreetMap();
      } else {
        console.error('Leaflet toujours indisponible après timeout');
        hasError.value = true;
        errorMessage.value = 'OpenStreetMap met trop de temps à charger.';
        isLoading.value = false;
      }
    }
  }, 10000); // 10 secondes

  document.head.appendChild(script);
};

const initializeOpenStreetMap = () => {
  console.log('Initialisation OpenStreetMap...');
  
  if (!mapContainer.value) {
    console.error('Conteneur de carte non trouvé');
    hasError.value = true;
    errorMessage.value = 'Conteneur de carte non trouvé.';
    isLoading.value = false;
    return;
  }

  if (!window.L) {
    console.error('Leaflet non disponible');
    hasError.value = true;
    errorMessage.value = 'Leaflet (OpenStreetMap) n\'est pas disponible.';
    isLoading.value = false;
    return;
  }

  try {
    console.log('Création de la carte OpenStreetMap...');
    console.log('Position initiale:', selectedLocation.value);
    
    // Initialiser la carte OpenStreetMap
    map.value = window.L.map(mapContainer.value).setView([selectedLocation.value.lat, selectedLocation.value.lng], 15);
    console.log('Carte créée avec succès');

    // Ajouter les tuiles OpenStreetMap
    const tileLayer = window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors',
      maxZoom: 19
    });
    
    tileLayer.addTo(map.value);
    console.log('Tuiles OpenStreetMap ajoutées');

    // Créer le marqueur initial
    const customIcon = window.L.divIcon({
      html: '<div style="background-color: #3B82F6; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
      iconSize: [30, 30],
      iconAnchor: [15, 15],
      popupAnchor: [0, -15],
      className: 'custom-marker'
    });

    marker.value = window.L.marker([selectedLocation.value.lat, selectedLocation.value.lng], {
      icon: customIcon,
      draggable: !props.readonly,
      title: 'Position de votre salle'
    }).addTo(map.value);
    console.log('Marqueur créé et ajouté');

    // Ajouter un popup
    marker.value.bindPopup('Position de votre salle').openPopup();

    // Ajouter la recherche d'adresse
    if (!props.readonly) {
      console.log('Ajout de la recherche d\'adresse...');
      addOpenStreetMapSearch();
    }

    // Si la carte n'est pas en lecture seule, ajouter les événements
    if (!props.readonly) {
      console.log('Ajout des événements de pointage...');
      // Événement de clic sur la carte
      map.value.on('click', (e) => {
        console.log('Clic sur la carte:', e.latlng);
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        updateLocation(lat, lng);
      });

      // Événement de déplacement du marqueur
      marker.value.on('dragend', (e) => {
        console.log('Marqueur déplacé:', e.target.getLatLng());
        const position = e.target.getLatLng();
        updateLocation(position.lat, position.lng);
      });
    }

    isLoading.value = false;
    hasError.value = false;
    console.log('OpenStreetMap initialisé avec succès');
    
  } catch (error) {
    console.error('Erreur lors de l\'initialisation OpenStreetMap:', error);
    hasError.value = true;
    errorMessage.value = `Erreur d'initialisation: ${error.message}`;
    isLoading.value = false;
  }
};

const addOpenStreetMapSearch = () => {
  // Créer un conteneur pour la recherche
  const searchContainer = window.L.DomUtil.create('div', 'leaflet-search-container');
  searchContainer.style.cssText = `
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 1000;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    padding: 8px;
    min-width: 250px;
  `;

  // Créer le champ de recherche
  const searchInput = window.L.DomUtil.create('input', 'leaflet-search-input');
  searchInput.type = 'text';
  searchInput.placeholder = 'Rechercher une adresse...';
  searchInput.style.cssText = `
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box;
    outline: none;
  `;

  // Créer la liste de résultats
  const resultsList = window.L.DomUtil.create('div', 'leaflet-search-results');
  resultsList.style.cssText = `
    max-height: 200px;
    overflow-y: auto;
    margin-top: 4px;
    background: white;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: none;
  `;

  searchContainer.appendChild(searchInput);
  searchContainer.appendChild(resultsList);
  mapContainer.value.appendChild(searchContainer);

  // Variable pour le debounce
  let searchTimeout;

  // Gérer la recherche
  searchInput.addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    const query = e.target.value.trim();
    
    if (query.length < 3) {
      resultsList.style.display = 'none';
      return;
    }

    searchTimeout = setTimeout(async () => {
      try {
        const results = await searchOpenStreetMap(query);
        displaySearchResults(results, resultsList);
      } catch (error) {
        console.error('Erreur de recherche:', error);
      }
    }, 300);
  });

  // Empêcher la propagation des clics
  window.L.DomEvent.disableClickPropagation(searchContainer);
  window.L.DomEvent.disableScrollPropagation(searchContainer);
};

const searchOpenStreetMap = async (query) => {
  const response = await fetch(
    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`
  );
  
  if (!response.ok) {
    throw new Error('Erreur de recherche');
  }
  
  return response.json();
};

const displaySearchResults = (results, resultsList) => {
  resultsList.innerHTML = '';
  
  if (results.length === 0) {
    resultsList.style.display = 'none';
    return;
  }

  results.forEach(result => {
    const item = window.L.DomUtil.create('div', 'search-result-item');
    item.style.cssText = `
      padding: 8px 12px;
      cursor: pointer;
      border-bottom: 1px solid #eee;
      font-size: 14px;
      transition: background-color 0.2s;
    `;
    
    const displayName = result.display_name || `${result.name}, ${result.address?.city || ''}`;
    item.textContent = displayName;
    
    item.addEventListener('mouseenter', () => {
      item.style.backgroundColor = '#f5f5f5';
    });
    
    item.addEventListener('mouseleave', () => {
      item.style.backgroundColor = 'white';
    });
    
    item.addEventListener('click', () => {
      const lat = parseFloat(result.lat);
      const lng = parseFloat(result.lon);
      
      // Centrer la carte sur le résultat
      map.value.setView([lat, lng], 16);
      
      // Déplacer le marqueur
      updateLocation(lat, lng);
      
      // Masquer les résultats
      resultsList.style.display = 'none';
      
      // Vider le champ
      document.querySelector('.leaflet-search-input').value = '';
    });
    
    resultsList.appendChild(item);
  });
  
  resultsList.style.display = 'block';
};

const loadGoogleMaps = () => {
  isLoading.value = true;
  hasError.value = false;
  errorMessage.value = '';

  // Vérifier si Google Maps est déjà chargé
  if (window.google && window.google.maps) {
    initializeMap();
    return;
  }

  // Définir les callbacks globaux
  window.initMap = () => {
    console.log('Google Maps chargé avec succès');
    initializeMap();
  };

  window.googleMapsError = () => {
    console.error('Erreur de chargement Google Maps');
    // Ne pas marquer comme erreur, essayer la solution de secours
    initFallbackMap();
  };

  // Créer le script Google Maps
  const script = document.createElement('script');
  script.src = `https://maps.googleapis.com/maps/api/js?key=${API_KEY}&libraries=places&callback=initMap`;
  script.async = true;
  script.defer = true;
  script.onerror = window.googleMapsError;
  
  // Timeout pour éviter un chargement infini
  const timeout = setTimeout(() => {
    if (isLoading.value) {
      console.log('Timeout Google Maps, utilisation de la solution de secours');
      initFallbackMap();
    }
  }, 5000); // Réduit à 5 secondes

  script.onload = () => {
    clearTimeout(timeout);
  };

  document.head.appendChild(script);
};

const initFallbackMap = () => {
  isLoading.value = false;
  hasError.value = false;
  
  console.log('Initialisation de la carte de secours');
  
  try {
    // Créer une carte simple avec OpenStreetMap comme alternative
    if (mapContainer.value) {
      // Vider le conteneur
      mapContainer.value.innerHTML = '';
      
      // Créer une interface de sélection de coordonnées manuelle
      const fallbackHtml = `
        <div class="fallback-map-container">
          <div class="fallback-header">
            <h3><i class="fas fa-map-marked-alt"></i> Sélection manuelle des coordonnées</h3>
            <p>Google Maps n'est pas disponible. Utilisez le formulaire ci-dessous pour définir la position de votre salle.</p>
          </div>
          
          <div class="manual-coords-form">
            <div class="coord-group">
              <label>
                <i class="fas fa-compass"></i>
                Latitude
              </label>
              <input 
                type="number" 
                id="fallback-lat"
                value="${selectedLocation.value.lat}" 
                step="0.000001"
                min="-90" 
                max="90"
                placeholder="Ex: 14.6928"
                class="coord-input-fallback"
              >
              <small>Coordonnée latitude (nord = positif, sud = négatif)</small>
            </div>
            
            <div class="coord-group">
              <label>
                <i class="fas fa-compass"></i>
                Longitude
              </label>
              <input 
                type="number" 
                id="fallback-lng"
                value="${selectedLocation.value.lng}" 
                step="0.000001"
                min="-180" 
                max="180"
                placeholder="Ex: -17.4467"
                class="coord-input-fallback"
              >
              <small>Coordonnée longitude (est = positif, ouest = négatif)</small>
            </div>
            
            <div class="coord-actions">
              <button type="button" id="apply-coords" class="apply-btn">
                <i class="fas fa-check"></i>
                Appliquer les coordonnées
              </button>
              <button type="button" id="retry-google" class="retry-btn">
                <i class="fas fa-redo"></i>
                Réessayer Google Maps
              </button>
            </div>
          </div>
          
          <div class="help-section">
            <h4><i class="fas fa-info-circle"></i> Comment trouver vos coordonnées ?</h4>
            <ol>
              <li>Allez sur <a href="https://maps.google.com" target="_blank">Google Maps</a></li>
              <li>Cherchez votre adresse</li>
              <li>Cliquez avec le bouton droit sur votre emplacement</li>
              <li>Copiez les coordonnées qui apparaissent</li>
            </ol>
          </div>
          
          <div class="preset-locations">
            <h4><i class="fas fa-map-pin"></i> Emplacements prédéfinis</h4>
            <div class="preset-buttons">
              <button type="button" class="preset-btn" data-lat="14.6928" data-lng="-17.4467">
                <i class="fas fa-city"></i> Dakar, Sénégal
              </button>
              <button type="button" class="preset-btn" data-lat="6.5244" data-lng="-3.3792">
                <i class="fas fa-city"></i> Abidjan, Côte d'Ivoire
              </button>
              <button type="button" class="preset-btn" data-lat="12.6392" data-lng="-8.0029">
                <i class="fas fa-city"></i> Bamako, Mali
              </button>
              <button type="button" class="preset-btn" data-lat="33.5731" data-lng="-7.5898">
                <i class="fas fa-city"></i> Casablanca, Maroc
              </button>
            </div>
          </div>
        </div>
      `;
      
      mapContainer.value.innerHTML = fallbackHtml;
      
      // Ajouter les événements
      setTimeout(() => {
        const latInput = document.getElementById('fallback-lat');
        const lngInput = document.getElementById('fallback-lng');
        const applyBtn = document.getElementById('apply-coords');
        const retryBtn = document.getElementById('retry-google');
        
        // Appliquer les coordonnées
        if (applyBtn) {
          applyBtn.addEventListener('click', () => {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            
            if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
              alert('Veuillez entrer des coordonnées valides.');
              return;
            }
            
            selectedLocation.value = { lat, lng };
            emit('location-selected', { lat, lng });
            
            // Animation de confirmation
            applyBtn.innerHTML = '<i class="fas fa-check"></i> Coordonnées appliquées !';
            applyBtn.classList.add('success');
            setTimeout(() => {
              applyBtn.innerHTML = '<i class="fas fa-check"></i> Appliquer les coordonnées';
              applyBtn.classList.remove('success');
            }, 2000);
          });
        }
        
        // Réessayer Google Maps
        if (retryBtn) {
          retryBtn.addEventListener('click', () => {
            cleanup();
            loadGoogleMaps();
          });
        }
        
        // Boutons prédéfinis
        const presetBtns = document.querySelectorAll('.preset-btn');
        presetBtns.forEach(btn => {
          btn.addEventListener('click', () => {
            const lat = parseFloat(btn.dataset.lat);
            const lng = parseFloat(btn.dataset.lng);
            latInput.value = lat;
            lngInput.value = lng;
          });
        });
      }, 100);
    }
  } catch (error) {
    console.error('Erreur lors de l\'initialisation de la carte de secours:', error);
    hasError.value = true;
    errorMessage.value = 'Impossible d\'initialiser la carte de secours.';
  }
};

const initializeMap = () => {
  isLoading.value = false;
  
  if (!mapContainer.value || !window.google || !window.google.maps) {
    hasError.value = true;
    errorMessage.value = 'Google Maps n\'est pas disponible.';
    return;
  }

  try {
    // Options de la carte
    const mapOptions = {
      center: selectedLocation.value,
      zoom: 15,
      mapTypeId: window.google.maps.MapTypeId.ROADMAP,
      styles: [
        {
          featureType: "poi",
          elementType: "labels",
          stylers: [{ visibility: "off" }]
        }
      ],
      disableDefaultUI: false,
      zoomControl: true,
      mapTypeControl: true,
      scaleControl: true,
      streetViewControl: true,
      rotateControl: true,
      fullscreenControl: true
    };

    // Initialiser la carte
    map.value = new window.google.maps.Map(mapContainer.value, mapOptions);

    // Créer le marqueur initial
    marker.value = new window.google.maps.Marker({
      position: selectedLocation.value,
      map: map.value,
      draggable: !props.readonly,
      title: 'Position de votre salle',
      animation: window.google.maps.Animation.DROP
    });

    // Si la carte n'est pas en lecture seule, ajouter les événements
    if (!props.readonly) {
      // Événement de clic sur la carte
      map.value.addListener('click', (event) => {
        placeMarker(event.latLng);
      });

      // Événement de drag du marqueur
      marker.value.addListener('dragend', (event) => {
        updateLocation(event.latLng.lat(), event.latLng.lng());
      });

      // Ajouter le champ de recherche
      setTimeout(() => addSearchBox(), 1000); // Attendre que la carte soit complètement chargée
    }

    // Centrer la carte sur le marqueur
    map.value.setCenter(selectedLocation.value);
    
    console.log('Carte initialisée avec succès');
  } catch (error) {
    console.error('Erreur lors de l\'initialisation de la carte:', error);
    hasError.value = true;
    errorMessage.value = 'Erreur lors de l\'initialisation de la carte.';
  }
};

const addSearchBox = () => {
  if (!window.google || !window.google.maps || !window.google.maps.places || !mapContainer.value) {
    console.warn('Google Places API non disponible');
    return;
  }

  try {
    const input = document.createElement('input');
    input.type = 'text';
    input.placeholder = 'Rechercher un lieu...';
    input.className = 'map-search-input';
    
    // Style du champ de recherche
    input.style.cssText = `
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 4px;
      padding: 8px 12px;
      margin: 10px;
      width: calc(100% - 20px);
      font-family: inherit;
      font-size: 14px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
      z-index: 1;
      position: relative;
    `;

    mapContainer.value.appendChild(input);

    const searchBox = new window.google.maps.places.SearchBox(input);

    searchBox.addListener('places_changed', () => {
      const places = searchBox.getPlaces();
      if (places.length === 0) return;

      const place = places[0];
      if (!place.geometry || !place.geometry.location) return;

      map.value.setCenter(place.geometry.location);
      map.value.setZoom(16);
      placeMarker(place.geometry.location);
    });
  } catch (error) {
    console.error('Erreur lors de l\'ajout du champ de recherche:', error);
  }
};

const placeMarker = (location) => {
  if (marker.value) {
    marker.value.setPosition(location);
    updateLocation(location.lat(), location.lng());
  }
};

const updateLocation = (lat, lng) => {
  selectedLocation.value = { lat, lng };
  emit('location-selected', { lat, lng });
};

// Méthodes publiques
const getCurrentLocation = () => {
  return selectedLocation.value;
};

const setLocation = (lat, lng) => {
  selectedLocation.value = { lat, lng };
  if (marker.value) {
    marker.value.setPosition({ lat, lng });
  }
  if (map.value) {
    map.value.setCenter({ lat, lng });
  }
};

const retryLoading = () => {
  cleanup();
  if (USE_OPENSTREETMAP) {
    initOpenStreetMap();
  } else {
    loadGoogleMaps();
  }
};

const runDiagnostics = () => {
  console.log('=== DIAGNOSTIC OPENSTREETMAP ===');
  
  const diagnostics = {
    browser: navigator.userAgent,
    leafletLoaded: !!window.L,
    leafletVersion: window.L ? window.L.version : 'Non chargé',
    mapContainer: !!mapContainer.value,
    mapLoaded: !!map.value,
    markerLoaded: !!marker.value,
    currentPosition: selectedLocation.value,
    networkStatus: navigator.onLine ? 'En ligne' : 'Hors ligne',
    errors: []
  };

  // Vérifier les dépendances
  if (!window.L) {
    diagnostics.errors.push('Leaflet non chargé');
  }
  
  if (!mapContainer.value) {
    diagnostics.errors.push('Conteneur de carte non trouvé');
  }

  // Vérifier le réseau
  fetch('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', { method: 'HEAD' })
    .then(response => {
      diagnostics.networkAccess = response.ok ? 'OK' : 'Erreur';
      console.log('Diagnostic complet:', diagnostics);
      
      // Afficher un rapport à l'utilisateur
      const report = `
=== RAPPORT DE DIAGNOSTIC ===
Navigateur: ${diagnostics.browser}
Réseau: ${diagnostics.networkStatus}
Accès CDN: ${diagnostics.networkAccess}
Leaflet: ${diagnostics.leafletLoaded ? '✅' : '❌'} ${diagnostics.leafletVersion}
Conteneur: ${diagnostics.mapContainer ? '✅' : '❌'}
Carte: ${diagnostics.mapLoaded ? '✅' : '❌'}
Marqueur: ${diagnostics.markerLoaded ? '✅' : '❌'}
Position: ${diagnostics.currentPosition.lat}, ${diagnostics.currentPosition.lng}
Erreurs: ${diagnostics.errors.length > 0 ? diagnostics.errors.join(', ') : 'Aucune'}
      `;
      
      alert(report);
    })
    .catch(error => {
      diagnostics.networkAccess = 'Erreur réseau';
      diagnostics.errors.push('Problème réseau: ' + error.message);
      console.log('Diagnostic avec erreur réseau:', diagnostics);
      alert('Problème réseau détecté. Vérifiez votre connexion internet.');
    });
};

const tryAlternativeCDN = () => {
  console.log('Tentative avec CDN alternatif...');
  isLoading.value = true;
  hasError.value = false;
  errorMessage.value = '';

  // Nettoyer les scripts précédents
  cleanup();

  // Essayer avec jsDelivr (alternative à unpkg)
  const leafletCSS = document.createElement('link');
  leafletCSS.rel = 'stylesheet';
  leafletCSS.href = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css';
  leafletCSS.onload = () => console.log('CSS Leaflet (jsDelivr) chargé');
  leafletCSS.onerror = () => console.error('Erreur chargement CSS Leaflet (jsDelivr)');
  document.head.appendChild(leafletCSS);

  const script = document.createElement('script');
  script.src = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js';
  script.onload = () => {
    console.log('JS Leaflet (jsDelivr) chargé avec succès');
    setTimeout(() => {
      if (window.L) {
        initializeOpenStreetMap();
      } else {
        hasError.value = true;
        errorMessage.value = 'Leaflet non disponible même avec CDN alternatif.';
        isLoading.value = false;
      }
    }, 500);
  };
  script.onerror = (error) => {
    console.error('Erreur de chargement Leaflet JS (jsDelivr):', error);
    hasError.value = true;
    errorMessage.value = 'Impossible de charger OpenStreetMap avec les CDN disponibles.';
    isLoading.value = false;
  };

  document.head.appendChild(script);
};

// Exposer les méthodes
defineExpose({
  getCurrentLocation,
  setLocation,
  retryLoading,
  runDiagnostics,
  tryAlternativeCDN
});
</script>

<template>
  <div class="google-map-container">
    <!-- État de chargement -->
    <div v-if="isLoading" class="google-map-loading" :style="{ height: height }">
      <div class="flex flex-col items-center justify-center h-full">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
        <p class="text-gray-600 dark:text-gray-400">Chargement de la carte...</p>
      </div>
    </div>

    <!-- État d'erreur -->
    <div v-else-if="hasError" class="google-map-error" :style="{ height: height }">
      <div class="flex flex-col items-center justify-center h-full p-8">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Erreur de chargement</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ errorMessage || 'La carte n\'est pas disponible.' }}</p>
        <div class="flex gap-3 flex-wrap">
          <button @click="retryLoading" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-redo mr-2"></i>Réessayer
          </button>
          <button @click="initFallbackMap" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-map-marked-alt mr-2"></i>Coordonnées manuelles
          </button>
          <button @click="runDiagnostics" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
            <i class="fas fa-stethoscope mr-2"></i>Diagnostic
          </button>
          <button @click="tryAlternativeCDN" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
            <i class="fas fa-server mr-2"></i>Autre CDN
          </button>
        </div>
      </div>
    </div>

    <!-- Carte (OpenStreetMap ou Google Maps) -->
    <div 
      v-else
      ref="mapContainer" 
      class="google-map"
      :style="{ height: height }"
    ></div>
    
    <!-- Coordonnées actuelles -->
    <div v-if="!readonly" class="coordinates-display">
      <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
        <span>
          <i class="fas fa-map-pin mr-1"></i>
          Latitude: {{ selectedLocation.lat.toFixed(6) }}
        </span>
        <span>
          <i class="fas fa-map-pin mr-1"></i>
          Longitude: {{ selectedLocation.lng.toFixed(6) }}
        </span>
        <button @click="retryLoading" class="text-blue-600 hover:text-blue-700">
          <i class="fas fa-sync-alt mr-1"></i>Recharger
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.google-map-container {
  width: 100%;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.google-map {
  width: 100%;
  background: #f0f0f0;
}

.google-map-loading {
  width: 100%;
  background: #f8f9fa;
  border: 2px dashed #dee2e6;
  border-radius: 8px;
}

.google-map-error {
  width: 100%;
  background: #fef2f2;
  border: 2px dashed #fecaca;
  border-radius: 8px;
}

/* Styles pour la carte de secours */
:deep(.fallback-map-container) {
  padding: 24px;
  background: #f8fafc;
  border-radius: 8px;
  height: 100%;
  overflow-y: auto;
}

:deep(.fallback-header) {
  text-align: center;
  margin-bottom: 24px;
  padding-bottom: 16px;
  border-bottom: 1px solid #e2e8f0;
}

:deep(.fallback-header h3) {
  color: #1e293b;
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

:deep(.fallback-header p) {
  color: #64748b;
  font-size: 0.875rem;
  line-height: 1.5;
}

:deep(.manual-coords-form) {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-bottom: 24px;
}

:deep(.coord-group) {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

:deep(.coord-group label) {
  color: #374151;
  font-weight: 500;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  gap: 8px;
}

:deep(.coord-input-fallback) {
  padding: 12px 16px;
  border: 2px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.2s;
  background: white;
}

:deep(.coord-input-fallback:focus) {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

:deep(.coord-group small) {
  color: #6b7280;
  font-size: 0.75rem;
  line-height: 1.4;
}

:deep(.coord-actions) {
  display: flex;
  gap: 12px;
  margin-top: 8px;
}

:deep(.apply-btn) {
  flex: 1;
  padding: 12px 20px;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

:deep(.apply-btn:hover) {
  background: #2563eb;
  transform: translateY(-1px);
}

:deep(.apply-btn.success) {
  background: #10b981;
}

:deep(.retry-btn) {
  padding: 12px 20px;
  background: #f3f4f6;
  color: #374151;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

:deep(.retry-btn:hover) {
  background: #e5e7eb;
  transform: translateY(-1px);
}

:deep(.help-section) {
  background: #eff6ff;
  border: 1px solid #bfdbfe;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 24px;
}

:deep(.help-section h4) {
  color: #1e40af;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 8px;
}

:deep(.help-section ol) {
  margin: 0;
  padding-left: 20px;
  color: #1e40af;
  font-size: 0.813rem;
  line-height: 1.6;
}

:deep(.help-section li) {
  margin-bottom: 4px;
}

:deep(.help-section a) {
  color: #2563eb;
  text-decoration: underline;
}

:deep(.preset-locations) {
  background: #fef3c7;
  border: 1px solid #fcd34d;
  border-radius: 8px;
  padding: 16px;
}

:deep(.preset-locations h4) {
  color: #92400e;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 8px;
}

:deep(.preset-buttons) {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 8px;
}

:deep(.preset-btn) {
  padding: 10px 16px;
  background: white;
  color: #92400e;
  border: 1px solid #fcd34d;
  border-radius: 6px;
  font-size: 0.813rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 8px;
}

:deep(.preset-btn:hover) {
  background: #fef3c7;
  transform: translateY(-1px);
}

.coordinates-display {
  display: flex;
  gap: 16px;
  padding: 12px;
  background: #f8f9fa;
  border-top: 1px solid #dee2e6;
}

.coord-item {
  display: flex;
  align-items: center;
  gap: 8px;
  flex: 1;
}

.coord-item label {
  font-weight: 500;
  color: #495057;
  min-width: 80px;
}

.coord-input {
  flex: 1;
  padding: 6px 8px;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 14px;
}

.coord-input:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

/* Style pour le champ de recherche Google */
:global(.map-search-input) {
  position: absolute;
  top: 10px;
  left: 10px;
  z-index: 1;
}

/* Responsive */
@media (max-width: 768px) {
  :deep(.fallback-map-container) {
    padding: 16px;
  }
  
  :deep(.coord-actions) {
    flex-direction: column;
  }
  
  :deep(.preset-buttons) {
    grid-template-columns: 1fr;
  }
  
  .coordinates-display {
    flex-direction: column;
    gap: 8px;
  }
  
  .coord-item {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .coord-item label {
    min-width: auto;
  }
}

/* Animation de chargement */
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>
