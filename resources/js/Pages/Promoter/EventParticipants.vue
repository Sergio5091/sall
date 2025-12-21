<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const props = defineProps({
    event: Object,
    participants: Array
});
</script>

<template>
  <Head title="Participants - {{ event.titre }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="flex h-screen bg-gray-50">
    <Sidebar current-route="promoter.events" />

    <main class="flex-1 overflow-y-auto lg:ml-64">
      <div class="p-8">
        <!-- Header -->
        <div class="mb-8">
          <div class="flex items-center mb-4">
            <Link href="/promoter/events" class="text-blue-600 hover:text-blue-800 mr-3">
              <i class="fas fa-arrow-left"></i> Retour aux événements
            </Link>
          </div>
          <h1 class="text-3xl font-bold text-gray-900">Participants à l'événement</h1>
          <p class="text-gray-600 mt-2">{{ event.titre }}</p>
        </div>

        <!-- Statistiques -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Statistiques</h3>
              <p class="text-gray-600">Total des participants: {{ participants.length }}</p>
            </div>
            <div class="text-right">
              <div class="text-2xl font-bold text-blue-600">{{ participants.length }}</div>
              <div class="text-sm text-gray-500">Personnes inscrites</div>
            </div>
          </div>
        </div>

        <!-- Liste des participants -->
        <div class="bg-white rounded-lg shadow-sm">
          <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Liste des participants</h2>
          </div>
          
          <div v-if="participants.length === 0" class="p-8 text-center">
            <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Aucun participant pour le moment</p>
            <p class="text-sm text-gray-400 mt-2">Les participants apparaîtront ici dès que des personnes s'inscriront à votre événement.</p>
          </div>
          
          <div v-else class="divide-y divide-gray-200">
            <!-- Les participants seront affichés ici quand la fonctionnalité sera complète -->
            <div class="p-4 text-center text-gray-500">
              <i class="fas fa-cog fa-spin mr-2"></i>
              Chargement des participants...
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
