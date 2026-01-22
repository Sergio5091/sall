<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import SubAdminLayout from '@/Layouts/SubAdminLayout.vue';

defineOptions({ layout: SubAdminLayout });

const props = defineProps({
    salle: Object
});

const toggleSalleStatus = (salle) => {
  const action = salle.statut === 'actif' ? 'désactiver' : 'activer';
  const actionText = salle.statut === 'actif' ? 'désactiver' : 'activer';
  
  if (confirm(`Êtes-vous sûr de vouloir ${actionText} le centre "${salle.nom}" ?`)) {
    router.post(route('admin.sub-admin.salles.toggle-status', salle.id), {}, {
      onSuccess: () => {
        location.reload();
      },
      onError: () => {
        alert(`Une erreur est survenue lors de la ${action}.`);
      }
    });
  }
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
  <Head :title="`Centre - ${salle.nom}`" />
  
  <!-- Header -->
  <div class="mb-6">
    <div class="flex items-center gap-4 mb-4">
      <Link 
        :href="route('admin.sub-admin.salles.index')"
        class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
      >
        <i class="fas fa-arrow-left mr-2"></i>
        Retour aux centres
      </Link>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ salle.nom }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
          <i class="fas fa-map-marker-alt mr-1"></i>
          {{ salle.ville }}, {{ salle.pays }}
        </p>
      </div>
      <span :class="getStatusClass(salle.statut)" class="px-3 py-1 text-sm font-medium rounded-full">
        {{ getStatusText(salle.statut) }}
      </span>
    </div>
  </div>

  <!-- Main Content -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Salle Details -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Basic Information -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
            Informations générales
          </h2>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom du centre</label>
              <p class="text-gray-900 dark:text-white">{{ salle.nom }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
              <p class="text-gray-900 dark:text-white">{{ salle.type || 'Non spécifié' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Capacité maximale</label>
              <p class="text-gray-900 dark:text-white">{{ salle.capacite_max || salle.capacite || 'Non spécifiée' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Surface</label>
              <p class="text-gray-900 dark:text-white">{{ salle.surface || salle.surface_area || 'Non spécifiée' }} m²</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ville</label>
              <p class="text-gray-900 dark:text-white">{{ salle.ville || 'Non spécifiée' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pays</label>
              <p class="text-gray-900 dark:text-white">{{ salle.pays || 'Non spécifié' }}</p>
            </div>
          </div>
          
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
            <p class="text-gray-900 dark:text-white whitespace-pre-line">{{ salle.description || 'Aucune description' }}</p>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            <i class="fas fa-phone mr-2 text-green-600"></i>
            Contact
          </h2>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
              <p class="text-gray-900 dark:text-white">{{ salle.telephone || 'Non spécifié' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
              <p class="text-gray-900 dark:text-white">{{ salle.email || 'Non spécifié' }}</p>
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adresse</label>
              <p class="text-gray-900 dark:text-white">{{ salle.adresse || 'Non spécifiée' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
      <!-- Promoter Information -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            <i class="fas fa-user mr-2 text-purple-600"></i>
            Promoteur
          </h2>
        </div>
        <div class="p-6">
          <div class="flex items-center mb-4">
            <div class="bg-purple-100 dark:bg-purple-900/30 w-12 h-12 rounded-full flex items-center justify-center mr-3">
              <i class="fas fa-user text-purple-600 dark:text-purple-400"></i>
            </div>
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">{{ salle.promoter?.name || 'N/A' }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ salle.promoter?.email || 'N/A' }}</p>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600 dark:text-gray-400">Statut</span>
              <span class="font-medium text-gray-900 dark:text-white">
                {{ salle.promoter?.status === 'active' ? 'Actif' : 'Inactif' }}
              </span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600 dark:text-gray-400">Localisation</span>
              <span class="font-medium text-gray-900 dark:text-white">
                {{ salle.promoter?.city || 'N/A' }}, {{ salle.promoter?.country || 'N/A' }}
              </span>
            </div>
          </div>
          <Link 
            :href="route('admin.sub-admin.promoters.show', salle.promoter?.id)"
            class="mt-4 block w-full text-center px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium"
          >
            Voir le promoteur
          </Link>
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
          <!-- Bouton unique d'activation/désactivation -->
          <button 
            v-if="salle.statut === 'actif'"
            @click="toggleSalleStatus(salle)"
            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium"
          >
            <i class="fas fa-ban mr-2"></i>
            Désactiver le centre
          </button>
          
          <button 
            v-else
            @click="toggleSalleStatus(salle)"
            class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium"
          >
            <i class="fas fa-check mr-2"></i>
            Activer le centre
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
            <span class="text-sm text-gray-600 dark:text-gray-400">Événements</span>
            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ salle.events?.length || 0 }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Réservations</span>
            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ salle.reservations?.length || 0 }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Date de création</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">
              {{ new Date(salle.created_at).toLocaleDateString('fr-FR') }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { router } from '@inertiajs/vue3';

const validateSalle = () => {
  if (confirm('Êtes-vous sûr de vouloir valider ce centre ?')) {
    router.post(route('admin.sub-admin.salles.validate', props.salle.id));
  }
};

const deactivateSalle = () => {
  if (confirm('Êtes-vous sûr de vouloir désactiver ce centre ?')) {
    router.post(route('admin.sub-admin.salles.deactivate', props.salle.id));
  }
};

const reactivateSalle = () => {
  if (confirm('Êtes-vous sûr de vouloir réactiver ce centre ?')) {
    router.put(route('admin.sub-admin.salles.update', props.salle.id), { status: 'active' });
  }
};
</script>
