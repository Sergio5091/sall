<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import AccountManagementModal from '../../Components/Promoter/AccountManagementModal.vue';
import PromoterLayout from '@/Layouts/PromoterLayout.vue';
defineOptions({ layout: PromoterLayout });

const page = usePage();
const props = defineProps({
    stats: Object,
    events: Array,
    notifications: Array,
    unreadCount: Number,
    salles: Array,
    salle: Object,
    mainAccount: Object,
    allAccounts: Array,
    activeAccount: Object,
    isMainAccount: Boolean,
    eventsEvolution: Array,
    statusDistrib: Object,
    recentActivity: Array,
});

const success = computed(() => {
    console.log('Dashboard - Flash success:', page.props.flash?.success);
    return page.props.flash?.success;
});
const error = computed(() => {
    console.log('Dashboard - Flash error:', page.props.flash?.error);
    return page.props.flash?.error;
});
const info = computed(() => {
    console.log('Dashboard - Flash info:', page.props.flash?.info);
    return page.props.flash?.info;
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

// helpers pour les statuts d'événement (couleurs, textes)
const getStatusColor = (status) => {
    switch (status) {
        case 'publie': return 'bg-green-100 text-green-800';
        case 'brouillon': return 'bg-gray-100 text-gray-800';
        case 'annule': return 'bg-red-100 text-red-800';
        case 'termine': return 'bg-blue-100 text-blue-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};
const getStatusText = (status) => {
    switch (status) {
        case 'publie': return 'Publié';
        case 'brouillon': return 'Brouillon';
        case 'annule': return 'Annulé';
        case 'termine': return 'Terminé';
        default: return status;
    }
};

// Helpers for the events evolution chart
const maxCount = computed(() => {
    if (!props.eventsEvolution || props.eventsEvolution.length === 0) return 1;
    return Math.max(...props.eventsEvolution.map(i => i.count), 1);
});

const getBarHeight = (count) => {
    return Math.max((count / maxCount.value) * 80, 5) + '%';
};
</script>

<template>
  <Head title="Tableau de bord Promoteur" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex min-h-screen w-full bg-gray-50 font-display text-gray-800">

    <main class="flex-1 overflow-y-auto transition-all duration-300">
      <!-- Conteneur principal responsive -->
      <div class="w-full sm:max-w-full md:max-w-4xl lg:max-w-6xl xl:max-w-7xl 2xl:max-w-screen-2xl mx-auto px-3 sm:px-4 md:px-6 py-6 md:py-8">
        <!-- Messages flash -->
        <div v-if="success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
          <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-2"></i>
            <p class="text-green-800">{{ success }}</p>
          </div>
        </div>
        
        <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
          <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
            <p class="text-red-800">{{ error }}</p>
          </div>
        </div>
        
        <div v-if="info" class="mb-6 p-6 bg-blue-50 border-2 border-blue-200 rounded-lg shadow-lg">
          <div class="flex items-start">
            <div class="flex-shrink-0">
              <i class="fas fa-exclamation-triangle text-blue-600 text-xl mr-3 mt-1"></i>
            </div>
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-blue-800 mb-2">Information importante</h3>
              <p class="text-blue-700">{{ info }}</p>
              <div class="mt-4">
                <Link href="/promoter/venues/create" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                  <i class="fas fa-plus mr-2"></i>
                  Créer une salle maintenant
                </Link>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
            <p class="text-gray-600 mt-2">Vue d'ensemble de vos activités</p>
          </div>
          <div class="mt-4 md:mt-0">
            <AccountManagementModal
              :main-account="mainAccount"
              :all-accounts="allAccounts"
              :active-account="activeAccount"
              :is-main-account="isMainAccount"
            />
          </div>
        </div>

        <!-- Conteneur principal avec grille unifiée -->
        <div class="grid grid-cols-12 gap-6">
          <!-- KPI Cards -->
          <div class="col-span-12 xs:col-span-6 sm:col-span-6 md:col-span-6 lg:col-span-3 xl:col-span-3 bg-white dark:bg-slate-850 p-4 md:p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:shadow-md transition-all">
            <div>
              <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Événements totaux</p>
              <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ stats?.total || 0 }}</h3>
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
              <i class="fas fa-calendar text-sm md:text-base"></i>
            </div>
          </div>
          
          <div class="col-span-12 xs:col-span-6 sm:col-span-6 md:col-span-6 lg:col-span-3 xl:col-span-3 bg-white dark:bg-slate-850 p-4 md:p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:shadow-md transition-all">
            <div>
              <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Événements publiés</p>
              <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ stats?.publies || 0 }}</h3>
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 flex items-center justify-center">
              <i class="fas fa-check-circle text-sm md:text-base"></i>
            </div>
          </div>
          
          <div class="col-span-12 xs:col-span-6 sm:col-span-6 md:col-span-6 lg:col-span-3 xl:col-span-3 bg-white dark:bg-slate-850 p-4 md:p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-orange-500"></div>
            <div class="pl-2">
              <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Brouillons</p>
              <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ stats?.brouillons || 0 }}</h3>
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 flex items-center justify-center">
              <i class="fas fa-file-alt text-sm md:text-base"></i>
            </div>
          </div>
          
          <div class="col-span-12 xs:col-span-6 sm:col-span-6 md:col-span-6 lg:col-span-3 xl:col-span-3 bg-white dark:bg-slate-850 p-4 md:p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:shadow-md transition-all">
            <div>
              <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Mes salles</p>
              <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ stats?.total_salles || 0 }}</h3>
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
              <i class="fas fa-building text-sm md:text-base"></i>
            </div>
          </div>

          <!-- Line chart for events evolution -->
          <div class="col-span-12 lg:col-span-7 bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-lg font-bold text-slate-800 dark:text-white">Évolution des événements</h3>
            </div>
            <div class="h-64 w-full flex items-end justify-between gap-2 px-2 pb-2 relative">
              <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                <div class="border-t border-slate-100 dark:border-slate-800 w-full h-px"></div>
                <div class="border-t border-slate-100 dark:border-slate-800 w-full h-px"></div>
                <div class="border-t border-slate-100 dark:border-slate-800 w-full h-px"></div>
                <div class="border-t border-slate-100 dark:border-slate-800 w-full h-px"></div>
              </div>
              <div 
                v-for="(item, index) in props.eventsEvolution || []" 
                :key="index"
                class="w-full bg-primary/10 rounded-t-sm hover:bg-primary/20 transition-all relative group cursor-pointer" 
                :style="{ height: getBarHeight(item.count) }"
                :title="item.date"
              >
                <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded">{{ item.count }}</div>
              </div>
            </div>
            <div class="flex justify-between text-xs text-slate-400 mt-2 px-1">
              <span v-for="(item, index) in props.eventsEvolution || []" :key="index">{{ item.date }}</span>
            </div>
          </div>

          <!-- Donut chart for status distribution -->
          <div class="col-span-12 lg:col-span-5 bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-6">Répartition des statuts</h3>
            <div class="flex items-center justify-center flex-1">
              <div 
                class="relative w-48 h-48 rounded-full" 
                :style="statusDonutStyle"
              >
                <div class="absolute inset-4 bg-white dark:bg-slate-850 rounded-full flex items-center justify-center">
                  <div class="text-center">
                    <span class="block text-3xl font-bold text-slate-800 dark:text-white">{{ stats?.total || 0 }}</span>
                    <span class="text-xs text-slate-500 uppercase tracking-wide">Total</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-6 flex justify-center gap-6">
              <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                <span class="text-sm text-slate-600 dark:text-slate-400">Publiés ({{ stats?.publies || 0 }})</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                <span class="text-sm text-slate-600 dark:text-slate-400">Brouillons ({{ stats?.brouillons || 0 }})</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                <span class="text-sm text-slate-600 dark:text-slate-400">Annulés ({{ stats?.annules || 0 }})</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                <span class="text-sm text-slate-600 dark:text-slate-400">Terminés ({{ stats?.passes || 0 }})</span>
              </div>
            </div>
          </div>

          <!-- Section C: Recent Activity -->
          <div class="col-span-12 bg-white dark:bg-slate-850 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
              <h3 class="text-lg font-bold text-slate-800 dark:text-white">Activité Récente</h3>
              <Link href="/promoter/events" class="text-primary text-sm font-medium hover:underline">Voir tout</Link>
            </div>
            <div class="p-0">
              <div 
                v-for="(activity, index) in props.recentActivity || []" 
                :key="index"
                class="flex items-start gap-4 p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-50 dark:border-slate-800/50"
                :class="{ 'border-b-0': index === (props.recentActivity?.length || 0) - 1 }"
              >
                <div 
                  class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                  :class="{
                    'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400': activity.color === 'green',
                    'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400': activity.color === 'blue',
                    'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400': activity.color === 'orange',
                    'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400': activity.color === 'purple'
                  }"
                >
                  <i :class="activity.icon"></i>
                </div>
                <div class="flex-1">
                  <div class="flex items-center justify-between mb-1">
                    <p class="text-sm font-medium text-slate-800 dark:text-white">{{ activity.title }}</p>
                    <span class="text-xs text-slate-400">{{ activity.time }}</span>
                  </div>
                  <p class="text-sm text-slate-500 dark:text-slate-400">{{ activity.description }}</p>
                </div>
              </div>
              <div v-if="props.recentActivity?.length === 0" class="p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                  <i class="fas fa-inbox text-slate-400 text-xl"></i>
                </div>
                <p class="text-slate-500 dark:text-slate-400">Aucune activité récente</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
