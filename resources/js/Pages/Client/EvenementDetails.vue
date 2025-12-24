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

    <!-- Main Content -->
    <main class="layout-container flex h-full grow flex-col pt-20">
      <!-- Breadcrumb -->
      <div class="bg-content-light border-b border-border-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
          <nav class="flex">
            <Link href="/client/evenements" class="text-text-light/50 hover:text-text-light">
              Événements
            </Link>
            <span class="mx-2 text-text-light/50">/</span>
            <span class="text-text-light">{{ evenement.titre }}</span>
          </nav>
        </div>
      </div>

      <!-- Contenu principal -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2">
          <!-- Galerie d'images -->
          <div class="mb-8">
            <!-- Image principale -->
            <div class="relative h-96 bg-gradient-to-br from-primary to-primary/70 rounded-xl overflow-hidden mb-4">
              <img 
                v-if="evenement.image_affiche"
                :src="evenement.image_affiche.startsWith('http') ? evenement.image_affiche : '/storage/' + evenement.image_affiche"
                :alt="evenement.titre"
                class="w-full h-full object-cover"
              >
              <div v-else class="w-full h-full flex items-center justify-center">
                <i class="fas fa-calendar-alt text-8xl text-white/50"></i>
              </div>
              
              <!-- Badge de favoris -->
              <div class="absolute top-4 right-4">
                <button class="p-3 bg-white/90 backdrop-blur-sm rounded-full shadow-lg hover:bg-white transition-colors">
                  <i class="fas fa-heart text-primary"></i>
                </button>
              </div>
              
              <!-- Badges d'événement -->
              <div class="absolute top-4 left-4 flex gap-2">
                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-primary text-sm rounded-full font-medium">
                  {{ getEventType(evenement.type) }}
                </span>
                <span :class="getEventStatus(evenement.date_fin).class" class="px-3 py-1 text-sm rounded-full font-medium">
                  {{ getEventStatus(evenement.date_fin).text }}
                </span>
              </div>
            </div>
            
            <!-- Miniatures d'images supplémentaires -->
            <div class="grid grid-cols-4 gap-2">
              <div 
                v-for="i in 4" 
                :key="i"
                class="aspect-video bg-gradient-to-br from-primary/20 to-primary/10 rounded-lg flex items-center justify-center hover:from-primary/30 hover:to-primary/20 transition-colors cursor-pointer"
              >
                <i class="fas fa-image text-primary/30"></i>
              </div>
            </div>
          </div>

          <!-- Informations de l'événement -->
          <div class="bg-content-light rounded-xl p-6 mb-8">
            <h1 class="text-3xl font-bold text-text-light mb-4">{{ evenement.titre }}</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="flex items-center text-text-light/70">
                <i class="fas fa-calendar-alt mr-3 text-primary"></i>
                <div>
                  <div class="font-medium">{{ formatDate(evenement.date_debut) }}</div>
                  <div class="text-sm">{{ formatTime(evenement.date_debut) }} - {{ formatTime(evenement.date_fin) }}</div>
                </div>
              </div>
              <div class="flex items-center text-text-light/70">
                <i class="fas fa-map-marker-alt mr-3 text-primary"></i>
                <div>
                  <div class="font-medium">{{ evenement.lieu }}</div>
                  <div class="text-sm">{{ evenement.salle?.nom || 'Lieu à déterminer' }}</div>
                </div>
              </div>
              <div class="flex items-center text-text-light/70">
                <i class="fas fa-users mr-3 text-primary"></i>
                <div>
                  <div class="font-medium">{{ evenement.capacite_max || 'Illimité' }} participants</div>
                  <div class="text-sm">Capacité maximale</div>
                </div>
              </div>
              <div class="flex items-center text-text-light/70">
                <i class="fas fa-tag mr-3 text-primary"></i>
                <div>
                  <div class="font-medium">{{ formatPrice(evenement.prix) }}</div>
                  <div class="text-sm">Prix par participant</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="bg-content-light rounded-xl p-6 mb-8">
            <h2 class="text-2xl font-bold text-text-light mb-4">Description</h2>
            <div class="prose prose-lg text-text-light/70">
              <p>{{ evenement.description }}</p>
            </div>
          </div>

          <!-- Informations supplémentaires -->
          <div class="bg-content-light rounded-xl p-6">
            <h2 class="text-2xl font-bold text-text-light mb-4">Informations pratiques</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div v-if="evenement.organisateur" class="flex items-start text-text-light/70">
                <i class="fas fa-user mr-3 text-primary mt-1"></i>
                <div>
                  <div class="font-medium">Organisateur</div>
                  <div class="text-sm">{{ evenement.organisateur }}</div>
                </div>
              </div>
              <div v-if="evenement.contact" class="flex items-start text-text-light/70">
                <i class="fas fa-envelope mr-3 text-primary mt-1"></i>
                <div>
                  <div class="font-medium">Contact</div>
                  <div class="text-sm">{{ evenement.contact }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
              <!-- Colonne latérale -->
        <div class="space-y-6">
          <!-- Carte de réservation -->
          <div class="bg-content-light rounded-xl p-6 sticky top-24">
            <h3 class="text-xl font-bold text-text-light mb-4">Participer à l'événement</h3>
            <div class="space-y-4">
              <div class="flex justify-between items-center">
                <span class="text-text-light/70">Prix</span>
                <span class="text-2xl font-bold text-primary">{{ formatPrice(evenement.prix) }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-text-light/70">Date</span>
                <span class="font-medium text-text-light">{{ formatDate(evenement.date_debut) }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-text-light/70">Heure</span>
                <span class="font-medium text-text-light">{{ formatTime(evenement.date_debut) }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-text-light/70">Lieu</span>
                <span class="font-medium text-text-light">{{ evenement.lieu }}</span>
              </div>
              
              <div class="border-t border-border-light pt-4">
                <button class="w-full bg-primary hover:bg-primary/90 text-white py-3 rounded-lg font-medium transition-colors">
                  Réserver ma place
                </button>
              </div>
            </div>
          </div>

          <!-- Informations de contact -->
          <div class="bg-content-light rounded-xl p-6">
            <h3 class="text-xl font-bold text-text-light mb-4">Contact</h3>
            <div class="space-y-3">
              <div v-if="evenement.contact" class="flex items-center text-text-light/70">
                <i class="fas fa-envelope mr-3 text-primary"></i>
                <span>{{ evenement.contact }}</span>
              </div>
              <div v-if="evenement.organisateur" class="flex items-center text-text-light/70">
                <i class="fas fa-user mr-3 text-primary"></i>
                <span>{{ evenement.organisateur }}</span>
              </div>
              <div class="flex items-center text-text-light/70">
                <i class="fas fa-phone mr-3 text-primary"></i>
                <span>Contact à venir</span>
              </div>
            </div>
          </div>

          <!-- Événements similaires -->
          <div v-if="evenementsSimilaires && evenementsSimilaires.length > 0" class="bg-content-light rounded-xl p-6">
            <h3 class="text-xl font-bold text-text-light mb-4">Événements similaires</h3>
            <div class="space-y-4">
              <div 
                v-for="similaire in evenementsSimilaires.slice(0, 3)" 
                :key="similaire.id"
                class="border border-border-light rounded-lg p-4 hover:border-primary/50 transition-colors cursor-pointer"
              >
                <div class="flex gap-4">
                  <div class="w-20 h-20 bg-gradient-to-br from-primary/20 to-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-primary"></i>
                  </div>
                  <div class="flex-1">
                    <h4 class="font-medium text-text-light mb-1">{{ similaire.titre }}</h4>
                    <p class="text-sm text-text-light/70 mb-2">{{ formatDate(similaire.date_debut) }}</p>
                    <div class="flex items-center justify-between">
                      <span class="text-primary font-medium">{{ formatPrice(similaire.prix) }}</span>
                      <Link 
                        :href="'/client/evenements/' + similaire.id"
                        class="text-sm text-primary hover:text-primary/80 transition-colors"
                      >
                        Voir →
                      </Link>
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
