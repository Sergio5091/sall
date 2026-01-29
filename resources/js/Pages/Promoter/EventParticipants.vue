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
            <div v-for="participant in participants" :key="participant.id" class="hover:bg-gray-50 transition-colors">
              <!-- Carte participant -->
              <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                  <!-- Profil principal -->
                  <div class="flex items-start space-x-4">
                    <!-- Avatar amélioré -->
                    <div class="relative">
                      <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        {{ participant.user.name ? participant.user.name.charAt(0).toUpperCase() : (participant.nom ? participant.nom.charAt(0).toUpperCase() : 'U') }}
                      </div>
                      <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                        <i class="fas fa-check text-white text-xs"></i>
                      </div>
                    </div>
                    
                    <!-- Informations complètes -->
                    <div class="flex-1">
                      <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-xl font-bold text-gray-900">
                          {{ participant.user.name || participant.nom || 'Nom non spécifié' }}
                        </h3>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                          Participant #{{ participant.id }}
                        </span>
                      </div>
                      
                      <!-- Contact principal -->
                      <div class="space-y-2 mb-3">
                        <!-- Email depuis inscription -->
                        <div v-if="participant.email" class="flex items-center text-sm">
                          <i class="fas fa-envelope w-5 text-blue-500 mr-3"></i>
                          <span class="text-gray-700 font-medium">{{ participant.email }}</span>
                          <span class="ml-2 text-xs text-gray-500">(formulaire)</span>
                        </div>
                        
                        <!-- Email depuis user -->
                        <div v-else-if="participant.user.email" class="flex items-center text-sm">
                          <i class="fas fa-envelope w-5 text-blue-500 mr-3"></i>
                          <span class="text-gray-700 font-medium">{{ participant.user.email }}</span>
                          <span class="ml-2 text-xs text-gray-500">(compte)</span>
                        </div>
                        
                        <!-- WhatsApp -->
                        <div v-if="participant.whatsapp" class="flex items-center text-sm">
                          <i class="fab fa-whatsapp w-5 text-green-500 mr-3"></i>
                          <span class="text-gray-700 font-medium">{{ participant.whatsapp }}</span>
                          <button class="ml-2 text-green-600 hover:text-green-800 text-xs underline">
                            <i class="fas fa-external-link-alt mr-1"></i>Ouvrir WhatsApp
                          </button>
                          <span class="ml-2 text-xs text-gray-500">(formulaire)</span>
                        </div>
                        
                        <!-- Téléphone depuis user -->
                        <div v-else-if="participant.user.telephone" class="flex items-center text-sm">
                          <i class="fas fa-phone w-5 text-gray-500 mr-3"></i>
                          <span class="text-gray-700 font-medium">{{ participant.user.telephone }}</span>
                          <span class="ml-2 text-xs text-gray-500">(compte)</span>
                        </div>
                        
                        <!-- Message si pas de contact -->
                        <div v-if="!participant.email && !participant.user.email && !participant.whatsapp && !participant.user.telephone" class="flex items-center text-sm text-orange-600">
                          <i class="fas fa-exclamation-triangle w-5 text-orange-500 mr-3"></i>
                          <span class="font-medium">Aucun contact disponible</span>
                        </div>
                      </div>
                      
                      <!-- Informations système -->
                      <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span><i class="fas fa-user mr-1"></i>Utilisateur: {{ participant.user.id || 'N/A' }}</span>
                        <span><i class="fas fa-calendar mr-1"></i>Inscription: {{ participant.date_inscription }}</span>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Statuts et actions -->
                  <div class="text-right space-y-3">
                    <!-- Statut inscription uniquement -->
                    <div>
                      <div class="text-xs text-gray-500 mb-1">Statut inscription</div>
                      <div class="px-3 py-2 rounded-lg text-sm font-bold inline-block"
                           :class="participant.statut === 'confirmé' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200'">
                        <i :class="participant.statut === 'confirmé' ? 'fas fa-check-circle' : 'fas fa-clock'" class="mr-1"></i>
                        {{ participant.statut }}
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Actions détaillées -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                      <button class="flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        Envoyer un email
                      </button>
                      <button class="flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-phone mr-2"></i>
                        Appeler
                      </button>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                      <button class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors" title="Voir le profil">
                        <i class="fas fa-user-circle"></i>
                      </button>
                      <button class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
