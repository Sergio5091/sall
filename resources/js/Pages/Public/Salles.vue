<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    salles: Object,
    villes: Array,
    filters: Object
});

const form = ref({
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
    
    if (newFilters.ville) params.append('ville', newFilters.ville);
    if (newFilters.capacite_min) params.append('capacite_min', newFilters.capacite_min);
    
    window.location.href = `/salles?${params.toString()}`;
}, { deep: true });
</script>

<template>
  <Head title="Salles de Gaming - GameOn" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <Link href="/" class="flex items-center gap-2">
              <i class="fas fa-gamepad text-2xl text-red-600"></i>
              <span class="text-xl font-bold text-gray-900 dark:text-white">GameOn</span>
            </Link>
          </div>
          
          <nav class="hidden md:flex space-x-8">
            <Link href="/" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 px-3 py-2 text-sm font-medium">
              Accueil
            </Link>
            <Link href="/salles" class="text-red-600 dark:text-red-400 px-3 py-2 text-sm font-medium border-b-2 border-red-600">
              Salles
            </Link>
            <Link href="/events" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 px-3 py-2 text-sm font-medium">
              Événements
            </Link>
          </nav>

          <div class="flex items-center space-x-4">
            <Link href="/login" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 px-3 py-2 text-sm font-medium">
              Connexion
            </Link>
            <Link href="/register" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700">
              S'inscrire
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-red-600 to-red-800 text-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-4xl font-bold mb-4">Découvrez les Meilleures Salles de Gaming</h1>
          <p class="text-xl mb-8">Trouvez la salle parfaite pour vos événements, tournois et sessions de gaming</p>
        </div>
      </div>
    </section>

    <!-- Filtres -->
    <section class="bg-white dark:bg-gray-800 py-8 border-b border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
              @click="form = { ville: '', capacite_min: '' }"
              class="w-full px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600"
            >
              Réinitialiser
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Liste des salles -->
    <section class="py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ salles.total }} salle{{ salles.total > 1 ? 's' : '' }} disponible{{ salles.total > 1 ? 's' : '' }}
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                {{ salle.description || 'Salle de gaming moderne équipée du meilleur matériel pour vos événements.' }}
              </p>

              <!-- Caractéristiques -->
              <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                  <i class="fas fa-users mr-2 text-red-600"></i>
                  {{ formatCapacity(salle.capacite) }} places
                </div>
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                  <i class="fas fa-desktop mr-2 text-red-600"></i>
                  {{ salle.nombre_postes }} postes
                </div>
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                  <i class="fas fa-wifi mr-2 text-red-600"></i>
                  WiFi
                </div>
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                  <i class="fas fa-parking mr-2 text-red-600"></i>
                  Parking
                </div>
              </div>

              <!-- Actions -->
              <div class="flex gap-3">
                <Link 
                  :href="`/salles/${salle.id}`"
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
        <div v-if="salles.links && salles.links.length > 3" class="mt-12">
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

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <div class="flex items-center gap-2 mb-4">
              <i class="fas fa-gamepad text-2xl text-red-600"></i>
              <span class="text-xl font-bold">GameOn</span>
            </div>
            <p class="text-gray-400">
              La plateforme N°1 pour les salles de gaming et événements esports.
            </p>
          </div>
          
          <div>
            <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
            <ul class="space-y-2">
              <li><Link href="/" class="text-gray-400 hover:text-white">Accueil</Link></li>
              <li><Link href="/salles" class="text-gray-400 hover:text-white">Salles</Link></li>
              <li><Link href="/events" class="text-gray-400 hover:text-white">Événements</Link></li>
            </ul>
          </div>
          
          <div>
            <h3 class="text-lg font-semibold mb-4">Support</h3>
            <ul class="space-y-2">
              <li><a href="#" class="text-gray-400 hover:text-white">Aide</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
            </ul>
          </div>
          
          <div>
            <h3 class="text-lg font-semibold mb-4">Suivez-nous</h3>
            <div class="flex space-x-4">
              <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
              <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
              <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-discord"></i></a>
            </div>
          </div>
        </div>
        
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
          <p>&copy; 2024 GameOn. Tous droits réservés.</p>
        </div>
      </div>
    </footer>
  </div>
</template>
