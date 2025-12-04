<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    salles: Object,
    villes: Array,
    filters: Object,
    reservations: Array
});

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

// Formater la capacité
const formatCapacity = (capacite) => {
    return new Intl.NumberFormat('fr-FR').format(capacite);
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
    
    window.location.href = `/client/salles?${params.toString()}`;
}, { deep: true });
</script>

<template>
  <Head title="Salles de Gaming - GameOn" />
  
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Client -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <Link href="/client/dashboard" class="flex items-center gap-2">
              <i class="fas fa-gamepad text-2xl text-red-600"></i>
              <span class="text-xl font-bold text-gray-900 dark:text-white">GameOn</span>
            </Link>
          </div>
          
          <nav class="hidden md:flex space-x-8">
            <Link href="/client/dashboard" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 px-3 py-2 text-sm font-medium">
              Tableau de bord
            </Link>
            <Link href="/client/salles" class="text-red-600 dark:text-red-400 px-3 py-2 text-sm font-medium border-b-2 border-red-600">
              Salles
            </Link>
            <Link href="/client/profile" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 px-3 py-2 text-sm font-medium">
              Profil
            </Link>
          </nav>

          <div class="flex items-center space-x-4">
            <Link href="/logout" method="post" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 px-3 py-2 text-sm font-medium">
              Déconnexion
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-red-600 to-red-800 text-white py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
    <section class="bg-white dark:bg-gray-800 py-6 border-b border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Barre de recherche principale -->
        <div class="mb-6">
          <div class="max-w-2xl mx-auto">
            <div class="flex">
              <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-search text-gray-400"></i>
                </div>
                <input 
                  v-model="form.search" 
                  type="text" 
                  placeholder="Rechercher une salle, une ville, un jeu..." 
                  class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Filtres avancés -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Ville
            </label>
            <select v-model="form.ville" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
              <option value="">Toutes les villes</option>
              <option v-for="ville in villes" :key="ville" :value="ville">
                {{ ville }}
              </option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Capacité minimale
            </label>
            <input 
              v-model="form.capacite_min" 
              type="number" 
              placeholder="Ex: 50" 
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            >
          </div>
          
          <div class="flex items-end">
            <button 
              @click="form = { search: '', ville: '', capacite_min: '' }"
              class="w-full px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600"
            >
              Réinitialiser
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Liste des salles -->
    <section class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ salles.total }} salle{{ salles.total > 1 ? 's' : '' }} disponible{{ salles.total > 1 ? 's' : '' }}
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="salle in salles.data" 
            :key="salle.id"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300"
          >
            <!-- Image -->
            <div class="h-48 bg-gradient-to-br from-red-500 to-red-700 rounded-lg overflow-hidden">
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
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ salle.nom }}
                  </h3>
                  <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    {{ salle.ville }}, {{ salle.pays }}
                  </div>
                </div>
                <div class="text-right">
                  <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                    {{ formatPrice(salle.prix_heure) }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">/heure</div>
                </div>
              </div>

              <!-- Description -->
              <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                {{ salle.description || 'Salle de gaming moderne équipée du meilleur matériel.' }}
              </p>

              <!-- Caractéristiques -->
              <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                  <i class="fas fa-users mr-2 text-red-600"></i>
                  {{ formatCapacity(salle.capacite_max) }} places
                </div>
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                  <i class="fas fa-wifi mr-2 text-red-600"></i>
                  WiFi
                </div>
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                  <i class="fas fa-parking mr-2 text-red-600"></i>
                  Parking
                </div>
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                  <i class="fas fa-shield-alt mr-2 text-red-600"></i>
                  Sécurisé
                </div>
              </div>

              <!-- Actions -->
              <div class="flex gap-3">
                <Link 
                  :href="`/client/salles/${salle.id}`"
                  class="flex-1 text-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors"
                >
                  Voir les détails
                </Link>
                <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                  <i class="fas fa-heart text-gray-400 hover:text-red-600"></i>
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
                      ? 'bg-red-600 text-white' 
                      : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600'
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
    </section>
  </div>
</template>
