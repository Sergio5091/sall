<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const props = defineProps({
    events: Object,
    stats: Object
});

// Formater les nombres
const formatNumber = (num) => {
    return new Intl.NumberFormat('fr-FR').format(num);
};

// Formater les dates
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

// Obtenir la couleur du statut
const getStatusColor = (status) => {
    switch (status) {
        case 'publie': return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
        case 'brouillon': return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
        case 'annule': return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
        case 'termine': return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
        default: return 'bg-gray-100 text-gray-800';
    }
};

// Obtenir le texte du statut
const getStatusText = (status) => {
    switch (status) {
        case 'publie': return 'Publié';
        case 'brouillon': return 'Brouillon';
        case 'annule': return 'Annulé';
        case 'termine': return 'Terminé';
        default: return status;
    }
};

// Publier un événement directement depuis la liste
const publishEvent = (evt) => {
  if (!confirm(`Publier l'événement "${evt.titre}" ?`)) return;

  // Utiliser l'endpoint de publication dédié
  router.post(`/promoter/events/${evt.id}/publish`, {}, {
    onSuccess: () => {
      // reload the list
      router.visit('/promoter/events');
    },
    onError: (errors) => {
      console.error('Erreur de publication:', errors);
      
      // Afficher un message d'erreur plus spécifique
      let errorMessage = 'La publication a échoué. ';
      
      if (typeof errors === 'string') {
        errorMessage += errors;
      } else if (errors.message) {
        errorMessage += errors.message;
      } else {
        errorMessage += 'Veuillez contacter le support.';
      }
      
      alert(errorMessage);
    }
  });
};

// Supprimer un événement
const deleteEvent = (evt) => {
  if (!confirm(`Supprimer définitivement l'événement "${evt.titre}" ?`)) return;

  router.delete(`/promoter/events/${evt.id}`, {
    onSuccess: () => {
      router.visit('/promoter/events');
    },
    onError: (errors) => {
      console.error('Erreur suppression:', errors);
      
      let errorMessage = 'La suppression a échoué. ';
      if (typeof errors === 'string') {
        errorMessage += errors;
      } else if (errors.message) {
        errorMessage += errors.message;
      } else if (errors.inscriptions) {
        errorMessage += 'Des inscriptions confirmées existent. ';
      } else {
        errorMessage += 'Veuillez contacter le support.';
      }
      
      alert(errorMessage);
    }
  });
};
</script>

<template>
  <Head title="Mes Événements" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <!-- Sidebar Component -->
    <Sidebar current-route="promoter.events" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto transition-all duration-300 pt-16 lg:pt-0">
      <div class="p-8">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Mes Événements</p>
          <Link href="/promoter/events/create" class="flex items-center justify-center rounded-lg h-10 bg-blue-600 text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] px-4 hover:bg-blue-500 transition-colors">
            <i class="fas fa-plus text-base font-bold"></i>
            <span class="truncate">Créer un événement</span>
          </Link>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mt-8">
          <div class="flex flex-col gap-2 rounded-xl p-4 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total événements</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">{{ formatNumber(stats.total || 0) }}</p>
          </div>
          <div class="flex flex-col gap-2 rounded-xl p-4 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Événements publiés</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">{{ formatNumber(stats.publies || 0) }}</p>
          </div>
          <div class="flex flex-col gap-2 rounded-xl p-4 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Brouillons</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">{{ formatNumber(stats.brouillons || 0) }}</p>
          </div>
          <div class="flex flex-col gap-2 rounded-xl p-4 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">À venir</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">{{ formatNumber(stats.avenir || 0) }}</p>
          </div>
          <div class="flex flex-col gap-2 rounded-xl p-4 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">En cours</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">{{ formatNumber(stats.en_cours || 0) }}</p>
          </div>
          <div class="flex flex-col gap-2 rounded-xl p-4 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Passés</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">{{ formatNumber(stats.passes || 0) }}</p>
          </div>
        </div>

        <!-- Events Grid -->
        <div class="mt-8">
          <div v-if="events.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="event in events.data" 
              :key="event.id"
              class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-300"
            >
              <!-- Event Image -->
              <div class="relative h-48 overflow-hidden">
                <img 
                  :src="event.url_image_affiche || '/images/default-event.jpg'" 
                  :alt="event.titre"
                  class="w-full h-full object-cover"
                >
                <div class="absolute top-4 right-4">
                  <span 
                    :class="[
                      'px-3 py-1 rounded-full text-xs font-bold',
                      getStatusColor(event.statut)
                    ]"
                  >
                    {{ getStatusText(event.statut) }}
                  </span>
                </div>
              </div>

              <!-- Event Details -->
              <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ event.titre }}</h3>
                
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
                  {{ event.description }}
                </p>

                <div class="space-y-2 text-sm">
                  <!-- Date -->
                  <div class="flex items-center text-gray-600 dark:text-gray-400">
                    <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                    <span>{{ formatDate(event.date_debut) }}</span>
                  </div>
                  
                  <!-- Location -->
                  <div class="flex items-center text-gray-600 dark:text-gray-400">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                    <span>{{ event.salle?.nom || 'Lieu à définir' }}</span>
                  </div>

                  <!-- Price -->
                  <div class="flex items-center text-gray-600 dark:text-gray-400">
                    <i class="fas fa-tag mr-2 text-green-500"></i>
                    <span v-if="event.gratuit">Gratuit</span>
                    <span v-else>{{ event.prix_base }} {{ event.devise }}</span>
                  </div>

                  <!-- Capacity -->
                  <div class="flex items-center text-gray-600 dark:text-gray-400">
                    <i class="fas fa-users mr-2 text-purple-500"></i>
                    <span v-if="event.capacite_max">{{ event.places_disponibles || 0 }}/{{ event.capacite_max }} places</span>
                    <span v-else>Illimité</span>
                  </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex gap-2">
                  <button
                    v-if="event.statut !== 'publie'"
                    @click.prevent="publishEvent(event)"
                    class="flex-1 text-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors"
                  >
                    Publier
                  </button>
                  <Link
                    v-else
                    :href="`/promoter/events/${event.id}`"
                    class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors"
                  >
                    Voir
                  </Link>
                  <Link 
                    :href="`/promoter/events/${event.id}/edit`"
                    class="flex-1 text-center px-3 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors"
                  >
                    Modifier
                  </Link>
                  <button
                    @click.prevent="deleteEvent(event)"
                    class="flex-1 text-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors"
                  >
                    Supprimer
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-12">
            <i class="fas fa-calendar-times text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Aucun événement</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Vous n'avez pas encore créé d'événement.</p>
            <Link 
              href="/promoter/events/create"
              class="inline-flex items-center px-4 py-2 bg-brand-red text-white font-medium rounded-lg hover:bg-brand-red/90 transition-colors"
            >
              <i class="fas fa-plus mr-2"></i>
              Créer votre premier événement
            </Link>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="events.data.length > 0" class="mt-8 flex justify-center">
          <div class="flex gap-2">
            <Link 
              v-if="events.prev_page_url"
              :href="events.prev_page_url"
              class="px-3 py-2 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
            >
              <i class="fas fa-chevron-left"></i>
            </Link>
            
            <span class="px-3 py-2 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-lg">
              {{ events.current_page }} / {{ events.last_page }}
            </span>
            
            <Link 
              v-if="events.next_page_url"
              :href="events.next_page_url"
              class="px-3 py-2 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
            >
              <i class="fas fa-chevron-right"></i>
            </Link>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
