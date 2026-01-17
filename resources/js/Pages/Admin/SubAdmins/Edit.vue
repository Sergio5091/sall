<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    subAdmin: Object
});

const form = useForm({
    name: props.subAdmin.user.name,
    email: props.subAdmin.user.email,
    password: '',
    country: props.subAdmin.country,
    city: props.subAdmin.city || '',
    permissions: props.subAdmin.permissions || []
});

const submit = () => {
    form.put(route('admin.sub-admins.update', props.subAdmin.id));
};
</script>

<template>
  <Head title="Modifier un sous-admin" />
  
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <nav class="flex mb-4">
        <Link :href="route('admin.sub-admins.index')" class="text-blue-600 hover:text-blue-800">
          Sous-admins
        </Link>
        <span class="mx-2 text-gray-500">/</span>
        <span class="text-gray-500">Modifier</span>
      </nav>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Modifier un sous-admin</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Mettre à jour les informations du sous-admin</p>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
      <form @submit.prevent="submit">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Nom -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Nom complet
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
              placeholder="Nom du sous-admin"
            />
            <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
              {{ form.errors.name }}
            </div>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Email
            </label>
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
              placeholder="email@exemple.com"
            />
            <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
              {{ form.errors.email }}
            </div>
          </div>

          <!-- Mot de passe -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Mot de passe (laisser vide pour ne pas changer)
            </label>
            <input
              v-model="form.password"
              type="password"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
              placeholder="••••••••"
            />
            <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
              {{ form.errors.password }}
            </div>
          </div>

          <!-- Pays -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Pays
            </label>
            <select
              v-model="form.country"
              required
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
            >
              <option value="">Sélectionner un pays</option>
              <option value="Bénin">Bénin</option>
              <option value="France">France</option>
              <option value="Côte d'Ivoire">Côte d'Ivoire</option>
              <option value="Sénégal">Sénégal</option>
              <option value="Togo">Togo</option>
              <option value="Burkina Faso">Burkina Faso</option>
              <option value="Mali">Mali</option>
              <option value="Niger">Niger</option>
              <option value="Guinée">Guinée</option>
              <option value="Cameroun">Cameroun</option>
            </select>
            <div v-if="form.errors.country" class="text-red-500 text-sm mt-1">
              {{ form.errors.country }}
            </div>
          </div>

          <!-- Ville -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Ville (optionnelle)
            </label>
            <input
              v-model="form.city"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
              placeholder="Cotonou, Paris, etc."
            />
            <div v-if="form.errors.city" class="text-red-500 text-sm mt-1">
              {{ form.errors.city }}
            </div>
          </div>

          <!-- Statut -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Statut
            </label>
            <select
              v-model="form.status"
              required
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
            >
              <option value="active">Actif</option>
              <option value="inactive">Inactif</option>
            </select>
            <div v-if="form.errors.status" class="text-red-500 text-sm mt-1">
              {{ form.errors.status }}
            </div>
          </div>
        </div>

        <!-- Permissions -->
        <div class="mt-6">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Permissions supplémentaires
          </label>
          <div class="space-y-2">
            <label class="flex items-center">
              <input type="checkbox" value="validate_salles" v-model="form.permissions" 
                     class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Valider les centres de loisirs</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" value="manage_promoters" v-model="form.permissions" 
                     class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Gérer les promoteurs</span>
            </label>
            <label class="flex items-center">
              <input type="checkbox" value="view_statistics" v-model="form.permissions" 
                     class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Voir les statistiques</span>
            </label>
          </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex justify-end gap-4">
          <Link
            :href="route('admin.sub-admins.index')"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
          >
            Annuler
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
          >
            {{ form.processing ? 'Mise à jour...' : 'Mettre à jour' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
