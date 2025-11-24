<script setup>
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import Navbar from '@/Components/Navbar.vue';
</script>

<template>
  <Head title="Tableau de bord Promoteur" />
  
  <Navbar />
  
  <div class="pt-16 min-h-screen bg-green-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h1 class="text-2xl font-bold text-gray-900">Tableau de bord Promoteur</h1>
          <p class="mt-2 text-gray-600">
            Bienvenue dans votre espace promoteur. Gérez vos salles et événements ici.
          </p>
          
          <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Carte Salles -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <span class="material-symbols-outlined text-3xl text-cyan">storefront</span>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">Mes Salles</h3>
                  <p class="text-sm text-gray-500">Gérer vos salles</p>
                </div>
              </div>
            </div>
            
            <!-- Carte Événements -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <span class="material-symbols-outlined text-3xl text-cyan">event</span>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">Événements</h3>
                  <p class="text-sm text-gray-500">Créer et gérer des événements</p>
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
                  <h3 class="text-lg font-medium text-gray-900">Réservations</h3>
                  <p class="text-sm text-gray-500">Voir les réservations</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
