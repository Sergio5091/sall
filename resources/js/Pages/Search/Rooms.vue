<template>
  <div class="min-h-screen text-[#222222] font-body">
    <!-- TopNavBar -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center backdrop-blur-sm shadow-sm">
      <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
        <div class="flex items-center gap-8">
          <a href="/" class="flex items-center gap-2 text-white">
            <i class="fas fa-arrow-left text-xl"></i>
            <span class="text-lg">Retour</span>
          </a>
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
                <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
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
                  <i class="fas fa-location-crosshairs"></i>
                </button>
              </div>
            </div>

            <!-- Search Button -->
            <button 
              @click="searchRooms"
              class="w-full py-4 bg-gradient-to-r from-gray-600 to-gray-500 text-white text-lg font-bold rounded-xl hover:from-gray-500 hover:to-gray-400 transition-all duration-300 transform hover:scale-105 shadow-lg"
            >
              <i class="fas fa-search mr-3"></i>
              Rechercher des salles
            </button>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="mt-8 text-center">
          <div class="inline-flex items-center gap-3 text-white">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-400"></div>
            <span>Recherche en cours...</span>
          </div>
        </div>

        <!-- Results -->
        <div v-if="searchResults.length > 0 && !isLoading" class="mt-8 space-y-4">
          <h2 class="text-white text-2xl font-bold mb-4">
            {{ searchResults.length }} salles trouvées
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div 
              v-for="room in searchResults" 
              :key="room.id"
              class="bg-[#1c1c27]/90 backdrop-blur-sm rounded-xl p-6 border border-gray-600/20 hover:border-gray-500/40 transition-all duration-300 transform hover:scale-105 group"
            >
              <div class="flex gap-4">
                <img 
                  :src="room.image" 
                  :alt="room.name"
                  class="w-24 h-24 rounded-lg object-cover"
                />
                <div class="flex-1">
                  <h3 class="text-white text-lg font-bold mb-1 group-hover:text-gray-400 transition-colors">
                    {{ room.name }}
                  </h3>
                  <div class="flex items-center gap-2 text-sm text-gray-300 mb-2">
                    <i class="fas fa-star text-yellow-500"></i>
                    <span>{{ room.rating }}</span>
                    <span>({{ room.reviews }} avis)</span>
                  </div>
                  <div class="flex items-center gap-4 text-sm text-gray-300">
                    <span class="flex items-center gap-1">
                      <i class="fas fa-map-marker-alt text-gray-400"></i>
                      {{ room.distance }}
                    </span>
                    <span class="flex items-center gap-1">
                      <i class="fas fa-euro-sign text-gray-400"></i>
                      {{ room.price }}/h
                    </span>
                  </div>
                </div>
              </div>
              <div class="mt-4 flex gap-3">
                <button class="flex-1 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition-colors">
                  Réserver
                </button>
                <button class="flex-1 py-2 border border-gray-500 text-gray-400 rounded-lg hover:bg-gray-500/10 transition-colors">
                  Détails
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- No Results -->
        <div v-if="searchResults.length === 0 && !isLoading && hasSearched" class="mt-8 text-center py-12">
          <i class="fas fa-search text-6xl text-gray-500 mb-4"></i>
          <h3 class="text-white text-xl font-bold mb-2">Aucune salle trouvée</h3>
          <p class="text-gray-400">
            Essayez d'élargir votre recherche ou de modifier les filtres
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Background3D from '../../Components/Background3D.vue';

// Data
const searchQuery = ref('');
const isLoading = ref(false);
const hasSearched = ref(false);
const searchResults = ref([]);

// Mock data for demonstration
const mockRooms = [
  {
    id: 1,
    name: 'Cyber Arena Pro',
    rating: 4.8,
    reviews: 156,
    distance: '1.2 km',
    price: '15€',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDZk-K3kdE3ajUoJvzYE-P5V9EppOKL8LPxzcGNhBBEFNBXC7F5jYXrYrwDrW0P4VKmutq7EmadaTVN_b9AVhEasTSc4KYfjGVTFn0s903IJxUxwFXFS_K1QsZ4gFcXhPRD0FaretJixko9EAwJGx96RTLdxrfXwfeugzGsGp-jYct8KgevHhFT-0FSU6WxM0SZ5Phpkqu5Q6RHdudPx25ttQcImu_6BD-CPUjTWe7VC8fHQDsTcVJp58dXTQnjEpHJh_Ba6CrOPUsD'
  },
  {
    id: 2,
    name: 'VR Experience Center',
    rating: 4.9,
    reviews: 89,
    distance: '2.5 km',
    price: '25€',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCYHh5IqU9m7sGzI6nN-2TOY3cjqw6LvCPwENSrv1VEvphNaLlp8oKX_ChHWzDiAi9cQHqZzYOB5KUtfhaLcjJW_UOgoH9tS0xJuEpB9hPjC-ug8sBblwZq9yB1nXRMBDpKZy9x-ckdOciw7G1dgP5bJQflmdbedf6-LEwhU_rUgZPWNclac2ejM5-wf7h7ZqLDxaZ26KdjbR7S9QmN2aHgH3b6Wrcxc1LAK-t53YktCKAWh_nWLzRxSqobo14Awculpec_FS2SU2pt'
  },
  {
    id: 3,
    name: 'Retro Game Paradise',
    rating: 4.7,
    reviews: 234,
    distance: '3.8 km',
    price: '12€',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDimTmosVRtitqU9V2oDIOQw50iGnF5QW3_KIKBaqu9WSJ6grhZy_x9Ae_lduBc_pSW7n6n0fdslfekYXLqoiES9JL0QHnKTOhiuS0amxXj43wWxYzVYUBCmQu2VSKPJVPWu7LoNMIQI1myV_R0FkUiHFHXNPfCG-wSmSlJgDp7jtXCr5fTgDvAynUYwM1PHaumBxDPSfTTJ40KvLX01F4PWrPOpTz6IGey33XXIKdUJHkgz2V0gqb2kltAbruyvqecqYp37qlscfYo'
  },
  {
    id: 4,
    name: 'Esports Training Zone',
    rating: 4.6,
    reviews: 178,
    distance: '4.1 km',
    price: '18€',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAFHU-O26YCVGb8nn72fw_t3zCT0ekH-nFV7acoJIzDsxjIR-Lc_K7kG27Cdlw2y9G6cjOtbMeCCTpb6fpvIprI0mmn5ex2yb-BXmD0L6KlPW5rU6p-lXc__1mF2es4ZEWq3q4ApJbjtDkC2TSC7mq-_NE0vXwbHX6WGf4RibZubgcspYf4t8fKul0l8KUZrUNKhCv41euF-GtVhgfA3ESe3VG4R69TUjr3MK0L6Mo_FiYuXRADPqlhMa6yL4JRSgTFG6wPWKvxjCOM'
  }
];

// Methods
const getCurrentLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        // In a real app, you would convert coordinates to address
        searchQuery.value = 'Position actuelle';
      },
      (error) => {
        console.error('Error getting location:', error);
        alert('Impossible d\'obtenir votre position. Veuillez entrer votre localisation manuellement.');
      }
    );
  } else {
    alert('La géolocalisation n\'est pas supportée par votre navigateur.');
  }
};

const searchRooms = async () => {
  if (!searchQuery.value.trim()) {
    alert('Veuillez entrer une localisation');
    return;
  }

  isLoading.value = true;
  hasSearched.value = true;

  // Simulate API call
  setTimeout(() => {
    // Return all mock rooms (no filtering needed)
    searchResults.value = mockRooms;
    isLoading.value = false;
  }, 1500);
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
