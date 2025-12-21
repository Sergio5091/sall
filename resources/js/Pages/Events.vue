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
            <a href="/search/rooms" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Salles</a>
            <a href="/events" class="text-blue-600 hover:text-blue-700 px-3 py-2 text-sm font-medium border-b-2 border-blue-600">Événements</a>
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
    
    <!-- Hero Section -->
    <section class="relative pt-24 pb-16 bg-blue-600">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-fade-in-up">
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-display font-bold text-white leading-tight mb-6">
            Événements Gaming
          </h1>
          <p class="text-xl text-white/90 max-w-3xl mx-auto">
            Découvrez les tournois, compétitions et événements gaming près de chez vous
          </p>
        </div>
      </div>
    </section>

    <!-- Filters Section -->
    <section class="py-8 bg-white sticky top-16 z-40 border-b border-gray-200 animate-fade-in-up delay-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-4 items-center justify-between">
          <div class="flex flex-wrap gap-3">
            <button 
              v-for="category in categories" 
              :key="category.id"
              @click="selectedCategory = category.id"
              :class="[
                'px-4 py-2 rounded-full text-sm font-medium transition-colors',
                selectedCategory === category.id 
                  ? 'bg-blue-600 text-white' 
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              ]"
            >
              {{ category.name }}
            </button>
          </div>
          
          <div class="flex items-center gap-3">
            <select 
              v-model="sortBy" 
              class="px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            >
              <option value="date">Date</option>
              <option value="name">Nom</option>
              <option value="popularity">Popularité</option>
            </select>
            
            <div class="relative">
              <input 
                v-model="searchQuery"
                type="text" 
                placeholder="Rechercher un événement..."
                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 w-64"
              >
              <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Events Grid -->
    <section class="py-12 bg-gray-50 animate-fade-in-up delay-400">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div v-if="filteredEvents.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Event cards here -->
          <div 
            v-for="event in filteredEvents" 
            :key="event.id"
            class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden group"
            >
            <!-- Event Image -->
            <div class="relative h-48 overflow-hidden">
              <img 
                :src="event.image || 'https://via.placeholder.com/400x200/3B82F6/FFFFFF?text=Event'" 
                :alt="event.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                >
                
                <div class="absolute top-4 right-4">
                  <span 
                  :class="[
                    'px-3 py-1 rounded-full text-xs font-bold',
                    event.status === 'upcoming' ? 'bg-green-100 text-green-800' :
                    event.status === 'ongoing' ? 'bg-yellow-100 text-yellow-800' :
                    event.status === 'completed' ? 'bg-gray-100 text-gray-800' :
                    'bg-gray-100 text-gray-800'
                    ]"
                >
                {{ getStatusText(event.status) }}
              </span>
            </div>
            <div class="absolute bottom-0 left-0 right-0 bg-black/70 p-4">
              <h3 class="text-white text-xl font-bold">{{ event.name }}</h3>
            </div>
          </div>
          
            <!-- Event Details -->
            <div class="p-6">
              <div class="space-y-3">
                <!-- Date and Time -->
                <div class="flex items-center text-gray-600">
                  <i class="fas fa-calendar-alt mr-3 text-blue-500"></i>
                  <span>{{ formatDate(event.date) }}</span>
                </div>
                
                <!-- Location -->
                <div class="flex items-center text-gray-600">
                  <i class="fas fa-map-marker-alt mr-3 text-red-500"></i>
                  <span>{{ event.location }}</span>
                </div>

                <!-- Game Type -->
                <div class="flex items-center text-gray-600">
                  <i class="fas fa-gamepad mr-3 text-purple-500"></i>
                  <span>{{ event.game_type }}</span>
                </div>

                <!-- Prize Pool -->
                <div v-if="event.prize_pool" class="flex items-center text-gray-600">
                  <i class="fas fa-trophy mr-3 text-yellow-500"></i>
                  <span class="font-bold text-green-600">{{ event.prize_pool }}€</span>
                </div>

                <!-- Participants -->
                <div class="flex items-center justify-between text-gray-600">
                  <div class="flex items-center">
                    <i class="fas fa-users mr-3 text-indigo-500"></i>
                    <span>{{ event.current_participants }}/{{ event.max_participants }}</span>
                  </div>
                  <div class="w-full max-w-xs bg-gray-200 rounded-full h-2">
                    <div 
                      class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                      :style="{ width: `${(event.current_participants / event.max_participants) * 100}%` }"
                    ></div>
                  </div>
                </div>
              </div>

              <!-- Description -->
              <p class="text-gray-600 mt-4 line-clamp-3">
                {{ event.description }}
              </p>

              <!-- Action Buttons -->
              <div class="mt-6 flex gap-3">
                <button 
                  @click="viewEventDetails(event)"
                  class="flex-1 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                >
                  Voir détails
                </button>
                <button 
                  v-if="event.status === 'upcoming' && event.current_participants < event.max_participants"
                  @click="registerForEvent(event)"
                  class="flex-1 px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors"
                >
                  Se connecter pour s'inscrire
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Empty State -->
        <div v-else class="text-center py-16">
          <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun événement publié</h3>
          <p class="text-gray-600">Il n'y a aucun événement publié pour le moment.</p>
          <p class="text-sm text-gray-500 mt-2">Vérifiez les logs de la console pour voir les données reçues.</p>
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
console.log('Events page loading...');

import { ref, computed, onMounted } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import MainNavbar from '../Components/MainNavbar.vue';

const page = usePage();

const events = ref([]);
const categories = ref([]);

// Récupérer les données depuis les props passées par le controller
const props = defineProps({
  events: Array,
  categories: Array
});

console.log('Props received:', props);

// Vérifier si l'utilisateur est connecté
const isAuthenticated = computed(() => page.props.auth?.user);
const currentUser = computed(() => page.props.auth?.user);

// Initialiser les données avec les props
console.log('Events props:', props.events);
console.log('First event structure:', props.events?.[0]);

// Le backend ne renvoie que les événements publiés, donc pas besoin de filtrer
events.value = props.events || [];
categories.value = props.categories || [];

console.log('Filtered events:', events.value);
console.log('Filtered events length:', events.value.length);

const selectedCategory = ref(0);
const sortBy = ref('date');
const searchQuery = ref('');

// Computed properties
const filteredEvents = computed(() => {
  let filtered = events.value;

  // Filter by category
  if (selectedCategory.value > 0) {
    filtered = filtered.filter(event => event.category_id === selectedCategory.value);
  }

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(event => 
      event.name.toLowerCase().includes(query) ||
      event.description.toLowerCase().includes(query) ||
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

console.log('Computed filteredEvents:', filteredEvents.value);

// Methods
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

const viewEventDetails = (event) => {
  // Navigation vers les détails de l'événement
  router.visit(`/events/${event.id}`);
};

const registerForEvent = (event) => {
  // Toujours rediriger vers la page de connexion
  router.visit('/login', {
    method: 'get',
    data: {
      redirect_to: `/client/evenements`
    }
  });
};

// Lifecycle
onMounted(() => {
  // Charger les événements depuis la base de données
  // axios.get('/api/events').then(response => {
  //   events.value = response.data;
  // });
});
</script>

<style scoped>
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
