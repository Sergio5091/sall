<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
</script>

<template>
  <Head title="Tableau de bord Admin" />
  
  <Navbar />
  
  <div class="pt-16 min-h-screen bg-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h1 class="text-2xl font-bold text-gray-900">Tableau de bord Administrateur</h1>
          <p class="mt-2 text-gray-600">
            Panneau d'administration - Gérez l'ensemble de la plateforme.
          </p>
          
          <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Carte Utilisateurs -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <span class="material-symbols-outlined text-3xl text-cyan">people</span>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">Utilisateurs</h3>
                  <p class="text-sm text-gray-500">Gérer les utilisateurs</p>
                </div>
              </div>
            </div>
            
            <!-- Carte Salles -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <span class="material-symbols-outlined text-3xl text-cyan">storefront</span>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">Salles</h3>
                  <p class="text-sm text-gray-500">Gérer toutes les salles</p>
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
                  <p class="text-sm text-gray-500">Gérer les événements</p>
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
                  <p class="text-sm text-gray-500">Voir toutes les réservations</p>
                </div>
              </div>
            </div>
            
            <!-- Carte Parrainage -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <span class="material-symbols-outlined text-3xl text-cyan">handshake</span>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">Parrainage</h3>
                  <p class="text-sm text-gray-500">Gérer le parrainage</p>
                </div>
              </div>
            </div>
            
            <!-- Carte Statistiques -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <span class="material-symbols-outlined text-3xl text-cyan">analytics</span>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">Statistiques</h3>
                  <p class="text-sm text-gray-500">Voir les statistiques</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
