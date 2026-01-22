<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import Sidebar from '../Components/Promoter/Sidebar.vue';

const page = usePage();
const props = defineProps({
    stats: Object,
    venues: Array,
    events: Array,
    reservations: Array,
    notifications: Array,
    totalVenues: Number,
    totalRevenue: Number,
    totalParticipants: Number,
    occupancyRate: Number,
    occupationByDay: Array,
    categoryTrends: Object,
    eventsEvolution: Number,
});

const success = computed(() => page.props.flash?.success);
const error = computed(() => page.props.flash?.error);
const info = computed(() => page.props.flash?.info);

const formatNumber = (num) => {
    if (num === null || num === undefined) return '0';
    return new Intl.NumberFormat('fr-FR').format(num);
};

const formatPrice = (prix) => {
    if (!prix) return '0 XOF';
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(prix);
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};
</script>

<template>
  <Head title="Tableau de bord" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="min-h-screen bg-gray-50">
    <!-- Contenu principal (sans sidebar - géré par les pages individuelles) -->
    <main class="flex-1">
      <!-- Dashboard Header - Affiché sur toutes les pages -->
      <header class="sticky top-0 z-10 bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <h1 class="text-2xl font-semibold text-gray-900">Tableau de bord</h1>
          </div>
          
          <div class="flex items-center gap-4">
            <!-- Recherche -->
            <div class="relative">
              <input 
                type="text" 
                placeholder="Rechercher..." 
                class="w-64 px-4 py-2 pl-10 bg-gray-50 border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
              <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            
            <!-- Profil utilisateur -->
            <div class="flex items-center gap-3">
              <div class="text-right">
                <div class="text-sm font-medium text-gray-900">{{ page.props.auth.user.name }}</div>
                <div class="text-xs text-gray-500">Promoteur</div>
              </div>
              <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                <span class="text-white font-medium">{{ page.props.auth.user.name.charAt(0).toUpperCase() }}</span>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Messages flash -->
      <div class="px-6 py-4">
        <div v-if="success" class="mb-4 p-4 bg-green-50 border border-green-200 rounded">
          <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3"></i>
            <p class="text-green-800">{{ success }}</p>
          </div>
        </div>
        
        <div v-if="error" class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
          <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
            <p class="text-red-800">{{ error }}</p>
          </div>
        </div>
        
        <div v-if="info" class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded">
          <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-600 mr-3"></i>
            <p class="text-blue-800">{{ info }}</p>
          </div>
        </div>
      </div>

      <!-- KPI Cards - Design professionnel épuré -->
      <div class="px-6 pb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 mb-6">
          <!-- Total Venues -->
          <div class="bg-white rounded border border-gray-200 p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-3">
              <div class="w-10 h-10 bg-blue-50 rounded flex items-center justify-center">
                <i class="fas fa-building text-blue-600 text-sm"></i>
              </div>
              <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">
                {{ props.eventsEvolution > 0 ? '+' : '' }}{{ formatNumber(props.eventsEvolution) }}%
              </span>
            </div>
            <div class="text-xl font-bold text-gray-900">{{ formatNumber(props.totalVenues || 0) }}</div>
            <div class="text-xs text-gray-500 mt-1">Venues</div>
          </div>

          <!-- Active Events -->
          <div class="bg-white rounded border border-gray-200 p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-3">
              <div class="w-10 h-10 bg-purple-50 rounded flex items-center justify-center">
                <i class="fas fa-calendar-alt text-purple-600 text-sm"></i>
              </div>
              <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">
                {{ props.eventsEvolution > 0 ? '+' : '' }}{{ formatNumber(props.eventsEvolution) }}%
              </span>
            </div>
            <div class="text-xl font-bold text-gray-900">{{ formatNumber(props.stats?.en_cours || 0) }}</div>
            <div class="text-xs text-gray-500 mt-1">Events</div>
          </div>

          <!-- Participants -->
          <div class="bg-white rounded border border-gray-200 p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-3">
              <div class="w-10 h-10 bg-green-50 rounded flex items-center justify-center">
                <i class="fas fa-users text-green-600 text-sm"></i>
              </div>
              <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-1 rounded">
                {{ props.eventsEvolution > 0 ? '+' : '' }}{{ formatNumber(props.eventsEvolution) }}%
              </span>
            </div>
            <div class="text-xl font-bold text-gray-900">{{ formatNumber(props.totalParticipants || 0) }}</div>
            <div class="text-xs text-gray-500 mt-1">Participants</div>
          </div>

          <!-- Occupancy Rate -->
          <div class="bg-white rounded border border-gray-200 p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-3">
              <div class="w-10 h-10 bg-orange-50 rounded flex items-center justify-center">
                <i class="fas fa-chart-pie text-orange-600 text-sm"></i>
              </div>
              <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">
                {{ props.eventsEvolution > 0 ? '+' : '' }}{{ formatNumber(props.eventsEvolution) }}%
              </span>
            </div>
            <div class="text-xl font-bold text-gray-900">{{ formatNumber(props.occupancyRate || 0) }}%</div>
            <div class="text-xs text-gray-500 mt-1">Occupancy</div>
          </div>

          <!-- Estimated Revenue -->
          <div class="bg-white rounded border border-gray-200 p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-3">
              <div class="w-10 h-10 bg-emerald-50 rounded flex items-center justify-center">
                <i class="fas fa-dollar-sign text-emerald-600 text-sm"></i>
              </div>
              <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">
                {{ props.eventsEvolution > 0 ? '+' : '' }}{{ formatNumber(props.eventsEvolution) }}%
              </span>
            </div>
            <div class="text-xl font-bold text-gray-900">{{ formatPrice(props.totalRevenue || 0) }}</div>
            <div class="text-xs text-gray-500 mt-1">Revenue</div>
          </div>

          <!-- Notifications -->
          <div class="bg-white rounded border border-gray-200 p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-3">
              <div class="w-10 h-10 bg-red-50 rounded flex items-center justify-center">
                <i class="fas fa-bell text-red-600 text-sm"></i>
              </div>
              <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded">New</span>
            </div>
            <div class="text-xl font-bold text-gray-900">{{ formatNumber(props.notifications?.filter(n => !n.is_read).length || 0) }}</div>
            <div class="text-xs text-gray-500 mt-1">Notifications</div>
          </div>
        </div>
      </div>

      <!-- Contenu de la page -->
      <div class="px-6 pb-6">
        <slot />
      </div>
    </main>
  </div>
</template>
