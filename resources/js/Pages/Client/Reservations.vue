<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import NotificationModal from '../../Components/NotificationModal.vue';
import Navigation from '../../Components/Navigation.vue';
import AlertModal from '../../Components/AlertModal.vue';
import { useAlert } from '../../Composables/useAlert.js';

const props = defineProps({
  reservations: [Array, Object],
  user: Object
});

const reservationsArray = computed(() => {
  if (Array.isArray(props.reservations)) {
    return props.reservations;
  }
  if (props.reservations && Array.isArray(props.reservations.data)) {
    return props.reservations.data;
  }
  return [];
});

// Alert composable
const { alertState, showSuccess, showError, showConfirm } = useAlert();

// Fonction de déconnexion
const logout = async () => {
  const confirmed = await showConfirm(
    'Déconnexion',
    'Êtes-vous sûr de vouloir vous déconnecter ?'
  );
  
  if (confirmed) {
    router.post('/logout');
  }
};

// États pour les modaux
const showNotificationModal = ref(false);
const notificationType = ref('success');
const notificationTitle = ref('');
const notificationMessage = ref('');

// États pour les filtres
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

// Formater la date courte
const formatDateShort = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
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
  let filtered = reservationsArray.value;

  // Filtrer par statut
  if (selectedStatus.value !== 'all') {
    filtered = filtered.filter(r => r.statut === selectedStatus.value);
  }

  // Filtrer par recherche
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(r => 
      r.venue_name?.toLowerCase().includes(query) ||
      r.message?.toLowerCase().includes(query)
    );
  }

  return filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

// Obtenir la couleur du statut
const getStatusColor = (status) => {
  const colors = {
    'en_attente': 'text-yellow-600 bg-yellow-50 border-yellow-100',
    'confirmee': 'text-green-600 bg-green-50 border-green-100',
    'annulee': 'text-red-600 bg-red-50 border-red-100',
    'terminee': 'text-gray-600 bg-gray-50 border-gray-100',
    'refusee': 'text-red-700 bg-red-50 border-red-100'
  };
  return colors[status] || 'text-gray-600 bg-gray-50 border-gray-100';
};

// Obtenir le libellé du statut
const getStatusLabel = (status) => {
  const labels = {
    'en_attente': 'En attente',
    'confirmee': 'Confirmée',
    'annulee': 'Annulée',
    'terminee': 'Terminée',
    'refusee': 'Refusée'
  };
  return labels[status] || status;
};

// Obtenir l'icône du statut
const getStatusIcon = (status) => {
  const icons = {
    'en_attente': 'fas fa-clock',
    'confirmee': 'fas fa-check-circle',
    'annulee': 'fas fa-times-circle',
    'terminee': 'fas fa-check-double',
    'refusee': 'fas fa-times-circle'
  };
  return icons[status] || 'fas fa-question-circle';
};

// Annuler une réservation
const cancelReservation = async (reservation) => {
  const confirmed = await showConfirm(
    'Annulation de réservation',
    'Êtes-vous sûr de vouloir annuler cette réservation ?'
  );
  
  if (!confirmed) return;

  try {
    await router.patch(`/client/reservations/${reservation.id}/cancel`, {}, {
      onSuccess: () => {
        showSuccess('Réservation annulée', 'Votre réservation a été annulée avec succès.');
      },
      onError: () => {
        showError('Erreur', 'Une erreur est survenue lors de l\'annulation de la réservation.');
      }
    });
  } catch (error) {
    showError('Erreur', 'Une erreur est survenue lors de l\'annulation de la réservation.');
  }
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

// Calculer les statistiques
const stats = computed(() => {
  const reservations = reservationsArray.value;
  return {
    total: reservations.length,
    en_attente: reservations.filter(r => r.statut === 'en_attente').length,
    confirmee: reservations.filter(r => r.statut === 'confirmee').length,
    annulee: reservations.filter(r => r.statut === 'annulee').length,
    terminee: reservations.filter(r => r.statut === 'terminee').length
  };
});
</script>

<template>
  <Head title="Mes Réservations" />
  
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden bg-background-light font-display">
    <!-- Navigation Component -->
    <Navigation :user="$page.props.auth?.user" current-page="reservations" />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Page Header -->
      <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-900">Mes Réservations</h1>
        <p class="text-gray-600 mt-2">Suivez l'état de vos demandes de réservation</p>
      </div>

      <!-- Statistiques -->
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mr-3">
              <i class="fas fa-calendar text-blue-600"></i>
            </div>
            <div>
              <p class="text-2xl font-bold text-gray-900">{{ stats.total }}</p>
              <p class="text-sm text-gray-500">Total</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center mr-3">
              <i class="fas fa-clock text-yellow-600"></i>
            </div>
            <div>
              <p class="text-2xl font-bold text-gray-900">{{ stats.en_attente }}</p>
              <p class="text-sm text-gray-500">En attente</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center mr-3">
              <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div>
              <p class="text-2xl font-bold text-gray-900">{{ stats.confirmee }}</p>
              <p class="text-sm text-gray-500">Confirmées</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center mr-3">
              <i class="fas fa-times-circle text-red-600"></i>
            </div>
            <div>
              <p class="text-2xl font-bold text-gray-900">{{ stats.annulee }}</p>
              <p class="text-sm text-gray-500">Annulées</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center mr-3">
              <i class="fas fa-check-double text-gray-600"></i>
            </div>
            <div>
              <p class="text-2xl font-bold text-gray-900">{{ stats.terminee }}</p>
              <p class="text-sm text-gray-500">Terminées</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtres -->
      <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div class="flex flex-wrap gap-2">
            <button
              v-for="status in ['all', 'en_attente', 'confirmee', 'annulee', 'terminee']"
              :key="status"
              @click="selectedStatus = status"
              :class="`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                selectedStatus === status
                  ? 'bg-blue-600 text-white'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              }`"
            >
              {{ status === 'all' ? 'Toutes' : getStatusLabel(status) }}
            </button>
          </div>

          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Rechercher une réservation..."
              class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full md:w-64"
            />
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
        </div>
      </div>

      <!-- Liste des réservations -->
      <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div v-if="filteredReservations.length > 0">
          <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Salle
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Durée
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Montant
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Statut
                  </th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="reservation in filteredReservations" :key="reservation.id" class="hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-building text-gray-600"></i>
                      </div>
                      <div>
                        <div class="font-medium text-gray-900">{{ reservation.venue_name || 'N/A' }}</div>
                        <div class="text-sm text-gray-500">Créée le {{ formatDateShort(reservation.created_at) }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ formatDate(reservation.date_heure) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-50 text-blue-700">
                      {{ reservation.duree }} heure(s)
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="font-medium text-gray-900">{{ formatPrice(reservation.montant_total) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="`inline-flex items-center px-3 py-1 rounded-full text-sm ${getStatusColor(reservation.statut)} border`">
                      <i :class="`${getStatusIcon(reservation.statut)} mr-1.5`"></i>
                      {{ getStatusLabel(reservation.statut) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                      <button 
                        @click="viewReservationDetails(reservation)"
                        class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-50 rounded"
                      >
                        <i class="fas fa-eye"></i>
                      </button>
                      <button 
                        v-if="reservation.statut === 'confirmee'"
                        @click="cancelReservation(reservation)"
                        class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded"
                      >
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="space-y-4 p-4 md:hidden">
            <div v-for="reservation in filteredReservations" :key="reservation.id" class="border border-gray-200 rounded-2xl bg-white p-4 shadow-sm">
              <div class="flex items-start justify-between gap-4">
                <div>
                  <p class="text-sm text-gray-500">Salle</p>
                  <p class="text-base font-semibold text-gray-900">{{ reservation.venue_name || 'N/A' }}</p>
                </div>
                <span :class="`inline-flex items-center px-3 py-1 rounded-full text-sm ${getStatusColor(reservation.statut)} border`">
                  {{ getStatusLabel(reservation.statut) }}
                </span>
              </div>
              <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-gray-600">
                <div>
                  <p class="font-medium text-gray-900">Date</p>
                  <p>{{ formatDate(reservation.date_heure) }}</p>
                </div>
                <div>
                  <p class="font-medium text-gray-900">Durée</p>
                  <p>{{ reservation.duree }} heure(s)</p>
                </div>
                <div>
                  <p class="font-medium text-gray-900">Montant</p>
                  <p>{{ formatPrice(reservation.montant_total) }}</p>
                </div>
                <div>
                  <p class="font-medium text-gray-900">Créée</p>
                  <p>{{ formatDateShort(reservation.created_at) }}</p>
                </div>
              </div>
              <div class="mt-4 flex flex-wrap gap-2">
                <button 
                  @click="viewReservationDetails(reservation)"
                  class="flex-1 min-w-[120px] justify-center inline-flex items-center px-3 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors"
                >
                  <i class="fas fa-eye mr-2"></i>
                  Détails
                </button>
                <button 
                  v-if="reservation.statut === 'confirmee'"
                  @click="cancelReservation(reservation)"
                  class="flex-1 min-w-[120px] justify-center inline-flex items-center px-3 py-2 rounded-lg bg-red-50 text-red-600 text-sm font-medium hover:bg-red-100 transition-colors"
                >
                  <i class="fas fa-times mr-2"></i>
                  Annuler
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Message si aucune réservation -->
        <div v-else class="text-center py-16">
          <div class="mb-6">
            <i class="fas fa-calendar-times text-6xl text-gray-300"></i>
          </div>
          <h3 class="text-xl font-semibold text-gray-700 mb-3">Aucune réservation trouvée</h3>
          <p class="text-gray-600 max-w-md mx-auto mb-6">
            <span v-if="selectedStatus === 'all'">
              Vous n'avez pas encore fait de demande de réservation.
            </span>
            <span v-else>
              Aucune réservation avec ce statut.
            </span>
          </p>
          <div class="space-x-4">
            <Link 
              v-if="selectedStatus === 'all'"
              href="/salles" 
              class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              <i class="fas fa-search mr-2"></i>
              Découvrir nos salles
            </Link>
            <button 
              v-else
              @click="selectedStatus = 'all'"
              class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
            >
              Voir toutes les réservations
            </button>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal de détails de réservation -->
    <div 
      v-if="showDetailsModal && selectedReservation" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    >
      <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex justify-between items-center mb-6">
            <div>
              <h3 class="text-xl font-bold text-gray-900">Détails de la réservation</h3>
              <p class="text-sm text-gray-500 mt-1">ID: #{{ selectedReservation.id }}</p>
            </div>
            <button 
              @click="closeDetailsModal" 
              class="text-gray-400 hover:text-gray-600 p-2"
            >
              <i class="fas fa-times text-lg"></i>
            </button>
          </div>
          
          <div class="space-y-6">
            <!-- En-tête -->
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="flex items-start justify-between">
                <div class="flex items-start space-x-3">
                  <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-gray-600 text-xl"></i>
                  </div>
                  <div>
                    <h4 class="font-medium text-gray-900">{{ selectedReservation.venue_name || 'N/A' }}</h4>
                    <p class="text-sm text-gray-500">Réservation créée le {{ formatDate(selectedReservation.created_at) }}</p>
                  </div>
                </div>
                <span :class="`inline-flex items-center px-3 py-1 rounded-full text-sm ${getStatusColor(selectedReservation.statut)} border`">
                  <i :class="`${getStatusIcon(selectedReservation.statut)} mr-1.5`"></i>
                  {{ getStatusLabel(selectedReservation.statut) }}
                </span>
              </div>
            </div>

            <!-- Informations principales -->
            <div class="grid grid-cols-2 gap-6">
              <div class="space-y-1">
                <p class="text-sm text-gray-500">Date et heure</p>
                <p class="font-medium">{{ formatDate(selectedReservation.date_heure) }}</p>
              </div>
              <div class="space-y-1">
                <p class="text-sm text-gray-500">Durée</p>
                <p class="font-medium">{{ selectedReservation.duree }} heure(s)</p>
              </div>
              <div class="space-y-1">
                <p class="text-sm text-gray-500">Nombre de personnes</p>
                <p class="font-medium">{{ selectedReservation.nombre_personnes || 'N/A' }}</p>
              </div>
              <div class="space-y-1">
                <p class="text-sm text-gray-500">Type d'événement</p>
                <p class="font-medium">{{ selectedReservation.type_evenement || 'N/A' }}</p>
              </div>
            </div>

            <!-- Montant -->
            <div class="border-t border-gray-200 pt-6">
              <div class="flex justify-between items-center">
                <p class="text-sm text-gray-500">Montant total</p>
                <p class="text-2xl font-bold text-gray-900">{{ formatPrice(selectedReservation.montant_total) }}</p>
              </div>
            </div>

            <!-- Informations supplémentaires -->
            <div v-if="selectedReservation.message || selectedReservation.besoins_speciaux" class="space-y-4">
              <h4 class="font-medium text-gray-900">Informations supplémentaires</h4>
              <div v-if="selectedReservation.message" class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-2">Message</p>
                <p class="text-gray-900">{{ selectedReservation.message }}</p>
              </div>
              <div v-if="selectedReservation.besoins_speciaux" class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-2">Besoins spéciaux</p>
                <p class="text-gray-900">{{ selectedReservation.besoins_speciaux }}</p>
              </div>
            </div>

            <!-- Actions -->
            <div v-if="selectedReservation.statut === 'confirmee'" class="border-t border-gray-200 pt-6">
              <button 
                @click="cancelReservation(selectedReservation)"
                class="w-full bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-lg py-3 px-4 transition-colors"
              >
                <i class="fas fa-times mr-2"></i>
                Annuler la réservation
              </button>
            </div>
            <div v-else-if="selectedReservation.statut === 'en_attente'" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
              <div class="flex items-start">
                <i class="fas fa-info-circle text-yellow-600 mt-0.5 mr-3"></i>
                <p class="text-sm text-yellow-800">
                  Votre réservation est en attente de validation par le propriétaire de la salle.
                </p>
              </div>
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

    <!-- Alert Modal -->
    <AlertModal
      :show="alertState.show"
      :type="alertState.type"
      :title="alertState.title"
      :message="alertState.message"
      :confirm-text="alertState.confirmText"
      :cancel-text="alertState.cancelText"
      :show-cancel="alertState.showCancel"
      @close="alertState.show = false"
      @confirm="alertState.resolve"
    />
</div>
</template>

<style scoped>
/* Smooth transitions */
* {
  transition-property: background-color, border-color, color, fill, stroke;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* Custom scrollbar for modal */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
