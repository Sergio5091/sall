<script setup>
import { Head } from '@inertiajs/vue3';

import PromoterLayout from '@/Layouts/PromoterLayout.vue';
defineOptions({ layout: PromoterLayout });

const props = defineProps({
    salle: Object,
    coordinates: Object
});
</script>

<template>
  <Head title="Ma Salle" />
  
  <div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->

    <!-- Contenu principal -->
    <main class="flex-1">
      <!-- Header -->
      <header class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-semibold text-gray-900">Ma Salle</h1>
            <p class="text-gray-600 mt-1">Gérez les informations de votre salle</p>
          </div>
        </div>
      </header>

      <!-- Contenu -->
      <div class="p-6">
        <div v-if="props.salle" class="bg-white rounded border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ props.salle.nom }}</h2>
          <p class="text-gray-600">{{ props.salle.description }}</p>
        </div>
        
        <div v-else class="bg-white rounded border border-gray-200 p-6 text-center">
          <p class="text-gray-600">Vous n'avez pas encore de salle.</p>
        </div>
      </div>
    </main>
  </div>
</template>
