<script setup>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    evenement: Object,
    evenementsSimilaires: Array
});

// Fonction pour formater la date
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

// Fonction pour formater l'heure
const formatTime = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Fonction pour formater le prix
const formatPrice = (prix) => {
    if (!prix || prix === 0) return 'Gratuit';
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(prix);
};

// Fonction pour obtenir le statut de l'événement
const getEventStatus = (dateFin) => {
    const now = new Date();
    const fin = new Date(dateFin);
    
    if (fin < now) {
        return { text: 'Terminé', class: 'bg-gray-100 text-gray-700' };
    } else {
        return { text: 'À venir', class: 'bg-green-100 text-green-700' };
    }
};

// Fonction pour obtenir le type d'événement formaté
const getEventType = (type) => {
    const types = {
        'tournament': 'Tournoi',
        'lan_party': 'LAN Party',
        'showmatch': 'Showmatch',
        'casual_gaming': 'Gaming Casual'
    };
    return types[type] || type || 'Événement';
};
</script>

<template>
  <Head :title="`${evenement.titre} - GameOn`" />
  
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden bg-background-light font-display">
    <!-- Header - Same as Welcome page but with client navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center backdrop-blur-sm shadow-sm">
      <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-2 text-black">
            <a href="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
              <i class="fas fa-gamepad text-3xl text-accent-cyan"></i>
              <h2 class="text-black text-2xl font-display font-bold">GameOn</h2>
            </a>
          </div>
          <!-- Client Navigation -->
          <nav class="hidden md:flex items-center gap-6">
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/dashboard">Dashboard</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/salles">Salles</a>
            <a class="text-black text-sm font-medium text-accent-cyan" href="/client/evenements">Événements</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/reservations">Mes Réservations</a>
          </nav>
        </div>
        <div class="flex items-center gap-3">
          <button class="flex relative cursor-pointer items-center justify-center overflow-hidden rounded-full size-10 bg-[#e5e7eb] text-black gap-2">
            <i class="fas fa-bell"></i>
            <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
            </span>
          </button>
          <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 bg-gray-300"></div>
        </div>
      </div>
    </header>

    <!-- Contenu principal -->
    <main class="flex-1 pt-16">
      <!-- Hero avec image -->
      <div class="relative h-96 bg-blue-600">
        <img 
          v-if="evenement.image_affiche"
          :src="evenement.image_affiche.startsWith('http') ? evenement.image_affiche : '/storage/' + evenement.image_affiche"
          :alt="evenement.titre"
          class="w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
        
        <!-- Contenu overlay -->
        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
          <div class="max-w-7xl mx-auto">
            <div class="flex items-center gap-4 mb-4">
              <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm rounded-full font-medium">
                {{ getEventType(evenement.type) }}
              </span>
              <span :class="getEventStatus(evenement.date_fin).class" class="px-3 py-1 text-sm rounded-full font-medium">
                {{ getEventStatus(evenement.date_fin).text }}
              </span>
            </div>
            <h1 class="text-4xl font-bold mb-4">{{ evenement.titre }}</h1>
            <div class="flex items-center gap-6 text-white/90">
              <div class="flex items-center gap-2">
                <i class="fas fa-calendar"></i>
                <span>{{ formatDate(evenement.date_debut) }}</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="fas fa-clock"></i>
                <span>{{ formatTime(evenement.date_debut) }} - {{ formatTime(evenement.date_fin) }}</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ evenement.lieu }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Contenu détaillé -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Colonne principale -->
          <div class="lg:col-span-2 space-y-8">
            <!-- Description -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
              <div class="prose prose-lg text-gray-700">
                <p>{{ evenement.description }}</p>
              </div>
            </div>

            <!-- Informations de la salle -->
            <div v-if="evenement.salle" class="bg-white rounded-2xl shadow-lg p-8">
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Salle : {{ evenement.salle.nom }}</h2>
                <Link 
                  :href="'/client/salles/' + evenement.salle.id"
                  class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors"
                >
                  <i class="fas fa-eye mr-2"></i>
                  Voir la salle
                </Link>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Image et description -->
                <div>
                  <div v-if="evenement.salle.image_url" class="mb-4">
                    <img 
                      :src="evenement.salle.image_url.startsWith('http') ? evenement.salle.image_url : '/storage/' + evenement.salle.image_url"
                      :alt="evenement.salle.nom"
                      class="w-full h-48 object-cover rounded-lg"
                    >
                  </div>
                  <p class="text-gray-700">{{ evenement.salle.description }}</p>
                </div>
                
                <!-- Informations de la salle -->
                <div class="space-y-3">
                  <div class="flex items-center gap-3">
                    <i class="fas fa-map-marker-alt text-purple-600 w-5"></i>
                    <div>
                      <div class="font-medium">Adresse</div>
                      <div class="text-gray-600">{{ evenement.salle.adresse }}</div>
                      <div class="text-gray-600">{{ evenement.salle.ville }}, {{ evenement.salle.pays }}</div>
                    </div>
                  </div>
                  
                  <div class="flex items-center gap-3">
                    <i class="fas fa-users text-purple-600 w-5"></i>
                    <div>
                      <div class="font-medium">Capacité</div>
                      <div class="text-gray-600">{{ evenement.salle.capacite_max }} personnes maximum</div>
                    </div>
                  </div>
                  
                  <div class="flex items-center gap-3">
                    <i class="fas fa-ruler-combined text-purple-600 w-5"></i>
                    <div>
                      <div class="font-medium">Surface</div>
                      <div class="text-gray-600">{{ evenement.salle.surface || 'Non spécifiée' }} m²</div>
                    </div>
                  </div>
                  
                  <div class="flex items-center gap-3">
                    <i class="fas fa-tag text-purple-600 w-5"></i>
                    <div>
                      <div class="font-medium">Prix</div>
                      <div class="text-gray-600">{{ formatPrice(evenement.salle.prix_heure) }} / heure</div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Équipements de la salle -->
              <div v-if="evenement.salle.equipements" class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Équipements disponibles</h3>
                <div class="flex flex-wrap gap-2">
                  <span 
                    v-if="evenement.salle.equipements.wifi" 
                    class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full"
                  >
                    <i class="fas fa-wifi mr-1"></i> WiFi
                  </span>
                  <span 
                    v-if="evenement.salle.equipements.parking" 
                    class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full"
                  >
                    <i class="fas fa-parking mr-1"></i> Parking
                  </span>
                  <span 
                    v-if="evenement.salle.equipements.climatisation" 
                    class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full"
                  >
                    <i class="fas fa-snowflake mr-1"></i> Climatisation
                  </span>
                  <span 
                    v-if="evenement.salle.equipements.ecrans" 
                    class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full"
                  >
                    <i class="fas fa-tv mr-1"></i> Écrans
                  </span>
                  <span 
                    v-if="evenement.salle.equipements.consoles" 
                    class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full"
                  >
                    <i class="fas fa-gamepad mr-1"></i> Consoles
                  </span>
                  <span 
                    v-if="evenement.salle.equipements.son" 
                    class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full"
                  >
                    <i class="fas fa-volume-up mr-1"></i> Système audio
                  </span>
                </div>
              </div>
            </div>

            <!-- Informations complètes de l'événement -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
              <h2 class="text-2xl font-bold text-gray-900 mb-6">Informations de l'événement</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dates -->
                <div class="space-y-4">
                  <h3 class="text-lg font-semibold text-gray-800">Dates et horaires</h3>
                  <div class="space-y-2">
                    <div class="flex items-center gap-3">
                      <i class="fas fa-calendar text-purple-600 w-5"></i>
                      <div>
                        <div class="font-medium">Date de début</div>
                        <div class="text-gray-600">{{ formatDate(evenement.date_debut) }} à {{ formatTime(evenement.date_debut) }}</div>
                      </div>
                    </div>
                    <div class="flex items-center gap-3">
                      <i class="fas fa-calendar-check text-purple-600 w-5"></i>
                      <div>
                        <div class="font-medium">Date de fin</div>
                        <div class="text-gray-600">{{ formatDate(evenement.date_fin) }} à {{ formatTime(evenement.date_fin) }}</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Lieu -->
                <div class="space-y-4">
                  <h3 class="text-lg font-semibold text-gray-800">Lieu</h3>
                  <div class="space-y-2">
                    <div class="flex items-center gap-3">
                      <i class="fas fa-map-marker-alt text-purple-600 w-5"></i>
                      <div>
                        <div class="font-medium">{{ evenement.lieu }}</div>
                        <div class="text-gray-600">{{ evenement.adresse }}</div>
                        <div class="text-gray-600">{{ evenement.ville }}, {{ evenement.pays }}</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Tarifs -->
                <div class="space-y-4">
                  <h3 class="text-lg font-semibold text-gray-800">Tarifs</h3>
                  <div class="space-y-2">
                    <div class="flex items-center gap-3">
                      <i class="fas fa-ticket-alt text-purple-600 w-5"></i>
                      <div>
                        <div class="font-medium">Prix</div>
                        <div class="text-2xl font-bold text-purple-600">{{ formatPrice(evenement.prix) }}</div>
                      </div>
                    </div>
                    <div class="flex items-center gap-3">
                      <i class="fas fa-users text-purple-600 w-5"></i>
                      <div>
                        <div class="font-medium">Capacité</div>
                        <div class="text-gray-600">{{ evenement.capacite_max }} places maximum</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Contact -->
                <div class="space-y-4">
                  <h3 class="text-lg font-semibold text-gray-800">Contact</h3>
                  <div class="space-y-2">
                    <div v-if="evenement.telephone" class="flex items-center gap-3">
                      <i class="fas fa-phone text-purple-600 w-5"></i>
                      <div>
                        <div class="font-medium">Téléphone</div>
                        <div class="text-gray-600">{{ evenement.telephone }}</div>
                      </div>
                    </div>
                    <div class="flex items-center gap-3">
                      <i class="fas fa-envelope text-purple-600 w-5"></i>
                      <div>
                        <div class="font-medium">Email</div>
                        <div class="text-gray-600">{{ evenement.email }}</div>
                      </div>
                    </div>
                    <div v-if="evenement.site_web" class="flex items-center gap-3">
                      <i class="fas fa-globe text-purple-600 w-5"></i>
                      <div>
                        <div class="font-medium">Site web</div>
                        <a :href="evenement.site_web" target="_blank" class="text-purple-600 hover:underline">{{ evenement.site_web }}</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Galerie d'images -->
            <div v-if="evenement.galerie && evenement.galerie.length > 0" class="bg-white rounded-2xl shadow-lg p-8">
              <h2 class="text-2xl font-bold text-gray-900 mb-6">Galerie</h2>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div v-for="(image, index) in evenement.galerie" :key="index" class="aspect-video rounded-lg overflow-hidden">
                  <img 
                    :src="image.startsWith('http') ? image : '/storage/' + image"
                    :alt="`Image ${index + 1}`"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                  >
                </div>
              </div>
            </div>
          </div>

          <!-- Colonne latérale -->
          <div class="lg:col-span-1">
            <!-- Carte d'action -->
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">
              <div class="text-center mb-6">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ formatPrice(evenement.prix) }}</div>
                <div class="text-gray-600">Par participant</div>
              </div>

              <div class="space-y-4 mb-6">
                <div class="flex items-center justify-between">
                  <span class="text-gray-600">Places disponibles</span>
                  <span class="font-medium">{{ evenement.capacite_max }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-gray-600">Type</span>
                  <span class="font-medium">{{ getEventType(evenement.type) }}</span>
                </div>
              </div>

              <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                <i class="fas fa-ticket-alt mr-2"></i>
                Réserver ma place
              </button>

              <div class="mt-4 text-center">
                <p class="text-sm text-gray-500">Réservation sécurisée et annulation gratuite jusqu'à 48h avant</p>
              </div>
            </div>

            <!-- Réseaux sociaux -->
            <div v-if="evenement.facebook || evenement.instagram" class="bg-white rounded-2xl shadow-lg p-6 mt-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Suivez-nous</h3>
              <div class="flex gap-4">
                <a v-if="evenement.facebook" :href="`https://facebook.com/${evenement.facebook}`" target="_blank" class="text-blue-600 hover:text-blue-700">
                  <i class="fab fa-facebook text-2xl"></i>
                </a>
                <a v-if="evenement.instagram" :href="`https://instagram.com/${evenement.instagram}`" target="_blank" class="text-pink-600 hover:text-pink-700">
                  <i class="fab fa-instagram text-2xl"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Événements similaires -->
        <div v-if="evenementsSimilaires && evenementsSimilaires.length > 0" class="mt-12">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">Événements similaires</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div 
              v-for="event in evenementsSimilaires" 
              :key="event.id"
              class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow p-6"
            >
              <h3 class="font-bold text-lg mb-2">{{ event.titre }}</h3>
              <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ event.description }}</p>
              <div class="text-sm text-gray-500 mb-4">
                <div class="flex items-center gap-2 mb-1">
                  <i class="fas fa-calendar"></i>
                  <span>{{ formatDate(event.date_debut) }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-clock"></i>
                  <span>{{ formatTime(event.date_debut) }}</span>
                </div>
              </div>
              <Link 
                :href="'/client/evenements/' + event.id"
                class="block w-full text-center px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors"
              >
                Voir détails
              </Link>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
/* Exact colors from the design */
.bg-background-light { background-color: #f6f8f6; }
.bg-content-light { background-color: #ffffff; }
.bg-subtle-light { background-color: #f0f4f2; }
.text-text-light { color: #111813; }
.text-text-light-70 { color: #111813; opacity: 0.7; }
.text-text-light-50 { color: #111813; opacity: 0.5; }
.text-primary { color: #3b82f6; }
.bg-primary { background-color: #3b82f6; }
.border-border-light { border-color: #dbe6df; }

/* Accent cyan color */
.text-accent-cyan { color: #06b6d4; }

/* Truncate text */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-clamp: 2;
}

/* Prose styles */
.prose {
  color: #374151;
  max-width: none;
}

.prose p {
  margin-bottom: 1rem;
  line-height: 1.75;
}
</style>
