<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    salle: Object,
    sallesSimilaires: Array
});

const currentImage = ref(0);

// Formater le prix
const formatPrice = (prix) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(prix);
};

// Formater la capacité
const formatCapacity = (capacite) => {
    return new Intl.NumberFormat('fr-FR').format(capacite);
};

// Navigation des images
const nextImage = () => {
    if (props.salle.images && props.salle.images.length > 0) {
        currentImage.value = (currentImage.value + 1) % props.salle.images.length;
    }
};

const prevImage = () => {
    if (props.salle.images && props.salle.images.length > 0) {
        currentImage.value = currentImage.value === 0 ? props.salle.images.length - 1 : currentImage.value - 1;
    }
};

// Obtenir l'image actuelle
const getCurrentImage = () => {
    if (props.salle.images && props.salle.images.length > 0) {
        const image = props.salle.images[currentImage.value];
        return image.startsWith('http') ? image : `/storage/${image}`;
    }
    return props.salle.image_url ? (props.salle.image_url.startsWith('http') ? props.salle.image_url : `/storage/${props.salle.image_url}`) : null;
};
</script>

<template>
  <Head :title="`${salle.nom} - GameOn`" />
  
  <div class="min-h-screen bg-gray-50">
    <!-- Header Public -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center backdrop-blur-sm shadow-sm bg-white/90">
      <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-2 text-gray-800">
            <i class="fas fa-gamepad text-3xl text-gray-400"></i>
            <h2 class="text-gray-800 text-2xl font-display font-bold">GameOn</h2>
          </div>
          <nav class="hidden md:flex space-x-8">
            <a href="/" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Accueil</a>
            <a href="/search/rooms" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Salles</a>
            <a href="/events" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Événements</a>
          </nav>
        </div>
        <div class="flex items-center gap-3">
          <a href="/login" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-200 text-gray-800 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-300 transition-colors">
            <span class="truncate">Connexion</span>
          </a>
          <a href="/register" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-800 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-700 transition-all">
            <span class="truncate">S'inscrire</span>
          </a>
        </div>
      </div>
    </header>

    <!-- Contenu principal -->
    <main class="pt-20">
      <!-- Galerie d'images -->
      <div class="relative h-96 bg-gray-200 overflow-hidden">
        <div v-if="getCurrentImage()" class="relative h-full">
          <img 
            :src="getCurrentImage()" 
            :alt="salle.nom"
            class="w-full h-full object-cover"
          >
          
          <!-- Navigation des images -->
          <div v-if="salle.images && salle.images.length > 1" class="absolute inset-0 flex items-center justify-between p-4">
            <button 
              @click="prevImage"
              class="bg-black/50 text-white p-2 rounded-full hover:bg-black/70 transition-colors"
            >
              <i class="fas fa-chevron-left"></i>
            </button>
            <button 
              @click="nextImage"
              class="bg-black/50 text-white p-2 rounded-full hover:bg-black/70 transition-colors"
            >
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
          
          <!-- Indicateur d'images -->
          <div v-if="salle.images && salle.images.length > 1" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
            <div 
              v-for="(image, index) in salle.images" 
              :key="index"
              class="w-2 h-2 rounded-full transition-colors"
              :class="index === currentImage ? 'bg-white' : 'bg-white/50'"
            ></div>
          </div>
        </div>
        <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
          <i class="fas fa-gamepad text-6xl"></i>
        </div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Colonne principale -->
          <div class="lg:col-span-2">
            <!-- Informations de la salle -->
            <div class="bg-white rounded-xl p-6 mb-8 shadow-sm">
              <div class="flex justify-between items-start mb-4">
                <h1 class="text-3xl font-bold text-gray-900">{{ salle.nom }}</h1>
                <div class="flex items-center gap-2">
                  <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                    Disponible
                  </span>
                </div>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="flex items-center text-gray-600">
                  <i class="fas fa-map-marker-alt mr-3 text-red-600"></i>
                  <div>
                    <div class="font-medium">{{ salle.ville }}, {{ salle.pays }}</div>
                    <div class="text-sm">{{ salle.adresse }}</div>
                  </div>
                </div>
                
                <div class="flex items-center text-gray-600">
                  <i class="fas fa-user mr-3 text-red-600"></i>
                  <div>
                    <div class="font-medium">Propriétaire</div>
                    <div class="text-sm">{{ salle.promoter?.name || 'Non spécifié' }}</div>
                  </div>
                </div>
              </div>

              <div class="prose max-w-none text-gray-600 mb-8">
                <p>{{ salle.description || 'Salle de gaming moderne équipée du meilleur matériel pour vos sessions de gaming.' }}</p>
              </div>

              <!-- Équipements et caractéristiques -->
              <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Équipements et services</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-users text-2xl text-red-600 mb-2"></i>
                    <div class="font-medium text-gray-900">{{ formatCapacity(salle.capacite_max) }}</div>
                    <div class="text-sm text-gray-500">Places</div>
                  </div>
                  
                  <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-wifi text-2xl text-red-600 mb-2"></i>
                    <div class="font-medium text-gray-900">WiFi</div>
                    <div class="text-sm text-gray-500">Inclus</div>
                  </div>
                  
                  <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-parking text-2xl text-red-600 mb-2"></i>
                    <div class="font-medium text-gray-900">Parking</div>
                    <div class="text-sm text-gray-500">Disponible</div>
                  </div>
                  
                  <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-shield-alt text-2xl text-red-600 mb-2"></i>
                    <div class="font-medium text-gray-900">Sécurisé</div>
                    <div class="text-sm text-gray-500">24/7</div>
                  </div>
                </div>
              </div>

              <!-- Mini galerie d'images -->
              <div v-if="salle.images && salle.images.length > 1" class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Galerie</h3>
                <div class="grid grid-cols-4 gap-2">
                  <div 
                    v-for="(image, index) in salle.images" 
                    :key="index"
                    @click="currentImage = index"
                    class="cursor-pointer rounded-lg overflow-hidden aspect-square"
                  >
                    <img 
                      :src="image.startsWith('http') ? image : `/storage/${image}`" 
                      :alt="`${salle.nom} - Image ${index + 1}`"
                      class="w-full h-full object-cover hover:scale-110 transition-transform"
                    >
                  </div>
                </div>
              </div>

              <!-- Événements à venir -->
              <div v-if="salle.evenements && salle.evenements.length > 0" class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Événements à venir</h3>
                <div class="space-y-4">
                  <div 
                    v-for="event in salle.evenements" 
                    :key="event.id"
                    class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
                  >
                    <div class="flex justify-between items-start">
                      <div class="flex-1">
                        <h4 class="font-medium text-gray-900 mb-2">{{ event.titre }}</h4>
                        <p class="text-sm text-gray-600 mb-2">{{ event.description }}</p>
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                          <div class="flex items-center gap-1">
                            <i class="fas fa-calendar"></i>
                            {{ new Date(event.date_debut).toLocaleDateString('fr-FR') }}
                          </div>
                          <div class="flex items-center gap-1">
                            <i class="fas fa-clock"></i>
                            {{ new Date(event.date_debut).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                          </div>
                          <div class="flex items-center gap-1">
                            <i class="fas fa-users"></i>
                            {{ event.capacite_max || 'Illimité' }}
                          </div>
                        </div>
                      </div>
                      <div class="ml-4">
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                          {{ event.statut }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Salles similaires -->
            <div v-if="sallesSimilaires && sallesSimilaires.length > 0">
              <h3 class="text-xl font-bold text-gray-900 mb-4">Salles similaires</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div 
                  v-for="salleSimilaire in sallesSimilaires" 
                  :key="salleSimilaire.id"
                  class="bg-white rounded-lg p-4 shadow-sm hover:shadow-lg transition-shadow"
                >
                  <div class="h-32 bg-gray-200 rounded-lg mb-4 overflow-hidden">
                    <img 
                      v-if="salleSimilaire.image_url"
                      :src="salleSimilaire.image_url.startsWith('http') ? salleSimilaire.image_url : `/storage/${salleSimilaire.image_url}`" 
                      :alt="salleSimilaire.nom"
                      class="w-full h-full object-cover"
                    >
                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                      <i class="fas fa-gamepad text-3xl"></i>
                    </div>
                  </div>
                  <h4 class="font-medium text-gray-900 mb-2">{{ salleSimilaire.nom }}</h4>
                  <div class="text-sm text-gray-600 mb-2">{{ salleSimilaire.ville }}</div>
                  <div class="flex justify-between items-center">
                    <span class="text-red-600 font-bold">{{ formatPrice(salleSimilaire.prix_heure) }}/h</span>
                    <Link 
                      :href="`/salles/${salleSimilaire.id}`"
                      class="text-red-600 hover:text-red-700 text-sm font-medium"
                    >
                      Voir
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Colonne latérale -->
          <div class="lg:col-span-1">
            <!-- Carte de réservation -->
            <div class="bg-white rounded-xl p-6 shadow-sm sticky top-24">
              <div class="mb-6">
                <div class="text-3xl font-bold text-gray-900">{{ formatPrice(salle.prix_heure) }}</div>
                <div class="text-gray-500">par heure</div>
              </div>

              <div class="space-y-4 mb-6">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                  <span class="text-gray-600">Capacité</span>
                  <span class="font-medium">{{ formatCapacity(salle.capacite_max) }} personnes</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                  <span class="text-gray-600">WiFi</span>
                  <span class="font-medium">Inclus</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                  <span class="text-gray-600">Parking</span>
                  <span class="font-medium">Disponible</span>
                </div>
              </div>

              <div class="space-y-3">
                <Link 
                  href="/login"
                  class="w-full bg-red-600 text-white py-3 rounded-lg font-medium hover:bg-red-700 transition-colors text-center block"
                >
                  Se connecter pour réserver
                </Link>
                <Link 
                  href="/register"
                  class="w-full border border-red-600 text-red-600 py-3 rounded-lg font-medium hover:bg-red-50 transition-colors text-center block"
                >
                  Créer un compte
                </Link>
              </div>

              <!-- Contact -->
              <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="font-medium text-gray-900 mb-3">Besoin d'aide ?</h4>
                <div class="space-y-2 text-sm text-gray-600">
                  <div class="flex items-center gap-2">
                    <i class="fas fa-phone text-red-600"></i>
                    <span>Support disponible 24/7</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <i class="fas fa-envelope text-red-600"></i>
                    <span>support@gameon.com</span>
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
