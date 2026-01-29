<template>
  <div class="min-h-screen bg-white font-display">
    <!-- Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100">
      <div class="max-w-[1320px] mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <!-- Logo -->
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold">GB</span>
            </div>
            <h1 class="text-soft-black text-xl font-bold tracking-tight">YOUPIHUB</h1>
          </div>
          
          <!-- Desktop Menu -->
          <div class="hidden md:flex items-center gap-10">
            <a class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors" href="/">Accueil</a>
            <a class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors" href="/search/rooms">Salles</a>
            <a class="text-primary hover:text-primary text-sm font-medium transition-colors" href="/events">Événements</a>
            <a 
              :class="[
                'text-sm font-medium transition-colors',
                $page.url === '/products' ? 'text-primary hover:text-primary' : 'text-medium-grey hover:text-soft-black'
              ]" 
              href="/products"
            >
              Boutique
            </a>
            <a 
              :class="[
                'text-sm font-medium transition-colors',
                $page.url === '/about' ? 'text-primary hover:text-primary' : 'text-medium-grey hover:text-soft-black'
              ]" 
              href="/about"
            >
              À propos
            </a>
          </div>
          
          <!-- Desktop Auth Buttons -->
          <div class="hidden md:flex items-center gap-3">
            <a href="/login" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-soft-black text-sm font-medium rounded-lg transition-colors">Connexion</a>
            <a href="/register" class="flex items-center justify-center rounded-full bg-primary hover:bg-blue-600 text-white text-sm font-bold px-6 py-2.5 transition-all shadow-lg shadow-primary/20">
              Inscription
            </a>
          </div>

          <!-- Mobile Menu Button -->
          <button 
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <i class="fas fa-bars text-2xl text-soft-black" v-if="!isMobileMenuOpen"></i>
            <i class="fas fa-times text-2xl text-soft-black" v-else></i>
          </button>
        </div>

        <!-- Mobile Menu -->
        <div 
          :class="[
            'md:hidden transition-all duration-300 overflow-hidden',
            isMobileMenuOpen ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'
          ]"
        >
          <div class="py-4 border-t border-gray-100">
            <div class="flex flex-col gap-4">
              <a href="/" class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors py-2 text-center">Accueil</a>
              <a href="/search/rooms" class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors py-2 text-center">Salles</a>
              <a href="/events" class="text-primary hover:text-primary text-sm font-medium transition-colors py-2 text-center">Événements</a>
              <a 
                :class="[
                  'text-sm font-medium transition-colors py-2 text-center',
                  $page.url === '/products' ? 'text-primary hover:text-primary' : 'text-medium-grey hover:text-soft-black'
                ]" 
                href="/products"
              >
                Boutique
              </a>
              <a 
                :class="[
                  'text-sm font-medium transition-colors py-2 text-center',
                  $page.url === '/about' ? 'text-primary hover:text-primary' : 'text-medium-grey hover:text-soft-black'
                ]" 
                href="/about"
              >
                À propos
              </a>
              <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="/login" class="flex-1 text-center text-medium-grey hover:text-soft-black text-sm font-medium transition-colors py-2 border border-gray-200 rounded-lg">Connexion</a>
                <a href="/register" class="flex-1 text-center bg-primary hover:bg-blue-600 text-white text-sm font-bold py-2 rounded-lg transition-all shadow-lg shadow-primary/20">Inscription</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Hero Section with Search -->
    <section class="relative min-h-[500px] sm:min-h-[600px] pt-16 sm:pt-20 overflow-hidden">
      <!-- Background Image with Overlay -->
      <div class="absolute inset-0">
        <img 
          src="https://picsum.photos/seed/gaming-events/1920/800.jpg" 
          alt="Gaming Events Background"
          class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-br from-primary/60 via-purple-600/50 to-indigo-700/60"></div>
      </div>
      
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 right-20 w-40 h-40 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-1/3 w-36 h-36 bg-white rounded-full blur-3xl"></div>
      </div>
      
      <div class="relative z-10 max-w-[1320px] mx-auto px-4 sm:px-6 py-12 sm:py-16">
        <div class="text-center mb-8 sm:mb-12">
          <h1 class="text-white text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1] mb-4 sm:mb-6">
            Événements Gaming<br/>à ne pas manquer
          </h1>
          <p class="text-white/80 text-lg md:text-xl font-normal leading-relaxed max-w-2xl mx-auto">
            Découvrez les tournois, compétitions et événements gaming près de chez vous
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
                  placeholder="Rechercher un événement..."
                  @keyup.enter="searchEvents"
                />
              </div>
              <button 
                @click="searchEvents"
                class="bg-primary hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold text-base transition-all shadow-lg shadow-primary/20 flex items-center gap-2"
              >
                <i class="fas fa-search"></i>
                Rechercher
              </button>
            </div>
          </div>
        </div>
        
        <!-- Quick Filters -->
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
          <button 
            v-for="category in categories" 
            :key="category.id"
            @click="filterByCategory(category.id)"
            :class="[
              'px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all',
              selectedCategory === category.id 
                ? 'bg-white text-primary shadow-lg' 
                : 'bg-white/10 backdrop-blur-md hover:bg-white/20 text-white border border-white/20'
            ]"
          >
            {{ category.name }}
          </button>
        </div>
      </div>
    </section>

    <!-- Results Section -->
    <section class="py-12 sm:py-16 bg-gray-50">
      <div class="max-w-[1320px] mx-auto px-4 sm:px-6">
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
              <h2 class="text-2xl sm:text-3xl font-bold text-soft-black mb-1">
                {{ totalResults }} événement{{ totalResults > 1 ? 's' : '' }} trouvé{{ totalResults > 1 ? 's' : '' }}
              </h2>
              <p class="text-sm sm:text-base text-medium-grey">
                {{ searchQuery ? `Résultats pour "${searchQuery}"` : 'Tous les événements disponibles' }}
              </p>
            </div>
            
            <!-- Sort Dropdown -->
            <div class="flex items-center gap-3">
              <select 
                v-model="sortBy" 
                class="bg-white border border-gray-200 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
              >
                <option value="date">Date</option>
                <option value="name">Nom</option>
                <option value="popularity">Popularité</option>
              </select>
            </div>
          </div>

          <!-- Results Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 stagger-animation">
            <div 
              v-for="(event, index) in paginatedEvents" 
              :key="event.id"
              :class="[
                'bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group cursor-pointer hover-lift',
                'animate-fade-in-up'
              ]"
              :style="{ animationDelay: `${index * 0.1}s` }"
              @click="viewEventDetails(event)"
            >
              <!-- Image -->
              <div class="relative h-48 sm:h-56 overflow-hidden">
                <img 
                  :src="event.image || 'https://picsum.photos/seed/event-' + event.id + '/400/300.jpg'"
                  :alt="event.name"
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                />
                
                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                  <span 
                    :class="[
                      'px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-bold',
                      event.status === 'upcoming' ? 'bg-green-100 text-green-800' :
                      event.status === 'ongoing' ? 'bg-yellow-100 text-yellow-800' :
                      event.status === 'completed' ? 'bg-gray-100 text-gray-800' :
                      'bg-gray-100 text-gray-800'
                    ]"
                  >
                    {{ getStatusText(event.status) }}
                  </span>
                </div>
                
                <!-- Event Type Badge -->
                <div class="absolute top-3 left-3" v-if="event.type === 'standalone'">
                  <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-bold">
                    <i class="fas fa-star mr-1"></i>
                    Ponctuel
                  </span>
                </div>
              </div>

              <!-- Content -->
              <div class="p-4 sm:p-6">
                <div class="flex items-start justify-between mb-4">
                  <div class="flex-1">
                    <h3 class="text-lg sm:text-xl font-bold text-soft-black mb-2 group-hover:text-primary transition-colors">
                      {{ event.name }}
                    </h3>
                    <div class="flex items-center gap-2 text-medium-grey text-sm mb-3">
                      <span class="material-symbols-outlined text-[16px]">event</span>
                      <span>{{ formatDate(event.date) }}</span>
                    </div>
                  </div>
                </div>

                <!-- Description -->
                <p v-if="event.description" class="text-medium-grey text-sm mb-4 line-clamp-2">
                  {{ event.description }}
                </p>

                <!-- Features -->
                <div class="flex flex-wrap gap-2 mb-4">
                  <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">
                    <span class="material-symbols-outlined text-xs align-middle mr-1">location_on</span>
                    {{ event.location }}
                  </span>
                  <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">
                    <span class="material-symbols-outlined text-xs align-middle mr-1">sports_esports</span>
                    {{ event.game_type }}
                  </span>
                  <span v-if="event.prize_pool" class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-medium">
                    <span class="material-symbols-outlined text-xs align-middle mr-1">emoji_events</span>
                    {{ event.prize_pool }}€
                  </span>
                  <span v-if="event.type === 'standalone' && event.organizer_name" class="px-2 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium">
                    <span class="material-symbols-outlined text-xs align-middle mr-1">person</span>
                    {{ event.organizer_name }}
                  </span>
                </div>

                <!-- Participants Progress -->
                <div class="mb-4" v-if="event.type !== 'standalone'">
                  <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-medium-grey">Participants</span>
                    <span class="text-soft-black font-medium">{{ event.current_participants }}/{{ event.max_participants }}</span>
                  </div>
                  <div class="w-full bg-gray-200 rounded-full h-2">
                    <div 
                      class="bg-primary h-2 rounded-full transition-all duration-300"
                      :style="{ width: `${(event.current_participants / event.max_participants) * 100}%` }"
                    ></div>
                  </div>
                </div>

                <!-- Price and Action -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                  <div>
                    <div class="text-2xl sm:text-3xl font-bold text-soft-black mb-1">
                      {{ event.type === 'standalone' ? (event.price ? formatPrice(event.price) : 'Gratuit') : (event.price ? formatPrice(event.price) : 'Gratuit') }}
                    </div>
                    <div class="text-medium-grey text-sm">par participant</div>
                  </div>
                  <div class="flex gap-2">
                    <button 
                      @click.stop="viewEventDetails(event)"
                      class="px-4 py-2 border border-gray-200 text-soft-black rounded-xl hover:border-primary hover:text-primary transition-all text-sm font-semibold"
                    >
                      Détails
                    </button>
                    <button 
                      v-if="event.type !== 'standalone' && event.status === 'upcoming' && event.current_participants < event.max_participants"
                      @click.stop="registerForEvent(event)"
                      class="px-4 py-2 bg-primary text-white rounded-xl hover:bg-blue-600 transition-all text-sm font-semibold"
                    >
                      S'inscrire
                    </button>
                    <button 
                      v-else-if="event.type === 'standalone'"
                      class="px-4 py-2 bg-gray-300 text-gray-600 rounded-xl cursor-not-allowed text-sm font-semibold"
                      disabled
                    >
                      Contact
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="totalPages > 1" class="mt-8 sm:mt-12">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
              <div class="text-sm text-medium-grey">
                Page {{ currentPage }} sur {{ totalPages }} ({{ totalResults }} événements)
              </div>
              
              <div class="flex items-center gap-2">
                <!-- Previous Button -->
                <button 
                  @click="goToPage(currentPage - 1)"
                  :disabled="currentPage === 1"
                  :class="[
                    'w-10 h-10 rounded-lg flex items-center justify-center transition-all',
                    currentPage === 1 
                      ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                      : 'bg-white border border-gray-200 text-gray-900 hover:bg-primary hover:text-white hover:border-primary'
                  ]"
                >
                  <span class="material-symbols-outlined">chevron_left</span>
                </button>
                
                <!-- Page Numbers -->
                <div class="flex items-center gap-1">
                  <button 
                    v-for="page in Math.min(totalPages, 5)" 
                    :key="page"
                    @click="goToPage(page)"
                    :class="[
                      'w-10 h-10 rounded-lg flex items-center justify-center text-sm font-medium transition-all',
                      currentPage === page 
                        ? 'bg-primary text-white' 
                        : 'bg-white border border-gray-200 text-gray-900 hover:bg-primary hover:text-white hover:border-primary'
                    ]"
                  >
                    {{ page }}
                  </button>
                  
                  <!-- Dots for more pages -->
                  <span v-if="totalPages > 5" class="px-2 text-gray-400">...</span>
                </div>
                
                <!-- Next Button -->
                <button 
                  @click="goToPage(currentPage + 1)"
                  :disabled="currentPage === totalPages"
                  :class="[
                    'w-10 h-10 rounded-lg flex items-center justify-center transition-all',
                    currentPage === totalPages 
                      ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                      : 'bg-white border border-gray-200 text-gray-900 hover:bg-primary hover:text-white hover:border-primary'
                  ]"
                >
                  <span class="material-symbols-outlined">chevron_right</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-20">
          <div class="max-w-md mx-auto">
            <div class="size-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="material-symbols-outlined text-[40px] text-gray-400">event_busy</span>
            </div>
            <h3 class="text-soft-black text-2xl font-bold mb-3">Aucun événement trouvé</h3>
            <p class="text-medium-grey text-lg mb-8">
              Essayez avec d'autres critères de recherche ou revenez plus tard
            </p>
            <button 
              @click="resetSearch"
              class="bg-primary hover:bg-blue-600 text-white px-6 py-3 rounded-full font-bold text-base transition-all shadow-lg shadow-primary/20"
            >
              Réinitialiser la recherche
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-soft-black text-white py-8 sm:py-12">
      <div class="max-w-[1320px] mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div>
            <h3 class="text-lg sm:text-xl font-bold mb-4">YOUPIHUB</h3>
            <p class="text-sm sm:text-base text-gray-400 mb-6">
              La plateforme N°1 pour la réservation de salles de gaming et d'événements en Afrique.
            </p>
            <div class="flex gap-4">
              <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-primary rounded-full flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-sm">facebook</span>
              </a>
              <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-primary rounded-full flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-sm">alternate_email</span>
              </a>
              <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-primary rounded-full flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-sm">smartphone</span>
              </a>
            </div>
          </div>
          <div>
            <h4 class="text-base sm:text-lg font-semibold mb-4">Liens utiles</h4>
            <div class="grid grid-cols-2 gap-2">
              <a href="/" class="text-sm sm:text-base text-gray-400 hover:text-white transition-colors">Accueil</a>
              <a href="/search/rooms" class="text-sm sm:text-base text-gray-400 hover:text-white transition-colors">Salles</a>
              <a href="/events" class="text-sm sm:text-base text-gray-400 hover:text-white transition-colors">Événements</a>
              <a href="/products" class="text-sm sm:text-base text-gray-400 hover:text-white transition-colors">Boutique</a>
              <a href="/about" class="text-sm sm:text-base text-gray-400 hover:text-white transition-colors">À propos</a>
              <a href="#" class="text-sm sm:text-base text-gray-400 hover:text-white transition-colors">Contact</a>
              <a href="#" class="text-sm sm:text-base text-gray-400 hover:text-white transition-colors">Mentions légales</a>
              <a href="#" class="text-sm sm:text-base text-gray-400 hover:text-white transition-colors">CGU</a>
            </div>
          </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
          <div class="flex flex-wrap gap-4 sm:gap-6">
            <a class="text-sm sm:text-base text-gray-400 hover:text-primary transition-colors" href="#">Mentions légales</a>
            <a class="text-sm sm:text-base text-gray-400 hover:text-primary transition-colors" href="#">CGU</a>
          </div>
          <div class="text-xs sm:text-sm text-gray-400">
            2024 YOUPIHUB. Tous droits réservés.
          </div>
        </div>
      </div>
    </footer>
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
import { ref, computed, onMounted } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

const page = usePage();

// Reactive data
const searchQuery = ref('');
const isLoading = ref(false);
const selectedCategory = ref(0);
const sortBy = ref('date');
const searchResults = ref([]);
const currentPage = ref(1);
const itemsPerPage = ref(6);
const isMobileMenuOpen = ref(false);

// Récupérer les données depuis les props passées par le controller
const props = defineProps({
  events: Array,
  categories: Array
});

// Initialiser les données avec les props
const events = ref(props.events || []);
const categories = ref(props.categories || []);

// Computed properties
const totalResults = computed(() => {
  return filteredEvents.value.length;
});

const hasResults = computed(() => {
  return filteredEvents.value.length > 0;
});

const hasMoreResults = computed(() => {
  return currentPage.value < totalPages.value;
});

const totalPages = computed(() => {
  return Math.ceil(filteredEvents.value.length / itemsPerPage.value);
});

const paginatedEvents = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredEvents.value.slice(start, end);
});

const filteredEvents = computed(() => {
  let filtered = [...events.value]; // Utiliser une copie pour éviter les mutations

  // Filter by category
  if (selectedCategory.value > 0) {
    filtered = filtered.filter(event => event.category_id === selectedCategory.value);
  }

  // Filter by search query
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(event => 
      event.name.toLowerCase().includes(query) ||
      (event.description && event.description.toLowerCase().includes(query)) ||
      event.location.toLowerCase().includes(query) ||
      event.game_type.toLowerCase().includes(query)
    );
  }

  // Sort
  filtered.sort((a, b) => {
    switch (sortBy.value) {
      case 'date':
        return new Date(a.date) - new Date(b.date);
      case 'name':
        return a.name.localeCompare(b.name);
      case 'popularity':
        return b.current_participants - a.current_participants;
      default:
        return 0;
    }
  });

  return filtered;
});

// Methods
const searchEvents = async () => {
  isLoading.value = true;
  
  try {
    // Simuler une recherche - en production, utiliser une vraie API
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    // Ne pas modifier events.value ici, laisser le computed filteredEvents gérer le filtrage
    // La recherche se fait maintenant automatiquement via le computed
    
  } catch (error) {
    console.error('Erreur recherche:', error);
  } finally {
    isLoading.value = false;
  }
};

const filterByCategory = (categoryId) => {
  selectedCategory.value = categoryId;
  // Le computed filteredEvents va automatiquement appliquer le filtre
};

const resetSearch = () => {
  searchQuery.value = '';
  selectedCategory.value = 0;
  sortBy.value = 'date';
  currentPage.value = 1;
  events.value = props.events || [];
};

const goToPage = (page) => {
  currentPage.value = page;
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const loadMore = () => {
  currentPage.value++;
};

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

const getStatusText = (status) => {
  switch (status) {
    case 'upcoming': return 'À venir';
    case 'ongoing': return 'En cours';
    case 'completed': return 'Terminé';
    default: return status;
  }
};

const formatPrice = (prix) => {
  if (!prix) return 'Gratuit';
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
    minimumFractionDigits: 0
  }).format(prix);
};

const viewEventDetails = (event) => {
  router.visit(`/events/${event.id}`);
};

const registerForEvent = (event) => {
  router.visit('/login', {
    method: 'get',
    data: {
      redirect_to: `/client/evenements`
    }
  });
};

// Lifecycle
onMounted(() => {
  // Les événements sont déjà chargés depuis les props
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

.font-display {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* Tailwind custom colors */
:root {
  --primary: #135bec;
  --soft-black: #111827;
  --medium-grey: #6B7280;
}

.text-primary,
[class*="text-primary"] {
  color: #135bec !important;
}

/* Ensure Tailwind blue-600 works */
.bg-blue-600 {
  background-color: #2563eb !important;
}

.hover\:bg-blue-600:hover {
  background-color: #2563eb !important;
}

/* Animations */
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

@keyframes bounce-in {
  from {
    opacity: 0;
    transform: scale(0.3);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

/* Delays */
.delay-100 { animation-delay: 0.1s; }
.delay-200 { animation-delay: 0.2s; }
.delay-300 { animation-delay: 0.3s; }
.delay-400 { animation-delay: 0.4s; }
.delay-500 { animation-delay: 0.5s; }
.delay-600 { animation-delay: 0.6s; }

/* Hover effects */
.hover-lift {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

/* Staggered animations for cards */
.stagger-animation > * {
  opacity: 0;
  animation: fade-in-up 0.6s ease-out forwards;
}

.stagger-animation > *:nth-child(1) { animation-delay: 0.1s; }
.stagger-animation > *:nth-child(2) { animation-delay: 0.2s; }
.stagger-animation > *:nth-child(3) { animation-delay: 0.3s; }
.stagger-animation > *:nth-child(4) { animation-delay: 0.4s; }
.stagger-animation > *:nth-child(5) { animation-delay: 0.5s; }
.stagger-animation > *:nth-child(6) { animation-delay: 0.6s; }

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  
  /* Standard properties for compatibility */
  display: box;
  line-clamp: 3;
  box-orient: vertical;
  
  /* Fallback for older browsers */
  max-height: 4.5em; /* Approximate 3 lines */
  text-overflow: ellipsis;
}
</style>
