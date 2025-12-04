<template>
  <div class="min-h-screen text-[#222222] font-body">
    <!-- TopNavBar -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center backdrop-blur-sm shadow-sm">
      <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-2 text-white">
            <i class="fas fa-gamepad text-3xl text-gray-400"></i>
            <h2 class="text-white text-2xl font-display font-bold">GameOn</h2>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <a href="/login" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-600 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-500 transition-colors">
            <span class="truncate">Connexion</span>
          </a>
          <a href="/register" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-700 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-600 transition-all">
            <span class="truncate">S'inscrire</span>
          </a>
        </div>
      </div>
    </header>

    <!-- Search Section -->
    <section class="relative min-h-screen bg-background-dark pt-20">
      <!-- Animation 3D Background -->
      <Background3D />
      <!-- Overlay gradient pour la lisibilité -->
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/20 to-black/60 z-5"></div>
      
      <div class="relative z-10 max-w-4xl mx-auto px-4 py-12">
          <!-- Bouton Retour -->
          <div class="mb-6">
            <a href="/" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition-colors group">
              <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
              </svg>
              <span class="text-lg">Retour à l'accueil</span>
            </a>
          </div>

          <!-- Search Header -->
          <div class="text-center mb-8">
            <h1 class="text-white text-3xl sm:text-4xl md:text-5xl font-display font-bold leading-tight tracking-tighter mb-4">
              Trouver une salle près de chez vous
            </h1>
            <p class="text-white/80 text-lg font-normal leading-normal">
              Entrez votre localisation pour découvrir les salles de jeux disponibles
            </p>
          </div>

        <!-- Search Form -->
        <div class="bg-[#1c1c27]/90 backdrop-blur-sm rounded-2xl p-6 shadow-2xl border border-gray-600/20">
          <div class="space-y-6">
            <!-- Location Input -->
            <div>
              <label class="block text-white text-sm font-medium mb-2">
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
                    class="w-full px-4 py-3 bg-[#2a2a3a] border border-gray-600/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-gray-500 focus:ring-2 focus:ring-gray-500/20 transition-all"
                    placeholder="Ville, code postal ou adresse..."
                    @keyup.enter="searchRooms"
                  />
                </div>
                <button 
                  @click="getCurrentLocation"
                  class="px-4 py-3 bg-gray-600 text-white rounded-xl hover:bg-gray-500 transition-all duration-300 transform hover:scale-105"
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
              class="w-full py-4 bg-gradient-to-r from-gray-600 to-gray-500 text-white text-lg font-bold rounded-xl hover:from-gray-500 hover:to-gray-400 transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center justify-center"
            >
              <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
              </svg>
              Rechercher des salles
            </button>
          </div>
        </div>

        <!-- Filtres -->
        <div class="bg-[#1c1c27]/90 backdrop-blur-sm rounded-2xl p-6 shadow-2xl border border-gray-600/20 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-white text-sm font-medium mb-2">
                Recherche
              </label>
              <input 
                v-model="form.search" 
                type="text" 
                placeholder="Nom, ville, description..." 
                class="w-full px-3 py-2 bg-[#2a2a3a] border border-gray-600/30 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-gray-500"
              >
            </div>
            
            <div>
              <label class="block text-white text-sm font-medium mb-2">
                Ville
              </label>
              <select v-model="form.ville" class="w-full px-3 py-2 bg-[#2a2a3a] border border-gray-600/30 rounded-lg text-white focus:outline-none focus:border-gray-500">
                <option value="">Toutes les villes</option>
                <option v-for="ville in villes" :key="ville" :value="ville">
                  {{ ville }}
                </option>
              </select>
            </div>
            
            <div>
              <label class="block text-white text-sm font-medium mb-2">
                Capacité min
              </label>
              <input 
                v-model="form.capacite_min" 
                type="number" 
                placeholder="Ex: 50" 
                class="w-full px-3 py-2 bg-[#2a2a3a] border border-gray-600/30 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-gray-500"
              >
            </div>
          </div>
        </div>

        <!-- Results -->
        <div v-if="salles.data.length > 0" class="mt-8">
          <h2 class="text-white text-2xl font-bold mb-6">
            {{ salles.total }} salle{{ salles.total > 1 ? 's' : '' }} disponible{{ salles.total > 1 ? 's' : '' }}
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="salle in salles.data" 
              :key="salle.id"
              class="bg-[#1c1c27]/90 backdrop-blur-sm rounded-xl p-6 border border-gray-600/20 hover:border-gray-500/40 transition-all duration-300 transform hover:scale-105 group"
            >
              <!-- Image -->
              <div class="h-48 bg-gradient-to-br from-gray-600 to-gray-700 rounded-lg mb-4 overflow-hidden">
                <img 
                  v-if="salle.image_url"
                  :src="salle.image_url.startsWith('http') ? salle.image_url : `/storage/${salle.image_url}`" 
                  :alt="salle.nom"
                  class="w-full h-full object-cover"
                >
                <div v-else class="w-full h-full flex items-center justify-center">
                  <i class="fas fa-gamepad text-4xl text-gray-400"></i>
                </div>
              </div>

              <div class="space-y-3">
                <h3 class="text-white text-lg font-bold group-hover:text-gray-400 transition-colors">
                  {{ salle.nom }}
                </h3>
                
                <div class="flex items-center gap-2 text-sm text-gray-300">
                  <i class="fas fa-map-marker-alt text-gray-400"></i>
                  <span>{{ salle.ville }}, {{ salle.pays }}</span>
                </div>

                <div class="flex items-center justify-between">
                  <span class="text-xl font-bold text-green-400">
                    {{ formatPrice(salle.prix_heure) }}
                  </span>
                  <span class="text-sm text-gray-400">/heure</span>
                </div>

                <div class="grid grid-cols-1 gap-2 text-sm text-gray-300">
                  <div class="flex items-center gap-1">
                    <i class="fas fa-users text-gray-400"></i>
                    <span>{{ formatCapacity(salle.capacite_max) }} places</span>
                  </div>
                  <div class="flex items-center gap-1">
                    <i class="fas fa-wifi text-gray-400"></i>
                    <span>WiFi disponible</span>
                  </div>
                </div>

                <p class="text-gray-400 text-sm line-clamp-2">
                  {{ salle.description || 'Salle de gaming moderne équipée du meilleur matériel.' }}
                </p>

                <div class="flex gap-2 pt-2">
                  <Link 
                    :href="`/salles/${salle.id}`"
                    class="flex-1 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition-colors text-center"
                  >
                    Voir détails
                  </Link>
                  <Link 
                    href="/login"
                    class="flex-1 py-2 border border-gray-500 text-gray-400 rounded-lg hover:bg-gray-500/10 transition-colors text-center"
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
          <h3 class="text-white text-xl font-bold mb-2">Aucune salle trouvée</h3>
          <p class="text-gray-400">
            Essayez de modifier vos critères de recherche
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    salles: Object,
    villes: Array,
    filters: Object
});

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
            },
            (error) => {
                console.error('Erreur de géolocalisation:', error);
            }
        );
    }
};

const searchRooms = () => {
    console.log('Recherche de salles pour:', searchQuery.value);
};
</script>

<style scoped>
/* Custom styles */
.group:hover .group-hover\:text-accent-cyan {
  color: #00ffff;
}

.group:hover .group-hover\:scale-105 {
  transform: scale(1.05);
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
