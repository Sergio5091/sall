<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch, onMounted, nextTick, computed } from 'vue';
import axios from 'axios';
import NotificationModal from '../../Components/NotificationModal.vue';
import Navigation from '../../Components/Navigation.vue';

const props = defineProps({
    salles: Object,
    villes: Array,
    filters: Object,
    reservations: Array
});

// États pour les modaux
const showNotificationModal = ref(false);
const notificationType = ref('error');
const notificationTitle = ref('');
const notificationMessage = ref('');

const form = ref({
    search: '',
    ville: props.filters.ville || '',
    capacite_min: props.filters.capacite_min || ''
});

// Formater le prix
const formatPrice = (prix) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(prix);
};

const heroImageUrl = computed(() => {
    const items = props.salles?.data || [];
    for (const salle of items) {
        const img = salle?.image_url || salle?.image || null;
        if (!img) continue;
        if (typeof img === 'string' && img.startsWith('http')) return img;
        return '/storage/' + String(img).replace(/^\/?storage\//, '');
    }
    return '/images/default-event.jpg';
});

// Formater la capacité
const formatCapacity = (capacite) => {
    return new Intl.NumberFormat('fr-FR').format(capacite);
};

// Variables pour la géolocalisation
const searchQuery = ref('');
const isLoading = ref(false);
const searchResults = ref([]);

// Variables pour les favoris
const favorites = ref([]);
const isLoadingFavorites = ref(false);

// Fonction de géolocalisation
const getCurrentLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                searchQuery.value = `${position.coords.latitude}, ${position.coords.longitude}`;
                searchNearbyRooms(position.coords.latitude, position.coords.longitude);
            },
            (error) => {
                notificationType.value = 'error';
                notificationTitle.value = 'Erreur de géolocalisation';
                notificationMessage.value = 'Impossible d\'obtenir votre position. Veuillez entrer votre adresse manuellement.';
                showNotificationModal.value = true;
            }
        );
    } else {
        notificationType.value = 'error';
        notificationTitle.value = 'Erreur de géolocalisation';
        notificationMessage.value = 'La géolocalisation n\'est pas supportée par votre navigateur.';
        showNotificationModal.value = true;
    }
};

// Recherche des salles à proximité
const searchNearbyRooms = async (lat, lng) => {
    isLoading.value = true;
    
    try {
        // Appel API pour récupérer les salles depuis la base de données
        const response = await axios.get('/api/search/nearby', {
            params: {
                lat: lat,
                lng: lng,
                radius: 50 // rayon de 50km par défaut
            }
        });
        
        // Prendre uniquement les 5 salles les plus proches
        searchResults.value = response.data.salles.slice(0, 5);
    } catch (error) {
        searchResults.value = [];
        
        // Message d'erreur plus convivial
        if (error.response && error.response.status === 422) {
            notificationType.value = 'error';
            notificationTitle.value = 'Erreur de recherche';
            notificationMessage.value = 'Coordonnées invalides. Veuillez réessayer.';
            showNotificationModal.value = true;
        } else {
            notificationType.value = 'error';
            notificationTitle.value = 'Erreur de recherche';
            notificationMessage.value = 'Erreur lors de la recherche des salles. Veuillez réessayer plus tard.';
            showNotificationModal.value = true;
        }
    } finally {
        isLoading.value = false;
    }
};

// Calcul de distance
const calculateDistance = (lat1, lon1, lat2, lon2) => {
    const R = 6371; // Rayon de la Terre en km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    const distance = R * c;
    
    return Math.round(distance * 10) / 10; // Arrondir à 1 décimale
};

// Recherche des salles
const searchRooms = async () => {
    if (searchQuery.value) {
        // Si la requête contient des coordonnées (lat, lng)
        const coords = searchQuery.value.split(',').map(s => s.trim());
        if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
            await searchNearbyRooms(parseFloat(coords[0]), parseFloat(coords[1]));
        } else {
            // Sinon, recherche par texte via les filtres existants
            form.value.search = searchQuery.value;
        }
    }
};

// Réinitialiser la recherche
const resetSearch = () => {
    searchQuery.value = '';
    searchResults.value = [];
    form.value.search = '';
};

// Fonctions pour les favoris
const loadFavorites = async () => {
    isLoadingFavorites.value = true;
    try {
        const response = await axios.get('/api/favorites');
        favorites.value = response.data.favorites || [];
    } catch (error) {
        favorites.value = [];
    } finally {
        isLoadingFavorites.value = false;
    }
};

const toggleFavorite = async (salleId) => {
    try {
        const isFavorite = favorites.value.some(fav => fav.id === salleId);
        
        if (isFavorite) {
            // Supprimer des favoris
            await axios.delete(`/api/favorites/${salleId}`);
            favorites.value = favorites.value.filter(fav => fav.id !== salleId);
            await nextTick();
        } else {
            // Ajouter aux favoris
            const response = await axios.post('/api/favorites', { salle_id: salleId });
            
            // Trouver la salle dans les résultats ou la liste principale
            let salle = searchResults.value.find(s => s.id === salleId);
            
            if (!salle && props.salles.data) {
                salle = props.salles.data.find(s => s.id === salleId);
            }
            
            if (salle) {
                // Créer une copie pour éviter les problèmes de réactivité
                const salleToAdd = { ...salle };
                favorites.value = [...favorites.value, salleToAdd];
                await nextTick();
            }
        }
    } catch (error) {
        // Gestion silencieuse des erreurs
    }
};

const isFavorite = (salleId) => {
    return favorites.value.some(fav => fav.id === salleId);
};

// Formater la date
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Watch pour les changements de filtres
watch(form, (newFilters) => {
    const params = new URLSearchParams();
    
    if (newFilters.search) params.append('search', newFilters.search);
    if (newFilters.ville) params.append('ville', newFilters.ville);
    if (newFilters.capacite_min) params.append('capacite_min', newFilters.capacite_min);
    
    window.location.href = `/search/rooms?${params.toString()}`;
}, { deep: true });

// Charger les favoris au démarrage
onMounted(() => {
    loadFavorites();
});
</script>

<template>
  <Head title="Salles de Gaming - YOUPIHUB" />
  
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden bg-background-light font-display">
    <!-- Navigation Component -->
    <Navigation :user="$page.props.auth?.user" current-page="salles" />

    <!-- Main Content -->
    <main class="layout-container flex h-full grow flex-col pt-20">
      <!-- Hero Section -->
      <section class="relative text-white py-12">
        <img
          :src="heroImageUrl"
          alt="Salles"
          class="absolute inset-0 w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center">
            <h1 class="text-3xl font-bold mb-4">Découvrez les Meilleures Salles de Gaming</h1>
            <p class="text-lg mb-6">Trouvez la salle parfaite pour vos sessions de gaming</p>
          
          <!-- Réservations récentes -->
          <div v-if="reservations && reservations.length > 0" class="mt-8">
            <h3 class="text-lg font-semibold mb-4">Vos réservations à venir</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div 
                v-for="reservation in reservations" 
                :key="reservation.id"
                class="bg-white/10 backdrop-blur-sm rounded-lg p-4"
              >
                <div class="font-medium">{{ reservation.salle?.nom }}</div>
                <div class="text-sm opacity-90">{{ formatDate(reservation.date_heure) }}</div>
                <div class="text-sm opacity-90">{{ reservation.duree }}h</div>
              </div>
            </div>
          </div>
          </div>
        </div>
      </section>

    <!-- Filtres -->
    <section class="bg-content-light py-6 border-b border-border-light">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Barre de recherche principale -->
        <div class="mb-6">
          <div class="max-w-2xl mx-auto">
            <div class="flex gap-3">
              <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-search text-text-light/50"></i>
                </div>
                <input 
                  v-model="searchQuery" 
                  type="text" 
                  placeholder="Rechercher une salle, une ville, un jeu..." 
                  class="block w-full pl-10 pr-3 py-3 border border-border-light rounded-lg leading-5 bg-subtle-light text-text-light placeholder:text-text-light/70 focus:outline-none focus:placeholder-text-text-light/70 focus:ring-2 focus:ring-primary focus:border-primary"
                  @keyup.enter="searchRooms"
                >
              </div>
              <button 
                @click="getCurrentLocation"
                class="px-4 py-3 bg-subtle-light text-text-light rounded-lg hover:bg-border-light transition-all duration-300 border border-border-light"
                title="Utiliser ma position actuelle"
              >
                <i class="fas fa-location-crosshairs"></i>
              </button>
              <button 
                @click="resetSearch"
                class="px-4 py-3 bg-subtle-light text-text-light rounded-lg hover:bg-border-light transition-all duration-300 border border-border-light"
                title="Réinitialiser la recherche"
              >
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Résultats de géolocalisation -->
    <section v-show="isLoading || searchResults.length > 0" class="py-8 bg-subtle-light">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Loading state -->
        <div v-if="isLoading" class="text-center mb-6">
          <i class="fas fa-spinner fa-spin text-2xl text-primary mb-2"></i>
          <h3 class="text-lg font-semibold text-text-light">Recherche en cours...</h3>
          <p class="text-text-light/70">Détection de votre position et recherche des salles à proximité</p>
        </div>

        <!-- Results from geolocation -->
        <div v-if="!isLoading">
          <!-- Debug info -->
          <div v-if="searchResults.length > 0" class="mb-4 p-4 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-800">Debug: {{ searchResults.length }} salles trouvées</p>
          </div>
          
          <h2 v-if="searchResults.length > 0" class="text-2xl font-bold text-text-light mb-6">
            Les {{ searchResults.length }} salles les plus proches
          </h2>
          
          <!-- Empty state for geolocation -->
          <div v-if="searchResults.length === 0" class="text-center py-12">
            <i class="fas fa-map-location-dot text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-semibold text-text-light mb-2">Aucune salle trouvée près de vous</h3>
            <p class="text-text-light/70 mb-4">Essayez d'élargir votre recherche ou utilisez les filtres ci-dessous</p>
          </div>
          
          <div v-if="searchResults.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div 
              v-for="salle in searchResults" 
              :key="salle.id"
              class="bg-content-light rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300"
            >
              <!-- Image -->
              <div class="h-48 bg-gradient-to-br from-primary to-primary/70 rounded-lg overflow-hidden">
                <img 
                  v-if="salle.image_url"
                  :src="salle.image_url.startsWith('http') ? salle.image_url : `/storage/${salle.image_url}`" 
                  :alt="salle.nom"
                  class="w-full h-full object-cover"
                >
                <div v-else class="w-full h-full flex items-center justify-center text-white/50">
                  <i class="fas fa-gamepad text-4xl"></i>
                </div>
              </div>

              <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                  <h3 class="text-lg font-bold text-text-light">{{ salle.nom }}</h3>
                  <div v-if="salle.distance !== null && salle.distance !== undefined" class="flex items-center gap-1 bg-primary/10 px-2 py-1 rounded-full">
                    <i class="fas fa-location-dot text-primary text-xs"></i>
                    <span class="text-primary text-sm font-semibold">{{ salle.distance }} km</span>
                  </div>
                  <div v-else class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded-full">
                    <i class="fas fa-map-pin text-gray-500 text-xs"></i>
                    <span class="text-gray-600 text-xs font-semibold">Distance non disponible</span>
                  </div>
                </div>
                
                <div class="flex items-center gap-2 text-sm text-text-light/70 mb-3">
                  <i class="fas fa-map-marker-alt"></i>
                  <span>{{ salle.adresse || salle.ville }}</span>
                </div>

                <div class="flex items-center justify-between mb-3">
                  <p class="text-lg font-bold text-primary">{{ formatPrice(salle.prix_heure) }}</p>
                  <span class="text-sm text-text-light/70">/heure</span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-sm text-text-light/70 mb-4">
                  <div class="flex items-center gap-1">
                    <i class="fas fa-users"></i>
                    <span>{{ salle.capacite || salle.capacite_max }} personnes</span>
                  </div>
                  <div class="flex items-center gap-1">
                    <i class="fas fa-wifi"></i>
                    <span>WiFi</span>
                  </div>
                </div>

                <div class="flex gap-2">
                  <Link 
                    :href="`/client/salles/${salle.id}`"
                    class="flex-1 py-2 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition-colors text-center"
                  >
                    Voir détails
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section Favoris -->
    <section v-if="favorites.length > 0" class="py-8 bg-primary/5">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
          <h2 class="text-2xl font-bold text-text-light flex items-center gap-2">
            <i class="fas fa-heart text-primary"></i>
            Mes Salles Favorites
          </h2>
        </div>

        <!-- Loading state for favorites -->
        <div v-if="isLoadingFavorites" class="text-center mb-6">
          <i class="fas fa-spinner fa-spin text-2xl text-primary mb-2"></i>
          <h3 class="text-lg font-semibold text-text-light">Chargement des favoris...</h3>
        </div>

        <!-- Favorites grid -->
        <div v-if="!isLoadingFavorites && favorites.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div 
            v-for="salle in favorites" 
            :key="salle.id"
            class="bg-content-light rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 relative"
          >
            <!-- Favorite badge -->
            <div class="absolute top-2 right-2 z-10">
              <button 
                @click="toggleFavorite(salle.id)"
                class="p-2 bg-white/90 rounded-full shadow-lg hover:bg-white transition-colors"
                title="Retirer des favoris"
              >
                <i class="fas fa-heart text-primary"></i>
              </button>
            </div>

            <!-- Image -->
            <div class="h-48 bg-gradient-to-br from-primary to-primary/70 rounded-lg overflow-hidden">
              <img 
                v-if="salle.image_url"
                :src="salle.image_url.startsWith('http') ? salle.image_url : `/storage/${salle.image_url}`" 
                :alt="salle.nom"
                class="w-full h-full object-cover"
              >
              <div v-else class="w-full h-full flex items-center justify-center text-white/50">
                <i class="fas fa-gamepad text-4xl"></i>
              </div>
            </div>

            <div class="p-6">
              <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-bold text-text-light">{{ salle.nom }}</h3>
                <div v-if="salle.distance !== null && salle.distance !== undefined" class="flex items-center gap-1 bg-primary/10 px-2 py-1 rounded-full">
                  <i class="fas fa-location-dot text-primary text-xs"></i>
                  <span class="text-primary text-sm font-semibold">{{ salle.distance }} km</span>
                </div>
              </div>
              
              <div class="flex items-center gap-2 text-sm text-text-light/70 mb-3">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ salle.adresse || salle.ville }}</span>
              </div>

              <div class="flex items-center justify-between mb-3">
                <p class="text-lg font-bold text-primary">{{ formatPrice(salle.prix_heure) }}</p>
                <span class="text-sm text-text-light/70">/heure</span>
              </div>

              <div class="grid grid-cols-2 gap-2 text-sm text-text-light/70 mb-4">
                <div class="flex items-center gap-1">
                  <i class="fas fa-users"></i>
                  <span>{{ salle.capacite || salle.capacite_max }} personnes</span>
                </div>
                <div class="flex items-center gap-1">
                  <i class="fas fa-wifi"></i>
                  <span>WiFi</span>
                </div>
              </div>

              <div class="flex gap-2">
                <Link 
                  :href="`/client/salles/${salle.id}`"
                  class="flex-1 py-2 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition-colors text-center"
                >
                  Voir détails
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Liste des salles -->
    <section class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
          <h2 class="text-2xl font-bold text-text-light">
            {{ salles.total }} salle{{ salles.total > 1 ? 's' : '' }} disponible{{ salles.total > 1 ? 's' : '' }}
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="salle in salles.data" 
            :key="salle.id"
            class="bg-content-light rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300"
          >
            <!-- Image -->
            <div class="h-48 bg-gradient-to-br from-primary to-primary/70 rounded-lg overflow-hidden">
              <img 
                v-if="salle.image_url"
                :src="salle.image_url.startsWith('http') ? salle.image_url : `/storage/${salle.image_url}`" 
                :alt="salle.nom"
                class="w-full h-full object-cover"
              >
              <div v-else class="w-full h-full flex items-center justify-center">
                <i class="fas fa-gamepad text-6xl text-white/50"></i>
              </div>
            </div>

            <!-- Contenu -->
            <div class="p-6">
              <div class="flex justify-between items-start mb-4">
                <div>
                  <h3 class="text-xl font-bold text-text-light mb-2">
                    {{ salle.nom }}
                  </h3>
                  <div class="flex items-center text-sm text-text-light/70 mb-2">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    {{ salle.ville }}, {{ salle.pays }}
                  </div>
                </div>
                <div class="text-right">
                  <div class="text-2xl font-bold text-primary">
                    {{ formatPrice(salle.prix_heure) }}
                  </div>
                  <div class="text-xs text-text-light/50">/heure</div>
                </div>
              </div>

              <!-- Description -->
              <p class="text-text-light/70 mb-4 line-clamp-3">
                {{ salle.description || 'Salle de gaming moderne équipée du meilleur matériel.' }}
              </p>

              <!-- Caractéristiques -->
              <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="flex items-center text-sm text-text-light/70">
                  <i class="fas fa-users mr-2 text-primary"></i>
                  {{ formatCapacity(salle.capacite_max) }} places
                </div>
                <div class="flex items-center text-sm text-text-light/70">
                  <i class="fas fa-wifi mr-2 text-primary"></i>
                  WiFi
                </div>
                <div class="flex items-center text-sm text-text-light/70">
                  <i class="fas fa-parking mr-2 text-primary"></i>
                  Parking
                </div>
                <div class="flex items-center text-sm text-text-light/70">
                  <i class="fas fa-shield-alt mr-2 text-primary"></i>
                  Sécurisé
                </div>
              </div>

              <!-- Actions -->
              <div class="flex gap-3">
                <Link 
                  :href="`/client/salles/${salle.id}`"
                  class="flex-1 text-center bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors"
                >
                  Voir les détails
                </Link>
                <button 
                  @click="toggleFavorite(salle.id)"
                  class="px-4 py-2 border border-border-light rounded-lg hover:bg-subtle-light transition-colors"
                  :title="isFavorite(salle.id) ? 'Retirer des favoris' : 'Ajouter aux favoris'"
                >
                  <i 
                    class="fas fa-heart" 
                    :class="isFavorite(salle.id) ? 'text-primary' : 'text-text-light/50 hover:text-primary'"
                  ></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="salles.links && salles.links.length > 3" class="mt-8">
          <div class="flex justify-center">
            <div class="flex gap-2">
              <template v-for="link in salles.links" :key="link.label">
                <Link 
                  v-if="link.url && link.label !== '...' "
                  :href="link.url"
                  v-html="link.label"
                  :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                    link.active 
                      ? 'bg-primary text-white' 
                      : 'bg-content-light text-text-light hover:bg-subtle-light border border-border-light'
                  ]"
                />
                <span 
                  v-else-if="link.label === '...'"
                  class="px-4 py-2 text-text-light/50"
                >
                  ...
                </span>
              </template>
            </div>
          </div>
        </div>
      </div>
    </section>
    </main>
  </div>
</template>

<style scoped>
/* Exact colors from the design */
.bg-background-light { background-color: #f6f8f6; }
.bg-content-light { background-color: #ffffff; }
.bg-subtle-light { background-color: #f0f4f2; }
.text-text-light { color: #111813; }
.text-text-light\/70 { color: #111813; opacity: 0.7; }
.text-primary { color: #3b82f6; }
.bg-primary { background-color: #3b82f6; }
.border-border-light { border-color: #dbe6df; }
.hover\:bg-border-light:hover { background-color: #dbe6df; }

/* Accent cyan color */
.text-accent-cyan { color: #06b6d4; }
.hover\:text-accent-cyan:hover { color: #06b6d4; }

/* Material Icons configuration */
.material-symbols-outlined {
  font-variation-settings:
    'FILL' 0,
    'wght' 400,
    'GRAD' 0,
    'opsz' 24;
  vertical-align: middle;
}

.material-icons-round {
  font-family: 'Material Icons Round';
  font-weight: normal;
  font-style: normal;
  font-size: 24px;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-block;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  font-feature-settings: 'liga';
  -webkit-font-smoothing: antialiased;
}

/* Font family */
.font-display {
  font-family: "Plus Jakarta Sans", sans-serif;
}

/* Custom border radius values */
.rounded-lg {
  border-radius: 1rem;
}

.rounded-full {
  border-radius: 9999px;
}

/* Tracking utility */
.tracking-light {
  letter-spacing: -0.025em;
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
