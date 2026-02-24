<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    event: Object
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatPrice = (price) => {
    if (!price) return 'Gratuit';
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0,
        currencyDisplay: 'symbol'
    }).format(price) + ' FCFA';
};

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
  <Head title="Détails de l'événement ponctuel" />
  
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
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ event.title }}</h1>
    <div class="flex items-center gap-4 mt-2">
      <span :class="getStatusClass(event.status)" class="px-3 py-1 text-sm font-semibold rounded-full">
        {{ getStatusText(event.status) }}
      </span>
      <span class="text-gray-600 dark:text-gray-400 text-sm">
        Créé le {{ formatDate(event.created_at) }}
      </span>
    </div>
  </div>

  <!-- Event Details -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Image -->
      <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
        <div class="h-64 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
          <img v-if="event.image" :src="'/storage/' + event.image" :alt="event.title" class="w-full h-full object-cover">
          <div v-else class="text-white text-center">
            <i class="fas fa-calendar-star text-6xl mb-4"></i>
            <p class="text-lg">Aucune image</p>
          </div>
        </div>
      </div>

      <!-- Description -->
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Description</h2>
        <div class="prose dark:prose-invert max-w-none">
          <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ event.description }}</p>
        </div>
      </div>

      <!-- Organizer Information -->
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations de l'organisateur</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom</label>
            <p class="text-gray-900 dark:text-white">{{ event.organizer_name }}</p>
          </div>
          <div v-if="event.organizer_email">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <a :href="'mailto:' + event.organizer_email" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
              {{ event.organizer_email }}
            </a>
          </div>
          <div v-if="event.organizer_phone">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
            <a :href="'tel:' + event.organizer_phone" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
              {{ event.organizer_phone }}
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
      <!-- Event Details -->
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Détails de l'événement</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date et heure</label>
            <p class="text-gray-900 dark:text-white">
              <i class="fas fa-calendar mr-2 text-gray-400"></i>
              {{ formatDate(event.event_date) }}
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lieu</label>
            <p class="text-gray-900 dark:text-white">
              <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
              {{ event.location }}
            </p>
          </div>
          <div v-if="event.country">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pays</label>
            <p class="text-gray-900 dark:text-white">
              <i class="fas fa-globe mr-2 text-gray-400"></i>
              {{ event.country }}
            </p>
          </div>
          <div v-if="event.city">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ville</label>
            <p class="text-gray-900 dark:text-white">
              <i class="fas fa-city mr-2 text-gray-400"></i>
              {{ event.city }}
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prix</label>
            <p class="text-gray-900 dark:text-white font-semibold">
              <i class="fas fa-tag mr-2 text-gray-400"></i>
              {{ formatPrice(event.price) }}
            </p>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h2>
        <div class="space-y-3">
          <Link
            :href="route('admin.standalone-events.edit', {id: event.id})"
            class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
          >
            <i class="fas fa-edit"></i>
            Modifier l'événement
          </Link>
          
          <button
            @click="confirmDelete"
            class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors"
          >
            <i class="fas fa-trash"></i>
            Supprimer l'événement
          </button>
          
          <Link
            :href="route('admin.standalone-events.toggle-status', {id: event.id})"
            method="patch"
            as="button"
            class="w-full flex items-center justify-center gap-2 text-white px-4 py-2 rounded-lg transition-colors"
            :class="event.status === 'active' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700'"
          >
            <i :class="event.status === 'active' ? 'fas fa-pause' : 'fas fa-play'"></i>
            {{ event.status === 'active' ? 'Désactiver' : 'Activer' }}
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>
