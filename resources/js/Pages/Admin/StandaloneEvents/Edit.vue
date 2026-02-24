<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    event: Object
});

// Debug pour vérifier les données
console.log('Event props:', props.event);

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

// Pré-remplir le formulaire quand les props changent
watch(() => props.event, (newEvent) => {
    if (newEvent) {
        form.title = newEvent.title || '';
        form.description = newEvent.description || '';
        form.organizer_name = newEvent.organizer_name || '';
        form.organizer_email = newEvent.organizer_email || '';
        form.organizer_phone = newEvent.organizer_phone || '';
        form.location = newEvent.location || '';
        form.country = newEvent.country || '';
        form.city = newEvent.city || '';
        form.event_date = newEvent.event_date ? new Date(newEvent.event_date).toISOString().slice(0, 16) : '';
        form.price = newEvent.price || '';
        form.status = newEvent.status || 'active';
        
        console.log('Form filled:', form);
    }
}, { immediate: true });

const submit = () => {
    form.transform(data => ({
        ...data,
        _method: 'PUT'
    })).post(route('admin.standalone-events.update', {id: props.event.id}), {
        onSuccess: () => {
            // Success message handled by controller
        },
        onError: () => {
            // Error handling
        }
    });
};

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
    }
};
</script>

<template>
  <Head title="Modifier l'événement ponctuel" />
  
  <!-- Header -->
  <div class="mb-6">
    <div class="flex items-center gap-4 mb-4">
      <Link
        :href="route('admin.standalone-events.index')"
        class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200"
      >
        <i class="fas fa-arrow-left mr-2"></i>
        Retour aux événements ponctuels
      </Link>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Modifier l'événement ponctuel</h1>
    <p class="text-gray-600 dark:text-gray-400 mt-1">Mettez à jour les informations de l'événement</p>
  </div>

  <!-- Form -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
    <form @submit.prevent="submit" class="p-6 space-y-6">
      <!-- Basic Information -->
      <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informations de base</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Titre de l'événement <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="title"
              v-model="form.title"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              required
            />
            <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">
              {{ form.errors.title }}
            </div>
          </div>
          
          <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Description <span class="text-red-500">*</span>
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="4"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              required
            ></textarea>
            <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
              {{ form.errors.description }}
            </div>
          </div>
        </div>
      </div>

      <!-- Image -->
      <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Image</h3>
        <div class="space-y-4">
          <div v-if="event.image" class="flex items-center gap-4">
            <img 
              :src="'/storage/' + event.image" 
              :alt="event.title"
              class="w-24 h-24 object-cover rounded-lg"
            />
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Image actuelle</p>
              <p class="text-xs text-gray-500 dark:text-gray-500">Laissez vide pour conserver l'image actuelle</p>
            </div>
          </div>
          
          <div>
            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Nouvelle image (optionnel)
            </label>
            <input
              type="file"
              id="image"
              @change="handleImageChange"
              accept="image/*"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            />
            <div v-if="form.errors.image" class="mt-1 text-sm text-red-600">
              {{ form.errors.image }}
            </div>
          </div>
        </div>
      </div>

      <!-- Organizer Information -->
      <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informations de l'organisateur</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="organizer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Nom de l'organisateur <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="organizer_name"
              v-model="form.organizer_name"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              required
            />
            <div v-if="form.errors.organizer_name" class="mt-1 text-sm text-red-600">
              {{ form.errors.organizer_name }}
            </div>
          </div>
          
          <div>
            <label for="organizer_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Email de l'organisateur
            </label>
            <input
              type="email"
              id="organizer_email"
              v-model="form.organizer_email"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            />
            <div v-if="form.errors.organizer_email" class="mt-1 text-sm text-red-600">
              {{ form.errors.organizer_email }}
            </div>
          </div>
          
          <div>
            <label for="organizer_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Téléphone de l'organisateur
            </label>
            <input
              type="tel"
              id="organizer_phone"
              v-model="form.organizer_phone"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            />
            <div v-if="form.errors.organizer_phone" class="mt-1 text-sm text-red-600">
              {{ form.errors.organizer_phone }}
            </div>
          </div>
        </div>
      </div>

      <!-- Event Details -->
      <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Détails de l'événement</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Lieu <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="location"
              v-model="form.location"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              required
            />
            <div v-if="form.errors.location" class="mt-1 text-sm text-red-600">
              {{ form.errors.location }}
            </div>
          </div>
          
          <div>
            <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Pays <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="country"
              v-model="form.country"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              required
            />
            <div v-if="form.errors.country" class="mt-1 text-sm text-red-600">
              {{ form.errors.country }}
            </div>
          </div>
          
          <div>
            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Ville
            </label>
            <input
              type="text"
              id="city"
              v-model="form.city"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            />
            <div v-if="form.errors.city" class="mt-1 text-sm text-red-600">
              {{ form.errors.city }}
            </div>
          </div>
          
          <div>
            <label for="event_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Date et heure de l'événement <span class="text-red-500">*</span>
            </label>
            <input
              type="datetime-local"
              id="event_date"
              v-model="form.event_date"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              required
            />
            <div v-if="form.errors.event_date" class="mt-1 text-sm text-red-600">
              {{ form.errors.event_date }}
            </div>
          </div>
          
          <div>
            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Prix (FCFA)
            </label>
            <div class="relative">
              <input
                type="number"
                id="price"
                v-model="form.price"
                step="1"
                min="0"
                placeholder="0"
                class="w-full px-3 py-2 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              />
              <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">
                FCFA
              </span>
            </div>
            <div v-if="form.errors.price" class="mt-1 text-sm text-red-600">
              {{ form.errors.price }}
            </div>
          </div>
          
          <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Statut <span class="text-red-500">*</span>
            </label>
            <select
              id="status"
              v-model="form.status"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              required
            >
              <option value="active">Actif</option>
              <option value="inactive">Inactif</option>
            </select>
            <div v-if="form.errors.status" class="mt-1 text-sm text-red-600">
              {{ form.errors.status }}
            </div>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
        <Link 
          :href="route('admin.standalone-events.index')"
          class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
        >
          Annuler
        </Link>
        
        <button
          type="submit"
          :disabled="form.processing"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <i v-if="form.processing" class="fas fa-spinner fa-spin mr-2"></i>
          {{ form.processing ? 'Enregistrement...' : 'Mettre à jour' }}
        </button>
      </div>
    </form>
  </div>
</template>
