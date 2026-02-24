<script setup>
import { Head } from '@inertiajs/vue3';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
  clients: Array,
  stats: Object,
  filters: Object
});

const searchQuery = ref(props.filters?.search || '');
const dateFrom = ref(props.filters?.date_from || '');
const dateTo = ref(props.filters?.date_to || '');

const handleSearch = debounce(() => {
  updateFilters();
}, 300);

const handleDateFilter = debounce(() => {
  updateFilters();
}, 300);

const updateFilters = () => {
  const params = new URLSearchParams(window.location.search);
  
  if (searchQuery.value) {
    params.set('search', searchQuery.value);
  } else {
    params.delete('search');
  }
  
  if (dateFrom.value) {
    params.set('date_from', dateFrom.value);
  } else {
    params.delete('date_from');
  }
  
  if (dateTo.value) {
    params.set('date_to', dateTo.value);
  } else {
    params.delete('date_to');
  }
  
  router.get(`${window.location.pathname}?${params.toString()}`, {}, { preserveState: true });
};

const clearFilters = () => {
  searchQuery.value = '';
  dateFrom.value = '';
  dateTo.value = '';
  updateFilters();
};

const showNotificationModal = ref(false);
const selectedClient = ref(null);
const notificationForm = ref({
  title: '',
  message: '',
  type: 'info'
});

const sendNotificationToClient = (client) => {
  selectedClient.value = client;
  showNotificationModal.value = true;
};

const sendNotification = () => {
  if (!selectedClient.value) return;
  
  router.post(`/admin/clients/${selectedClient.value.id}/notify`, notificationForm.value, {
    onSuccess: () => {
      showNotificationModal.value = false;
      selectedClient.value = null;
      notificationForm.value = { title: '', message: '', type: 'info' };
    }
  });
};

const toggleClientStatus = (client) => {
  const newStatus = client.status === 'active' ? 'inactive' : 'active';
  router.patch(`/admin/clients/${client.id}/toggle-status`, 
    { status: newStatus }, 
    { preserveScroll: true }
  );
};
</script>

<template>
  <Head title="Clients" />
  
  <div class="flex flex-col gap-6">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
      <Link :href="route('welcome')" class="hover:text-primary transition-colors">Accueil</Link>
      <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
      <span class="text-slate-900 dark:text-white font-medium">Administration</span>
      <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
      <span class="text-slate-900 dark:text-white font-medium">Clients</span>
    </nav>

    <!-- Page Heading -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Gestion des Clients</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Gérez et supervisez tous les clients de la plateforme.</p>
      </div>
      <button class="bg-primary hover:bg-blue-600 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 font-medium shadow-lg shadow-primary/20 transition-all active:scale-95">
        <i class="fas fa-plus icon-sm"></i>
        <span>Ajouter un client</span>
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Clients</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ stats.total_clients }}</p>
          </div>
          <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
            <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Réservations</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ stats.total_reservations }}</p>
          </div>
          <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
            <i class="fas fa-calendar-check text-green-600 dark:text-green-400"></i>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Moyenne Réservations/Client</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ stats.avg_reservations_per_client?.toFixed(1) || '0.0' }}</p>
          </div>
          <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg">
            <i class="fas fa-chart-line text-purple-600 dark:text-purple-400"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 md:p-6">
      <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Filtres</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Recherche</label>
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder="Nom ou email..."
            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
          />
        </div>
        
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Date d'inscription (de)</label>
          <input
            v-model="dateFrom"
            @input="handleDateFilter"
            type="date"
            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
          />
        </div>
        
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Date d'inscription (à)</label>
          <input
            v-model="dateTo"
            @input="handleDateFilter"
            type="date"
            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
          />
        </div>
        
        <div class="flex items-end">
          <button
            @click="clearFilters"
            class="w-full px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors"
          >
            Effacer les filtres
          </button>
        </div>
      </div>
    </div>

    <!-- Clients List -->
    <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
      <div class="px-4 md:px-6 py-4 border-b border-slate-200 dark:border-slate-800">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Liste des Clients</h3>
      </div>
      
      <div class="divide-y divide-slate-200 dark:divide-slate-800">
        <template v-for="client in clients" :key="client.id">
          <div class="px-4 md:px-6 py-4">
            <!-- Mobile Card Layout -->
            <div class="flex flex-col gap-4 sm:hidden">
              <div class="flex items-center gap-3">
                <div class="bg-slate-200 dark:bg-slate-700 rounded-full size-10 md:size-12 flex items-center justify-center flex-shrink-0">
                  <i class="fas fa-user text-slate-600 dark:text-slate-400 text-sm md:text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <h4 class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ client.name }}</h4>
                  <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ client.email }}</p>
                </div>
              </div>
              
              <div class="flex flex-wrap items-center gap-2">
                <span 
                  :class="[
                    'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border',
                    client.status === 'active' 
                      ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-200 dark:border-green-800'
                      : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-200 dark:border-red-800'
                  ]"
                >
                  <span 
                    :class="[
                      'size-1.5 rounded-full',
                      client.status === 'active' ? 'bg-green-500' : 'bg-red-500'
                    ]"
                  ></span>
                  {{ client.status === 'active' ? 'Actif' : 'Inactif' }}
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400">{{ client.reservations_count || 0 }} réservation(s)</span>
                <span class="text-xs text-slate-400">Inscrit le {{ new Date(client.created_at).toLocaleDateString('fr-FR') }}</span>
              </div>
              
              <div class="flex items-center justify-between">
                <div class="text-xs text-slate-500 dark:text-slate-400">
                  Actions:
                </div>
                <div class="flex items-center gap-2">
                  <Link 
                    :href="route('admin.clients.show', {id: client.id})"
                    class="text-slate-400 hover:text-primary dark:hover:text-primary transition-colors p-2" 
                    title="Voir les détails"
                  >
                    <i class="fas fa-eye icon-sm"></i>
                  </Link>
                  <button 
                    @click="toggleClientStatus(client)"
                    :class="[
                      'transition-colors p-2',
                      client.status === 'active' 
                        ? 'text-amber-500 hover:text-amber-600 dark:hover:text-amber-400' 
                        : 'text-green-500 hover:text-green-600 dark:hover:text-green-400'
                    ]" 
                    :title="client.status === 'active' ? 'Désactiver' : 'Activer'"
                  >
                    <i :class="client.status === 'active' ? 'fas fa-pause' : 'fas fa-play'"></i>
                  </button>
                  <button 
                    @click="sendNotificationToClient(client)"
                    class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-500 transition-colors p-2" 
                    title="Envoyer une notification"
                  >
                    <i class="fas fa-bell icon-sm"></i>
                  </button>
                </div>
              </div>
            </div>
            
            <!-- Desktop Layout -->
            <div class="hidden sm:flex sm:items-center sm:justify-between gap-4">
              <div class="flex items-center gap-4">
                <div class="bg-slate-200 dark:bg-slate-700 rounded-full size-12 flex items-center justify-center flex-shrink-0">
                  <i class="fas fa-user text-slate-600 dark:text-slate-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <h4 class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ client.name }}</h4>
                  <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ client.email }}</p>
                  <div class="flex flex-wrap items-center gap-2 mt-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800">
                      <span class="size-1.5 rounded-full bg-green-500"></span>
                      Actif
                    </span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ client.reservations_count || 0 }} réservation(s)</span>
                    <span class="text-xs text-slate-400">Inscrit le {{ new Date(client.created_at).toLocaleDateString('fr-FR') }}</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <Link 
                  :href="route('admin.clients.show', {id: client.id})"
                  class="text-slate-400 hover:text-primary dark:hover:text-primary transition-colors p-2" 
                  title="Voir les détails"
                >
                  <i class="fas fa-eye icon-sm"></i>
                </Link>
                <button 
                  @click="toggleClientStatus(client)"
                  :class="[
                    'transition-colors p-2',
                    client.status === 'active' 
                      ? 'text-amber-500 hover:text-amber-600 dark:hover:text-amber-400' 
                      : 'text-green-500 hover:text-green-600 dark:hover:text-green-400'
                  ]" 
                  :title="client.status === 'active' ? 'Désactiver' : 'Activer'"
                >
                  <i :class="client.status === 'active' ? 'fas fa-pause' : 'fas fa-play'"></i>
                </button>
                <button 
                  @click="sendNotificationToClient(client)"
                  class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-500 transition-colors p-2" 
                  title="Envoyer une notification"
                >
                  <i class="fas fa-bell icon-sm"></i>
                </button>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>

    <!-- Notification Modal -->
    <div v-if="showNotificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">
          Envoyer une notification à {{ selectedClient?.name }}
        </h3>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Titre</label>
            <input
              v-model="notificationForm.title"
              type="text"
              placeholder="Titre de la notification"
              class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Message</label>
            <textarea
              v-model="notificationForm.message"
              placeholder="Message de la notification"
              rows="4"
              class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
            ></textarea>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Type</label>
            <select
              v-model="notificationForm.type"
              class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
            >
              <option value="info">Information</option>
              <option value="success">Succès</option>
              <option value="warning">Avertissement</option>
              <option value="error">Erreur</option>
            </select>
          </div>
        </div>
        
        <div class="flex items-center gap-3 mt-6">
          <button
            @click="sendNotification"
            class="flex-1 bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
          >
            Envoyer
          </button>
          <button
            @click="showNotificationModal = false; selectedClient = null"
            class="flex-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-4 py-2 rounded-lg font-medium hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors"
          >
            Annuler
          </button>
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
