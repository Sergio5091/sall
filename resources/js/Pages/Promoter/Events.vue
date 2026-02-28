<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import ConfirmModal from '../../Components/ConfirmModal.vue';
import PromoterLayout from '@/Layouts/PromoterLayout.vue';
defineOptions({ layout: PromoterLayout });
import NotificationModal from '../../Components/NotificationModal.vue';

const page = usePage();
const props = defineProps({
    events: Object,
    stats: Object
});

console.log('Events - Toutes les props:', props);
console.log('Events - Toutes les page props:', page.props);

const success = computed(() => {
    console.log('Events - Flash success:', page.props.flash?.success);
    return page.props.flash?.success;
});
const error = computed(() => {
    console.log('Events - Flash error:', page.props.flash?.error);
    return page.props.flash?.error;
});
const info = computed(() => {
    console.log('Events - Flash info:', page.props.flash?.info);
    return page.props.flash?.info;
});

// États pour les modaux
const showConfirmModal = ref(false);
const confirmTitle = ref('');
const confirmMessage = ref('');
const confirmAction = ref(null);
const confirmData = ref(null);

const showNotificationModal = ref(false);
const notificationType = ref('success');
const notificationTitle = ref('');
const notificationMessage = ref('');

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

const showDrafts = ref(false);

// Log initial pour déboguer
console.log('Events props:', props.events);

// Watcher pour surveiller les changements d'événements
watch(() => props.events, (newEvents) => {
  console.log('Events updated:', newEvents);
}, { deep: true });

// Filtrer les événements selon le choix
const filteredEvents = computed(() => {
  if (!props.events?.data) return [];
  
  console.log('Events data:', props.events.data);
  console.log('Show drafts:', showDrafts.value);
  
  if (showDrafts.value) {
    return props.events.data; // Montrer tous les événements
  } else {
    // Filtrer pour n'afficher que les événements publiés
    const published = props.events.data.filter(event => {
      console.log('Event statut:', event.statut, 'for event:', event.titre);
      // Vérifier plusieurs valeurs possibles pour "publié"
      return event.statut === 'publie' || event.statut === 'published' || event.statut === 'actif';
    });
    console.log('Published events:', published);
    return published;
  }
});

// Obtenir la couleur du statut
const getStatusColor = (status) => {
    switch (status) {
        case 'publie':
        case 'published':
        case 'actif':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
        case 'brouillon':
        case 'draft':
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
        case 'annule':
        case 'cancelled':
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
        case 'termine':
        case 'finished':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
        default: return 'bg-gray-100 text-gray-800';
    }
};

// Obtenir le texte du statut
const getStatusText = (status) => {
    switch (status) {
        case 'publie':
        case 'published':
        case 'actif':
            return 'Publié';
        case 'brouillon':
        case 'draft':
            return 'Brouillon';
        case 'annule':
        case 'cancelled':
            return 'Annulé';
        case 'termine':
        case 'finished':
            return 'Terminé';
        default: return status;
    }
};

// Publier un événement directement (sans modale)
const publishEventDirect = (evt) => {
  console.log('Direct publishing event:', evt);
  
  // Publication directe sans confirmation
  router.post(`/promoter/events/${evt.id}/publish`, {}, {
    onSuccess: (response) => {
      console.log('Direct publish success:', response);
      notificationType.value = 'success';
      notificationTitle.value = 'Succès';
      notificationMessage.value = 'Événement publié avec succès !';
      showNotificationModal.value = true;
      
      // Mettre à jour le statut localement
      evt.statut = 'publie';
      
      // Recharger les données
      router.reload({ only: ['events', 'stats'] });
    },
    onError: (errors) => {
      console.error('Direct publish errors:', errors);
      notificationType.value = 'error';
      notificationTitle.value = 'Erreur';
      notificationMessage.value = 'Erreur lors de la publication';
      showNotificationModal.value = true;
    }
  });
};

// Publier un événement directement depuis la liste
const publishEvent = (evt) => {
  console.log('Publishing event:', evt);
  confirmTitle.value = 'Publier l\'événement';
  confirmMessage.value = `Voulez-vous vraiment publier l'événement "${evt.titre}" ?`;
  confirmAction.value = 'publish';
  confirmData.value = evt;
  console.log('Setting showConfirmModal to true');
  showConfirmModal.value = true;
  console.log('showConfirmModal value:', showConfirmModal.value);
};

// Confirmer l'action
const confirmActionHandler = () => {
  console.log('confirmActionHandler called');
  console.log('confirmAction:', confirmAction.value);
  console.log('confirmData:', confirmData.value);
  
  if (!confirmAction.value || !confirmData.value) {
    console.log('Missing confirmAction or confirmData');
    return;
  }
  
  if (confirmAction.value === 'publish') {
    const evt = confirmData.value;
    console.log('Confirming publish for event:', evt);
    // Utiliser l'endpoint de publication dédié
    router.post(`/promoter/events/${evt.id}/publish`, {}, {
      onSuccess: (response) => {
        console.log('Publish success:', response);
        notificationType.value = 'success';
        notificationTitle.value = 'Succès';
        notificationMessage.value = 'Événement publié avec succès !';
        showNotificationModal.value = true;
        
        // Mettre à jour le statut de l'événement localement
        if (confirmData.value) {
          confirmData.value.statut = 'publie';
        }
        
        // Fermer la modale de confirmation
        showConfirmModal.value = false;
        
        // Recharger les données via Inertia pour mettre à jour la page
        router.reload({ only: ['events', 'stats'] });
      },
      onError: (errors) => {
        console.error('Publish errors:', errors);
        let errorMessage = 'Une erreur est survenue lors de la publication:\n\n';
        
        if (typeof errors === 'object') {
          Object.keys(errors).forEach(key => {
            errorMessage += `${key}: ${errors[key]}\n`;
          });
        } else {
          errorMessage += errors || 'Veuillez contacter le support.';
        }
        
        notificationType.value = 'error';
        notificationTitle.value = 'Erreur';
        notificationMessage.value = errorMessage;
        showNotificationModal.value = true;
      }
    });
  } else if (confirmAction.value === 'delete') {
    const evt = confirmData.value;
    router.delete(`/promoter/events/${evt.id}`, {
      onSuccess: () => {
        notificationType.value = 'success';
        notificationTitle.value = 'Succès';
        notificationMessage.value = 'Événement supprimé avec succès !';
        showNotificationModal.value = true;
      },
      onError: (errors) => {
        let errorMessage = 'Une erreur est survenue lors de la suppression:\n\n';
        
        if (typeof errors === 'object') {
          Object.keys(errors).forEach(key => {
            errorMessage += `${key}: ${errors[key]}\n`;
          });
        } else {
          errorMessage += errors || 'Veuillez contacter le support.';
        }
        
        notificationType.value = 'error';
        notificationTitle.value = 'Erreur';
        notificationMessage.value = errorMessage;
        showNotificationModal.value = true;
      }
    });
  }
  
  // Réinitialiser
  showConfirmModal.value = false;
  confirmAction.value = null;
  confirmData.value = null;
};

// Voir les participants d'un événement
const viewParticipants = (event) => {
  router.visit(`/promoter/events/${event.id}/participants`);
};

// Supprimer un événement
const deleteEvent = (evt) => {
  confirmTitle.value = 'Supprimer l\'événement';
  confirmMessage.value = `Voulez-vous vraiment supprimer définitivement l'événement "${evt.titre}" ?`;
  confirmAction.value = 'delete';
  confirmData.value = evt;
  showConfirmModal.value = true;
};
</script>

<template>
  <Head title="Mes Événements" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="flex h-screen bg-gray-50">

    <main class="flex-1 overflow-y-auto lg:ml-64">
      <div class="p-8">
        <!-- Messages flash -->
        <div v-if="success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
          <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-2"></i>
            <p class="text-green-800">{{ success }}</p>
          </div>
        </div>
        
        <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
          <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
            <p class="text-red-800">{{ error }}</p>
          </div>
        </div>
        
        <div v-if="info" class="mb-6 p-6 bg-blue-50 border-2 border-blue-200 rounded-lg shadow-lg">
          <div class="flex items-start">
            <div class="flex-shrink-0">
              <i class="fas fa-exclamation-triangle text-blue-600 text-xl mr-3 mt-1"></i>
            </div>
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-blue-800 mb-2">Information importante</h3>
              <p class="text-blue-700">{{ info }}</p>
              <div class="mt-4">
                <Link href="/promoter/venues/create" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                  <i class="fas fa-plus mr-2"></i>
                  Créer une salle maintenant
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Mes Événements</p>
          <div class="flex items-center gap-3">
            <!-- Filtre brouillons -->
            <button
              @click="showDrafts = !showDrafts"
              class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium transition-colors"
              :class="showDrafts ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-white text-gray-700 hover:bg-gray-50'"
            >
              <i class="fas" :class="showDrafts ? 'fa-eye-slash' : 'fa-eye'"></i>
              <span>{{ showDrafts ? 'Cacher les brouillons' : 'Voir les brouillons' }}</span>
            </button>
            <Link href="/promoter/events/create" class="flex items-center justify-center rounded-lg h-10 bg-blue-600 text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] px-4 hover:bg-blue-500 transition-colors">
              <i class="fas fa-plus text-base font-bold"></i>
              <span class="truncate">Créer un événement</span>
            </Link>
          </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mt-8 max-w-7xl">
          <div class="group relative overflow-hidden bg-white border border-gray-100 rounded-2xl p-6 hover:border-gray-200 transition-all duration-300">
            <div class="relative z-10">
              <p class="text-gray-500 text-sm font-medium mb-1">Total</p>
              <p class="text-gray-900 text-2xl font-light tracking-tight">{{ formatNumber(stats.total || 0) }}</p>
            </div>
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="group relative overflow-hidden bg-white border border-gray-100 rounded-2xl p-6 hover:border-gray-200 transition-all duration-300">
            <div class="relative z-10">
              <p class="text-gray-500 text-sm font-medium mb-1">Publiés</p>
              <p class="text-gray-900 text-2xl font-light tracking-tight">{{ formatNumber(stats.publies || 0) }}</p>
            </div>
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-green-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="group relative overflow-hidden bg-white border border-gray-100 rounded-2xl p-6 hover:border-gray-200 transition-all duration-300">
            <div class="relative z-10">
              <p class="text-gray-500 text-sm font-medium mb-1">Brouillons</p>
              <p class="text-gray-900 text-2xl font-light tracking-tight">{{ formatNumber(stats.brouillons || 0) }}</p>
            </div>
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-gray-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="group relative overflow-hidden bg-white border border-gray-100 rounded-2xl p-6 hover:border-gray-200 transition-all duration-300">
            <div class="relative z-10">
              <p class="text-gray-500 text-sm font-medium mb-1">À venir</p>
              <p class="text-gray-900 text-2xl font-light tracking-tight">{{ formatNumber(stats.avenir || 0) }}</p>
            </div>
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-purple-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="group relative overflow-hidden bg-white border border-gray-100 rounded-2xl p-6 hover:border-gray-200 transition-all duration-300">
            <div class="relative z-10">
              <p class="text-gray-500 text-sm font-medium mb-1">En cours</p>
              <p class="text-gray-900 text-2xl font-light tracking-tight">{{ formatNumber(stats.en_cours || 0) }}</p>
            </div>
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-orange-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="group relative overflow-hidden bg-white border border-gray-100 rounded-2xl p-6 hover:border-gray-200 transition-all duration-300">
            <div class="relative z-10">
              <p class="text-gray-500 text-sm font-medium mb-1">Passés</p>
              <p class="text-gray-900 text-2xl font-light tracking-tight">{{ formatNumber(stats.passes || 0) }}</p>
            </div>
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-slate-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
        </div>

        <!-- Events Grid -->
        <div class="mt-8">
          <div v-if="filteredEvents.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="event in filteredEvents" 
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
                  <div class="flex items-center gap-2">
                    <button 
                      @click="viewParticipants(event)"
                      class="px-3 py-1.5 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 transition-colors"
                    >
                      <i class="fas fa-users mr-1"></i>
                      {{ event.inscriptions_count || 0 }} participant(s)
                    </button>
                  </div>
                <div class="mt-6 space-y-3">
                  <div class="grid grid-cols-2 gap-3">
                    <button
                      v-if="event.statut !== 'publie' && event.statut !== 'published' && event.statut !== 'actif'"
                      @click.prevent="publishEventDirect(event)"
                      class="group relative overflow-hidden bg-white border border-gray-200 text-gray-900 font-medium rounded-xl px-4 py-3 hover:border-gray-300 transition-all duration-300"
                    >
                      <span class="relative z-10 flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2 text-green-600"></i>
                        Publier
                      </span>
                      <div class="absolute inset-0 bg-gradient-to-r from-green-50 to-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                    <Link
                      v-else
                      :href="`/promoter/events/${event.id}`"
                      class="group relative overflow-hidden bg-white border border-gray-200 text-gray-900 font-medium rounded-xl px-4 py-3 hover:border-gray-300 transition-all duration-300"
                    >
                      <span class="relative z-10 flex items-center justify-center">
                        <i class="fas fa-eye mr-2 text-blue-600"></i>
                        Voir
                      </span>
                      <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-indigo-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </Link>
                  </div>
                  
                  <div class="grid grid-cols-2 gap-3">
                    <Link 
                      :href="`/promoter/events/${event.id}/edit`"
                      class="group relative overflow-hidden bg-white border border-gray-200 text-gray-900 font-medium rounded-xl px-4 py-3 hover:border-gray-300 transition-all duration-300"
                    >
                      <span class="relative z-10 flex items-center justify-center">
                        <i class="fas fa-edit mr-2 text-gray-600"></i>
                        Modifier
                      </span>
                      <div class="absolute inset-0 bg-gradient-to-r from-gray-50 to-slate-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </Link>
                    <Link 
                      :href="`/promoter/events/${event.id}/participants`"
                      class="group relative overflow-hidden bg-white border border-gray-200 text-gray-900 font-medium rounded-xl px-4 py-3 hover:border-gray-300 transition-all duration-300"
                    >
                      <span class="relative z-10 flex items-center justify-center">
                        <i class="fas fa-users mr-2 text-purple-600"></i>
                        Participants
                      </span>
                      <div class="absolute inset-0 bg-gradient-to-r from-purple-50 to-violet-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </Link>
                  </div>
                  
                  <button
                    @click.prevent="deleteEvent(event)"
                    class="group relative overflow-hidden bg-white border border-gray-200 text-red-600 font-medium rounded-xl px-4 py-3 hover:border-red-200 transition-all duration-300 w-full"
                  >
                    <span class="relative z-10 flex items-center justify-center">
                      <i class="fas fa-trash-alt mr-2"></i>
                      Supprimer
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-red-50 to-rose-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-12">
            <i class="fas fa-calendar-times text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
              {{ showDrafts ? 'Aucun événement' : 'Aucun événement publié' }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
              {{ showDrafts ? 'Vous n\'avez pas encore créé d\'événement.' : 'Vous n\'avez pas encore d\'événement publié. Créez-en un ou publiez un brouillon.' }}
            </p>
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
        <div v-if="filteredEvents.length > 0 && events.data.length > 0" class="mt-8 flex justify-center">
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

<!-- Confirm Modal -->
<ConfirmModal
  :show="showConfirmModal"
  :title="confirmTitle"
  :message="confirmMessage"
  @confirm="confirmActionHandler"
  @cancel="showConfirmModal = false"
  @close="showConfirmModal = false"
/>

<!-- Notification Modal -->
<NotificationModal
  :show="showNotificationModal"
  :type="notificationType"
  :title="notificationTitle"
  :message="notificationMessage"
  @close="showNotificationModal = false"
/>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  display: -moz-box;
  display: box;
  -webkit-line-clamp: 3;
  -moz-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  -moz-box-orient: vertical;
  box-orient: vertical;
  overflow: hidden;
}
</style>
