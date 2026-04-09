<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed, ref, onMounted, onUnmounted } from "vue";
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

// État local pour la sidebar mobile
const isMobileSidebarOpen = ref(false);

const toggleMobileSidebar = () => {
  isMobileSidebarOpen.value = !isMobileSidebarOpen.value;
};

const closeMobileSidebar = () => {
  isMobileSidebarOpen.value = false;
};

// Détecter si on est sur la page dashboard
const isDashboardPage = computed(() => {
  // Vérifier si les props de dashboard sont présentes
  const hasDashboardProps = props.stats && props.events !== undefined;
  
  // Alternative: vérifier l'URL
  const isDashboardUrl = window.location.pathname.includes('/promoter/dashboard');
  
  console.log('isDashboardPage - props:', hasDashboardProps, 'url:', isDashboardUrl);
  
  return hasDashboardProps || isDashboardUrl;
});
</script>

<template>
  <Head title="Tableau de bord" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="min-h-screen flex bg-gray-50">
    <!-- Overlay pour mobile -->
    <div 
      v-if="isMobileSidebarOpen"
      @click="closeMobileSidebar"
      class="fixed inset-0 bg-black/60 z-30 lg:hidden"
    ></div>
    
    <!-- Sidebar intégré au layout -->
    <Sidebar 
      :isMobileOpen="isMobileSidebarOpen"
      @close="closeMobileSidebar"
    />

    <!-- Contenu principal -->
    <main class="flex-1 lg:ml-64">
      <!-- Dashboard Header - Affiché sur toutes les pages -->
      <header class="sticky top-0 z-50 bg-white border-b border-gray-200 px-3 sm:px-4 md:px-6 py-3 sm:py-4">
        <div class="flex items-center justify-between gap-2">
          <div class="flex items-center gap-2 sm:gap-4 min-w-0 flex-1">
            <!-- Menu Hamburger pour mobile -->
            <button 
              @click="toggleMobileSidebar"
              class="text-gray-500 hover:text-gray-700 lg:hidden p-2 flex-shrink-0"
            >
              <i class="fas fa-bars text-lg sm:text-xl"></i>
            </button>
            <h1 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 truncate min-w-0">Tableau de bord</h1>
          </div>
          
          <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
            <!-- Recherche -->
            <div class="relative hidden sm:block">
              <input 
                type="text" 
                placeholder="Rechercher..." 
                class="w-24 sm:w-32 md:w-48 lg:w-64 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 pl-8 sm:pl-10 bg-gray-50 border border-gray-200 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
              <i class="fas fa-search absolute left-2.5 sm:left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
            </div>
            <button class="sm:hidden p-1.5 sm:p-2 text-gray-500 hover:text-gray-700 flex-shrink-0">
              <i class="fas fa-search text-sm"></i>
            </button>
            
            <!-- Profil utilisateur -->
            <div class="flex items-center gap-1.5 sm:gap-2 md:gap-3">
              <div class="text-right hidden md:block min-w-0">
                <div class="text-sm font-medium text-gray-900 truncate max-w-[100px] lg:max-w-[150px]">{{ page.props.auth.user.name }}</div>
                <div class="text-xs text-gray-500">Promoteur</div>
              </div>
              <div class="w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white text-xs sm:text-sm font-medium">{{ page.props.auth.user.name.charAt(0).toUpperCase() }}</span>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Messages flash -->
      <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4">
        <div v-if="success" class="mb-3 sm:mb-4 p-3 sm:p-4 bg-green-50 border border-green-200 rounded">
          <div class="flex items-center gap-2 sm:gap-3">
            <i class="fas fa-check-circle text-green-600 flex-shrink-0"></i>
            <p class="text-green-800 text-sm sm:text-base">{{ success }}</p>
          </div>
        </div>
        
        <div v-if="error" class="mb-3 sm:mb-4 p-3 sm:p-4 bg-red-50 border border-red-200 rounded">
          <div class="flex items-center gap-2 sm:gap-3">
            <i class="fas fa-exclamation-circle text-red-600 flex-shrink-0"></i>
            <p class="text-red-800 text-sm sm:text-base">{{ error }}</p>
          </div>
        </div>
        
        <div v-if="info" class="mb-3 sm:mb-4 p-3 sm:p-4 bg-blue-50 border border-blue-200 rounded">
          <div class="flex items-center gap-2 sm:gap-3">
            <i class="fas fa-info-circle text-blue-600 flex-shrink-0"></i>
            <p class="text-blue-800 text-sm sm:text-base">{{ info }}</p>
          </div>
        </div>
      </div>

      <!-- KPI Cards - Design professionnel épuré (uniquement sur dashboard) -->
      <div v-if="isDashboardPage" class="px-3 sm:px-4 md:px-6 pb-4 sm:pb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 mb-6">
          <!-- Total Venues -->
          <div class="bg-white rounded border border-gray-200 p-3 sm:p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-2 sm:mb-3">
              <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-50 rounded flex items-center justify-center flex-shrink-0">
                <i class="fas fa-building text-blue-600 text-xs sm:text-sm"></i>
              </div>
              <span class="text-xs font-medium text-green-600 bg-green-50 px-1.5 sm:px-2 py-1 rounded whitespace-nowrap">
                {{ props.eventsEvolution > 0 ? '+' : '' }}{{ formatNumber(props.eventsEvolution) }}%
              </span>
            </div>
            <div class="text-lg sm:text-xl font-bold text-gray-900">{{ formatNumber(props.totalVenues || 0) }}</div>
            <div class="text-xs text-gray-500 mt-1">Venues</div>
          </div>

          <!-- Active Events -->
          <div class="bg-white rounded border border-gray-200 p-3 sm:p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-2 sm:mb-3">
              <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-50 rounded flex items-center justify-center flex-shrink-0">
                <i class="fas fa-calendar-alt text-purple-600 text-xs sm:text-sm"></i>
              </div>
              <span class="text-xs font-medium text-green-600 bg-green-50 px-1.5 sm:px-2 py-1 rounded whitespace-nowrap">
                {{ props.eventsEvolution > 0 ? '+' : '' }}{{ formatNumber(props.eventsEvolution) }}%
              </span>
            </div>
            <div class="text-lg sm:text-xl font-bold text-gray-900">{{ formatNumber(props.activeEvents || 0) }}</div>
            <div class="text-xs text-gray-500 mt-1">Active</div>
          </div>

          <!-- Total Participants -->
          <div class="bg-white rounded border border-gray-200 p-3 sm:p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-2 sm:mb-3">
              <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-50 rounded flex items-center justify-center flex-shrink-0">
                <i class="fas fa-users text-indigo-600 text-xs sm:text-sm"></i>
              </div>
              <span class="text-xs font-medium text-green-600 bg-green-50 px-1.5 sm:px-2 py-1 rounded whitespace-nowrap">
                {{ props.eventsEvolution > 0 ? '+' : '' }}{{ formatNumber(props.eventsEvolution) }}%
              </span>
            </div>
            <div class="text-lg sm:text-xl font-bold text-gray-900">{{ formatNumber(props.totalParticipants || 0) }}</div>
            <div class="text-xs text-gray-500 mt-1">Participants</div>
          </div>

          <!-- Occupancy Rate -->
          <div class="bg-white rounded border border-gray-200 p-3 sm:p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-2 sm:mb-3">
              <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-50 rounded flex items-center justify-center flex-shrink-0">
                <i class="fas fa-chart-pie text-orange-600 text-xs sm:text-sm"></i>
              </div>
              <span class="text-xs font-medium text-green-600 bg-green-50 px-1.5 sm:px-2 py-1 rounded whitespace-nowrap">
                {{ props.eventsEvolution > 0 ? '+' : '' }}{{ formatNumber(props.eventsEvolution) }}%
              </span>
            </div>
            <div class="text-lg sm:text-xl font-bold text-gray-900">{{ formatNumber(props.occupancyRate || 0) }}%</div>
            <div class="text-xs text-gray-500 mt-1">Occupancy</div>
          </div>

          <!-- Estimated Revenue -->
          <div class="bg-white rounded border border-gray-200 p-3 sm:p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-2 sm:mb-3">
              <div class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-50 rounded flex items-center justify-center flex-shrink-0">
                <i class="fas fa-dollar-sign text-emerald-600 text-xs sm:text-sm"></i>
              </div>
              <span class="text-xs font-medium text-green-600 bg-green-50 px-1.5 sm:px-2 py-1 rounded whitespace-nowrap">
                {{ props.eventsEvolution > 0 ? '+' : '' }}{{ formatNumber(props.eventsEvolution) }}%
              </span>
            </div>
            <div class="text-lg sm:text-xl font-bold text-gray-900">{{ formatPrice(props.totalRevenue || 0) }}</div>
            <div class="text-xs text-gray-500 mt-1">Revenue</div>
          </div>

          <!-- Notifications -->
          <div class="bg-white rounded border border-gray-200 p-3 sm:p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-2 sm:mb-3">
              <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-50 rounded flex items-center justify-center flex-shrink-0">
                <i class="fas fa-bell text-red-600 text-xs sm:text-sm"></i>
              </div>
              <span class="text-xs font-medium text-red-600 bg-red-50 px-1.5 sm:px-2 py-1 rounded whitespace-nowrap">New</span>
            </div>
            <div class="text-lg sm:text-xl font-bold text-gray-900">{{ formatNumber(Array.isArray(props.notifications) ? props.notifications.filter(n => !n.is_read).length : 0) }}</div>
            <div class="text-xs text-gray-500 mt-1">Notifications</div>
          </div>
        </div>
      </div>

      <!-- Contenu de la page -->
      <div class="px-3 sm:px-4 md:px-6 pb-4 sm:pb-6">
        <slot />
      </div>
    </main>
  </div>
</template>
