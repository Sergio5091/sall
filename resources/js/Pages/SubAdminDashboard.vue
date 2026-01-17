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

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR');
};

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
</script>

<template>
  <div>
    <Head title="Tableau de bord" />
    
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                Bienvenue, {{ $page.props.auth.user.name }} !
              </h1>
              <p class="text-gray-500 dark:text-gray-400 mt-1">
                Gérez les centres et promoteurs de votre région
                <span v-if="subAdminInfo.location.country">
                  - {{ subAdminInfo.location.country }}
                  <span v-if="subAdminInfo.location.city">, {{ subAdminInfo.location.city }}</span>
                </span>
              </p>
            </div>
            
            <div class="flex items-center gap-3">
              <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">Votre rôle</p>
                <p class="font-semibold text-gray-900 dark:text-white">Sous-administrateur</p>
              </div>
              <div class="h-12 w-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                <i class="fas fa-user-shield text-indigo-600 dark:text-indigo-400"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total centres</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total_salles }}</p>
              </div>
              <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                <i class="fas fa-store text-blue-600 dark:text-blue-400"></i>
              </div>
            </div>
          </div>
          
          <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Centres actifs</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ stats.active_salles }}</p>
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
                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ stats.pending_salles }}</p>
              </div>
              <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-lg">
                <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
              </div>
            </div>
          </div>
          
          <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Promoteurs</p>
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ stats.total_promoters }}</p>
              </div>
              <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg">
                <i class="fas fa-users text-purple-600 dark:text-purple-400"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="fas fa-bolt text-yellow-600"></i>
            Actions rapides
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <Link 
              :href="route('admin.admin.sub-admin.salles.index')"
              class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors group"
            >
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                  <i class="fas fa-store text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">Gérer les centres</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ stats.total_salles }} centres</p>
                </div>
              </div>
            </Link>
            
            <Link 
              :href="route('admin.admin.sub-admin.promoters.index')"
              class="p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors group"
            >
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                  <i class="fas fa-users text-purple-600 dark:text-purple-400"></i>
                </div>
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">Gérer les promoteurs</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ stats.total_promoters }} promoteurs</p>
                </div>
              </div>
            </Link>
            
            <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors group">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                  <i class="fas fa-chart-line text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">Voir les statistiques</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Rapports détaillés</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Recent Salles -->
          <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
              <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                  <i class="fas fa-store text-blue-600"></i>
                  Centres récents
                </h2>
                <Link 
                  :href="route('admin.admin.sub-admin.salles.index')"
                  class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                >
                  Voir tout
                </Link>
              </div>
            </div>
            <div class="p-6">
              <div v-if="salles.length > 0" class="space-y-4">
                <div 
                  v-for="salle in salles.slice(0, 5)" 
                  :key="salle.id"
                  class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                >
                  <div class="flex items-center gap-3">
                    <div class="h-8 w-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                      <i class="fas fa-store text-blue-600 dark:text-blue-400 text-sm"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900 dark:text-white">{{ salle.nom }}</p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(salle.created_at) }}</p>
                    </div>
                  </div>
                  
                  <div class="flex items-center gap-2">
                    <span :class="getStatusClass(salle.statut)" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ getStatusText(salle.statut) }}
                    </span>
                    <Link 
                      :href="route('admin.admin.sub-admin.salles.show', salle.id)"
                      class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                      <i class="fas fa-eye text-sm"></i>
                    </Link>
                  </div>
                </div>
              </div>
              
              <div v-else class="text-center py-8">
                <i class="fas fa-store text-gray-400 text-2xl mb-2"></i>
                <p class="text-gray-500 dark:text-gray-400">Aucun centre trouvé</p>
              </div>
            </div>
          </div>

          <!-- Recent Promoters -->
          <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
              <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                  <i class="fas fa-users text-purple-600"></i>
                  Promoteurs récents
                </h2>
                <Link 
                  :href="route('admin.admin.sub-admin.promoters.index')"
                  class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                >
                  Voir tout
                </Link>
              </div>
            </div>
            <div class="p-6">
              <div v-if="promoters.length > 0" class="space-y-4">
                <div 
                  v-for="promoter in promoters.slice(0, 5)" 
                  :key="promoter.id"
                  class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                >
                  <div class="flex items-center gap-3">
                    <div class="h-8 w-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                      <i class="fas fa-user text-purple-600 dark:text-purple-400 text-sm"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900 dark:text-white">{{ promoter.name }}</p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(promoter.created_at) }}</p>
                    </div>
                  </div>
                  
                  <div class="flex items-center gap-2">
                    <span :class="getStatusClass(promoter.status)" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ getStatusText(promoter.status) }}
                    </span>
                    <Link 
                      :href="route('admin.admin.sub-admin.promoters.show', promoter.id)"
                      class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                      <i class="fas fa-eye text-sm"></i>
                    </Link>
                  </div>
                </div>
              </div>
              
              <div v-else class="text-center py-8">
                <i class="fas fa-users text-gray-400 text-2xl mb-2"></i>
                <p class="text-gray-500 dark:text-gray-400">Aucun promoteur trouvé</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Pending Validation -->
        <div v-if="stats.pending_salles > 0" class="p-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl shadow-sm">
          <div class="flex items-center gap-3 mb-4">
            <div class="h-10 w-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
              <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
            </div>
            <div>
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Validations en attente</h2>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ stats.pending_salles }} centre(s) nécessitent votre validation</p>
            </div>
          </div>
          
          <Link 
            :href="route('admin.admin.sub-admin.salles.index')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors"
          >
            <i class="fas fa-check-circle"></i>
            Valider les centres
          </Link>
        </div>
      </div>
  </div>
</template>

<style scoped>
.icon-sm {
  font-size: 20px;
}
</style>
