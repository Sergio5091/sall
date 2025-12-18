<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainNavbar from '../Components/MainNavbar.vue';

const props = defineProps({
    event: Object
});

// Formater les dates
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatDateOnly = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

// Obtenir la couleur du statut
const getStatusColor = (status) => {
    switch (status) {
        case 'upcoming': return 'bg-green-100 text-green-800';
        case 'ongoing': return 'bg-yellow-100 text-yellow-800';
        case 'completed': return 'bg-blue-100 text-blue-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

// Obtenir le texte du statut
const getStatusText = (status) => {
    switch (status) {
        case 'upcoming': return 'À venir';
        case 'ongoing': return 'En cours';
        case 'completed': return 'Terminé';
        case 'cancelled': return 'Annulé';
        default: return status;
    }
};

// Calculer les places restantes
const placesRestantes = () => {
    if (props.event.max_participants && props.event.current_participants !== null) {
        return Math.max(0, props.event.max_participants - props.event.current_participants);
    }
    return null;
};

// Calculer le pourcentage de remplissage
const pourcentageRemplissage = () => {
    if (props.event.max_participants && props.event.current_participants !== null) {
        return Math.round((props.event.current_participants / props.event.max_participants) * 100);
    }
    return 0;
};
</script>

<template>
  <Head :title="event.name" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="min-h-screen bg-gray-50">
    <MainNavbar />
    
    <!-- Hero Section with Event Image -->
    <div class="relative h-96 bg-gray-900">
      <img 
        :src="event.image || '/images/default-event.jpg'" 
        :alt="event.name"
        class="w-full h-full object-cover opacity-70"
      >
      <div class="absolute inset-0 bg-black/70"></div>
      
      <!-- Back Button -->
      <div class="absolute top-4 left-4 z-10">
        <Link 
          href="/events"
          class="flex items-center gap-2 px-4 py-2 bg-white/90 backdrop-blur-sm rounded-lg hover:bg-white transition-colors"
        >
          <i class="fas fa-arrow-left"></i>
          <span>Retour aux événements</span>
        </Link>
      </div>
      
      <!-- Event Title Overlay -->
      <div class="absolute bottom-8 left-8 right-8 z-10">
        <div class="flex flex-wrap items-center gap-4">
          <h1 class="text-4xl md:text-5xl font-bold text-white">{{ event.name }}</h1>
          <span 
            :class="[
              'px-4 py-2 rounded-full text-sm font-bold',
              getStatusColor(event.status)
            ]"
          >
            {{ getStatusText(event.status) }}
          </span>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Main Event Info (2/3) -->
        <div class="lg:col-span-2 space-y-8">
          
          <!-- Description -->
          <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
            <div class="prose prose-lg max-w-none text-gray-600" v-html="event.description"></div>
          </div>

          <!-- Event Details Grid -->
          <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Informations sur l'événement</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              
              <!-- Date et heure -->
              <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                  <i class="fas fa-calendar-alt text-blue-600"></i>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900">Date et heure</h3>
                  <p class="text-gray-600">{{ formatDate(event.date) }}</p>
                  <p v-if="event.date_fin" class="text-gray-500 text-sm">Fin : {{ formatDate(event.date_fin) }}</p>
                </div>
              </div>

              <!-- Prix -->
              <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                  <i class="fas fa-tag text-green-600"></i>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900">Tarif</h3>
                  <p v-if="event.gratuit" class="text-gray-600 font-semibold text-green-600">Gratuit</p>
                  <div v-else class="space-y-1">
                    <p class="text-gray-600 font-semibold">{{ event.prize_pool }} {{ event.prix_base ? 'FCFA' : '' }}</p>
                    <p v-if="event.prix_vip" class="text-gray-500 text-sm">VIP : {{ event.prix_vip }} FCFA</p>
                  </div>
                </div>
              </div>

              <!-- Participants -->
              <div v-if="event.max_participants" class="flex items-start gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                  <i class="fas fa-users text-purple-600"></i>
                </div>
                <div class="flex-1">
                  <h3 class="font-semibold text-gray-900">Participants</h3>
                  <div class="space-y-2">
                    <p class="text-gray-600">{{ event.current_participants }} / {{ event.max_participants }} places</p>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div 
                        class="bg-purple-600 h-2 rounded-full transition-all duration-300"
                        :style="{ width: pourcentageRemplissage() + '%' }"
                      ></div>
                    </div>
                    <p v-if="placesRestantes() > 0" class="text-sm text-gray-500">{{ placesRestantes() }} places disponibles</p>
                    <p v-else class="text-sm text-red-500">Complet</p>
                  </div>
                </div>
              </div>

              <!-- Type -->
              <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                  <i class="fas fa-gamepad text-orange-600"></i>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900">Type d'événement</h3>
                  <p class="text-gray-600 capitalize">{{ event.category || 'Non spécifié' }}</p>
                </div>
              </div>

              <!-- Date limite d'inscription -->
              <div v-if="event.date_limite_inscription" class="flex items-start gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                  <i class="fas fa-clock text-red-600"></i>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900">Date limite d'inscription</h3>
                  <p class="text-gray-600">{{ formatDateOnly(event.date_limite_inscription) }}</p>
                </div>
              </div>

              <!-- Contact -->
              <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                  <i class="fas fa-envelope text-indigo-600"></i>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900">Contact</h3>
                  <p class="text-gray-600">{{ event.contact_email }}</p>
                  <p v-if="event.contact_telephone" class="text-gray-500">{{ event.contact_telephone }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar (1/3) -->
        <div class="space-y-6">
          
          <!-- Venue Information -->
          <div v-if="event.salle" class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="relative h-48">
              <img 
                :src="event.salle.image_url || '/images/default-venue.jpg'" 
                :alt="event.salle.nom"
                class="w-full h-full object-cover"
              >
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
              <div class="absolute bottom-4 left-4 right-4">
                <h3 class="text-xl font-bold text-white">{{ event.salle.nom }}</h3>
              </div>
            </div>
            
            <div class="p-6">
              <h4 class="font-semibold text-gray-900 mb-4">Informations sur la salle</h4>
              
              <div class="space-y-3">
                <!-- Description -->
                <div v-if="event.salle.description" class="flex items-start gap-3">
                  <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                  <p class="text-gray-600 text-sm">{{ event.salle.description }}</p>
                </div>
                
                <!-- Adresse -->
                <div class="flex items-start gap-3">
                  <i class="fas fa-map-marker-alt text-red-500 mt-1"></i>
                  <div>
                    <p class="text-gray-600">{{ event.salle.adresse }}</p>
                    <p class="text-gray-500 text-sm">{{ event.salle.ville }}, {{ event.salle.pays }}</p>
                  </div>
                </div>
                
                <!-- Capacité -->
                <div v-if="event.salle.capacite" class="flex items-start gap-3">
                  <i class="fas fa-users text-purple-500 mt-1"></i>
                  <p class="text-gray-600">Capacité : {{ event.salle.capacite }} personnes</p>
                </div>
                
                <!-- Promoter -->
                <div v-if="event.salle.promoter" class="flex items-start gap-3">
                  <i class="fas fa-user-tie text-green-500 mt-1"></i>
                  <div>
                    <p class="text-gray-600">Organisé par : {{ event.salle.promoter.name }}</p>
                    <p class="text-gray-500 text-sm">{{ event.salle.promoter.email }}</p>
                  </div>
                </div>
              </div>
              
              <!-- Action Button -->
              <div class="mt-6">
                <Link 
                  :href="`/venues/${event.salle.id}`"
                  class="w-full text-center px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                >
                  <i class="fas fa-building mr-2"></i>
                  Voir la salle
                </Link>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Actions</h3>
            <div class="space-y-3">
              <button 
                v-if="event.status === 'upcoming' && placesRestantes() > 0"
                class="w-full px-4 py-3 bg-brand-red text-white font-medium rounded-lg hover:bg-brand-red/90 transition-colors"
              >
                <i class="fas fa-ticket-alt mr-2"></i>
                S'inscrire à l'événement
              </button>
              
              <button 
                v-else-if="placesRestantes() === 0"
                disabled
                class="w-full px-4 py-3 bg-gray-300 text-gray-500 font-medium rounded-lg cursor-not-allowed"
              >
                <i class="fas fa-times-circle mr-2"></i>
                Événement complet
              </button>
              
              <button 
                v-else
                disabled
                class="w-full px-4 py-3 bg-gray-300 text-gray-500 font-medium rounded-lg cursor-not-allowed"
              >
                <i class="fas fa-lock mr-2"></i>
                Inscriptions closes
              </button>
              
              <button class="w-full px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-share-alt mr-2"></i>
                Partager l'événement
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
