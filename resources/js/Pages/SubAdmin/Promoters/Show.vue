<script setup>
import { Head, Link } from '@inertiajs/vue3';
import SubAdminLayout from '@/Layouts/SubAdminLayout.vue';

defineOptions({ layout: SubAdminLayout });

const props = defineProps({
    promoter: Object
});

const getStatusClass = (status) => {
    return status === 'active' 
        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
        : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
};

const getStatusText = (status) => {
    return status === 'active' ? 'Actif' : 'Inactif';
};
</script>

<template>
  <Head :title="`Promoteur - ${promoter.name}`" />
  
  <!-- Header -->
  <div class="mb-6">
    <div class="flex items-center gap-4 mb-4">
      <Link 
        :href="route('admin.sub-admin.promoters.index')"
        class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
      >
        <i class="fas fa-arrow-left mr-2"></i>
        Retour aux promoteurs
      </Link>
    </div>
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-4">
        <div class="bg-purple-100 dark:bg-purple-900/30 w-16 h-16 rounded-full flex items-center justify-center">
          <i class="fas fa-user text-purple-600 dark:text-purple-400 text-2xl"></i>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ promoter.name }}</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">{{ promoter.email }}</p>
        </div>
      </div>
      <span :class="getStatusClass(promoter.status)" class="px-3 py-1 text-sm font-medium rounded-full">
        {{ getStatusText(promoter.status) }}
      </span>
    </div>
  </div>

  <!-- Main Content -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Promoter Details -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Personal Information -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            <i class="fas fa-user-circle mr-2 text-blue-600"></i>
            Informations personnelles
          </h2>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom complet</label>
              <p class="text-gray-900 dark:text-white">{{ promoter.name }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
              <p class="text-gray-900 dark:text-white">{{ promoter.email }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
              <p class="text-gray-900 dark:text-white">{{ promoter.phone || 'Non spécifié' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date d'inscription</label>
              <p class="text-gray-900 dark:text-white">
                {{ new Date(promoter.created_at).toLocaleDateString('fr-FR') }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Location Information -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>
            Localisation
          </h2>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pays</label>
              <p class="text-gray-900 dark:text-white">{{ promoter.country || 'Non spécifié' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ville</label>
              <p class="text-gray-900 dark:text-white">{{ promoter.city || 'Non spécifiée' }}</p>
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adresse</label>
              <p class="text-gray-900 dark:text-white">{{ promoter.address || 'Non spécifiée' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Salles List -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            <i class="fas fa-store mr-2 text-purple-600"></i>
            Centres de loisirs ({{ promoter.salles?.length || 0 }})
          </h2>
        </div>
        <div class="p-6">
          <div v-if="promoter.salles && promoter.salles.length > 0" class="space-y-4">
            <div v-for="salle in promoter.salles" :key="salle.id" 
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
                  Capacité: {{ salle.capacite_max || salle.capacite || 'N/A' }} | 
                  Surface: {{ salle.surface || salle.surface_area || 'N/A' }} m²
                </p>
              </div>
              <div class="flex items-center gap-2">
                <Link 
                  :href="route('admin.sub-admin.salles.show', salle.id)"
                  class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-colors"
                >
                  <i class="fas fa-eye"></i>
                </Link>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <div class="bg-gray-100 dark:bg-gray-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-store text-gray-400 text-2xl"></i>
            </div>
            <p class="text-gray-500 dark:text-gray-400">Aucun centre de loisirs</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Ce promoteur n'a pas encore créé de centre</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
      <!-- Account Status -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            <i class="fas fa-shield-alt mr-2 text-indigo-600"></i>
            Statut du compte
          </h2>
        </div>
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-gray-600 dark:text-gray-400">Statut actuel</span>
            <span :class="getStatusClass(promoter.status)" class="px-2 py-1 text-xs font-medium rounded-full">
              {{ getStatusText(promoter.status) }}
            </span>
          </div>
          <div class="space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600 dark:text-gray-400">Email vérifié</span>
              <span class="font-medium text-gray-900 dark:text-white">
                {{ promoter.email_verified_at ? 'Oui' : 'Non' }}
              </span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600 dark:text-gray-400">Dernière connexion</span>
              <span class="font-medium text-gray-900 dark:text-white">
                {{ promoter.last_login_at ? new Date(promoter.last_login_at).toLocaleDateString('fr-FR') : 'Jamais' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            <i class="fas fa-cog mr-2 text-gray-600"></i>
            Actions
          </h2>
        </div>
        <div class="p-6 space-y-3">
          <button 
            v-if="promoter.status === 'active'"
            @click="togglePromoterStatus"
            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium"
          >
            <i class="fas fa-ban mr-2"></i>
            Désactiver le compte
          </button>
          
          <button 
            v-if="promoter.status === 'inactive'"
            @click="togglePromoterStatus"
            class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium"
          >
            <i class="fas fa-check mr-2"></i>
            Activer le compte
          </button>
          
          <button 
            @click="resetPassword"
            class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium"
          >
            <i class="fas fa-key mr-2"></i>
            Réinitialiser le mot de passe
          </button>
        </div>
      </div>

      <!-- Statistics -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>
            Statistiques
          </h2>
        </div>
        <div class="p-6 space-y-4">
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Total centres</span>
            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ promoter.salles?.length || 0 }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Centres actifs</span>
            <span class="text-lg font-semibold text-gray-900 dark:text-white">
              {{ promoter.salles?.filter(s => s.status === 'active').length || 0 }}
            </span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">En attente</span>
            <span class="text-lg font-semibold text-gray-900 dark:text-white">
              {{ promoter.salles?.filter(s => s.status === 'pending').length || 0 }}
            </span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Date d'inscription</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">
              {{ new Date(promoter.created_at).toLocaleDateString('fr-FR') }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { router } from '@inertiajs/vue3';

const togglePromoterStatus = () => {
  const action = props.promoter.status === 'active' ? 'désactiver' : 'activer';
  if (confirm(`Êtes-vous sûr de vouloir ${action} ce promoteur ?`)) {
    router.post(route('admin.sub-admin.promoters.toggle-status', props.promoter.id));
  }
};

const resetPassword = () => {
  if (confirm('Êtes-vous sûr de vouloir réinitialiser le mot de passe de ce promoteur ?')) {
    router.post(route('admin.promoters.reset-password', props.promoter.id));
  }
};
</script>
