<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    events: Array
});

const searchQuery = ref('');
const showCreateModal = ref(false);

const filteredEvents = computed(() => {
    if (!searchQuery.value) return props.events;
    
    return props.events.filter(event => 
        event.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        event.organizer_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        event.country.toLowerCase().includes(searchQuery.value.toLowerCase())
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

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const isUpcoming = (eventDate) => {
    return new Date(eventDate) > new Date();
};

const confirmDelete = (event) => {
    if (confirm(`Êtes-vous sûr de vouloir supprimer l'événement "${event.title}" ? Cette action est irréversible.`)) {
        router.delete(route('admin.standalone-events.destroy', event), {
            onSuccess: () => {
                // Success message handled by controller
            },
            onError: () => {
                // Error handling
            }
        });
    }
};

const toggleStatus = (event) => {
    router.patch(route('admin.standalone-events.toggle-status', event), {}, {
        onSuccess: () => {
            // Success message handled by controller
        },
        onError: () => {
            // Error handling
        }
    });
};
</script>

<template>
  <Head title="Événements Ponctuels" />
  
  <!-- Header -->
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Événements Ponctuels</h1>
    <p class="text-gray-600 dark:text-gray-400 mt-1">Gérez les événements organisés par des tiers (concerts, hôtels, etc.)</p>
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
      :href="route('admin.standalone-events.create')"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
    >
      <i class="fas fa-plus"></i>
      Ajouter un événement
    </Link>
  </div>

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="event in filteredEvents" :key="event.id" class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow">
        <!-- Event Image -->
        <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 rounded-t-lg flex items-center justify-center">
          <img v-if="event.image" :src="'/storage/' + event.image" :alt="event.title" class="w-full h-full object-cover rounded-t-lg">
          <div v-else class="text-white text-center">
            <i class="fas fa-calendar-star text-4xl mb-2"></i>
            <p class="text-sm">Aucune image</p>
          </div>
        </div>

        <!-- Event Content -->
        <div class="p-4">
          <div class="flex justify-between items-start mb-2">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
              {{ event.title }}
            </h3>
            <span :class="getStatusClass(event.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
              {{ getStatusText(event.status) }}
            </span>
          </div>

          <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
            <div class="flex items-center gap-2">
              <i class="fas fa-user text-gray-400"></i>
              <span>{{ event.organizer_name }}</span>
            </div>
            
            <div class="flex items-center gap-2">
              <i class="fas fa-map-marker-alt text-gray-400"></i>
              <span>{{ event.location }}, {{ event.country }}</span>
            </div>
            
            <div class="flex items-center gap-2">
              <i class="fas fa-clock text-gray-400"></i>
              <span :class="{ 'text-green-600 dark:text-green-400': isUpcoming(event.event_date) }">
                {{ formatDate(event.event_date) }}
              </span>
            </div>
            
            <div class="flex items-center gap-2">
              <i class="fas fa-tag text-gray-400"></i>
              <span v-if="event.price" class="font-semibold">
                {{ parseFloat(event.price).toLocaleString('fr-FR') }} €
              </span>
              <span v-else class="text-green-600 font-semibold">Gratuit</span>
            </div>
          </div>

          <!-- Actions -->
          <div class="mt-4 flex justify-between items-center">
            <div class="flex gap-2">
              <Link :href="route('admin.standalone-events.show', event)" 
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Voir
              </Link>
              <Link :href="route('admin.standalone-events.edit', event)" 
                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                Modifier
              </Link>
            </div>
            
            <div class="flex gap-2">
              <button 
                @click="toggleStatus(event)"
                :class="event.status === 'active' ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800'"
                class="text-sm font-medium"
                :title="event.status === 'active' ? 'Désactiver' : 'Activer'"
              >
                <i :class="event.status === 'active' ? 'fas fa-pause' : 'fas fa-play'"></i>
              </button>
              <button 
                @click="confirmDelete(event)"
                class="text-red-600 hover:text-red-800 text-sm font-medium"
                title="Supprimer"
              >
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="filteredEvents.length === 0" class="text-center py-12">
      <i class="fas fa-calendar-star text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
      <p class="text-gray-500 dark:text-gray-400">Aucun événement ponctuel trouvé</p>
      <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">
        Commencez par ajouter votre premier événement ponctuel
      </p>
    </div>
</template>
