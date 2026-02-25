<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    subAdmin: Object
});

const getStatusClass = (status) => {
    return status === 'active' 
        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
        : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
};

const getStatusText = (status) => {
    return status === 'active' ? 'Actif' : 'Inactif';
};

const getPermissionText = (permission) => {
    const permissions = {
        'validate_salles': 'Valider les centres de loisirs',
        'manage_promoters': 'Gérer les promoteurs',
        'view_statistics': 'Voir les statistiques'
    };
    return permissions[permission] || permission;
};
</script>

<template>
  <Head title="Détails du sous-admin" />
  
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <nav class="flex mb-4">
        <Link :href="route('admin.sub-admins.index')" class="text-blue-600 hover:text-blue-800">
          Sous-admins
        </Link>
        <span class="mx-2 text-gray-500">/</span>
        <span class="text-gray-500">Détails</span>
      </nav>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Détails du sous-admin</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Informations complètes sur le sous-admin</p>
    </div>

    <!-- Informations principales -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
      <div class="flex items-start justify-between">
        <div class="flex items-center">
          <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="fas fa-user-shield text-blue-600 text-2xl"></i>
          </div>
          <div class="ml-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
              {{ subAdmin.user.name }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400">{{ subAdmin.user.email }}</p>
            <div class="mt-2 flex items-center gap-4">
              <span :class="getStatusClass(subAdmin.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                {{ getStatusText(subAdmin.status) }}
              </span>
              <span class="text-sm text-gray-500 dark:text-gray-400">
                Créé le {{ new Date(subAdmin.created_at).toLocaleDateString('fr-FR') }}
              </span>
            </div>
          </div>
        </div>
        
        <div class="flex gap-2">
          <Link
            :href="route('admin.sub-admins.edit', {id: subAdmin.id})"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
          >
            <i class="fas fa-edit"></i>
            Modifier
          </Link>
        </div>
      </div>
    </div>

    <!-- Informations détaillées -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Informations personnelles -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          <i class="fas fa-user mr-2 text-blue-600"></i>
          Informations personnelles
        </h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Nom complet:</span>
            <span class="text-gray-900 dark:text-white font-medium">{{ subAdmin.user.name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Email:</span>
            <span class="text-gray-900 dark:text-white font-medium">{{ subAdmin.user.email }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Statut:</span>
            <span :class="getStatusClass(subAdmin.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
              {{ getStatusText(subAdmin.status) }}
            </span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Date de création:</span>
            <span class="text-gray-900 dark:text-white font-medium">
              {{ new Date(subAdmin.created_at).toLocaleDateString('fr-FR') }}
            </span>
          </div>
          <div v-if="subAdmin.updated_at !== subAdmin.created_at" class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Dernière modification:</span>
            <span class="text-gray-900 dark:text-white font-medium">
              {{ new Date(subAdmin.updated_at).toLocaleDateString('fr-FR') }}
            </span>
          </div>
        </div>
      </div>

      <!-- Localisation -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
          Localisation
        </h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Pays:</span>
            <span class="text-gray-900 dark:text-white font-medium">{{ subAdmin.country }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Ville:</span>
            <span class="text-gray-900 dark:text-white font-medium">
              {{ subAdmin.city || 'Non spécifiée' }}
            </span>
          </div>
        </div>
      </div>

      <!-- Permissions -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          <i class="fas fa-key mr-2 text-blue-600"></i>
          Permissions
        </h3>
        <div v-if="subAdmin.permissions && subAdmin.permissions.length > 0" class="space-y-2">
          <div v-for="permission in subAdmin.permissions" :key="permission" 
               class="flex items-center p-2 bg-gray-50 dark:bg-gray-700 rounded">
            <i class="fas fa-check-circle text-green-500 mr-2"></i>
            <span class="text-gray-900 dark:text-white">{{ getPermissionText(permission) }}</span>
          </div>
        </div>
        <div v-else class="text-gray-500 dark:text-gray-400 italic">
          Aucune permission supplémentaire
        </div>
      </div>

      <!-- Actions rapides -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          <i class="fas fa-bolt mr-2 text-blue-600"></i>
          Actions rapides
        </h3>
        <div class="space-y-3">
          <Link
            :href="route('admin.sub-admins.edit', {id: subAdmin.id})"
            class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-center"
          >
            <i class="fas fa-edit mr-2"></i>
            Modifier le sous-admin
          </Link>
          <button
            @click="confirmToggleStatus"
            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
          >
            <i class="fas fa-power-off mr-2"></i>
            {{ subAdmin.status === 'active' ? 'Désactiver' : 'Activer' }} le compte
          </button>
        </div>
      </div>
    </div>

    <!-- Bouton retour -->
    <div class="mt-6">
      <Link
        :href="route('admin.sub-admins.index')"
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
      >
        <i class="fas fa-arrow-left mr-2"></i>
        Retour à la liste
      </Link>
    </div>
  </div>
</template>
