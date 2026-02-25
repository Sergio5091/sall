<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { debounce } from 'lodash'
import ConfirmModal from '../../Components/ConfirmModal.vue'
import NotificationModal from '../../Components/NotificationModal.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  events: Object,
  filters: Object,
  stats: Object
})

const searchQuery = ref(props.filters?.search || '')
const selectedStatus = ref(props.filters?.status || '')

// États pour les modaux
const showConfirmModal = ref(false)
const confirmTitle = ref('')
const confirmMessage = ref('')
const confirmAction = ref(null)
const confirmData = ref(null)

const showNotificationModal = ref(false)
const notificationType = ref('success')
const notificationTitle = ref('')
const notificationMessage = ref('')

// Recherche avec debounce
const search = debounce(() => {
  router.get(route('admin.events.index'), {
    search: searchQuery.value,
    status: selectedStatus.value
  }, {
    preserveState: true,
    replace: true
  })
}, 300)

// Watchers
watch(searchQuery, search)
watch(selectedStatus, search)

// Fonctions utilitaires
const getStatusClass = (status) => {
  switch(status) {
    case 'active':
    case 'actif':
      return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
    case 'inactive':
    case 'inactif':
      return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
    case 'pending':
    case 'en_attente':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'
    case 'cancelled':
    case 'annulé':
      return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400'
    default:
      return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400'
  }
}

const getStatusText = (status) => {
  switch(status) {
    case 'active':
    case 'actif':
      return 'Actif'
    case 'inactive':
    case 'inactif':
      return 'Inactif'
    case 'pending':
    case 'en_attente':
      return 'En attente'
    case 'cancelled':
    case 'annulé':
      return 'Annulé'
    default:
      return status
  }
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Actions
const toggleEventStatus = (event) => {
  confirmTitle.value = 'Changer le statut'
  confirmMessage.value = `Êtes-vous sûr de vouloir ${event.status === 'active' ? 'désactiver' : 'activer'} cet événement ?`
  confirmAction.value = () => {
    router.patch(route('admin.events.toggle-status', {id: event.id}), {}, {
      onSuccess: () => {
        showNotification('success', 'Succès', 'Statut de l\'événement mis à jour avec succès')
      },
      onError: () => {
        showNotification('error', 'Erreur', 'Une erreur est survenue lors de la mise à jour du statut')
      }
    })
  }
  showConfirmModal.value = true
}

const deleteEvent = (event) => {
  confirmTitle.value = 'Supprimer l\'événement'
  confirmMessage.value = `Êtes-vous sûr de vouloir supprimer l'événement "${event.title}" ? Cette action est irréversible.`
  confirmAction.value = () => {
    router.delete(route('admin.events.destroy', {id: event.id}), {
      onSuccess: () => {
        showNotification('success', 'Succès', 'Événement supprimé avec succès')
      },
      onError: () => {
        showNotification('error', 'Erreur', 'Une erreur est survenue lors de la suppression')
      }
    })
  }
  showConfirmModal.value = true
}

const showNotification = (type, title, message) => {
  notificationType.value = type
  notificationTitle.value = title
  notificationMessage.value = message
  showNotificationModal.value = true
}

const closeConfirmModal = () => {
  showConfirmModal.value = false
  confirmTitle.value = ''
  confirmMessage.value = ''
  confirmAction.value = null
  confirmData.value = null
}

const closeNotificationModal = () => {
  showNotificationModal.value = false
}
</script>

<template>
  <Head title="Événements" />

  <!-- Header -->
  <div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Événements</h1>
    <p class="text-slate-600 dark:text-slate-400 mt-2">Gérez tous les événements de la plateforme</p>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total</p>
          <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats?.total || 0 }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
          <i class="fas fa-calendar"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Actifs</p>
          <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats?.active || 0 }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 flex items-center justify-center">
          <i class="fas fa-check-circle"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400">En attente</p>
          <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats?.pending || 0 }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 flex items-center justify-center">
          <i class="fas fa-clock"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Annulés</p>
          <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats?.cancelled || 0 }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 flex items-center justify-center">
          <i class="fas fa-times-circle"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Filters -->
  <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Rechercher</label>
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Rechercher par titre, lieu..."
          class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white"
        >
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Statut</label>
        <select
          v-model="selectedStatus"
          class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white"
        >
          <option value="">Tous les statuts</option>
          <option value="active">Actif</option>
          <option value="pending">En attente</option>
          <option value="inactive">Inactif</option>
          <option value="cancelled">Annulé</option>
        </select>
      </div>

      <div class="flex items-end">
        <Link
          :href="route('admin.events.index')"
          class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors"
        >
          Réinitialiser
        </Link>
      </div>
    </div>
  </div>

  <!-- Events Table -->
  <div class="bg-white dark:bg-slate-850 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
              Événement
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
              Organisateur
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
              Date
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
              Lieu
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
              Statut
            </th>
            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
          <tr v-for="event in events.data" :key="event.id" class="hover:bg-slate-50 dark:hover:bg-slate-800">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <img v-if="event.image" :src="`/storage/${event.image}`" class="h-10 w-10 rounded-full object-cover" alt="">
                  <div v-else class="h-10 w-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                    <i class="fas fa-calendar text-slate-500 dark:text-slate-400"></i>
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-slate-900 dark:text-white">
                    {{ event.title }}
                  </div>
                  <div class="text-sm text-slate-500 dark:text-slate-400">
                    {{ event.description?.substring(0, 50) }}...
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-slate-900 dark:text-white">
                {{ event.promoter?.name || 'N/A' }}
              </div>
              <div class="text-sm text-slate-500 dark:text-slate-400">
                {{ event.promoter?.email || '' }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-slate-900 dark:text-white">
                {{ formatDate(event.date_debut) }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-slate-900 dark:text-white">
                {{ event.location || 'N/A' }}
              </div>
              <div class="text-sm text-slate-500 dark:text-slate-400">
                {{ event.city || '' }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(event.status)" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                {{ getStatusText(event.status) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex items-center justify-end space-x-2">
                <Link
                  :href="route('admin.events.show', {id: event.id})"
                  class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 p-2"
                  title="Voir les détails"
                >
                  <i class="fas fa-eye"></i>
                </Link>
                <button
                  @click="toggleEventStatus(event)"
                  class="text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 p-2"
                  :title="event.status === 'active' ? 'Désactiver' : 'Activer'"
                >
                  <i class="fas" :class="event.status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off'"></i>
                </button>
                <button
                  @click="deleteEvent(event)"
                  class="text-slate-400 hover:text-red-600 dark:hover:text-red-400 p-2"
                  title="Supprimer"
                >
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="events.links" class="bg-slate-50 dark:bg-slate-800 px-6 py-3 border-t border-slate-200 dark:border-slate-700">
      <div class="flex items-center justify-between">
        <div class="text-sm text-slate-700 dark:text-slate-300">
          Affichage de {{ events.from || 0 }} à {{ events.to || 0 }} sur {{ events.total || 0 }} résultats
        </div>
        <div class="flex space-x-2">
          <Link
            v-if="events.prev_page_url"
            :href="events.prev_page_url"
            class="px-3 py-1 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-md text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-600"
          >
            Précédent
          </Link>
          <Link
            v-if="events.next_page_url"
            :href="events.next_page_url"
            class="px-3 py-1 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-md text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-600"
          >
            Suivant
          </Link>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals -->
  <ConfirmModal
    :show="showConfirmModal"
    :title="confirmTitle"
    :message="confirmMessage"
    @confirm="confirmAction"
    @cancel="closeConfirmModal"
  />

  <NotificationModal
    :show="showNotificationModal"
    :type="notificationType"
    :title="notificationTitle"
    :message="notificationMessage"
    @close="closeNotificationModal"
  />
</template>
