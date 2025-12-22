<script setup>
import { Head, Link } from '@inertiajs/vue3';
import SubAdminLayout from '@/Layouts/SubAdminLayout.vue';

defineOptions({ layout: SubAdminLayout });

const props = defineProps({
    stats: Object,
    salles: Array,
    promoters: Array,
    subAdminInfo: Object
});

const getStatusClass = (status) => {
    switch(status) {
        case 'active':
        case 'actif':
            return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'inactive':
        case 'inactif':
            return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
        case 'pending':
        case 'en_attente':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
        case 'maintenance':
            return 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
    }
};

const getStatusText = (status) => {
    switch(status) {
        case 'active':
        case 'actif':
            return 'Actif';
        case 'inactive':
        case 'inactif':
            return 'Inactif';
        case 'pending':
        case 'en_attente':
            return 'En attente';
        case 'maintenance':
            return 'Maintenance';
        default:
            return status;
    }
};
</script>

<template>
  <Head title="Tableau de bord Sous-admin" />
  
  <!-- Welcome Section -->
  <div class="mb-8">
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-8 text-white">
      <div class="max-w-4xl">
        <h1 class="text-3xl font-bold mb-2">Bienvenue, {{ $page.props.auth.user.name }} !</h1>
        <p class="text-indigo-100 text-lg">
          Gérez les centres de loisirs et promoteurs de votre zone
          <span v-if="subAdminInfo.location.country">
            - {{ subAdminInfo.location.country }}
            <span v-if="subAdminInfo.location.city">, {{ subAdminInfo.location.city }}</span>
          </span>
        </p>
      </div>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
          <i class="fas fa-store text-blue-600 dark:text-blue-400 text-xl"></i>
        </div>
        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_salles }}</span>
      </div>
      <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Centres</h3>
      <div class="mt-2 flex items-center text-sm">
        <span class="text-green-600 dark:text-green-400">
          <i class="fas fa-arrow-up mr-1"></i>
          {{ stats.active_salles }} actifs
        </span>
      </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
          <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
        </div>
        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.active_salles }}</span>
      </div>
      <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium">Centres Actifs</h3>
      <div class="mt-2 flex items-center text-sm">
        <span class="text-gray-500 dark:text-gray-400">
          {{ stats.total_salles > 0 ? Math.round((stats.active_salles / stats.total_salles) * 100) : 0 }}% du total
        </span>
      </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-lg">
          <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
        </div>
        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.pending_salles }}</span>
      </div>
      <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium">En Attente</h3>
      <div class="mt-2 flex items-center text-sm">
        <span class="text-yellow-600 dark:text-yellow-400">
          Validation requise
        </span>
      </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg">
          <i class="fas fa-user-friends text-purple-600 dark:text-purple-400 text-xl"></i>
        </div>
        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_promoters }}</span>
      </div>
      <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium">Promoteurs</h3>
      <div class="mt-2 flex items-center text-sm">
        <span class="text-green-600 dark:text-green-400">
          {{ stats.active_promoters }} actifs
        </span>
      </div>
    </div>
  </div>

  <!-- Recent Activity -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Salles -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
          <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg mr-3">
            <i class="fas fa-store text-blue-600 dark:text-blue-400"></i>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Centres Récents</h3>
        </div>
      </div>
      <div class="p-6">
        <div v-if="salles.length > 0" class="space-y-4">
          <div v-for="salle in salles.slice(0, 5)" :key="salle.id" 
               class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <div class="flex-1">
              <div class="flex items-center mb-1">
                <h4 class="font-medium text-gray-900 dark:text-white">{{ salle.nom }}</h4>
                <span :class="getStatusClass(salle.status)" class="ml-2 px-2 py-1 text-xs font-medium rounded-full">
                  {{ getStatusText(salle.status) }}
                </span>
              </div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                <i class="fas fa-map-marker-alt mr-1"></i>
                {{ salle.ville }}, {{ salle.pays }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-500">
                <i class="fas fa-user mr-1"></i>
                {{ salle.promoter?.name || 'N/A' }}
              </p>
            </div>
          </div>
        </div>
        <div v-else class="text-center py-8">
          <div class="bg-gray-100 dark:bg-gray-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-store text-gray-400 text-2xl"></i>
          </div>
          <p class="text-gray-500 dark:text-gray-400">Aucun centre trouvé</p>
          <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Les centres apparaîtront ici une fois créés</p>
        </div>
      </div>
    </div>

    <!-- Recent Promoters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
          <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-lg mr-3">
            <i class="fas fa-user-friends text-purple-600 dark:text-purple-400"></i>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Promoteurs Récents</h3>
        </div>
      </div>
      <div class="p-6">
        <div v-if="promoters.length > 0" class="space-y-4">
          <div v-for="promoter in promoters.slice(0, 5)" :key="promoter.id" 
               class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <div class="flex-1">
              <div class="flex items-center mb-1">
                <h4 class="font-medium text-gray-900 dark:text-white">{{ promoter.name }}</h4>
                <span :class="getStatusClass(promoter.status)" class="ml-2 px-2 py-1 text-xs font-medium rounded-full">
                  {{ getStatusText(promoter.status) }}
                </span>
              </div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                <i class="fas fa-envelope mr-1"></i>
                {{ promoter.email }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-500">
                <i class="fas fa-map-marker-alt mr-1"></i>
                {{ promoter.city }}, {{ promoter.country }}
              </p>
            </div>
          </div>
        </div>
        <div v-else class="text-center py-8">
          <div class="bg-gray-100 dark:bg-gray-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-user-friends text-gray-400 text-2xl"></i>
          </div>
          <p class="text-gray-500 dark:text-gray-400">Aucun promoteur trouvé</p>
          <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Les promoteurs apparaîtront ici une fois inscrits</p>
        </div>
      </div>
    </div>
  </div>
</template>
