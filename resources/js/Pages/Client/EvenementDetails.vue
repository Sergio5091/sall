<script setup>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    evenement: Object,
    evenementsSimilaires: Array
});

// États UI
const activeImage = ref(0);
const showShareModal = ref(false);
const isFavorite = ref(false);

// Formatter les dates
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

const formatTime = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Prix formatté
const formatPrice = (prix) => {
    if (!prix || prix === 0) return 'Gratuit';
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(prix);
};

// Statut de l'événement
const eventStatus = computed(() => {
    if (!props.evenement?.date_fin) return { text: 'À venir', class: 'bg-blue-100 text-blue-700' };
    
    const now = new Date();
    const fin = new Date(props.evenement.date_fin);
    
    if (fin < now) {
        return { text: 'Terminé', class: 'bg-gray-100 text-gray-700' };
    } else {
        return { text: 'À venir', class: 'bg-green-100 text-green-700' };
    }
});

// Type d'événement
const eventType = computed(() => {
    const types = {
        'tournament': 'Tournoi',
        'lan_party': 'LAN Party',
        'showmatch': 'Showmatch',
        'casual_gaming': 'Gaming Casual',
        'workshop': 'Atelier',
        'exhibition': 'Exposition'
    };
    return types[props.evenement?.type] || props.evenement?.type || 'Événement';
});

// Images de la galerie
const galleryImages = computed(() => {
    const images = [];
    if (props.evenement?.image_affiche) {
        images.push(props.evenement.image_affiche);
    }
    // Ajouter d'autres images si disponibles
    return images.slice(0, 5);
});

// Partager l'événement
const shareEvent = () => {
    showShareModal.value = true;
    // Logique de partage ici
};

// Toggle favori
const toggleFavorite = () => {
    isFavorite.value = !isFavorite.value;
    // API call pour sauvegarder
};
</script>

<template>
  <Head :title="`${evenement?.titre || 'Événement'} - YOUPIHUB`" />
  
  <div class="min-h-screen bg-gray-50 font-sans">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center gap-8">
            <Link href="/client/dashboard" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
              <i class="fas fa-gamepad text-xl text-blue-600"></i>
              <span class="text-lg font-bold text-gray-900">YOUPIHUB</span>
            </Link>
            <nav class="hidden md:flex items-center gap-6">
              <Link href="/client/dashboard" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">Dashboard</Link>
              <Link href="/client/salles" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">Salles</Link>
              <Link href="/client/evenements" class="text-sm font-medium text-blue-600">Événements</Link>
              <Link href="/client/reservations" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">Réservations</Link>
            </nav>
          </div>
          <div class="flex items-center gap-3">
            <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
              <i class="fas fa-bell text-gray-600"></i>
              <span class="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full"></span>
            </button>
            <Link href="/client/profile" class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold">
              {{ $page.props.auth?.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="pt-16">
      <!-- Breadcrumb -->
      <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
          <nav class="flex items-center text-sm">
            <Link href="/client/evenements" class="text-gray-500 hover:text-gray-700 transition-colors">
              Événements
            </Link>
            <i class="fas fa-chevron-right mx-2 text-gray-400 text-xs"></i>
            <span class="font-medium text-gray-900">{{ evenement?.titre || 'Détails' }}</span>
          </nav>
        </div>
      </div>

      <!-- Event Content -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Column -->
          <div class="lg:col-span-2 space-y-8">
            <!-- Event Gallery -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
              <!-- Main Image -->
              <div class="relative h-80 md:h-96 bg-gradient-to-br from-blue-500 to-purple-600">
                <img 
                  v-if="galleryImages[activeImage]"
                  :src="galleryImages[activeImage].startsWith('http') ? galleryImages[activeImage] : `/storage/${galleryImages[activeImage]}`"
                  :alt="evenement?.titre"
                  class="w-full h-full object-cover"
                />
                <div v-else class="w-full h-full flex items-center justify-center">
                  <i class="fas fa-gamepad text-8xl text-white/30"></i>
                </div>

                <!-- Image Navigation -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
                  <button 
                    v-for="(_, index) in galleryImages" 
                    :key="index"
                    @click="activeImage = index"
                    :class="[
                      'w-2 h-2 rounded-full transition-all',
                      activeImage === index 
                        ? 'bg-white w-6' 
                        : 'bg-white/50 hover:bg-white/70'
                    ]"
                  />
                </div>

                <!-- Event Badges -->
                <div class="absolute top-4 left-4 flex gap-2">
                  <span :class="eventStatus.class" class="px-3 py-1 text-sm rounded-full font-medium">
                    {{ eventStatus.text }}
                  </span>
                  <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-blue-600 text-sm rounded-full font-medium">
                    {{ eventType }}
                  </span>
                </div>

                <!-- Action Buttons -->
                <div class="absolute top-4 right-4 flex gap-2">
                  <button 
                    @click="toggleFavorite"
                    :class="[
                      'p-3 rounded-full bg-white/90 backdrop-blur-sm shadow-lg transition-colors',
                      isFavorite ? 'text-red-500' : 'text-gray-600 hover:text-red-500'
                    ]"
                  >
                    <i class="fas fa-heart"></i>
                  </button>
                  <button 
                    @click="shareEvent"
                    class="p-3 rounded-full bg-white/90 backdrop-blur-sm shadow-lg text-gray-600 hover:text-blue-600 transition-colors"
                  >
                    <i class="fas fa-share-alt"></i>
                  </button>
                </div>
              </div>

              <!-- Thumbnails -->
              <div class="p-4 grid grid-cols-5 gap-2">
                <button 
                  v-for="(image, index) in galleryImages" 
                  :key="index"
                  @click="activeImage = index"
                  :class="[
                    'aspect-square rounded-lg overflow-hidden border-2 transition-all',
                    activeImage === index 
                      ? 'border-blue-500 ring-2 ring-blue-500 ring-opacity-30' 
                      : 'border-transparent hover:border-gray-300'
                  ]"
                >
                  <img 
                    :src="image.startsWith('http') ? image : `/storage/${image}`"
                    :alt="`Image ${index + 1}`"
                    class="w-full h-full object-cover"
                  />
                </button>
              </div>
            </div>

            <!-- Event Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
              <div class="p-6 border-b border-gray-100">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ evenement?.titre }}</h1>
                <p class="text-gray-600">{{ evenement?.description_short || evenement?.description?.substring(0, 150) }}</p>
              </div>

              <div class="p-6">
                <!-- Event Meta -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                  <div class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="p-3 rounded-lg bg-blue-100 text-blue-600">
                      <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                      <div class="font-medium text-gray-700">Date et heure</div>
                      <div class="text-sm text-gray-900">
                        {{ formatDate(evenement?.date_debut) }}<br>
                        {{ formatTime(evenement?.date_debut) }} - {{ formatTime(evenement?.date_fin) }}
                      </div>
                    </div>
                  </div>

                  <div class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="p-3 rounded-lg bg-green-100 text-green-600">
                      <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                      <div class="font-medium text-gray-700">Lieu</div>
                      <div class="text-sm text-gray-900">
                        <div class="font-medium">{{ evenement?.salle?.nom || evenement?.lieu }}</div>
                        <div class="text-gray-600">{{ evenement?.adresse }}</div>
                      </div>
                    </div>
                  </div>

                  <div class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="p-3 rounded-lg bg-purple-100 text-purple-600">
                      <i class="fas fa-users"></i>
                    </div>
                    <div>
                      <div class="font-medium text-gray-700">Capacité</div>
                      <div class="text-sm text-gray-900">
                        {{ evenement?.capacite_max || 'Illimité' }} places
                        <div class="text-gray-600">{{ evenement?.inscrits || '0' }} inscrits</div>
                      </div>
                    </div>
                  </div>

                  <div class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="p-3 rounded-lg bg-orange-100 text-orange-600">
                      <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div>
                      <div class="font-medium text-gray-700">Prix</div>
                      <div class="text-2xl font-bold text-gray-900">{{ formatPrice(evenement?.prix) }}</div>
                      <div class="text-sm text-gray-600">par participant</div>
                    </div>
                  </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                  <h3 class="text-xl font-bold text-gray-900 mb-4">Description</h3>
                  <div class="prose prose-blue max-w-none">
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                      {{ evenement?.description }}
                    </p>
                  </div>
                </div>

                <!-- Organizer Info -->
                <div v-if="evenement?.organisateur" class="border-t border-gray-100 pt-6">
                  <h3 class="text-xl font-bold text-gray-900 mb-4">Organisateur</h3>
                  <div class="flex items-center gap-4 p-4 rounded-lg bg-gray-50">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                      {{ evenement.organisateur.charAt(0) }}
                    </div>
                    <div>
                      <div class="font-bold text-gray-900">{{ evenement.organisateur }}</div>
                      <div v-if="evenement.contact" class="text-sm text-gray-600">
                        <i class="fas fa-envelope mr-2"></i>{{ evenement.contact }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-6">
            <!-- Reservation Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 sticky top-24">
              <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-900">Réserver ma place</h3>
                <p class="text-sm text-gray-600 mt-1">Sécurisé et simple</p>
              </div>

              <div class="p-6">
                <div class="space-y-4 mb-6">
                  <div class="flex justify-between items-center">
                    <span class="text-gray-600">Prix</span>
                    <span class="text-2xl font-bold text-gray-900">{{ formatPrice(evenement?.prix) }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-gray-600">Date</span>
                    <span class="font-medium text-gray-900">{{ formatDate(evenement?.date_debut) }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-gray-600">Heure</span>
                    <span class="font-medium text-gray-900">{{ formatTime(evenement?.date_debut) }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-gray-600">Lieu</span>
                    <span class="font-medium text-gray-900">{{ evenement?.lieu }}</span>
                  </div>
                </div>

                <button class="w-full py-3 px-4 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium hover:from-blue-700 hover:to-blue-800 transition-all transform hover:-translate-y-0.5 active:translate-y-0 shadow-md hover:shadow-lg">
                  <i class="fas fa-ticket-alt mr-2"></i>
                  Réserver maintenant
                </button>

                <div class="mt-4 text-center text-sm text-gray-500">
                  <p>Annulation gratuite jusqu'à 48h avant</p>
                </div>
              </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
              <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-900">Contact</h3>
              </div>
              <div class="p-6 space-y-4">
                <div v-if="evenement?.contact" class="flex items-center gap-3">
                  <div class="p-2 rounded-lg bg-blue-100 text-blue-600">
                    <i class="fas fa-envelope"></i>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-700">Email</div>
                    <a :href="`mailto:${evenement.contact}`" class="text-blue-600 hover:text-blue-800 transition-colors">
                      {{ evenement.contact }}
                    </a>
                  </div>
                </div>
                <div v-if="evenement?.telephone" class="flex items-center gap-3">
                  <div class="p-2 rounded-lg bg-green-100 text-green-600">
                    <i class="fas fa-phone"></i>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-700">Téléphone</div>
                    <a :href="`tel:${evenement.telephone}`" class="text-gray-900 hover:text-blue-600 transition-colors">
                      {{ evenement.telephone }}
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Similar Events -->
            <div v-if="evenementsSimilaires?.length" class="bg-white rounded-xl shadow-sm border border-gray-200">
              <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-900">Événements similaires</h3>
              </div>
              <div class="p-6 space-y-4">
                <Link 
                  v-for="event in evenementsSimilaires.slice(0, 3)" 
                  :key="event.id"
                  :href="`/client/evenements/${event.id}`"
                  class="group flex gap-3 p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all"
                >
                  <div class="w-16 h-16 flex-shrink-0 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <i class="fas fa-gamepad text-white text-xl"></i>
                  </div>
                  <div class="flex-1">
                    <h4 class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors">
                      {{ event.titre }}
                    </h4>
                    <p class="text-sm text-gray-600 mt-1">
                      {{ formatDate(event.date_debut) }}
                    </p>
                    <div class="flex items-center justify-between mt-2">
                      <span class="text-blue-600 font-medium">{{ formatPrice(event.prix) }}</span>
                      <i class="fas fa-arrow-right text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </div>
                  </div>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Share Modal -->
    <div v-if="showShareModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showShareModal = false"></div>
      <div class="relative w-full max-w-md rounded-xl bg-white shadow-2xl">
        <div class="p-6 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Partager l'événement</h3>
            <button @click="showShareModal = false" class="p-2 rounded-lg hover:bg-gray-100">
              <i class="fas fa-times text-gray-500"></i>
            </button>
          </div>
        </div>
        <div class="p-6">
          <div class="flex justify-center gap-4">
            <button class="p-4 rounded-xl bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors">
              <i class="fab fa-facebook-f text-xl"></i>
            </button>
            <button class="p-4 rounded-xl bg-blue-50 text-blue-400 hover:bg-blue-100 transition-colors">
              <i class="fab fa-twitter text-xl"></i>
            </button>
            <button class="p-4 rounded-xl bg-red-100 text-red-600 hover:bg-red-200 transition-colors">
              <i class="fab fa-whatsapp text-xl"></i>
            </button>
            <button class="p-4 rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
              <i class="fas fa-link text-xl"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.font-sans {
  font-family: 'Inter', sans-serif;
}

.transition-colors {
  transition: all 0.2s ease;
}

.transition-all {
  transition: all 0.3s ease;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}

/* Prose styles */
.prose {
  color: #374151;
  max-width: none;
}

.prose p {
  margin-bottom: 1.25rem;
  line-height: 1.75;
}

.prose-blue a {
  color: #3b82f6;
}

.prose-blue a:hover {
  color: #1d4ed8;
}
</style>