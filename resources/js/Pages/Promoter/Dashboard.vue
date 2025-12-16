<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const props = defineProps({
    stats: Object,
    salle: Object,
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
    <main class="flex-1 overflow-y-auto transition-all duration-300">
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
            <Link href="/promoter/events/create" class="flex items-center justify-center rounded-lg h-9 bg-brand-red text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] px-3 hover:bg-brand-red/90 transition-colors">
              <i class="fas fa-plus text-base font-bold"></i>
              <span class="truncate">Créer un événement</span>
            </Link>
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
            <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-3 pb-8">
              <div class="flex justify-between items-center mb-4">
                <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">Gestion des Informations de la Salle</h2>
                <button class="flex items-center justify-center rounded-lg h-9 bg-primary text-white gap-2 text-sm font-bold leading-normal px-3 hover:bg-primary/90 transition-colors">
                  <i class="fas fa-edit text-base"></i>
                  <span class="truncate">Modifier</span>
                </button>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1 flex flex-col items-center text-center gap-4">
                  <div class="relative w-36 h-36">
                    <img 
                      alt="Venue Banner" 
                      class="w-full h-full object-cover rounded-xl" 
                      :src="salle?.image_url ? (salle.image_url.startsWith('http') ? salle.image_url : `/storage/${salle.image_url}`) : 'https://picsum.photos/seed/venue/400/400.jpg'"
                    >
                    <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-12 h-12 rounded-full border-2 border-white dark:border-[#19202e] bg-cover bg-center" 
                         :style="salle?.image_url ? `background-image: url('${salle.image_url.startsWith('http') ? salle.image_url : `/storage/${salle.image_url}`}')` : 'background-image: url(\'https://picsum.photos/seed/logo/200/200.jpg\')'">
                    </div>
                  </div>
                  <div class="mt-2">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ salle?.nom || 'Ma Salle' }}</h3>
                    <div class="mt-1 flex justify-center items-center gap-2">
                      <span :class="[
                        'text-xs font-medium px-2.5 py-0.5 rounded-full',
                        salle?.statut === 'actif' 
                          ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' 
                          : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
                      ]">
                        {{ salle?.statut === 'actif' ? 'Active' : 'Inactive' }}
                      </span>
                    </div>
                  </div>
                </div>
                <div class="md:col-span-2 space-y-4">
                  <div>
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Description</h4>
                    <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">{{ salle?.description || 'Description de votre salle' }}</p>
                  </div>
                  <div>
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Adresse</h4>
                    <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">
                      {{ salle?.adresse || 'Adresse à définir' }}, {{ salle?.ville || 'Ville' }}, {{ salle?.pays || 'Pays' }}
                    </p>
                  </div>
                  <div>
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Capacité</h4>
                    <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">{{ salle?.capacite_max || '0' }} personnes maximum</p>
                  </div>
                  <div>
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Tarifs</h4>
                    <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">
                      {{ formatPrice(salle?.prix_heure || 0) }} / heure
                    </p>
                  </div>
                </div>
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
              <div v-else class="p-4 text-sm text-gray-600 dark:text-gray-400">
                Aucun événement pour le moment.
              </div>
            </div>
          </div>

          <!-- Right: stats + notifications -->
          <aside class="lg:col-span-1 flex flex-col gap-4">
            <div class="space-y-3">
              <div class="flex flex-col gap-2 rounded-xl p-3 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total événements</p>
                <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold">{{ formatNumber(stats.total || 0) }}</p>
              </div>
              <div class="flex flex-col gap-2 rounded-xl p-3 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Événements publiés</p>
                <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold">{{ formatNumber(stats.publies || 0) }}</p>
              </div>
              <div class="flex flex-col gap-2 rounded-xl p-3 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Brouillons</p>
                <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold">{{ formatNumber(stats.brouillons || 0) }}</p>
              </div>
              <div class="flex flex-col gap-2 rounded-xl p-3 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">À venir</p>
                <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold">{{ formatNumber(stats.avenir || 0) }}</p>
              </div>
              <div class="flex flex-col gap-2 rounded-xl p-3 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">En cours</p>
                <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold">{{ formatNumber(stats.en_cours || 0) }}</p>
              </div>
              <div class="flex flex-col gap-2 rounded-xl p-3 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Passés</p>
                <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold">{{ formatNumber(stats.passes || 0) }}</p>
              </div>
            </div>

            <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-3">
              <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Notifications</h3>
                <Link href="#" class="text-xs text-gray-500">Voir tout</Link>
              </div>
              <div class="space-y-2">
                <template v-if="notifications && notifications.length">
                  <div v-for="(n, idx) in notifications.slice(0,6)" :key="idx" class="text-sm text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-transparent p-2 rounded">
                    <div class="flex items-center justify-between">
                      <div class="truncate">{{ n.message || n.title || 'Notification' }}</div>
                      <div class="text-xs text-gray-400">{{ n.date ? formatDate(n.date) : '' }}</div>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Aucune notification récente.</p>
                </template>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </main>
  </div>
</template>
