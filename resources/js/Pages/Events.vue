<template>
  <div class="min-h-screen text-gray-800 font-body">
    <!-- MainNavbar -->
    <MainNavbar />
    
    <!-- Hero Section -->
    <section class="relative pt-24 pb-16 bg-gradient-to-br from-blue-50 to-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-display font-bold text-gray-800 leading-tight mb-6">
            Événements Gaming
          </h1>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Découvrez les tournois, compétitions et événements gaming près de chez vous
          </p>
        </div>
      </div>
    </section>

    <!-- Filters Section -->
    <section class="py-8 bg-white sticky top-16 z-40 border-b border-gray-200">
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
    <section class="py-12 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div v-if="filteredEvents.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                    'bg-gray-100 text-gray-800'
                    ]"
                >
                {{ getStatusText(event.status) }}
              </span>
            </div>
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
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
                  S'inscrire
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import MainNavbar from '../Components/MainNavbar.vue';

const events = ref([]);
const categories = ref([]);

// Récupérer les données depuis les props passées par le controller
const props = defineProps({
  events: Array,
  categories: Array
});

// Initialiser les données avec les props
events.value = props.events || [];
categories.value = props.categories || [];

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
  // Inscription à l'événement
  console.log('Register for event:', event);
  // Logique d'inscription
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
}
</style>
