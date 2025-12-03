<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const props = defineProps({
    events: {
        type: Object,
        default: () => ({ data: [] })
    },
    stats: {
        type: Object,
        default: () => ({
            total: 0,
            publies: 0,
            brouillons: 0,
            avenir: 0,
            en_cours: 0,
            passes: 0
        })
    }
});

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

const getStatutColor = (statut) => {
    const colors = {
        'brouillon': 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
        'publie': 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-300',
        'annule': 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-300',
        'termine': 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-300'
    };
    return colors[statut] || 'bg-gray-100 text-gray-800';
};

const getCategorieIcon = (categorie) => {
    const icons = {
        'jeux_video': 'fas fa-gamepad',
        'musique': 'fas fa-music',
        'sport': 'fas fa-football-ball',
        'conference': 'fas fa-microphone',
        'festival': 'fas fa-music',
        'tournament': 'fas fa-trophy',
        'lan_party': 'fas fa-desktop',
        'showmatch': 'fas fa-tv',
        'casual_gaming': 'fas fa-gamepad'
    };
    return icons[categorie] || 'fas fa-calendar';
};

const getTypeIcon = (type) => {
    const icons = {
        'online': 'fas fa-laptop',
        'offline': 'fas fa-map-marker-alt',
        'hybride': 'fas fa-globe'
    };
    return icons[type] || 'fas fa-calendar';
};

const estPasse = (dateFin) => {
    return dateFin ? new Date(dateFin) < new Date() : false;
};

const estEnCours = (dateDebut, dateFin) => {
    if (!dateDebut || !dateFin) return false;
    const now = new Date();
    return new Date(dateDebut) <= now && new Date(dateFin) >= now;
};

// Helper pour accéder aux propriétés en toute sécurité
const getEventProp = (event, prop, defaultValue = '') => {
    return event && event[prop] !== undefined ? event[prop] : defaultValue;
};

// Navigation vers la création d'événement
const goToCreateEvent = () => {
    router.get('/promoter/events/create');
};
</script>

<template>
  <Head title="Événements" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <!-- Sidebar Component -->
    <Sidebar current-route="promoter.events" />
    
    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto transition-all duration-300">
      <div class="p-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mes Événements</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Gérez vos événements et suivez leurs performances</p>
          </div>
          <Link href="/promoter/events/create" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus-circle"></i>
            <span>Créer un événement</span>
          </Link>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar text-blue-600 dark:text-blue-400 text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Total événements</p>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-eye text-green-600 dark:text-green-400 text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.publies }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Publiés</p>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-orange-600 dark:text-orange-400 text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.avenir }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">À venir</p>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-play-circle text-purple-600 dark:text-purple-400 text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.en_cours }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">En cours</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Liste des événements -->
        <div v-if="props.events.data && props.events.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="event in props.events.data" :key="event.id" class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300">
            <!-- Image header -->
            <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 relative">
              <img 
                v-if="event.image_banniere" 
                :src="`/storage/events/bannieres/${event.image_banniere}`"
                :alt="event.titre"
                class="w-full h-full object-cover"
                @error="$event.target.style.display='none'"
              >
              <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center" :class="{ 'bg-opacity-0': event.image_banniere }">
                <i :class="[getCategorieIcon(event.categorie), 'text-white text-4xl', { 'opacity-50': !event.image_banniere, 'opacity-0': event.image_banniere }]"></i>
              </div>
            </div>

            <!-- Content -->
            <div class="p-6">
              <!-- Header -->
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ event.titre }}</h3>
                  <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2">{{ event.description }}</p>
                </div>
                <span :class="getStatutColor(event.statut)" class="px-3 py-1 rounded-full text-xs font-medium">
                  {{ event.statut }}
                </span>
              </div>

              <!-- Info grid -->
              <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                  <i class="fas fa-calendar"></i>
                  <span>Début: {{ formatDate(event.date_debut) }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                  <i class="fas fa-calendar-check"></i>
                  <span>Fin: {{ formatDate(event.date_fin) }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                  <i class="fas fa-map-marker-alt"></i>
                  <span>{{ event.lieu }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                  <i class="fas fa-users"></i>
                  <span>{{ event.capacite_max }} places</span>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex gap-2">
                <Link :href="`/promoter/events/${event.id}/edit`" class="flex-1 bg-blue-50 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-100 transition-colors text-center text-sm font-medium">
                  <i class="fas fa-edit mr-2"></i>Modifier
                </Link>
                <button @click="deleteEvent(event)" class="bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty state -->
        <div v-else class="text-center py-20">
          <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-calendar text-gray-400 text-4xl"></i>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Aucun événement créé</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
            Commencez par créer votre premier événement pour commencer à organiser vos activités.
          </p>
          <Link href="/promoter/events/create" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus-circle"></i>
            <span>Créer mon premier événement</span>
          </Link>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
