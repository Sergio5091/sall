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
  
  <div class="flex flex-col xs:flex-col sm:flex-row bg-gray-50 min-h-screen">

    <main class="flex-1 w-full overflow-y-auto">
      <div class="w-full max-w-7xl mx-auto p-3 xs:p-4 sm:p-6 md:p-8">
        <!-- Messages flash -->
        <div v-if="success" class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border border-green-200 rounded-lg">
          <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-green-600"></i>
            <p class="text-green-800 text-sm sm:text-base">{{ success }}</p>
          </div>
        </div>
        
        <div v-if="error" class="mb-4 sm:mb-6 p-3 sm:p-4 bg-red-50 border border-red-200 rounded-lg">
          <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-red-600"></i>
            <p class="text-red-800 text-sm sm:text-base">{{ error }}</p>
          </div>
        </div>
        
        <div v-if="info" class="mb-4 sm:mb-6 p-3 sm:p-6 bg-blue-50 border-2 border-blue-200 rounded-lg shadow-lg">
          <div class="flex items-start gap-2 sm:gap-3">
            <div class="flex-shrink-0">
              <i class="fas fa-exclamation-triangle text-blue-600 text-lg sm:text-xl mt-0.5"></i>
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="text-base sm:text-lg font-semibold text-blue-800 mb-2">Information importante</h3>
              <p class="text-blue-700 text-sm sm:text-base">{{ info }}</p>
              <div class="mt-3 sm:mt-4">
                <Link href="/promoter/venues/create" class="inline-flex items-center px-3 sm:px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                  <i class="fas fa-plus mr-1 sm:mr-2"></i>
                  Créer une salle
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Header -->
        <div class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-3 xs:gap-4 mb-6 sm:mb-8">
          <p class="text-gray-900 dark:text-white text-2xl xs:text-3xl sm:text-4xl font-black leading-tight tracking-[-0.033em] truncate">Mes Événements</p>
          <div class="flex items-center gap-2 xs:gap-3 flex-wrap xs:flex-nowrap">
            <!-- Filtre brouillons -->
            <button
              @click="showDrafts = !showDrafts"
              class="flex items-center gap-1 xs:gap-2 px-2 xs:px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-xs xs:text-sm font-medium transition-colors whitespace-nowrap"
              :class="showDrafts ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-white text-gray-700 hover:bg-gray-50'"
            >
              <i class="fas" :class="showDrafts ? 'fa-eye-slash' : 'fa-eye'"></i>
              <span class="hidden sm:inline">{{ showDrafts ? 'Cacher' : 'Voir' }} brouillons</span>
              <span class="sm:hidden">{{ showDrafts ? 'Cacher' : 'Voir' }}</span>
            </button>
            <Link href="/promoter/events/create" class="group relative overflow-hidden flex items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white gap-1 xs:gap-2 text-xs xs:text-sm font-bold px-3 xs:px-6 py-2.5 xs:py-3 hover:shadow-lg transition-all duration-300 whitespace-nowrap border border-blue-600 hover:border-blue-700">
              <span class="relative z-10 flex items-center justify-center gap-1 xs:gap-2">
                <i class="fas fa-plus text-xs xs:text-base"></i>
                <span class="hidden xs:inline">Créer un événement</span>
                <span class="xs:hidden">Créer</span>
              </span>
              <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-blue-800 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </Link>
          </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-3 md:gap-4 mb-6 sm:mb-8">
          <div class="group relative overflow-hidden bg-white border border-gray-100 rounded-lg sm:rounded-2xl p-3 xs:p-4 sm:p-6 hover:border-gray-200 transition-all duration-300">
            <div class="relative z-10">
              <p class="text-gray-500 text-xs sm:text-sm font-medium mb-0.5 sm:mb-1">Total</p>
              <p class="text-gray-900 text-lg xs:text-xl sm:text-2xl font-light tracking-tight">{{ formatNumber(stats.total || 0) }}</p>
            </div>
            <div class="absolute top-0 right-0 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="group relative overflow-hidden bg-white border border-gray-100 rounded-lg sm:rounded-2xl p-3 xs:p-4 sm:p-6 hover:border-gray-200 transition-all duration-300">
            <div class="relative z-10">
              <p class="text-gray-500 text-xs sm:text-sm font-medium mb-0.5 sm:mb-1">Publiés</p>
              <p class="text-gray-900 text-lg xs:text-xl sm:text-2xl font-light tracking-tight">{{ formatNumber(stats.publies || 0) }}</p>
            </div>
            <div class="absolute top-0 right-0 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-green-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="group relative overflow-hidden bg-white border border-gray-100 rounded-lg sm:rounded-2xl p-3 xs:p-4 sm:p-6 hover:border-gray-200 transition-all duration-300">
            <div class="relative z-10">
              <p class="text-gray-500 text-xs sm:text-sm font-medium mb-0.5 sm:mb-1">Brouillons</p>
              <p class="text-gray-900 text-lg xs:text-xl sm:text-2xl font-light tracking-tight">{{ formatNumber(stats.brouillons || 0) }}</p>
            </div>
            <div class="absolute top-0 right-0 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-gray-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="group relative overflow-hidden bg-white border border-gray-100 rounded-lg sm:rounded-2xl p-3 xs:p-4 sm:p-6 hover:border-gray-200 transition-all duration-300">
            <div class="relative z-10">
              <p class="text-gray-500 text-xs sm:text-sm font-medium mb-0.5 sm:mb-1">À venir</p>
              <p class="text-gray-900 text-lg xs:text-xl sm:text-2xl font-light tracking-tight">{{ formatNumber(stats.avenir || 0) }}</p>
            </div>
            <div class="absolute top-0 right-0 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-purple-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="group relative overflow-hidden bg-white border border-gray-100 rounded-lg sm:rounded-2xl p-3 xs:p-4 sm:p-6 hover:border-gray-200 transition-all duration-300">
            <div class="relative z-10">
              <p class="text-gray-500 text-xs sm:text-sm font-medium mb-0.5 sm:mb-1">En cours</p>
              <p class="text-gray-900 text-lg xs:text-xl sm:text-2xl font-light tracking-tight">{{ formatNumber(stats.en_cours || 0) }}</p>
            </div>
            <div class="absolute top-0 right-0 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-orange-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="group relative overflow-hidden bg-white border border-gray-100 rounded-lg sm:rounded-2xl p-3 xs:p-4 sm:p-6 hover:border-gray-200 transition-all duration-300">
            <div class="relative z-10">
              <p class="text-gray-500 text-xs sm:text-sm font-medium mb-0.5 sm:mb-1">Passés</p>
              <p class="text-gray-900 text-lg xs:text-xl sm:text-2xl font-light tracking-tight">{{ formatNumber(stats.passes || 0) }}</p>
            </div>
            <div class="absolute top-0 right-0 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-slate-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
        </div>

        <!-- Events Grid -->
        <div class="mt-6 sm:mt-8">
          <div v-if="filteredEvents.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 md:gap-6">
            <div 
              v-for="event in filteredEvents" 
              :key="event.id"
              class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col"
            >
              <!-- Event Image -->
              <div class="relative h-40 xs:h-44 sm:h-48 overflow-hidden">
                <img 
                  :src="event.url_image_affiche || '/images/default-event.jpg'" 
                  :alt="event.titre"
                  class="w-full h-full object-cover"
                >
                <div class="absolute top-2 xs:top-3 right-2 xs:right-3">
                  <span 
                    :class="[
                      'px-2 xs:px-3 py-0.5 xs:py-1 rounded-full text-xs font-bold',
                      getStatusColor(event.statut)
                    ]"
                  >
                    {{ getStatusText(event.statut) }}
                  </span>
                </div>
              </div>

              <!-- Event Details -->
              <div class="p-3 xs:p-4 sm:p-6 flex-1 flex flex-col">
                <h3 class="text-sm xs:text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-1 xs:mb-2 line-clamp-2">{{ event.titre }}</h3>
                
                <p class="text-gray-600 dark:text-gray-400 text-xs xs:text-sm mb-2 xs:mb-3 sm:mb-4 line-clamp-2 sm:line-clamp-3">
                  {{ event.description }}
                </p>

                <div class="space-y-1 xs:space-y-1.5 text-xs xs:text-sm mb-3 xs:mb-4 sm:mb-6 flex-1">
                  <!-- Date -->
                  <div class="flex items-center text-gray-600 dark:text-gray-400 gap-2 truncate">
                    <i class="fas fa-calendar-alt text-blue-500 flex-shrink-0"></i>
                    <span class="truncate">{{ formatDate(event.date_debut) }}</span>
                  </div>
                  
                  <!-- Location -->
                  <div class="flex items-center text-gray-600 dark:text-gray-400 gap-2 truncate">
                    <i class="fas fa-map-marker-alt text-red-500 flex-shrink-0"></i>
                    <span class="truncate">{{ event.salle?.nom || 'Lieu à définir' }}</span>
                  </div>

                  <!-- Price -->
                  <div class="flex items-center text-gray-600 dark:text-gray-400 gap-2 truncate">
                    <i class="fas fa-tag text-green-500 flex-shrink-0"></i>
                    <span class="truncate" v-if="event.gratuit">Gratuit</span>
                    <span class="truncate" v-else>{{ event.prix_base }} {{ event.devise }}</span>
                  </div>

                  <!-- Capacity -->
                  <div class="flex items-center text-gray-600 dark:text-gray-400 gap-2 truncate">
                    <i class="fas fa-users text-purple-500 flex-shrink-0"></i>
                    <span class="truncate" v-if="event.capacite_max">{{ event.places_disponibles || 0 }}/{{ event.capacite_max }}</span>
                    <span class="truncate" v-else>Illimité</span>
                  </div>
                </div>

                <!-- Participants Button -->
                <div class="mb-3 xs:mb-4 sm:mb-6">
                  <button 
                    @click="viewParticipants(event)"
                    class="w-full px-2 xs:px-3 py-1.5 xs:py-2 bg-blue-100 text-blue-700 text-xs xs:text-sm font-medium rounded-lg hover:bg-blue-200 transition-colors"
                  >
                    <i class="fas fa-users mr-1"></i>
                    {{ event.inscriptions_count || 0 }} participants
                  </button>
                </div>

                <!-- Actions -->
                <div class="space-y-2 xs:space-y-2.5 sm:space-y-3">
                  <div class="grid grid-cols-2 gap-2">
                    <button
                      v-if="event.statut !== 'publie' && event.statut !== 'published' && event.statut !== 'actif'"
                      @click.prevent="publishEventDirect(event)"
                      class="group relative overflow-hidden bg-white border border-gray-200 text-gray-900 font-medium rounded-xl px-2 xs:px-3 py-2 xs:py-3 hover:border-gray-300 transition-all duration-300 text-xs xs:text-sm"
                    >
                      <span class="relative z-10 flex items-center justify-center gap-1">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span>Publier</span>
                      </span>
                      <div class="absolute inset-0 bg-gradient-to-r from-green-50 to-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                    <Link
                      v-else
                      :href="`/promoter/events/${event.id}`"
                      class="group relative overflow-hidden bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl px-3 xs:px-4 py-2 xs:py-3 hover:shadow-lg transition-all duration-300 text-xs xs:text-sm border border-blue-600 hover:border-blue-700"
                    >
                      <span class="relative z-10 flex items-center justify-center gap-1">
                        <i class="fas fa-eye"></i>
                        <span>Voir détails</span>
                      </span>
                      <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-blue-800 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </Link>
                  </div>
                  
                  <div class="grid grid-cols-2 gap-2">
                    <Link 
                      :href="`/promoter/events/${event.id}/edit`"
                      class="group relative overflow-hidden bg-white border border-gray-200 text-gray-900 font-medium rounded-xl px-2 xs:px-3 py-2 xs:py-3 hover:border-gray-300 transition-all duration-300 text-xs xs:text-sm"
                    >
                      <span class="relative z-10 flex items-center justify-center gap-1">
                        <i class="fas fa-edit text-gray-600"></i>
                        <span>Modifier</span>
                      </span>
                      <div class="absolute inset-0 bg-gradient-to-r from-gray-50 to-slate-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </Link>
                    <Link 
                      :href="`/promoter/events/${event.id}/participants`"
                      class="group relative overflow-hidden bg-white border border-gray-200 text-gray-900 font-medium rounded-xl px-2 xs:px-3 py-2 xs:py-3 hover:border-gray-300 transition-all duration-300 text-xs xs:text-sm"
                    >
                      <span class="relative z-10 flex items-center justify-center gap-1">
                        <i class="fas fa-users text-purple-600"></i>
                        <span>Part.</span>
                      </span>
                      <div class="absolute inset-0 bg-gradient-to-r from-purple-50 to-violet-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </Link>
                  </div>
                  
                  <button
                    @click.prevent="deleteEvent(event)"
                    class="group relative overflow-hidden bg-white border border-gray-200 text-red-600 font-medium rounded-xl px-2 xs:px-3 py-2 xs:py-3 hover:border-red-200 transition-all duration-300 w-full text-xs xs:text-sm"
                  >
                    <span class="relative z-10 flex items-center justify-center gap-1">
                      <i class="fas fa-trash-alt"></i>
                      <span>Supprimer</span>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-red-50 to-rose-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-12 sm:py-16">
            <i class="fas fa-calendar-times text-5xl sm:text-6xl text-gray-300 dark:text-gray-600 mb-3 sm:mb-4"></i>
            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-2">
              {{ showDrafts ? 'Aucun événement' : 'Aucun événement publié' }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4 sm:mb-6 text-sm sm:text-base">
              {{ showDrafts ? 'Vous n\'avez pas encore créé d\'événement.' : 'Vous n\'avez pas encore d\'événement publié. Créez-en un ou publiez un brouillon.' }}
            </p>
            <Link 
              href="/promoter/events/create"
              class="inline-flex items-center px-3 xs:px-4 py-2 bg-brand-red text-white text-sm font-medium rounded-lg hover:bg-brand-red/90 transition-colors"
            >
              <i class="fas fa-plus mr-2"></i>
              Créer un événement
            </Link>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="filteredEvents.length > 0 && events.data.length > 0" class="mt-6 sm:mt-8 flex justify-center">
          <div class="flex gap-1 xs:gap-2">
            <Link 
              v-if="events.prev_page_url"
              :href="events.prev_page_url"
              class="px-2 xs:px-3 py-2 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-sm"
            >
              <i class="fas fa-chevron-left"></i>
            </Link>
            
            <span class="px-2 xs:px-3 py-2 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-lg text-sm">
              {{ events.current_page }} / {{ events.last_page }}
            </span>
            
            <Link 
              v-if="events.next_page_url"
              :href="events.next_page_url"
              class="px-2 xs:px-3 py-2 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-sm"
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
