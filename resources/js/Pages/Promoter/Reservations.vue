<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

import PromoterLayout from '@/Layouts/PromoterLayout.vue';
defineOptions({ layout: PromoterLayout });
import NotificationModal from '../../Components/NotificationModal.vue';

const props = defineProps({
  reservations: Object,
  venues: Array,
  stats: Object
});

// Debug
console.log('Reservations page - props:', props);

// États pour les modaux
const showNotificationModal = ref(false);
const notificationType = ref('success');
const notificationTitle = ref('');
const notificationMessage = ref('');

// États pour les filtres
const selectedVenue = ref('all');
const selectedStatus = ref('all');
const searchQuery = ref('');

// État pour la réservation sélectionnée
const selectedReservation = ref(null);
const showDetailsModal = ref(false);

// Formater la date
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

// Formater le prix
const formatPrice = (price) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XOF',
    minimumFractionDigits: 0
  }).format(price);
};

// Filtrer les réservations
const filteredReservations = computed(() => {
  console.log('Props reservations:', props.reservations);
  let filtered = props.reservations?.data || [];
  
  // S'assurer que filtered est un tableau
  if (!Array.isArray(filtered)) {
    console.warn('filtered is not an array:', filtered);
    filtered = [];
  }

  // Filtrer par salle
  if (selectedVenue.value !== 'all') {
    filtered = filtered.filter(r => r.venue_id == selectedVenue.value);
  }

  // Filtrer par statut
  if (selectedStatus.value !== 'all') {
    filtered = filtered.filter(r => r.statut === selectedStatus.value);
  }

  // Filtrer par recherche
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(r => 
      r.client_name?.toLowerCase().includes(query) ||
      r.client_email?.toLowerCase().includes(query) ||
      r.client_telephone?.toLowerCase().includes(query) ||
      r.venue_name?.toLowerCase().includes(query)
    );
  }

  console.log('Filtered reservations:', filtered);
  return filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

// Obtenir la couleur du statut
const getStatusColor = (status) => {
  const colors = {
    'en_attente': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'confirme': 'bg-green-100 text-green-800 border-green-200',
    'annule': 'bg-red-100 text-red-800 border-red-200',
    'termine': 'bg-gray-100 text-gray-800 border-gray-200'
  };
  return colors[status] || 'bg-gray-100 text-gray-800 border-gray-200';
};

// Obtenir le libellé du statut
const getStatusLabel = (status) => {
  const labels = {
    'en_attente': 'En attente',
    'confirme': 'Confirmée',
    'annule': 'Annulée',
    'termine': 'Terminée'
  };
  return labels[status] || status;
};

// Accepter une réservation
const acceptReservation = (reservation) => {
  router.patch(`/promoter/reservations/${reservation.id}/accept`, {}, {
    onSuccess: () => {
      notificationType.value = 'success';
      notificationTitle.value = 'Réservation acceptée';
      notificationMessage.value = 'La réservation a été acceptée avec succès.';
      showNotificationModal.value = true;
    },
    onError: () => {
      notificationType.value = 'error';
      notificationTitle.value = 'Erreur';
      notificationMessage.value = 'Une erreur est survenue lors de l\'acceptation de la réservation.';
      showNotificationModal.value = true;
    }
  });
};

// Refuser une réservation
const rejectReservation = (reservation) => {
  router.patch(`/promoter/reservations/${reservation.id}/reject`, {}, {
    onSuccess: () => {
      notificationType.value = 'success';
      notificationTitle.value = 'Réservation refusée';
      notificationMessage.value = 'La réservation a été refusée avec succès.';
      showNotificationModal.value = true;
    },
    onError: () => {
      notificationType.value = 'error';
      notificationTitle.value = 'Erreur';
      notificationMessage.value = 'Une erreur est survenue lors du refus de la réservation.';
      showNotificationModal.value = true;
    }
  });
};

// Aller à la conversation
const goToConversation = (reservation) => {
  // Trouver la conversation associée à cette réservation
  router.get(`/promoter/conversations`, {
    data: {
      search: reservation.client_name
    }
  });
};

// Voir les détails d'une réservation
const viewReservationDetails = (reservation) => {
  selectedReservation.value = reservation;
  showDetailsModal.value = true;
};

// Fermer le modal de détails
const closeDetailsModal = () => {
  showDetailsModal.value = false;
  selectedReservation.value = null;
};
</script>

<template>
  <Head title="Gestion des Réservations" />

  <div class="relative flex min-h-screen w-full bg-gray-50 font-display text-gray-800">
    <main class="flex-1 overflow-y-auto transition-all duration-300">
      <!-- Conteneur principal responsive -->
      <div class="w-full sm:max-w-full md:max-w-4xl lg:max-w-6xl xl:max-w-7xl 2xl:max-w-screen-2xl mx-auto px-3 sm:px-4 md:px-6 py-6 md:py-8">
        <!-- Header -->
        <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border border-gray-200 rounded-lg px-3 sm:px-4 md:px-6 py-3 sm:py-4 mb-4 sm:mb-6">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="flex flex-col gap-1 min-w-0">
              <p class="text-gray-900 text-lg sm:text-xl md:text-2xl font-bold leading-tight tracking-tight truncate">Gestion des Réservations</p>
              <p class="text-gray-600 text-xs sm:text-sm font-normal leading-normal">Consultez et gérez les demandes de réservation</p>
            </div>
            <!-- Stats pour débogage -->
            <div v-if="props.stats" class="flex flex-wrap gap-2 sm:gap-4 text-xs sm:text-sm">
              <span class="px-1.5 sm:px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs sm:text-xs">
                Total: {{ props.stats.total || 0 }}
              </span>
              <span class="px-1.5 sm:px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs sm:text-xs">
                En attente: {{ props.stats.en_attente || 0 }}
              </span>
            </div>
          </div>
        </div>
        <!-- Filtres -->
        <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 md:p-6 mb-4 sm:mb-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Recherche</label>
              <input 
                v-model="searchQuery"
                type="text" 
                class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg bg-white text-gray-900 text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                placeholder="Nom, email, téléphone..."
              >
            </div>
            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Salle</label>
              <select 
                v-model="selectedVenue"
                class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg bg-white text-gray-900 text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
              >
                <option value="all">Toutes les salles</option>
                <option v-for="venue in props.venues" :key="venue.id" :value="venue.id">
                  {{ venue.nom }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Statut</label>
              <select 
                v-model="selectedStatus"
                class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg bg-white text-gray-900 text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
              >
                <option value="all">Tous les statuts</option>
                <option value="en_attente">En attente</option>
                <option value="confirme">Confirmée</option>
                <option value="annule">Annulée</option>
                <option value="termine">Terminée</option>
              </select>
            </div>
            <div class="flex items-end">
              <div class="text-xs sm:text-sm text-gray-600">
                <span class="font-medium">{{ filteredReservations.length }}</span> réservation(s) trouvée(s)
              </div>
            </div>
          </div>
        </div>

        <!-- Liste des réservations -->
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full min-w-[500px] sm:min-w-[600px] lg:min-w-[800px]">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Client</th>
                  <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap hidden sm:table-cell">Salle</th>
                  <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Date/Heure</th>
                  <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap hidden md:table-cell">Durée</th>
                  <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Montant</th>
                  <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Statut</th>
                  <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-if="filteredReservations.length === 0">
                  <td colspan="7" class="px-3 sm:px-4 md:px-6 py-8 sm:py-12 text-center">
                    <div class="flex flex-col items-center gap-3 sm:gap-4">
                      <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-xmark text-gray-400 text-lg sm:text-2xl"></i>
                      </div>
                      <div class="max-w-sm">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-1">Aucune réservation trouvée</h3>
                        <p class="text-xs sm:text-sm text-gray-500 break-words">
                          {{ searchQuery || selectedVenue !== 'all' || selectedStatus !== 'all' 
                            ? 'Essayez de modifier vos filtres' 
                            : 'Vous n\'avez pas encore de réservations' }}
                        </p>
                      </div>
                    </div>
                  </td>
                </tr>
                      <div class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ reservation.client_name || 'N/A' }}</div>
                      <div class="text-xs sm:text-sm text-gray-500 truncate hidden sm:block">{{ reservation.client_email || 'N/A' }}</div>
                    </div>
                  </td>
                  <td class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                    <div class="text-xs sm:text-sm text-gray-900 truncate max-w-[120px] sm:max-w-full">{{ reservation.venue_name || 'N/A' }}</div>
                  </td>
                  <td class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 sm:py-4 whitespace-nowrap">
                    <div class="text-xs sm:text-sm text-gray-900">{{ formatDate(reservation.date_heure) }}</div>
                  </td>
                  <td class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                    <div class="text-xs sm:text-sm text-gray-900">{{ reservation.duree }}h</div>
                  </td>
                  <td class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 sm:py-4 whitespace-nowrap">
                    <div class="text-xs sm:text-sm font-medium text-gray-900">{{ formatPrice(reservation.montant_total) }}</div>
                  </td>
                  <td class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 sm:py-4 whitespace-nowrap">
                    <span :class="`inline-flex px-1.5 sm:px-2 py-0.5 sm:py-1 text-xs font-medium rounded-full border ${getStatusColor(reservation.statut)}`">
                      {{ getStatusLabel(reservation.statut) }}
                    </span>
                  </td>
                  <td class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium">
                    <div class="flex gap-0.5 sm:gap-1 md:gap-2">
                      <button 
                        @click="viewReservationDetails(reservation)"
                        class="text-blue-600 hover:text-blue-900 p-1 sm:p-1.5 md:p-2 rounded hover:bg-blue-50 transition-colors"
                        title="Voir les détails"
                      >
                        <i class="fas fa-eye text-xs sm:text-sm"></i>
                      </button>
                      <button 
                        v-if="reservation.statut === 'en_attente'"
                        @click="acceptReservation(reservation)"
                        class="text-green-600 hover:text-green-900 p-1 sm:p-1.5 md:p-2 rounded hover:bg-green-50 transition-colors"
                        title="Accepter"
                      >
                        <i class="fas fa-check text-xs sm:text-sm"></i>
                      </button>
                      <button 
                        v-if="reservation.statut === 'en_attente'"
                        @click="rejectReservation(reservation)"
                        class="text-red-600 hover:text-red-900 p-1 sm:p-1.5 md:p-2 rounded hover:bg-red-50 transition-colors"
                        title="Refuser"
                      >
                        <i class="fas fa-times text-xs sm:text-sm"></i>
                      </button>
                      <button 
                        v-if="reservation.has_conversation"
                        @click="goToConversation(reservation)"
                        class="text-blue-600 hover:text-blue-900 p-1 sm:p-1.5 md:p-2 rounded hover:bg-blue-50 transition-colors"
                        title="Discuter"
                      >
                        <i class="fas fa-comments text-xs sm:text-sm"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          </div>

        <!-- Vue mobile alternative (cartes) -->
        <div class="lg:hidden mt-4 space-y-3">
          <div v-for="reservation in filteredReservations" :key="reservation.id" 
               class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <!-- En-tête de la carte -->
            <div class="flex justify-between items-start mb-3">
              <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-gray-900 truncate">{{ reservation.client_name || 'N/A' }}</h3>
                <p class="text-xs text-gray-500 truncate">{{ reservation.client_email || 'N/A' }}</p>
              </div>
              <span :class="`inline-flex px-2 py-1 text-xs font-medium rounded-full border ${getStatusColor(reservation.statut)}`">
                {{ getStatusLabel(reservation.statut) }}
              </span>
            </div>
            
            <!-- Détails principaux -->
            <div class="grid grid-cols-2 gap-3 mb-3">
              <div class="bg-gray-50 rounded p-2">
                <p class="text-xs text-gray-500 mb-1">Date/Heure</p>
                <p class="text-xs font-medium text-gray-900">{{ formatDate(reservation.date_heure) }}</p>
              </div>
              <div class="bg-gray-50 rounded p-2">
                <p class="text-xs text-gray-500 mb-1">Montant</p>
                <p class="text-xs font-medium text-gray-900">{{ formatPrice(reservation.montant_total) }}</p>
              </div>
            </div>
            
            <!-- Informations supplémentaires -->
            <div class="space-y-2 mb-3">
              <div class="flex items-center gap-2">
                <i class="fas fa-store text-gray-400 text-xs"></i>
                <p class="text-xs text-gray-700 truncate">{{ reservation.venue_name || 'N/A' }}</p>
              </div>
              <div class="flex items-center gap-2">
                <i class="fas fa-clock text-gray-400 text-xs"></i>
                <p class="text-xs text-gray-700">{{ reservation.duree }} heure(s)</p>
              </div>
            </div>
            
            <!-- Actions -->
            <div class="flex gap-2 pt-3 border-t border-gray-100">
              <button @click="viewReservationDetails(reservation)"
                      class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-3 rounded transition-colors">
                <i class="fas fa-eye mr-1"></i> Détails
              </button>
              <button v-if="reservation.statut === 'en_attente'" @click="acceptReservation(reservation)"
                      class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium py-2 px-3 rounded transition-colors">
                <i class="fas fa-check mr-1"></i> Accepter
              </button>
              <button v-if="reservation.statut === 'en_attente'" @click="rejectReservation(reservation)"
                      class="flex-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium py-2 px-3 rounded transition-colors">
                <i class="fas fa-times mr-1"></i> Refuser
              </button>
            </div>
          </div>
        </div>
    </main>
  </div>

  <!-- Modal de détails de réservation -->
  <div v-if="showDetailsModal && selectedReservation" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-[#19202e] rounded-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white">Détails de la réservation</h3>
          <button @click="closeDetailsModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <div class="space-y-6">
          <!-- Informations client -->
          <div>
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Informations client</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Nom:</span>
                <p class="text-gray-900 dark:text-white">{{ selectedReservation.client_name || 'N/A' }}</p>
              </div>
              <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Email:</span>
                <p class="text-gray-900 dark:text-white">{{ selectedReservation.client_email || 'N/A' }}</p>
              </div>
              <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Téléphone:</span>
                <p class="text-gray-900 dark:text-white">{{ selectedReservation.client_telephone || 'N/A' }}</p>
              </div>
              <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Date de demande:</span>
                <p class="text-gray-900 dark:text-white">{{ formatDate(selectedReservation.created_at) }}</p>
              </div>
            </div>
          </div>

          <!-- Détails de la réservation -->
          <div>
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Détails de la réservation</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Salle:</span>
                <p class="text-gray-900 dark:text-white">{{ selectedReservation.venue_name || 'N/A' }}</p>
              </div>
              <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Date/Heure:</span>
                <p class="text-gray-900 dark:text-white">{{ formatDate(selectedReservation.date_heure) }}</p>
              </div>
              <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Durée:</span>
                <p class="text-gray-900 dark:text-white">{{ selectedReservation.duree }} heure(s)</p>
              </div>
              <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Nombre de personnes:</span>
                <p class="text-gray-900 dark:text-white">{{ selectedReservation.nombre_personnes || 'N/A' }}</p>
              </div>
              <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Type d'événement:</span>
                <p class="text-gray-900 dark:text-white">{{ selectedReservation.type_evenement || 'N/A' }}</p>
              </div>
              <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Montant total:</span>
                <p class="text-gray-900 dark:text-white font-medium">{{ formatPrice(selectedReservation.montant_total) }}</p>
              </div>
            </div>
          </div>

          <!-- Message et besoins spéciaux -->
          <div v-if="selectedReservation.message || selectedReservation.besoins_speciaux">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Informations supplémentaires</h4>
            <div v-if="selectedReservation.message" class="mb-4">
              <span class="text-sm text-gray-500 dark:text-gray-400">Message:</span>
              <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-[#2d3748] p-3 rounded-lg">{{ selectedReservation.message }}</p>
            </div>
            <div v-if="selectedReservation.besoins_speciaux">
              <span class="text-sm text-gray-500 dark:text-gray-400">Besoins spéciaux:</span>
              <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-[#2d3748] p-3 rounded-lg">{{ selectedReservation.besoins_speciaux }}</p>
            </div>
          </div>

          <!-- Actions -->
          <div v-if="selectedReservation.statut === 'en_attente'" class="flex gap-3 pt-4 border-t border-gray-200">
            <button 
              @click="acceptReservation(selectedReservation)"
              class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
            >
              <i class="fas fa-check mr-2"></i>
              Accepter la réservation
            </button>
            <button 
              @click="rejectReservation(selectedReservation)"
              class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
            >
              <i class="fas fa-times mr-2"></i>
              Refuser la réservation
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Notification Modal -->
  <NotificationModal 
    :show="showNotificationModal"
    :type="notificationType"
    :title="notificationTitle"
    :message="notificationMessage"
    @close="showNotificationModal = false"
  />
</template>
