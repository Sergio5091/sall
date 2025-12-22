<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { debounce } from 'lodash';
import SubAdminLayout from '@/Layouts/SubAdminLayout.vue';

defineOptions({ layout: SubAdminLayout });

const props = defineProps({
    promoters: Array,
    stats: Object
});

const searchQuery = ref('');
const statusFilter = ref('');

const filteredPromoters = computed(() => {
    let filtered = props.promoters;
    
    if (searchQuery.value) {
        filtered = filtered.filter(promoter => 
            promoter.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            promoter.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            promoter.city?.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    
    if (statusFilter.value) {
        filtered = filtered.filter(promoter => promoter.status === statusFilter.value);
    }
    
    return filtered;
});

const handleSearch = debounce(() => {
    // Search logic already handled by computed
}, 300);

const getStatusClass = (status) => {
    return status === 'active' 
        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
        : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
};

const getStatusText = (status) => {
    return status === 'active' ? 'Actif' : 'Inactif';
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR');
};

const togglePromoterStatus = (promoter) => {
  if (confirm(`Êtes-vous sûr de vouloir ${promoter.status === 'active' ? 'désactiver' : 'activer'} ce promoteur ?`)) {
    router.post(route('admin.sub-admin.promoters.toggle-status', promoter.id));
  }
};

const resetPromoterPassword = (promoter) => {
  if (confirm(`Êtes-vous sûr de vouloir réinitialiser le mot de passe de "${promoter.name}" ?`)) {
    router.post(route('admin.sub-admin.promoters.reset-password', promoter.id));
  }
};

const editPromoter = (promoter) => {
  // TODO: Implement edit modal or redirect to edit page
  console.log('Edit promoter:', promoter);
};
</script>

<template>
  <Head title="Gestion des Promoteurs" />
  
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-slate-850 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-slate-850 border-b border-slate-200 dark:border-slate-800">
          <!-- Breadcrumbs -->
          <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-6">
            <Link :href="route('admin.sub-admins.dashboard')" class="hover:text-primary transition-colors">Tableau de bord</Link>
            <i class="fas fa-chevron-right icon-sm text-slate-300"></i>
            <span class="text-slate-900 dark:text-white font-medium">Promoteurs</span>
          </nav>

          <!-- Page Heading -->
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Gestion des promoteurs</h2>
              <p class="text-slate-500 dark:text-slate-400 mt-1">Gérez les promoteurs de votre région.</p>
            </div>
          </div>

          <!-- Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total promoteurs</p>
                  <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats?.total || 0 }}</p>
                </div>
                <div class="bg-primary/10 p-3 rounded-lg">
                  <i class="fas fa-users text-primary"></i>
                </div>
              </div>
            </div>
            
            <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Actifs</p>
                  <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ stats?.active || 0 }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                  <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
              </div>
            </div>
            
            <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Inactifs</p>
                  <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ stats?.inactive || 0 }}</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-lg">
                  <i class="fas fa-ban text-red-600 dark:text-red-400"></i>
                </div>
              </div>
            </div>
            
            <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total centres</p>
                  <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ stats?.total_salles || 0 }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                  <i class="fas fa-store text-blue-600 dark:text-blue-400"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Filters & Controls -->
          <div class="bg-white dark:bg-slate-850 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between mt-6">
            <!-- Search Field -->
            <div class="relative w-full md:max-w-md">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-slate-400 icon-sm"></i>
              </div>
              <input
                v-model="searchQuery"
                @input="handleSearch"
                class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg leading-5 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-shadow"
                placeholder="Rechercher par nom, email ou ville..."
                type="text"
              />
            </div>
            
            <!-- Filter Actions -->
            <div class="flex items-center gap-3 w-full md:w-auto">
              <div class="relative w-full md:w-48">
                <select
                  v-model="statusFilter"
                  @change="handleSearch"
                  class="block w-full pl-3 pr-10 py-2.5 text-base border-slate-300 dark:border-slate-700 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none cursor-pointer"
                >
                  <option value="">Tous les statuts</option>
                  <option value="active">Actifs</option>
                  <option value="inactive">Inactifs</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                  <i class="fas fa-chevron-down icon-sm"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
              <thead class="bg-slate-50 dark:bg-slate-900/50">
                <tr>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider" scope="col">ID</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider" scope="col">Promoteur</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell" scope="col">Email</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell" scope="col">Localisation</th>
                  <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider" scope="col">Centres</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider" scope="col">Statut</th>
                  <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider" scope="col">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-slate-850 divide-y divide-slate-200 dark:divide-slate-800">
                <tr v-for="promoter in filteredPromoters" :key="promoter.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400 font-mono">#PR-{{ promoter.id }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-10 w-10 flex-shrink-0 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-slate-400"></i>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-slate-900 dark:text-white group-hover:text-primary transition-colors cursor-pointer">{{ promoter.name }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Promoteur</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400 hidden sm:table-cell">
                    {{ promoter.email }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400 hidden lg:table-cell">
                    <div class="flex items-center gap-1">
                      <i class="fas fa-map-marker-alt text-xs"></i>
                      {{ promoter.city || 'N/A' }}, {{ promoter.country }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white text-center">
                    {{ promoter.salles_count || 0 }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusClass(promoter.status)" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ getStatusText(promoter.status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end gap-2">
                      <Link 
                        :href="route('admin.sub-admin.promoters.show', promoter.id)"
                        class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-500 transition-colors p-2" 
                        title="Voir les détails"
                      >
                        <i class="fas fa-eye icon-sm"></i>
                      </Link>
                      <button 
                        @click="editPromoter(promoter)"
                        class="text-slate-400 hover:text-amber-600 dark:hover:text-amber-500 transition-colors p-2" 
                        title="Modifier"
                      >
                        <i class="fas fa-edit icon-sm"></i>
                      </button>
                      <button 
                        @click="togglePromoterStatus(promoter)"
                        class="text-slate-400 hover:text-yellow-600 dark:hover:text-yellow-500 transition-colors p-2" 
                        :title="promoter.status === 'active' ? 'Désactiver' : 'Activer'"
                      >
                        <i class="fas fa-power-off icon-sm"></i>
                      </button>
                      <button 
                        @click="resetPromoterPassword(promoter)"
                        class="text-slate-400 hover:text-red-600 dark:hover:text-red-500 transition-colors p-2" 
                        title="Réinitialiser le mot de passe"
                      >
                        <i class="fas fa-key icon-sm"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Empty State -->
          <div v-if="filteredPromoters.length === 0" class="text-center py-12">
            <div class="bg-slate-100 dark:bg-slate-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-users text-slate-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Aucun promoteur trouvé</h3>
            <p class="text-slate-500 dark:text-slate-400">
              {{ searchQuery ? 'Aucun promoteur ne correspond à votre recherche' : 'Aucun promoteur dans votre région' }}
            </p>
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
