<script setup>
import { ref, onMounted, onUnmounted, defineProps, defineEmits } from 'vue';
import NotificationModal from './NotificationModal.vue';

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

// États pour les modaux
const showNotificationModal = ref(false);
const notificationType = ref('error');
const notificationTitle = ref('');
const notificationMessage = ref('');

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

// Clé Google Maps API
const API_KEY = 'AIzaSyC5ZH8Ysj0RhqMJEBbQgub-yUbtKX77_z4';

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
  loadGoogleMaps();
});

onUnmounted(() => {
  cleanup();
});

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
              notificationType.value = 'error';
              notificationTitle.value = 'Coordonnées invalides';
              notificationMessage.value = 'Veuillez entrer des coordonnées valides.';
              showNotificationModal.value = true;
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
  loadGoogleMaps();
};

// Exposer les méthodes
defineExpose({
  getCurrentLocation,
  setLocation,
  retryLoading
});
</script>

<template>
  <div class="google-map-container">
    <!-- État de chargement -->
    <div v-if="isLoading" class="google-map-loading" :style="{ height: height }">
      <div class="flex flex-col items-center justify-center h-full">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
        <p class="text-gray-600 dark:text-gray-400">Chargement de Google Maps...</p>
      </div>
    </div>

    <!-- État d'erreur -->
    <div v-else-if="hasError" class="google-map-error" :style="{ height: height }">
      <div class="flex flex-col items-center justify-center h-full p-8">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Erreur de chargement</h3>
        <p class="text-gray-600 dark:text-gray-400 text-center mb-4">{{ errorMessage }}</p>
        <button @click="retryLoading" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
          <i class="fas fa-redo mr-2"></i>
          Réessayer
        </button>
      </div>
    </div>

    <!-- Carte Google Maps -->
    <div 
      v-else
      ref="mapContainer" 
      class="google-map"
      :style="{ height: height }"
    ></div>
    
    <!-- Coordonnées actuelles -->
    <div v-if="!readonly" class="coordinates-display">
      <div class="coord-item">
        <label>Latitude:</label>
        <input 
          type="number" 
          :value="selectedLocation.lat.toFixed(8)" 
          @input="setLocation(parseFloat($event.target.value), selectedLocation.lng)"
          step="0.00000001"
          class="coord-input"
        >
      </div>
      <div class="coord-item">
        <label>Longitude:</label>
        <input 
          type="number" 
          :value="selectedLocation.lng.toFixed(8)" 
          @input="setLocation(selectedLocation.lat, parseFloat($event.target.value))"
          step="0.00000001"
          class="coord-input"
        >
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

<!-- Notification Modal -->
<NotificationModal
  :show="showNotificationModal"
  :type="notificationType"
  :title="notificationTitle"
  :message="notificationMessage"
  @close="showNotificationModal = false"
/>
