<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    subAdmins: Array
});

const searchQuery = ref('');

const filteredSubAdmins = computed(() => {
    if (!searchQuery.value) return props.subAdmins;
    
    return props.subAdmins.filter(subAdmin => 
        subAdmin.user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        subAdmin.user.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        subAdmin.country.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
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
  <Head title="Sous-admins" />
  
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Sous-admins</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Créez et gérez les sous-admins par pays et ville</p>
    </div>

    <!-- Actions -->
    <div class="flex justify-between items-center mb-6">
      <div class="flex items-center gap-4">
        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Rechercher..."
            class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
          />
          <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
      </div>
      
      <Link
        :href="route('admin.sub-admins.create')"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
      >
        <i class="fas fa-plus"></i>
        Ajouter un sous-admin
      </Link>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-900">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
              Sous-admin
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
              Localisation
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
              Statut
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
              Date de création
            </th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="subAdmin in filteredSubAdmins" :key="subAdmin.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user-shield text-blue-600"></i>
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ subAdmin.user.name }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ subAdmin.user.email }}
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900 dark:text-white">
                {{ subAdmin.country }}
                <span v-if="subAdmin.city">, {{ subAdmin.city }}</span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(subAdmin.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                {{ getStatusText(subAdmin.status) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
              {{ new Date(subAdmin.created_at).toLocaleDateString('fr-FR') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <Link :href="route('admin.sub-admins.show', {id: subAdmin.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                Voir
              </Link>
              <Link :href="route('admin.sub-admins.edit', {id: subAdmin.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                Modifier
              </Link>
              <button class="text-red-600 hover:text-red-900">
                Supprimer
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      
      <div v-if="filteredSubAdmins.length === 0" class="text-center py-12">
        <i class="fas fa-user-shield text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <p class="text-gray-500 dark:text-gray-400">Aucun sous-admin trouvé</p>
      </div>
    </div>
  </div>
</template>
