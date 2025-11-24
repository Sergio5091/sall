<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
</script>

<template>
  <Head title="Tableau de bord Client" />
  
  <Navbar />
  
  <div class="pt-16 min-h-screen bg-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h1 class="text-2xl font-bold text-gray-900">Tableau de bord Client</h1>
          <p class="mt-2 text-gray-600">
            Bienvenue dans votre espace client. Découvrez et réservez des salles de jeu.
          </p>
          
          <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Carte Rechercher -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <span class="material-symbols-outlined text-3xl text-cyan">search</span>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">Rechercher</h3>
                  <p class="text-sm text-gray-500">Trouver des salles</p>
                </div>
              </div>
            </div>
            
            <!-- Carte Réservations -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <span class="material-symbols-outlined text-3xl text-cyan">calendar_month</span>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">Mes Réservations</h3>
                  <p class="text-sm text-gray-500">Voir vos réservations</p>
                </div>
              </div>
            </div>
            
            <!-- Carte Parrainage -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <span class="material-symbols-outlined text-3xl text-cyan">people</span>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">Parrainage</h3>
                  <p class="text-sm text-gray-500">Parrainez vos amis</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
