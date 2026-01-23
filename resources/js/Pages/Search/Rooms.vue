<template>
  <div class="min-h-screen bg-white font-display antialiased selection:bg-primary/20 selection:text-primary">
    <!-- Navigation -->
    <MainNavbar />

    <!-- Hero Section with Search -->
    <section class="relative min-h-[500px] sm:min-h-[600px] pt-16 sm:pt-20 overflow-hidden">
      <!-- Background Image with Overlay -->
      <div class="absolute inset-0">
        <img 
          src="https://picsum.photos/seed/gaming-room-search/1920/800.jpg" 
          alt="Gaming Room Background"
          class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-br from-primary/60 via-blue-600/50 to-indigo-700/60"></div>
      </div>
      
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 right-20 w-40 h-40 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-1/3 w-36 h-36 bg-white rounded-full blur-3xl"></div>
      </div>
      
      <div class="relative z-10 max-w-[1320px] mx-auto px-4 sm:px-6 py-12 sm:py-16">
        <div class="text-center mb-12">
          <h1 class="text-white text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1] mb-4 sm:mb-6">
            Trouvez votre salle<br/>de gaming parfaite
          </h1>
          <p class="text-white/80 text-lg md:text-xl font-normal leading-relaxed max-w-2xl mx-auto">
            Découvrez les meilleures salles de gaming près de chez vous avec nos filtres avancés
          </p>
        </div>
        
        <!-- Search Bar -->
        <div class="max-w-3xl mx-auto mb-8 sm:mb-12">
          <div class="bg-white rounded-2xl shadow-2xl p-2">
            <div class="flex flex-col md:flex-row gap-2">
              <div class="flex-1 flex items-center px-4 py-3">
                <i class="fas fa-search text-2xl sm:text-3xl text-primary"></i>
                <input 
                  v-model="searchQuery"
                  type="text" 
                  class="w-full text-soft-black placeholder-gray-500 outline-none text-sm"
                  placeholder="Ville, code postal, ou adresse..."
                  @keyup.enter="searchRooms"
                />
              </div>
              <div class="flex gap-2">
                <button 
                  @click="getCurrentLocation"
                  class="px-6 py-3 bg-gray-100 text-soft-black rounded-xl hover:bg-gray-200 transition-all duration-300 flex items-center gap-2"
                  title="Utiliser ma position actuelle"
                >
                  <i class="fas fa-location-dot"></i>
                  <span class="hidden sm:inline">Ma position</span>
                </button>
                <button 
                  @click="searchRooms"
                  class="px-8 py-3 bg-primary text-white rounded-xl hover:bg-blue-600 transition-all duration-300 flex items-center gap-2 shadow-lg"
                >
                  <i class="fas fa-search"></i>
                  Rechercher
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Quick Filters -->
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
          <button 
            v-for="city in popularCities" 
            :key="city"
            @click="searchByCity(city)"
            class="bg-white/10 backdrop-blur-md hover:bg-white/20 text-white border border-white/20 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all"
          >
            {{ city }}
          </button>
        </div>
      </div>
    </section>

    <!-- Results Section -->
    <section class="py-12 sm:py-16 bg-gray-50">
      <div class="max-w-[1320px] mx-auto px-6 py-16">
        <!-- Loading State -->
        <div v-if="isLoading" class="text-center py-20">
          <div class="inline-flex items-center gap-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
            <span class="text-soft-black text-lg">Recherche en cours...</span>
          </div>
        </div>

        <!-- Search Results -->
        <div v-else-if="hasResults">
          <!-- Results Header -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
              <h2 class="text-soft-black text-3xl font-bold tracking-tight mb-2">
                {{ totalResults }} salle{{ totalResults > 1 ? 's' : '' }} trouvée{{ totalResults > 1 ? 's' : '' }}
              </h2>
              <p class="text-sm sm:text-base text-medium-grey">
                {{ searchQuery ? `Résultats pour "${searchQuery}"` : 'Toutes les salles disponibles' }}
              </p>
            </div>
            
            <!-- Sort Dropdown -->
            <div class="flex items-center gap-3">
              <div class="flex items-center gap-3">
                <select class="bg-white border border-gray-200 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                  <option>Trier par pertinence</option>
                  <option>Prix croissant</option>
                  <option>Prix décroissant</option>
                  <option>Distance</option>
                  <option>Note</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Results Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 sm:gap-8">
            <div 
              v-for="salle in allRooms" 
              :key="salle.id"
              class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group cursor-pointer"
              @click="viewRoom(salle)"
            >
              <!-- Image -->
              <div class="relative h-48 sm:h-56 overflow-hidden">
                <img 
                  :src="salle.image_url ? '/storage/' + salle.image_url : 'https://picsum.photos/seed/room-' + salle.id + '/400/300.jpg'"
                  :alt="salle.nom"
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                />
                
                <!-- Distance Badge -->
                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2 sm:px-3 py-1 rounded-full flex items-center gap-1">
                  <i class="fas fa-location-dot text-xs sm:text-sm text-primary"></i>
                  <span class="text-xs sm:text-sm font-medium text-gray-900">{{ salle.distance || '2.5' }} km</span>
                </div>
              </div>

              <!-- Content -->
              <div class="p-4 sm:p-6">
                <div class="flex items-start justify-between mb-4">
                  <div class="flex-1">
                    <h3 class="text-lg sm:text-xl font-bold text-soft-black mb-2 group-hover:text-primary transition-colors">
                      {{ salle.nom }}
                    </h3>
                    <div class="flex items-center gap-2 text-medium-grey text-sm mb-3">
                      <i class="fas fa-map-marker-alt text-[16px]"></i>
                      <span>{{ salle.ville }}</span>
                    </div>
                  </div>
                </div>

                <!-- Description -->
                <p v-if="salle.description" class="text-medium-grey text-sm mb-4 line-clamp-2">
                  {{ salle.description }}
                </p>

                <!-- Features -->
                <div class="flex flex-wrap gap-2 mb-4">
                  <span v-if="salle.capacite_max" class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">
                    {{ salle.capacite_max }} personnes
                  </span>
                  <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">
                    WiFi
                  </span>
                  <span v-if="salle.parking" class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">
                    Parking
                  </span>
                </div>

                <!-- Price and Action -->
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 pt-4 border-t border-gray-100">
                  <div>
                    <div class="text-2xl sm:text-3xl font-bold text-soft-black mb-1">
                      {{ formatPrice(salle.prix_heure) }}
                    </div>
                    <div class="text-medium-grey text-sm">/heure</div>
                  </div>
                  <div class="flex gap-2 w-full md:w-auto">
                    <button 
                      @click="viewRoom(salle)"
                      class="flex-1 md:flex-none px-3 md:px-4 py-2 border border-gray-200 text-soft-black rounded-xl hover:border-primary hover:text-primary transition-all text-sm font-semibold"
                    >
                      Détails
                    </button>
                    <button 
                      @click="reserveRoom(salle)"
                      class="flex-1 md:flex-none px-3 md:px-4 py-2 bg-primary text-white rounded-xl hover:bg-blue-600 transition-all text-sm font-semibold"
                    >
                      Réserver
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Load More -->
          <div v-if="hasMoreResults" class="text-center mt-8 sm:mt-12">
            <button 
              @click="loadMore"
              class="bg-white hover:bg-gray-50 border border-gray-200 text-gray-900 px-6 sm:px-8 py-3 rounded-full text-sm sm:text-base font-medium transition-all"
            >
              Charger plus de résultats
            </button>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-20">
          <div class="max-w-md mx-auto">
            <div class="size-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-search text-[40px] text-gray-400"></i>
            </div>
            <h3 class="text-soft-black text-2xl font-bold mb-3">Aucune salle trouvée</h3>
            <p class="text-medium-grey text-lg mb-8">
              Essayez avec d'autres critères de recherche ou utilisez votre position actuelle
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
              <button 
                @click="getCurrentLocation"
                class="p-2 sm:p-2.5 hover:bg-gray-100 rounded-full transition-colors"
                title="Utiliser ma position"
              >
                <span class="material-symbols-outlined text-xl sm:text-2xl text-primary">location_on</span>
              </button>
              <button 
                @click="searchRooms"
                class="bg-primary hover:bg-blue-600 text-white px-4 sm:px-6 py-2.5 sm:py-3.5 rounded-full font-bold text-sm sm:text-base transition-all shadow-lg shadow-primary/20"
              >
                Rechercher
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <MainFooter />
  </div>
</template>

<script setup>
  // ...
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import MainNavbar from '@/Components/MainNavbar.vue';
import MainFooter from '@/Components/MainFooter.vue';

const props = defineProps({
    salles: Object,
    villes: Array,
    filters: Object
});

// Reactive data
const searchQuery = ref(props.filters?.search || '');
const isLoading = ref(false);
const searchResults = ref([]);
const allRooms = ref([]);
const isMobileMenuOpen = ref(false);

// État d'authentification (à implémenter avec votre système d'auth)
const authUser = ref(null);

// Popular cities for quick filters
const popularCities = ref([]);

// Computed properties
const totalResults = computed(() => {
    return allRooms.value.length;
});

const hasResults = computed(() => {
    return allRooms.value.length > 0;
});

const hasMoreResults = computed(() => {
    return false; // À implémenter avec la pagination
});

// Methods
const formatPrice = (prix) => {
    if (!prix) return 'Gratuit';
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(prix);
};

const getCurrentLocation = () => {
    console.log('Détection de position demandée...');
    console.log('Navigator geolocation:', navigator.geolocation);
    console.log('Protocole actuel:', window.location.protocol);
    console.log('URL actuelle:', window.location.href);
    
    // Vérifier si nous sommes en HTTP (problème pour la géolocalisation)
    if (window.location.protocol === 'http:' && window.location.hostname !== 'localhost') {
        console.warn('Attention: La géolocalisation peut ne pas fonctionner en HTTP sur les domaines non-localhost');
    }
    
    if (!navigator.geolocation) {
        console.error('Géolocalisation non supportée par ce navigateur');
        alert('La géolocalisation n\'est pas supportée par votre navigateur. Veuillez utiliser une recherche manuelle.');
        return;
    }

    isLoading.value = true;
    
    // Afficher une indication visuelle
    const button = event?.currentTarget;
    let originalHTML = '';
    
    if (button) {
        originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span class="hidden sm:inline">Détection...</span>';
        button.disabled = true;
    }

    // D'abord vérifier les permissions
    navigator.permissions.query({ name: 'geolocation' }).then((result) => {
        console.log('État des permissions de géolocalisation:', result.state);
        
        if (result.state === 'denied') {
            alert('L\'accès à la localisation a été refusé. Veuillez modifier les permissions dans les paramètres de votre navigateur.');
            if (button) {
                button.innerHTML = originalHTML;
                button.disabled = false;
            }
            isLoading.value = false;
            return;
        }
        
        // Procéder à la demande de position
        navigator.geolocation.getCurrentPosition(
            (position) => {
                console.log('Position obtenue:', position.coords);
                console.log('Latitude:', position.coords.latitude, 'Longitude:', position.coords.longitude);
                
                if (button) {
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }
                
                searchNearbyRooms(position.coords.latitude, position.coords.longitude);
            },
            (error) => {
                console.error('Erreur de géolocalisation:', error);
                console.error('Code erreur:', error.code);
                console.error('Message erreur:', error.message);
                
                if (button) {
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }
                
                isLoading.value = false;
                
                let errorMessage = 'Impossible d\'obtenir votre position. ';
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += 'Veuillez autoriser l\'accès à votre localisation dans les paramètres de votre navigateur.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += 'Les informations de localisation ne sont pas disponibles.';
                        break;
                    case error.TIMEOUT:
                        errorMessage += 'La demande de localisation a expiré. Veuillez réessayer.';
                        break;
                    default:
                        errorMessage += 'Erreur inconnue (code: ' + error.code + ')';
                        break;
                }
                
                alert(errorMessage);
                // Fallback: utiliser une recherche par défaut
                searchRooms();
            },
            {
                enableHighAccuracy: true,
                timeout: 15000, // Augmenté à 15 secondes
                maximumAge: 300000 // 5 minutes
            }
        );
    }).catch((error) => {
   });
};

// Fallback si les permissions ne sont pas supportées
const getCurrentPositionFallback = () => {
    navigator.geolocation.getCurrentPosition(
        (position) => {
            console.log('Position obtenue (fallback):', position.coords);
            searchNearbyRooms(position.coords.latitude, position.coords.longitude);
        },
        (error) => {
            console.error('Erreur de géolocalisation (fallback):', error);
            isLoading.value = false;
            alert('Erreur lors de l\'obtention de votre position. Veuillez réessayer.');
        },
        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 300000
        }
    );
};

const searchNearbyRooms = async (lat, lng) => {
    console.log(`Recherche des salles près de ${lat}, ${lng}`);
    
    try {
        const response = await axios.get('/api/search/nearby', {
            params: {
                lat: lat,
                lng: lng,
                radius: 50
            }
        });
        
        console.log('Réponse API:', response.data);
        
        allRooms.value = response.data.salles || [];
        searchResults.value = response.data.salles || [];
        
        // Mettre à jour l'URL avec les paramètres de recherche
        const url = new URL(window.location);
        url.searchParams.set('lat', lat);
        url.searchParams.set('lng', lng);
        url.searchParams.set('search', 'près de moi');
        window.history.replaceState({}, '', url);
        
    } catch (error) {
        console.error('Erreur recherche nearby:', error);
        
        if (error.response) {
            // Le serveur a répondu avec un statut d'erreur
            console.error('Status:', error.response.status);
            console.error('Data:', error.response.data);
            
            if (error.response.status === 422) {
                alert('Erreur de validation: ' + JSON.stringify(error.response.data.errors));
            } else {
                alert('Erreur serveur lors de la recherche: ' + (error.response.data.message || 'Veuillez réessayer plus tard.'));
            }
        } else if (error.request) {
            // La requête a été faite mais aucune réponse reçue
            console.error('Pas de réponse du serveur');
            alert('Impossible de contacter le serveur. Vérifiez votre connexion internet.');
        } else {
            // Erreur lors de la configuration de la requête
            console.error('Erreur de configuration:', error.message);
            alert('Erreur lors de la recherche: ' + error.message);
        }
        
        // Fallback: utiliser les salles existantes
        loadExistingRooms();
    } finally {
        isLoading.value = false;
    }
};

const searchRooms = async () => {
    console.log('Recherche lancée avec:', searchQuery.value);
    
    if (!searchQuery.value.trim()) {
        console.log('Recherche vide, chargement des salles existantes');
        loadExistingRooms();
        return;
    }
    
    isLoading.value = true;
    
    try {
        // Simuler une recherche - en production, utiliser une vraie API
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        console.log('Filtrage des salles avec:', searchQuery.value);
        console.log('Salles disponibles:', props.salles?.data?.length || 0);
        
        // Filtrer les salles existantes selon la recherche
        const filtered = props.salles?.data?.filter(salle => {
            const searchLower = searchQuery.value.toLowerCase();
            const matchName = salle.nom?.toLowerCase().includes(searchLower);
            const matchCity = salle.ville?.toLowerCase().includes(searchLower);
            const matchDescription = salle.description?.toLowerCase().includes(searchLower);
            
            return matchName || matchCity || matchDescription;
        }) || [];
        
        console.log('Salles filtrées:', filtered.length);
        allRooms.value = filtered;
        
        // Update URL with search parameters
        const url = new URL(window.location);
        url.searchParams.set('search', searchQuery.value);
        window.history.replaceState({}, '', url);
        
    } catch (error) {
        console.error('Erreur recherche:', error);
        loadExistingRooms();
    } finally {
        isLoading.value = false;
    }
};

const searchByCity = (city) => {
    searchQuery.value = city;
    searchRooms();
};

const loadExistingRooms = () => {
    // Utiliser les salles existantes depuis les props
    allRooms.value = props.salles?.data || [];
};

const resetSearch = () => {
    searchQuery.value = '';
    loadExistingRooms();
    // Reset URL parameters
    const url = new URL(window.location);
    url.searchParams.delete('search');
    url.searchParams.delete('ville');
    url.searchParams.delete('capacite_min');
    window.history.replaceState({}, '', url);
    // Rechercher les salles existantes
    searchRooms();
};

const viewRoom = (salle) => {
    // Naviguer vers la page de détails de la salle
    window.location.href = `/salles/${salle.id}`;
};

const reserveRoom = (salle) => {
  // Vérifier si l'utilisateur est connecté
  if (!authUser.value) {
    // Rediriger vers la page de connexion
    window.location.href = '/login';
    return;
  }
  // Naviguer vers la page de réservation
  window.location.href = `/salles/${salle.id}/reserver`;
};

const loadMore = () => {
    // Implémenter le chargement de plus de résultats
    console.log('Charger plus de résultats');
    // À implémenter avec la pagination
};

// Lifecycle
onMounted(() => {
    loadExistingRooms();
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap');

.font-display {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* Tailwind custom colors */
:root {
  --primary: #135bec;
  --soft-black: #111827;
  --medium-grey: #6B7280;
}

.text-primary {
  color: var(--primary);
}

.bg-primary {
  background-color: var(--primary);
}

.text-soft-black {
  color: var(--soft-black);
}

.text-medium-grey {
  color: var(--medium-grey);
}

.bg-soft-black {
  background-color: var(--soft-black);
}

/* Material Symbols */
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

/* Line clamp utility */
.line-clamp-2 {
  display: -webkit-box;
  display: -moz-box;
  display: box;
  -webkit-line-clamp: 2;
  -moz-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  -moz-box-orient: vertical;
  box-orient: vertical;
  overflow: hidden;
}
</style>
