<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const form = useForm({
    title: '',
    description: '',
    image: null,
    organizer_name: '',
    organizer_email: '',
    organizer_phone: '',
    location: '',
    country: '',
    city: '',
    event_date: '',
    price: '',
    status: 'active'
});

const submit = () => {
    form.post(route('admin.standalone-events.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
    }
};
</script>

<template>
  <Head title="Créer un événement ponctuel" />

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
              Créer un événement ponctuel
            </h1>
            <Link 
              :href="route('admin.standalone-events.index')"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
            >
              Retour
            </Link>
          </div>

          <form @submit.prevent="submit" class="space-y-6">
            <!-- Informations générales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Titre de l'événement *
                </label>
                <input
                  type="text"
                  v-model="form.title"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  required
                >
                <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">
                  {{ form.errors.title }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Statut
                </label>
                <select
                  v-model="form.status"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                >
                  <option value="active">Actif</option>
                  <option value="inactive">Inactif</option>
                </select>
                <div v-if="form.errors.status" class="text-red-500 text-sm mt-1">
                  {{ form.errors.status }}
                </div>
              </div>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Description *
              </label>
              <textarea
                v-model="form.description"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                required
              ></textarea>
              <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                {{ form.errors.description }}
              </div>
            </div>

            <!-- Image -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Image de l'événement
              </label>
              <input
                type="file"
                @change="handleImageUpload"
                accept="image/*"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
              >
              <div v-if="form.errors.image" class="text-red-500 text-sm mt-1">
                {{ form.errors.image }}
              </div>
            </div>

            <!-- Organisateur -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Nom de l'organisateur *
                </label>
                <input
                  type="text"
                  v-model="form.organizer_name"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  required
                >
                <div v-if="form.errors.organizer_name" class="text-red-500 text-sm mt-1">
                  {{ form.errors.organizer_name }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Email de l'organisateur
                </label>
                <input
                  type="email"
                  v-model="form.organizer_email"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                >
                <div v-if="form.errors.organizer_email" class="text-red-500 text-sm mt-1">
                  {{ form.errors.organizer_email }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Téléphone de l'organisateur
                </label>
                <input
                  type="tel"
                  v-model="form.organizer_phone"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                >
                <div v-if="form.errors.organizer_phone" class="text-red-500 text-sm mt-1">
                  {{ form.errors.organizer_phone }}
                </div>
              </div>
            </div>

            <!-- Localisation -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Lieu de l'événement *
                </label>
                <input
                  type="text"
                  v-model="form.location"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  required
                >
                <div v-if="form.errors.location" class="text-red-500 text-sm mt-1">
                  {{ form.errors.location }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Pays *
                </label>
                <input
                  type="text"
                  v-model="form.country"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  required
                >
                <div v-if="form.errors.country" class="text-red-500 text-sm mt-1">
                  {{ form.errors.country }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Ville
                </label>
                <input
                  type="text"
                  v-model="form.city"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                >
                <div v-if="form.errors.city" class="text-red-500 text-sm mt-1">
                  {{ form.errors.city }}
                </div>
              </div>
            </div>

            <!-- Date et prix -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Date de l'événement *
                </label>
                <input
                  type="datetime-local"
                  v-model="form.event_date"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  required
                >
                <div v-if="form.errors.event_date" class="text-red-500 text-sm mt-1">
                  {{ form.errors.event_date }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Prix (optionnel)
                </label>
                <input
                  type="number"
                  v-model="form.price"
                  step="0.01"
                  min="0"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  placeholder="0.00"
                >
                <div v-if="form.errors.price" class="text-red-500 text-sm mt-1">
                  {{ form.errors.price }}
                </div>
              </div>
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-end space-x-4">
              <Link 
                :href="route('admin.standalone-events.index')"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
              >
                Annuler
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50"
              >
                {{ form.processing ? 'Création en cours...' : 'Créer l\'événement' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
