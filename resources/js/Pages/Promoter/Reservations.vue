<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

import PromoterLayout from '@/Layouts/PromoterLayout.vue';
defineOptions({ layout: PromoterLayout });
import NotificationModal from '../../Components/NotificationModal.vue';

const props = defineProps({
  reservations: Array,
  venues: Array
});

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
    'confirmee': 'bg-green-100 text-green-800 border-green-200',
    'annulee': 'bg-red-100 text-red-800 border-red-200',
    'terminee': 'bg-gray-100 text-gray-800 border-gray-200'
  };
  return colors[status] || 'bg-gray-100 text-gray-800 border-gray-200';
};

// Obtenir le libellé du statut
const getStatusLabel = (status) => {
  const labels = {
    'en_attente': 'En attente',
    'confirmee': 'Confirmée',
    'annulee': 'Annulée',
    'terminee': 'Terminée'
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

  <div class="flex h-screen bg-gray-50">

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto lg:ml-64">
      <!-- Header -->
      <div class="sticky top-0 z-10 bg-white/80 dark:bg-[#19202e]/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 px-8 py-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div class="flex min-w-72 flex-col gap-1">
            <p class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Gestion des Réservations</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm font-normal leading-normal">Consultez et gérez les demandes de réservation</p>
          </div>
        </div>
      </div>

      <div class="p-8">
        <!-- Filtres -->
        <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recherche</label>
              <input 
                v-model="searchQuery"
                type="text" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                placeholder="Nom, email, téléphone..."
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Salle</label>
              <select 
                v-model="selectedVenue"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
              >
                <option value="all">Toutes les salles</option>
                <option v-for="venue in venues" :key="venue.id" :value="venue.id">{{ venue.nom }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Statut</label>
              <select 
                v-model="selectedStatus"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
              >
                <option value="all">Tous les statuts</option>
                <option value="en_attente">En attente</option>
                <option value="confirmee">Confirmée</option>
                <option value="annulee">Annulée</option>
                <option value="terminee">Terminée</option>
              </select>
            </div>
            <div class="flex items-end">
              <div class="text-sm text-gray-600 dark:text-gray-400">
                <span class="font-medium">{{ filteredReservations.length }}</span> réservation(s) trouvée(s)
              </div>
            </div>
          </div>
        </div>

        <!-- Liste des réservations -->
        <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-[#1a202c] border-b border-gray-200 dark:border-gray-700">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Salle</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date/Heure</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Durée</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Montant</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statut</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="reservation in filteredReservations" :key="reservation.id" class="hover:bg-gray-50 dark:hover:bg-[#2d3748] transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                      <div class="text-sm font-medium text-gray-900 dark:text-white">{{ reservation.client_name || 'N/A' }}</div>
                      <div class="text-sm text-gray-500 dark:text-gray-400">{{ reservation.client_email || 'N/A' }}</div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-white">{{ reservation.venue_name || 'N/A' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-white">{{ formatDate(reservation.date_heure) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-white">{{ reservation.duree }} heure(s)</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ formatPrice(reservation.montant_total) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="`inline-flex px-2 py-1 text-xs font-medium rounded-full border ${getStatusColor(reservation.statut)}`">
                      {{ getStatusLabel(reservation.statut) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex gap-2">
                      <button 
                        @click="viewReservationDetails(reservation)"
                        class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300"
                        title="Voir les détails"
                      >
                        <i class="fas fa-eye"></i>
                      </button>
                      <button 
                        v-if="reservation.statut === 'en_attente'"
                        @click="acceptReservation(reservation)"
                        class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300"
                        title="Accepter"
                      >
                        <i class="fas fa-check"></i>
                      </button>
                      <button 
                        v-if="reservation.statut === 'en_attente'"
                        @click="rejectReservation(reservation)"
                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                        title="Refuser"
                      >
                        <i class="fas fa-times"></i>
                      </button>
                      <button 
                        v-if="reservation.has_conversation"
                        @click="goToConversation(reservation)"
                        class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300"
                        title="Discuter"
                      >
                        <i class="fas fa-comments"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Message si aucune réservation -->
          <div v-if="filteredReservations.length === 0" class="text-center py-12">
            <i class="fas fa-calendar-times text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <p class="text-gray-500 dark:text-gray-400 text-lg">Aucune réservation trouvée</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Les réservations apparaîtront ici une fois que les clients commenceront à faire des demandes</p>
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
          <div v-if="selectedReservation.statut === 'en_attente'" class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
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
    v-if="showNotificationModal"
    :type="notificationType"
    :title="notificationTitle"
    :message="notificationMessage"
    @close="showNotificationModal = false"
  />
</template>
