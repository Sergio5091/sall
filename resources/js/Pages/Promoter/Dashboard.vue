<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const props = defineProps({
    stats: Object,
    salles: Array,
    events: Array,
});

const formatNumber = (num) => {
    return new Intl.NumberFormat('fr-FR').format(num);
};

const formatPrice = (prix) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(prix);
};

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
  <Head title="Tableau de bord Promoteur" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="flex h-screen bg-gray-50">
    <Sidebar current-route="promoter.dashboard" />

    <main class="flex-1 overflow-y-auto">
      <div class="p-8">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
          <p class="text-gray-600 mt-2">Vue d'ensemble de vos activités</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Total événements</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ formatNumber(stats.total || 0) }}</h3>
              </div>
              <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar text-blue-600"></i>
              </div>
            </div>
          </div>
          
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Événements publiés</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ formatNumber(stats.publies || 0) }}</h3>
              </div>
              <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600"></i>
              </div>
            </div>
          </div>
          
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Brouillons</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ formatNumber(stats.brouillons || 0) }}</h3>
              </div>
              <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-alt text-orange-600"></i>
              </div>
            </div>
          </div>
          
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">À venir</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ formatNumber(stats.avenir || 0) }}</h3>
              </div>
              <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-purple-600"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-gray-900">Mes Salles</h3>
              <Link href="/promoter/venues/create" class="text-blue-600 text-sm hover:underline">
                + Ajouter
              </Link>
            </div>
            
            <div v-if="salles && salles.length > 0" class="space-y-3">
              <div v-for="salleItem in salles" :key="salleItem.id" class="p-3 border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between">
                  <h4 class="font-medium text-gray-900">{{ salleItem.nom || 'Ma Salle' }}</h4>
                  <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                    {{ salleItem.statut === 'actif' ? 'Active' : 'Inactive' }}
                  </span>
                </div>
                <p class="text-sm text-gray-600 mt-1">{{ salleItem.adresse || 'Adresse non définie' }}</p>
                <div class="flex gap-4 text-xs text-gray-500 mt-2">
                  <span><i class="fas fa-users mr-1"></i>{{ salleItem.capacite_max || '0' }} pers.</span>
                  <span><i class="fas fa-clock mr-1"></i>{{ formatPrice(salleItem.prix_heure || 0) }}/h</span>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-8">
              <i class="fas fa-store text-gray-400 text-3xl mb-3"></i>
              <p class="text-gray-600">Aucune salle</p>
              <Link href="/promoter/venues/create" class="inline-block mt-3 text-blue-600 hover:underline">
                Créer une salle
              </Link>
            </div>
          </div>
          
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-gray-900">Événements récents</h3>
              <select class="text-sm border border-gray-300 rounded px-2 py-1">
                <option>À venir</option>
                <option>Tous</option>
              </select>
            </div>
            
            <div v-if="events && events.length > 0" class="space-y-3">
              <div v-for="event in events.slice(0, 5)" :key="event.id" class="p-3 border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between">
                  <h4 class="font-medium text-gray-900">{{ event.titre }}</h4>
                  <span :class="[
                    'text-xs px-2 py-1 rounded-full',
                    event.statut === 'publie' ? 'bg-green-100 text-green-800' :
                    event.statut === 'brouillon' ? 'bg-yellow-100 text-yellow-800' :
                    'bg-gray-100 text-gray-800'
                  ]">
                    {{ event.statut === 'publie' ? 'Publié' : event.statut === 'brouillon' ? 'Brouillon' : event.statut }}
                  </span>
                </div>
                <p class="text-sm text-gray-600 mt-1">{{ formatDate(event.date_debut) }}</p>
                <div class="flex gap-2 mt-2">
                  <Link :href="`/promoter/events/${event.id}/edit`" class="text-xs text-blue-600 hover:underline">
                    Modifier
                  </Link>
                  <button class="text-xs text-red-600 hover:underline">
                    Supprimer
                  </button>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-8">
              <i class="fas fa-calendar text-gray-400 text-3xl mb-3"></i>
              <p class="text-gray-600">Aucun événement</p>
              <Link href="/promoter/events/create" class="inline-block mt-3 text-blue-600 hover:underline">
                Créer un événement
              </Link>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
