<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const props = defineProps({
    stats: Object,
    salle: Object,
    salles: Array,
    events: Array,
    notifications: Array
});

// Formater les nombres
const formatNumber = (num) => {
    return new Intl.NumberFormat('fr-FR').format(num);
};

// Formater les prix
const formatPrice = (prix) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(prix);
};

// Formater les dates
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};
</script>

<template>
  <Head title="Dashboard Promoteur" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <!-- Sidebar Component -->
    <Sidebar current-route="promoter.dashboard" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto transition-all duration-300 pt-16 lg:pt-0">
      <div class="p-3">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div class="flex-1 min-w-0">
            <p class="text-gray-900 dark:text-white text-2xl md:text-3xl font-black leading-tight tracking-[-0.033em] truncate">Dashboard Promoteur</p>
          </div>
          <div class="flex items-center gap-2">
            <div class="relative">
              <i class="fas fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
              <select class="pl-7 pr-2 py-1 text-sm font-medium bg-white dark:bg-background-dark border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 focus:ring-brand-blue focus:border-brand-blue">
                <option>30 derniers jours</option>
                <option>7 derniers jours</option>
                <option>Ce mois-ci</option>
                <option>Cette année</option>
              </select>
            </div>
            <button class="p-1.5 text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">
              <i class="fas fa-upload text-sm"></i>
            </button>
          </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 mt-3">
          <div class="flex flex-col gap-1 rounded-xl p-3 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 min-h-[72px]">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total événements</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold">{{ formatNumber(stats.total || 0) }}</p>
          </div>
          <div class="flex flex-col gap-1 rounded-xl p-3 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 min-h-[72px]">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Événements publiés</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold">{{ formatNumber(stats.publies || 0) }}</p>
          </div>
          <div class="flex flex-col gap-1 rounded-xl p-3 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 min-h-[72px]">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Brouillons</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold">{{ formatNumber(stats.brouillons || 0) }}</p>
          </div>
          <div class="flex flex-col gap-1 rounded-xl p-3 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 min-h-[72px]">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">À venir</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold">{{ formatNumber(stats.avenir || 0) }}</p>
          </div>
          <div class="flex flex-col gap-1 rounded-xl p-3 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 min-h-[72px]">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">En cours</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold">{{ formatNumber(stats.en_cours || 0) }}</p>
          </div>
          <div class="flex flex-col gap-1 rounded-xl p-3 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 min-h-[72px]">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Passés</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold">{{ formatNumber(stats.passes || 0) }}</p>
          </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mt-6">
          <div class="flex flex-col gap-4">
            <!-- Venue Info -->
            <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-3">
              <!-- Salles List -->
              <div v-if="salles && salles.length > 0" class="space-y-4">
                <div v-for="salleItem in salles" :key="salleItem.id" class="border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                  <div class="flex items-start justify-between">
                    <div class="flex-1">
                      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ salleItem.nom || 'Ma Salle' }}</h3>
                      
                      <!-- Informations principales -->
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                        <div class="flex items-center gap-2">
                          <i class="fas fa-map-marker-alt text-gray-400 text-sm"></i>
                          <span class="text-sm text-gray-600">{{ salleItem.adresse || 'Adresse non définie' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                          <i class="fas fa-users text-gray-400 text-sm"></i>
                          <span class="text-sm text-gray-600">{{ salleItem.capacite_max || '0' }} personnes</span>
                        </div>
                        <div class="flex items-center gap-2">
                          <i class="fas fa-clock text-gray-400 text-sm"></i>
                          <span class="text-sm text-gray-600">{{ formatPrice(salleItem.prix_heure || 0) }}/heure</span>
                        </div>
                      </div>
                      
                      <!-- Description courte -->
                      <p class="text-sm text-gray-500 mb-3 line-clamp-2">
                        {{ salleItem.description || 'Aucune description disponible' }}
                      </p>
                      
                      <!-- Statut et catégorie -->
                      <div class="flex items-center gap-2 mb-3">
                        <span :class="[
                          'px-2 py-1 text-xs font-medium rounded-full',
                          salleItem.statut === 'actif' 
                            ? 'bg-green-100 text-green-800' 
                            : 'bg-gray-100 text-gray-800'
                        ]">
                          {{ salleItem.statut === 'actif' ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                          {{ salleItem.categorie || 'Non définie' }}
                        </span>
                        <span class="text-xs text-gray-400">
                          {{ salleItem.images ? salleItem.images.length : 0 }} photos
                        </span>
                      </div>
                    </div>
                    
                    <div class="flex gap-2 ml-4">
                      <Link :href="`/promoter/venues/${salleItem.id}`" class="inline-flex items-center px-3 py-1 text-sm text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50">
                        Voir
                      </Link>
                      <Link :href="`/promoter/venues/${salleItem.id}/edit`" class="inline-flex items-center px-3 py-1 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">
                        Modifier
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Empty State -->
              <div v-else class="text-center py-8">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucune salle</h3>
                <Link href="/promoter/venues/create" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg">
                  Créer une salle
                </Link>
              </div>
            </div>

            <!-- Events Management -->
            <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl">
              <div class="p-3 flex justify-between items-center">
                <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">Gestion des événements</h2>
                <div class="flex items-center gap-2">
                  <button class="p-2 text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 text-sm">À venir</button>
                  <button class="p-2 text-gray-500 dark:text-gray-400 border border-transparent rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 text-sm">En cours</button>
                  <button class="p-2 text-gray-500 dark:text-gray-400 border border-transparent rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 text-sm">Passés</button>
                </div>
              </div>
              <!-- Events List -->
              <div v-if="events && events.length > 0" class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                  <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-background-dark border-b border-gray-200 dark:border-gray-800">
                    <tr>
                      <th class="px-4 py-3" scope="col">Événement</th>
                      <th class="px-4 py-3" scope="col">Date</th>
                      <th class="px-4 py-3" scope="col">Statut</th>
                      <th class="px-4 py-3 text-right" scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="event in events.slice(0, 5)" :key="event.id" class="border-b border-gray-200 dark:border-gray-800">
                      <td class="px-4 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                        <div class="flex items-center gap-3">
                          <div class="w-8 h-8 rounded-lg bg-cover bg-center" 
                               :style="event.image_affiche ? `background-image: url('/storage/events/affiches/${event.image_affiche}')` : 'background-image: url(\'https://picsum.photos/seed/event/100/100.jpg\')'">
                          </div>
                          <span>{{ event.titre }}</span>
                        </div>
                      </td>
                      <td class="px-4 py-3">{{ formatDate(event.date_debut) }}</td>
                      <td class="px-4 py-3">
                        <span :class="[
                          'text-xs font-medium px-2.5 py-0.5 rounded-full',
                          event.statut === 'publie' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' :
                          event.statut === 'brouillon' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' :
                          'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
                        ]">
                          {{ event.statut === 'publie' ? 'Publié' : event.statut === 'brouillon' ? 'Brouillon' : event.statut }}
                        </span>
                      </td>
                      <td class="px-4 py-3 text-right space-x-2">
                        <Link :href="`/promoter/events/${event.id}/edit`" class="font-medium text-blue-600 hover:underline">
                          Modifier
                        </Link>
                        <button class="font-medium text-red-600 hover:underline">
                          Supprimer
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
