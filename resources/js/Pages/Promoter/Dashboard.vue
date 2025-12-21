<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const page = usePage();
const props = defineProps({
    stats: Object,
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
</script>

<template>
  <Head title="Tableau de bord Promoteur" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex min-h-screen w-full bg-gray-50 font-display text-gray-800">
    <Sidebar current-route="promoter.dashboard" />

    <main class="flex-1 overflow-y-auto transition-all duration-300 lg:ml-64">
      <div class="p-8">
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

        <!-- Actions rapides -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <Link 
              href="/promoter/venues/create"
              class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
              <i class="fas fa-plus mr-2"></i> Créer une salle
            </Link>
            <Link 
              href="/promoter/events/create"
              class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700"
            >
              <i class="fas fa-calendar-plus mr-2"></i> Créer un événement
            </Link>
            <Link 
              href="/promoter/profile"
              class="flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700"
            >
              <i class="fas fa-user mr-2"></i> Mon profil
            </Link>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
