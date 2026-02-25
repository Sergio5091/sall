<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
  promoteur: Object,
  stats: Object
});

const showNotificationModal = ref(false);
const notificationForm = ref({
  title: '',
  message: '',
  type: 'info'
});

const sendNotification = () => {
  router.post(`/admin/promoteurs/${props.promoteur.id}/notify`, notificationForm.value, {
    onSuccess: () => {
      showNotificationModal.value = false;
      notificationForm.value = { title: '', message: '', type: 'info' };
    }
  });
};

const toggleStatus = () => {
  const newStatus = props.promoteur.status === 'active' ? 'inactive' : 'active';
  router.patch(`/admin/promoteurs/${props.promoteur.id}/toggle-status`, 
    { status: newStatus }, 
    { preserveScroll: true }
  );
};

const formatNumber = (num) => {
  return new Intl.NumberFormat('fr-FR').format(num);
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};
</script>

<template>
  <Head :title="`Détails du promoteur - ${promoteur.name}`" />
  
  <div class="flex flex-col gap-6">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
      <Link :href="route('admin.promoteurs.index')" class="hover:text-primary transition-colors">Promoteurs</Link>
      <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
      <span class="text-slate-900 dark:text-white font-medium">{{ promoteur.name }}</span>
    </nav>

    <!-- Header Section -->
    <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="bg-slate-200 dark:bg-slate-700 rounded-full size-16 flex items-center justify-center">
            <i class="fas fa-user text-slate-600 dark:text-slate-400 text-xl"></i>
          </div>
          <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ promoteur.name }}</h2>
            <p class="text-slate-500 dark:text-slate-400">{{ promoteur.email }}</p>
            <div class="flex items-center gap-2 mt-1">
              <span 
                :class="[
                  'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border',
                  promoteur.status === 'active' 
                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-200 dark:border-green-800'
                    : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-200 dark:border-red-800'
                ]"
              >
                <span 
                  :class="[
                    'size-1.5 rounded-full',
                    promoteur.status === 'active' ? 'bg-green-500' : 'bg-red-500'
                  ]"
                ></span>
                {{ promoteur.status === 'active' ? 'Actif' : 'Inactif' }}
              </span>
              <span class="text-xs text-slate-400">Inscrit le {{ formatDate(promoteur.created_at) }}</span>
            </div>
          </div>
        </div>
        
        <div class="flex items-center gap-2">
          <button
            @click="toggleStatus"
            :class="[
              'px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2',
              promoteur.status === 'active'
                ? 'bg-amber-500 hover:bg-amber-600 text-white'
                : 'bg-green-500 hover:bg-green-600 text-white'
            ]"
          >
            <i :class="promoteur.status === 'active' ? 'fas fa-pause' : 'fas fa-play'"></i>
            {{ promoteur.status === 'active' ? 'Désactiver' : 'Activer' }}
          </button>
          
          <button
            @click="showNotificationModal = true"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2"
          >
            <i class="fas fa-bell"></i>
            Envoyer une notification
          </button>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Salles</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ formatNumber(stats.total_salles) }}</p>
          </div>
          <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
            <i class="fas fa-store text-blue-600 dark:text-blue-400"></i>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Salles Actives</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ formatNumber(stats.active_salles) }}</p>
          </div>
          <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
            <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Salles Inactives</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ formatNumber(stats.inactive_salles) }}</p>
          </div>
          <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-lg">
            <i class="fas fa-times-circle text-red-600 dark:text-red-400"></i>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">En Maintenance</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ formatNumber(stats.maintenance_salles) }}</p>
          </div>
          <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-lg">
            <i class="fas fa-tools text-amber-600 dark:text-amber-400"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Salles List -->
    <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Salles du promoteur</h3>
      </div>
      
      <div class="divide-y divide-slate-200 dark:divide-slate-800">
        <template v-if="promoteur.salles && promoteur.salles.length > 0">
          <div v-for="salle in promoteur.salles" :key="salle.id" class="px-6 py-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-4">
                <div class="bg-slate-200 dark:bg-slate-700 rounded-lg size-12 flex items-center justify-center">
                  <i class="fas fa-store text-slate-600 dark:text-slate-400"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-slate-900 dark:text-white">{{ salle.nom }}</h4>
                  <p class="text-sm text-slate-500 dark:text-slate-400">{{ salle.adresse }}</p>
                  <div class="flex items-center gap-2 mt-1">
                    <span 
                      :class="[
                        'inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium border',
                        salle.statut === 'actif' 
                          ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-200 dark:border-green-800'
                          : salle.statut === 'inactif'
                          ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-200 dark:border-red-800'
                          : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 border-amber-200 dark:border-amber-800'
                      ]"
                    >
                      {{ salle.statut }}
                    </span>
                  </div>
                </div>
              </div>
              <Link 
                :href="route('admin.salles.show', {id: salle.id})"
                class="text-slate-400 hover:text-primary transition-colors p-2"
                title="Voir les détails"
              >
                <i class="fas fa-eye"></i>
              </Link>
            </div>
          </div>
        </template>
        <div v-else class="px-6 py-8 text-center">
          <i class="fas fa-store text-slate-300 text-4xl mb-4"></i>
          <p class="text-slate-500 dark:text-slate-400">Ce promoteur n'a aucune salle</p>
        </div>
      </div>
    </div>

    <!-- Notification Modal -->
    <div v-if="showNotificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Envoyer une notification</h3>
        
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
            @click="showNotificationModal = false"
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
