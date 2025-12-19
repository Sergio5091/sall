<template>
  <div class="min-h-screen text-gray-800 font-body">
    <!-- TopNavBar -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center backdrop-blur-sm shadow-sm bg-white/90">
      <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-2 text-gray-800">
            <i class="fas fa-gamepad text-3xl text-gray-400"></i>
            <h2 class="text-gray-800 text-2xl font-display font-bold">GameOn</h2>
          </div>
          <nav class="hidden md:flex space-x-8">
            <a href="/" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Accueil</a>
            <a href="/search/rooms" class="text-blue-600 hover:text-blue-700 px-3 py-2 text-sm font-medium border-b-2 border-blue-600">Salles</a>
            <a href="/events" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Événements</a>
          </nav>
        </div>
        <div class="flex items-center gap-3">
          <a href="/login" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-200 text-gray-800 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-300 transition-colors">
            <span class="truncate">Connexion</span>
          </a>
          <a href="/register" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-800 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-700 transition-all">
            <span class="truncate">S'inscrire</span>
          </a>
        </div>
      </div>
    </header>

    <!-- Search Section -->
    <section class="relative min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 pt-20">
      <!-- Overlay gradient pour la lisibilité -->
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/20 to-white/60 z-5"></div>
      
      <div class="relative z-10 max-w-4xl mx-auto px-4 py-12">
          <!-- Bouton Retour -->
          <div class="mb-6">
            <a href="/" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors group">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
              </svg>
              <span class="text-lg text-gray-800">Retour à l'accueil</span>
            </a>
          </div>

          <!-- Search Header -->
          <div class="text-center mb-8 animate-fade-in-up">
            <h1 class="text-gray-800 text-3xl sm:text-4xl md:text-5xl font-display font-bold leading-tight tracking-tighter mb-4">
              Trouver une salle près de chez vous
            </h1>
            <p class="text-gray-600 text-lg font-normal leading-normal">
              Entrez votre localisation pour découvrir les salles de jeux disponibles
            </p>
          </div>

        <!-- Search Form -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-2xl border border-gray-600/20 animate-fade-in-up delay-200">
          <div class="space-y-6">
            <!-- Location Input -->
            <div>
              <label class="block text-gray-800 text-sm font-medium mb-2">
                <svg class="w-4 h-4 mr-2 inline text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                Votre localisation
              </label>
              <div class="flex gap-3">
                <div class="flex-1">
                  <input 
                    v-model="searchQuery"
                    type="text" 
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                    placeholder="Ville, code postal ou adresse..."
                    @keyup.enter="searchRooms"
                  />
                </div>
                <button 
                  @click="getCurrentLocation"
                  class="px-4 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-all duration-300"
                  title="Utiliser ma position actuelle"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Search Button -->
            <button 
              @click="searchRooms"
              class="w-full py-4 bg-blue-600 text-white text-lg font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-lg flex items-center justify-center"
            >
              <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
              </svg>
              Rechercher des salles
            </button>
          </div>
        </div>

        
        <!-- Results -->
        <!-- Results from geolocation -->
        <div class="mt-8 animate-fade-in-up delay-400">
          <h2 class="text-gray-800 text-2xl font-bold mb-6">
            {{ searchResults.length }} salle{{ searchResults.length > 1 ? 's' : '' }} trouvée{{ searchResults.length > 1 ? 's' : '' }} près de vous
          </h2>
          
          <div v-if="searchResults.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="salle in searchResults" 
              :key="salle.id"
              class="bg-white/90 backdrop-blur-sm rounded-xl p-6 border border-gray-200 hover:border-gray-300 transition-all duration-300 group"
            >
              <!-- Image -->
              <div class="h-48 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg mb-4 overflow-hidden">
                <img 
                  v-if="salle.image_url"
                  :src="salle.image_url" 
                  :alt="salle.nom"
                  class="w-full h-full object-cover"
                >
                <div v-else class="w-full h-full flex items-center justify-center text-gray-500">
                  <i class="fas fa-gamepad text-4xl text-gray-400"></i>
                </div>
              </div>

              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <h3 class="text-gray-800 text-lg font-bold">{{ salle.nom }}</h3>
                  <div v-if="salle.distance" class="flex items-center gap-1 bg-blue-100 px-2 py-1 rounded-full">
                    <i class="fas fa-location-dot text-blue-600 text-xs"></i>
                    <span class="text-blue-600 text-sm font-semibold">{{ salle.distance }} km</span>
                  </div>
                  <div v-else class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded-full">
                    <i class="fas fa-map-pin text-gray-600 text-xs"></i>
                    <span class="text-gray-600 text-sm font-semibold">Localisation approx.</span>
                  </div>
                </div>
                
                <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                  <i class="fas fa-map-marker-alt text-gray-400"></i>
                  <span class="text-gray-800">{{ salle.adresse }}</span>
                </div>

                <div class="flex items-center justify-between mb-2">
                  <p class="text-lg font-bold text-blue-600">{{ formatPrice(salle.prix_heure) }}</p>
                  <span class="text-sm text-gray-600">/heure</span>
                </div>

                <div class="grid grid-cols-1 gap-2 text-sm text-gray-600">
                  <div class="flex items-center gap-1">
                    <i class="fas fa-users text-gray-400"></i>
                    <span class="font-semibold text-gray-800">{{ salle.capacite }} personnes</span>
                  </div>
                  <div class="flex items-center gap-1">
                    <i class="fas fa-wifi text-gray-400"></i>
                    <span>WiFi disponible</span>
                  </div>
                </div>

                <div class="flex gap-2 pt-2">
                  <Link 
                    :href="`/salles/${salle.id}`"
                    class="flex-1 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors text-center"
                  >
                    Voir détails
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty state -->
        <div v-if="!isLoading && searchResults.length === 0" class="mt-8 text-center">
          <div class="bg-white/90 backdrop-blur-sm rounded-xl p-8 border border-gray-200">
            <i class="fas fa-map-location-dot text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-gray-800 text-lg font-bold mb-2">Aucune salle trouvée</h3>
            <p class="text-gray-600 mb-4">Cliquez sur le bouton de localisation pour trouver les salles près de vous</p>
            <button 
              @click="getCurrentLocation"
              class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors inline-flex items-center gap-2"
            >
              <i class="fas fa-location-crosshairs"></i>
              <span>DéTECTER MA POSITION</span>
            </button>
          </div>
        </div>

        <!-- Loading state -->
        <div v-if="isLoading" class="mt-8 text-center">
          <div class="bg-white/90 backdrop-blur-sm rounded-xl p-8 border border-gray-200">
            <i class="fas fa-spinner fa-spin text-3xl text-blue-600 mb-4"></i>
            <h3 class="text-gray-800 text-lg font-bold mb-2">Recherche en cours...</h3>
            <p class="text-gray-600">Détection de votre position et recherche des salles nearby</p>
          </div>
        </div>

        <!-- Original Results -->
        <div v-if="salles.data.length > 0" class="mt-8">
          <h2 class="text-gray-800 text-2xl font-bold mb-6">
            {{ salles.total }} salle{{ salles.total > 1 ? 's' : '' }} disponible{{ salles.total > 1 ? 's' : '' }}
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="salle in salles.data" 
              :key="salle.id"
              class="bg-white/90 backdrop-blur-sm rounded-xl p-6 border border-gray-200 hover:border-gray-300 transition-all duration-300 group"
            >
              <!-- Image -->
              <div class="h-48 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg mb-4 overflow-hidden">
                <img 
                  v-if="salle.image_url"
                  :src="salle.image_url.startsWith('http') ? salle.image_url : `/storage/${salle.image_url}`" 
                  :alt="salle.nom"
                  class="w-full h-full object-cover"
                >
                <div v-else class="w-full h-full flex items-center justify-center text-gray-500">
                  <i class="fas fa-gamepad text-4xl text-gray-400"></i>
                </div>
              </div>

              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <h3 class="text-gray-800 text-lg font-bold">{{ salle.nom }}</h3>
                  <div v-if="salle.distance" class="flex items-center gap-1 bg-blue-100 px-2 py-1 rounded-full">
                    <i class="fas fa-location-dot text-blue-600 text-xs"></i>
                    <span class="text-blue-600 text-sm font-semibold">{{ salle.distance }} km</span>
                  </div>
                  <div v-else class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded-full">
                    <i class="fas fa-map-pin text-gray-600 text-xs"></i>
                    <span class="text-gray-600 text-sm font-semibold">Localisation approx.</span>
                  </div>
                </div>
                
                <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                  <i class="fas fa-map-marker-alt text-gray-400"></i>
                  <span class="text-gray-800">{{ salle.adresse || salle.ville }}</span>
                </div>

                <div class="flex items-center justify-between mb-2">
                  <p class="text-lg font-bold text-blue-600">{{ formatPrice(salle.prix_heure) }}</p>
                  <span class="text-sm text-gray-600">/heure</span>
                </div>

                <div class="grid grid-cols-1 gap-2 text-sm text-gray-600">
                  <div class="flex items-center gap-1">
                    <i class="fas fa-users text-gray-400"></i>
                    <span class="font-semibold text-gray-800">{{ salle.capacite }} personnes</span>
                  </div>
                  <div class="flex items-center gap-1">
                    <i class="fas fa-wifi text-gray-400"></i>
                    <span>WiFi disponible</span>
                  </div>
                </div>

                <p class="text-gray-600 text-sm mb-4">{{ salle.description }}</p>

                <div class="flex gap-2 pt-2">
                  <Link 
                    :href="`/salles/${salle.id}`"
                    class="flex-1 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors text-center"
                  >
                    Voir détails
                  </Link>
                  <Link 
                    href="/login"
                    class="flex-1 py-2 border border-gray-500 text-gray-400 rounded-xl hover:bg-gray-500/10 transition-colors text-center"
                  >
                    Réserver
                  </Link>
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
                        ? 'bg-gray-600 text-white' 
                        : 'bg-[#1c1c27] text-gray-300 hover:bg-gray-600 border border-gray-600'
                    ]"
                  />
                  <span 
                    v-else-if="link.label === '...'"
                    class="px-4 py-2 text-gray-500"
                  >
                    ...
                  </span>
                </template>
              </div>
            </div>
          </div>
        </div>

        <!-- No Results -->
        <div v-else class="mt-8 text-center py-12">
          <i class="fas fa-search text-6xl text-gray-500 mb-4"></i>
          <h3 class="text-gray-800 text-xl font-bold mb-2">Aucune salle trouvée pour votre recherche.</h3>
          <p class="text-gray-400">
            Essayez de modifier vos critères de recherche
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
/* Page Animations */
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slide-in-left {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s ease-out forwards;
  opacity: 0;
}

.animate-fade-in {
  animation: fade-in 0.6s ease-out forwards;
  opacity: 0;
}

.animate-slide-in-left {
  animation: slide-in-left 0.8s ease-out forwards;
  opacity: 0;
}

.delay-200 { animation-delay: 0.2s; }
.delay-400 { animation-delay: 0.4s; }
.delay-600 { animation-delay: 0.6s; }
</style>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';
import NotificationModal from '../../Components/NotificationModal.vue';

const props = defineProps({
    salles: Object,
    villes: Array,
    filters: Object
});

// États pour les modaux
const showNotificationModal = ref(false);
const notificationType = ref('error');
const notificationTitle = ref('');
const notificationMessage = ref('');

const form = ref({
    search: props.filters.search || '',
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

// Formater la capacité
const formatCapacity = (capacite) => {
    return new Intl.NumberFormat('fr-FR').format(capacite);
};

// Watch pour les changements de filtres
watch(form, (newFilters) => {
    const params = new URLSearchParams();
    
    if (newFilters.search) params.append('search', newFilters.search);
    if (newFilters.ville) params.append('ville', newFilters.ville);
    if (newFilters.capacite_min) params.append('capacite_min', newFilters.capacite_min);
    
    window.location.href = `/search/rooms?${params.toString()}`;
}, { deep: true });

// Fonctions existantes pour la compatibilité
const searchQuery = ref('');
const isLoading = ref(false);
const searchResults = ref([]);

const getCurrentLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                console.log('Position obtenue:', position.coords);
                searchQuery.value = `${position.coords.latitude}, ${position.coords.longitude}`;
                searchNearbyRooms(position.coords.latitude, position.coords.longitude);
            },
            (error) => {
                console.error('Erreur de géolocalisation:', error);
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
        
        searchResults.value = response.data.salles;
        
        if (response.data.salles.length === 0) {
            console.log('Aucune salle trouvée dans un rayon de 50km');
        }
    } catch (error) {
        console.error('Erreur lors de la recherche des salles:', error);
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

const searchRooms = async () => {
    if (searchQuery.value) {
        // Si la requête contient des coordonnées (lat, lng)
        const coords = searchQuery.value.split(',').map(s => s.trim());
        if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
            await searchNearbyRooms(parseFloat(coords[0]), parseFloat(coords[1]));
        } else {
            // Sinon, recherche par texte via les filtres existants
            console.log('Recherche de salles pour:', searchQuery.value);
            form.value.search = searchQuery.value;
        }
    }
};
</script>

<style scoped>
/* Custom styles */
.group:hover .group-hover\:text-accent-cyan {
  color: #00ffff;
}

<!-- Notification Modal -->
<NotificationModal
  :show="showNotificationModal"
  :type="notificationType"
  :title="notificationTitle"
  :message="notificationMessage"
  @close="showNotificationModal = false"
/>

</style>
