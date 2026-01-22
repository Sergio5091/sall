<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { debounce } from 'lodash';
import SubAdminLayout from '@/Layouts/SubAdminLayout.vue';
import NotificationModal from '../../../Components/NotificationModal.vue';

defineOptions({ layout: SubAdminLayout });

const props = defineProps({
    salles: Array,
    stats: Object
});

const searchQuery = ref('');
const statusFilter = ref('');

// Notification system
const showNotificationModal = ref(false);
const notificationType = ref('success');
const notificationTitle = ref('');
const notificationMessage = ref('');

// Confirmation modal system
const showConfirmModal = ref(false);
const confirmAction = ref(null);
const confirmTitle = ref('');
const confirmMessage = ref('');
const confirmButtonText = ref('Confirmer');
const confirmButtonClass = ref('bg-blue-600 hover:bg-blue-700');

const showNotification = (type, title, message) => {
  notificationType.value = type;
  notificationTitle.value = title;
  notificationMessage.value = message;
  showNotificationModal.value = true;
};

const showConfirm = (title, message, action, buttonText = 'Confirmer', buttonClass = 'bg-blue-600 hover:bg-blue-700') => {
  confirmTitle.value = title;
  confirmMessage.value = message;
  confirmAction.value = action;
  confirmButtonText.value = buttonText;
  confirmButtonClass.value = buttonClass;
  showConfirmModal.value = true;
};

const executeConfirmAction = () => {
  if (confirmAction.value) {
    confirmAction.value();
  }
  closeConfirmModal();
};

const closeConfirmModal = () => {
  showConfirmModal.value = false;
  confirmAction.value = null;
  confirmTitle.value = '';
  confirmMessage.value = '';
  confirmButtonText.value = 'Confirmer';
  confirmButtonClass.value = 'bg-blue-600 hover:bg-blue-700';
};

const filteredSalles = computed(() => {
    let filtered = props.salles;
    
    if (searchQuery.value) {
        filtered = filtered.filter(salle => 
            salle.nom.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            salle.ville?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            salle.pays?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            salle.promoter?.name?.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    
    if (statusFilter.value) {
        filtered = filtered.filter(salle => salle.statut === statusFilter.value);
    }
    
    return filtered;
});

const handleSearch = debounce(() => {
    // Search logic already handled by computed
}, 300);

const getStatusClass = (status) => {
    switch(status) {
        case 'actif':
        case 'active':
            return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'inactif':
        case 'inactive':
            return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
        case 'maintenance':
            return 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
    }
};

const getValidationClass = (valide) => {
    return valide ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
};

const getStatusText = (status) => {
    switch(status) {
        case 'actif':
        case 'active':
            return 'Actif';
        case 'inactif':
        case 'inactive':
            return 'Inactif';
        case 'maintenance':
            return 'Maintenance';
        default:
            return status;
    }
};

const getValidationText = (valide) => {
    return valide ? 'Validée' : 'En attente';
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR');
};

const validateSalle = (salle) => {
  showConfirm(
    'Valider le centre',
    `Êtes-vous sûr de vouloir valider le centre "${salle.nom}" ? Cette action rendra le centre visible et activable.`,
    () => {
      router.post(route('admin.sub-admin.salles.validate', salle.id), {}, {
        onSuccess: () => {
          showNotification('success', 'Succès', 'Salle validée avec succès !');
        },
        onError: () => {
          showNotification('error', 'Erreur', 'Une erreur est survenue lors de la validation.');
        }
      });
    },
    'Valider',
    'bg-green-600 hover:bg-green-700'
  );
};

const deactivateSalle = (salle) => {
  showConfirm(
    'Désactiver le centre',
    `Êtes-vous sûr de vouloir désactiver le centre "${salle.nom}" ? Cette action le rendra temporairement indisponible.`,
    () => {
      router.post(route('admin.sub-admin.salles.deactivate', salle.id), {}, {
        onSuccess: () => {
          showNotification('success', 'Succès', 'Salle désactivée avec succès !');
        },
        onError: () => {
          showNotification('error', 'Erreur', 'Une erreur est survenue lors de la désactivation.');
        }
      });
    },
    'Désactiver',
    'bg-red-600 hover:bg-red-700'
  );
};

const toggleSalleStatus = (salle) => {
  // Empêcher l'activation/désactivation si la salle n'est pas validée
  if (!salle.valide) {
    showNotification('error', 'Action non autorisée', 'Cette salle doit d\'abord être validée avant de pouvoir être activée ou désactivée.');
    return;
  }
  
  const action = salle.statut === 'actif' ? 'désactiver' : 'activer';
  const actionText = salle.statut === 'actif' ? 'désactiver' : 'activer';
  const buttonClass = salle.statut === 'actif' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700';
  
  showConfirm(
    `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} le centre`,
    `Êtes-vous sûr de vouloir ${actionText} le centre "${salle.nom}" ?`,
    () => {
      router.post(route('admin.sub-admin.salles.toggle-status', salle.id), {}, {
        onSuccess: () => {
          showNotification('success', 'Succès', `Centre ${actionText} avec succès !`);
        },
        onError: () => {
          showNotification('error', 'Erreur', `Une erreur est survenue lors de la ${action}.`);
        }
      });
    },
    actionText.charAt(0).toUpperCase() + actionText.slice(1),
    buttonClass
  );
};

const viewSalle = (salle) => {
  window.location.href = route('admin.sub-admin.salles.show', salle.id);
};

const sendNotification = (salle) => {
  showConfirm(
    'Envoyer une notification',
    `Êtes-vous sûr de vouloir envoyer une notification au promoteur du centre "${salle.nom}" ?`,
    () => {
      // Simuler l'envoi de notification - à remplacer avec l'appel API réel
      showNotification('success', 'Notification envoyée', `Une notification a été envoyée au promoteur du centre "${salle.nom}".`);
    },
    'Envoyer',
    'bg-blue-600 hover:bg-blue-700'
  );
};
</script>

<template>
  <div>
    <Head title="Gestion des Centres" />
    
    <div class="space-y-6">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
          <!-- Breadcrumbs -->
          <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
            <Link :href="route('admin.sub-admins.dashboard')" class="hover:text-blue-600 transition-colors">Tableau de bord</Link>
            <i class="fas fa-chevron-right icon-sm text-gray-300"></i>
            <span class="text-gray-900 dark:text-white font-medium">Centres</span>
          </nav>

          <!-- Page Heading -->
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Gestion des centres</h2>
              <p class="text-gray-500 dark:text-gray-400 mt-1">Validez et gérez les centres de loisirs de votre région.</p>
            </div>
          </div>

          <!-- Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total centres</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats?.total || 0 }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                  <i class="fas fa-store text-blue-600"></i>
                </div>
              </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Actifs</p>
                  <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ stats?.active || 0 }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                  <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
              </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600 dark:text-gray-400">En attente</p>
                  <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ stats?.pending || 0 }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-lg">
                  <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                </div>
              </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Inactifs</p>
                  <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ stats?.inactive || 0 }}</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-lg">
                  <i class="fas fa-ban text-red-600 dark:text-red-400"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Filters & Controls -->
          <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between mt-6">
            <!-- Search Field -->
            <div class="relative w-full md:max-w-md">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 icon-sm"></i>
              </div>
              <input
                v-model="searchQuery"
                @input="handleSearch"
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-700 rounded-lg leading-5 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm transition-shadow"
                placeholder="Rechercher par nom, ville ou promoteur..."
                type="text"
              />
            </div>
            
            <!-- Filter Actions -->
            <div class="flex items-center gap-3 w-full md:w-auto">
              <div class="relative w-full md:w-48">
                <select
                  v-model="statusFilter"
                  @change="handleSearch"
                  class="block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-blue-600 focus:border-blue-600 sm:text-sm rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white appearance-none cursor-pointer"
                >
                  <option value="">Tous les statuts</option>
                  <option value="actif">Actifs</option>
                  <option value="inactif">Inactifs</option>
                  <option value="maintenance">Maintenance</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                  <i class="fas fa-chevron-down icon-sm"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
              <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider" scope="col">ID</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider" scope="col">Centre</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell" scope="col">Promoteur</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell" scope="col">Localisation</th>
                  <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider" scope="col">Capacité</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider" scope="col">Statut</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider" scope="col">Validation</th>
                  <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider" scope="col">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-800">
                <tr v-for="salle in filteredSalles" :key="salle.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">#SL-{{ salle.id }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-10 w-10 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg bg-cover bg-center flex items-center justify-center">
                        <i class="fas fa-store text-gray-400"></i>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors cursor-pointer">{{ salle.nom }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ salle.type || 'Centre de loisirs' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden sm:table-cell">
                    <div class="flex items-center gap-2">
                      <div class="size-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        <i class="fas fa-user text-xs text-gray-600 dark:text-gray-400"></i>
                      </div>
                      {{ salle.promoter?.name || 'N/A' }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell">
                    <div class="flex items-center gap-1">
                      <i class="fas fa-map-marker-alt text-xs"></i>
                      {{ salle.ville }}, {{ salle.pays }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">
                    {{ salle.capacite_max || salle.capacite || 'N/A' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusClass(salle.statut)" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ getStatusText(salle.statut) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getValidationClass(salle.valide)" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ getValidationText(salle.valide) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end gap-2">
                      <!-- Bouton de validation pour les salles en attente -->
                      <button v-if="!salle.valide" @click="validateSalle(salle)" class="text-yellow-600 hover:text-yellow-700 dark:hover:text-yellow-500 transition-colors p-1" title="Valider la salle">
                        <i class="fas fa-check-circle icon-sm"></i>
                      </button>
                      
                      <!-- Bouton unique d'activation/désactivation pour les salles validées -->
                      <button v-else-if="salle.statut === 'actif'" @click="toggleSalleStatus(salle)" class="text-gray-400 hover:text-red-600 dark:hover:text-red-500 transition-colors p-1" title="Désactiver">
                        <i class="fas fa-ban icon-sm"></i>
                      </button>
                      
                      <button v-else @click="toggleSalleStatus(salle)" class="text-gray-400 hover:text-green-600 dark:hover:text-green-500 transition-colors p-1" title="Activer">
                        <i class="fas fa-check icon-sm"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Empty State -->
          <div v-if="filteredSalles.length === 0" class="text-center py-12">
            <div class="bg-gray-100 dark:bg-gray-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-store text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun centre trouvé</h3>
            <p class="text-gray-500 dark:text-gray-400">
              {{ searchQuery ? 'Aucun centre ne correspond à votre recherche' : 'Aucun centre dans votre région' }}
            </p>
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

      <!-- Confirmation Modal -->
      <div v-if="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <!-- Background overlay -->
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeConfirmModal"></div>

          <!-- Center modal -->
          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

          <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 sm:mx-0 sm:h-10 sm:w-10">
                <i class="fas fa-exclamation-triangle text-blue-600 dark:text-blue-400"></i>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                  {{ confirmTitle }}
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ confirmMessage }}
                  </p>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
              <button
                type="button"
                @click="executeConfirmAction"
                :class="confirmButtonClass"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
              >
                {{ confirmButtonText }}
              </button>
              <button
                type="button"
                @click="closeConfirmModal"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<style scoped>
.icon-sm {
  font-size: 20px;
}
</style>
