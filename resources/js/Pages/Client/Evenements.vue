<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import NotificationModal from '../../Components/NotificationModal.vue';
import Navigation from '../../Components/Navigation.vue';

const props = defineProps({
    evenements: Object,
    villes: Array,
    filters: Object
});

// États pour les modaux
const showNotificationModal = ref(false);
const notificationType = ref('success');
const notificationTitle = ref('');
const notificationMessage = ref('');

// État local pour les filtres
const searchQuery = ref(props.filters.search || '');
const selectedVille = ref(props.filters.ville || '');
const selectedDate = ref(props.filters.date || '');

// Variable pour le debounce
let debounceTimeout = null;

// État du modal de réservation
const showReservationModal = ref(false);
const selectedEvent = ref(null);
const reservationForm = ref({
    nom: '',
    whatsapp: '',
    email: ''
});
const reservationErrors = ref({});

const heroImageUrl = computed(() => {
    const items = props.evenements?.data || [];
    for (const evt of items) {
        const img = evt?.image_affiche || evt?.image || evt?.salle?.image_url || null;
        if (!img) continue;
        if (typeof img === 'string' && img.startsWith('http')) return img;
        return '/storage/' + String(img).replace(/^\/?storage\//, '');
    }
    return '/images/default-event.jpg';
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

// Fonction pour rechercher
const searchEvents = () => {
    const params = {};
    
    if (searchQuery.value) params.search = searchQuery.value;
    if (selectedVille.value) params.ville = selectedVille.value;
    if (selectedDate.value) params.date = selectedDate.value;
    
    router.get('/client/evenements', params, {
        preserveState: true,
        preserveScroll: true
    });
};

// Fonction pour réinitialiser les filtres
const resetFilters = () => {
    searchQuery.value = '';
    selectedVille.value = '';
    selectedDate.value = '';
    router.get('/client/evenements', {}, {
        preserveState: true,
        preserveScroll: true
    });
};

// Watcher pour la recherche automatique avec debounce
watch(searchQuery, (newValue) => {
    // Annuler le timeout précédent
    if (debounceTimeout) {
        clearTimeout(debounceTimeout);
    }
    
    // Nouveau timeout de 500ms
    debounceTimeout = setTimeout(() => {
        performSearch();
    }, 500);
});

// Fonction pour effectuer la recherche (séparée pour être réutilisée)
const performSearch = () => {
    const params = {};
    
    if (searchQuery.value) params.search = searchQuery.value;
    if (selectedVille.value) params.ville = selectedVille.value;
    if (selectedDate.value) params.date = selectedDate.value;
    
    router.get('/client/evenements', params, {
        preserveState: true,
        preserveScroll: true
    });
};

// Fonction pour ouvrir le modal de réservation
const reserverPlace = (event) => {
    selectedEvent.value = event;
    showReservationModal.value = true;
    // Réinitialiser le formulaire
    reservationForm.value = {
        nom: '',
        whatsapp: '',
        email: ''
    };
    reservationErrors.value = {};
};

// Fonction pour valider le formulaire de réservation
const validateReservation = () => {
    const errors = {};
    
    if (!reservationForm.value.nom.trim()) {
        errors.nom = 'Le nom est obligatoire';
    }
    
    if (!reservationForm.value.whatsapp.trim()) {
        errors.whatsapp = 'Le numéro WhatsApp est obligatoire';
    } else if (!/^(?:\+221)?[77678]\d{7}$/.test(reservationForm.value.whatsapp.replace(/\s/g, ''))) {
        errors.whatsapp = 'Format invalide. Ex: +221771234567 ou 771234567';
    }
    
    if (!reservationForm.value.email.trim()) {
        errors.email = 'L\'email est obligatoire';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(reservationForm.value.email)) {
        errors.email = 'L\'email n\'est pas valide';
    }
    
    reservationErrors.value = errors;
    return Object.keys(errors).length === 0;
};

// Fonction pour soumettre l'inscription
const submitReservation = () => {
    console.log('submitReservation appelé');
    console.log('selectedEvent:', selectedEvent.value);
    console.log('reservationForm:', reservationForm.value);
    
    if (!validateReservation()) {
        console.log('Validation échouée:', reservationErrors.value);
        return;
    }
    
    console.log('Validation OK, envoi de la requête');
    
    // Créer FormData
    const formData = new FormData();
    formData.append('event_id', selectedEvent.value.id);
    formData.append('nom', reservationForm.value.nom);
    formData.append('whatsapp', reservationForm.value.whatsapp);
    formData.append('email', reservationForm.value.email);
    
    console.log('FormData créé:', formData);
    
    // Envoyer la requête d'inscription
    router.post(`/events/${selectedEvent.value.id}/register`, formData, {
        onSuccess: (page) => {
            console.log('Succès:', page);
            showReservationModal.value = false;
            // Afficher un message de succès
            notificationType.value = 'success';
            notificationTitle.value = 'Inscription réussie';
            notificationMessage.value = page.props.flash?.success || 'Inscription effectuée avec succès !';
            showNotificationModal.value = true;
            // Recharger la page pour mettre à jour les places disponibles
            router.reload();
        },
        onError: (errors) => {
            console.error('Erreurs inscription:', errors);
            reservationErrors.value = errors;
        },
        onStart: () => {
            console.log('Début de la requête');
        }
    });
};

// Fonction pour fermer le modal
const closeReservationModal = () => {
    showReservationModal.value = false;
    selectedEvent.value = null;
    reservationForm.value = {
        nom: '',
        whatsapp: '',
        email: ''
    };
    reservationErrors.value = {};
};

// Calculer si des filtres sont actifs
const hasActiveFilters = computed(() => {
    return searchQuery.value || selectedVille.value || selectedDate.value;
});

// Fonction pour gérer les erreurs d'images
const handleImageError = (event) => {
  const src = event.target.src;
  if (src.includes('/bannieres/')) {
    // Essayer le dossier affiches
    const filename = src.split('/').pop();
    event.target.src = '/storage/events/affiches/' + filename;
  } else if (src.includes('/affiches/')) {
    // Utiliser l'image par défaut
    event.target.src = '/images/default-event.jpg';
  }
};

// Fonction pour s'inscrire directement à un événement
const sInscrire = (event) => {
  router.post(`/events/${event.id}/register`, {}, {
    onSuccess: (page) => {
      // Afficher le message de succès depuis les flash messages
      const flashMessage = page.props.flash?.success;
      if (flashMessage) {
        alert(flashMessage);
      } else {
        alert('Inscription réussie !');
      }
      // Recharger la page pour mettre à jour le nombre de participants
      router.reload();
    },
    onError: (errors) => {
      // Afficher les erreurs
      const errorMessage = Object.values(errors).join('\n') || 'Erreur lors de l\'inscription';
      alert(errorMessage);
    }
  });
};
</script>

<template>
  <Head title="Événements - GameOn" />
  
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden bg-background-light font-display">
    <!-- Navigation Component -->
    <Navigation :user="$page.props.auth?.user" current-page="evenements" />

    <!-- Contenu principal -->
    <main class="flex-1 pt-16">
      <!-- Hero Section -->
      <div class="relative py-16">
        <img
          :src="heroImageUrl"
          alt="Événements"
          class="absolute inset-0 w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center">
            <h1 class="text-4xl font-bold text-white mb-4">
              Découvrez tous les événements gaming
            </h1>
            <p class="text-xl text-white text-opacity-90 mb-8">
              Tournois, soirées LAN, compétitions et bien plus encore
            </p>
            
            <!-- Barre de recherche automatique -->
            <div class="max-w-2xl mx-auto">
              <div class="bg-white rounded-full shadow-lg p-2 flex items-center">
                <div class="flex-1 flex items-center">
                  <i class="fas fa-search text-gray-400 mr-3 ml-3"></i>
                  <input 
                    v-model="searchQuery"
                    type="text" 
                    placeholder="Rechercher un événement... (recherche automatique)"
                    class="flex-1 px-4 py-2 text-gray-700 focus:outline-none"
                  >
                </div>
                <div class="px-4 py-2 text-sm text-gray-500">
                  <i class="fas fa-sync-alt animate-spin" v-if="searchQuery && searchQuery.length > 2"></i>
                  <span v-else>Auto</span>
                </div>
              </div>
              <p class="text-white/80 text-sm mt-2 text-center">
                La recherche se déclenche automatiquement après 500ms d'inactivité
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtres -->
      <div class="bg-white border-b border-gray-200 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex flex-wrap items-center gap-4">
            <!-- Filtre par ville -->
            <div class="flex items-center gap-2">
              <label class="text-sm font-medium text-gray-700">Ville:</label>
              <select 
                v-model="selectedVille"
                @change="searchEvents"
                class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
              >
                <option value="">Toutes les villes</option>
                <option v-for="ville in villes" :key="ville" :value="ville">
                  {{ ville }}
                </option>
              </select>
            </div>
            
            <!-- Filtre par date -->
            <div class="flex items-center gap-2">
              <label class="text-sm font-medium text-gray-700">Date:</label>
              <input 
                v-model="selectedDate"
                @change="searchEvents"
                type="date" 
                class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
              >
            </div>
            
            <!-- Bouton de réinitialisation -->
            <button 
              v-if="hasActiveFilters"
              @click="resetFilters"
              class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
            >
              <i class="fas fa-times mr-2"></i>
              Réinitialiser
            </button>
          </div>
        </div>
      </div>

      <!-- Liste des événements -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Message si aucun événement -->
        <div v-if="evenements.data.length === 0" class="text-center py-12">
          <div class="bg-gray-50 rounded-2xl p-8 max-w-md mx-auto">
            <i class="fas fa-calendar-xmark text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-700 mb-2">Aucun événement trouvé</h3>
            <p class="text-gray-500">
              {{ hasActiveFilters ? 'Essayez de modifier vos filtres' : 'Aucun événement n\'est programmé pour le moment.' }}
            </p>
          </div>
        </div>

        <!-- Grille d'événements -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="event in evenements.data" 
            :key="event.id"
            class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-purple-100"
          >
            <!-- Header avec image de l'événement -->
            <div class="relative h-48 bg-blue-600">
              <img 
                v-if="event.image_banniere || event.image_affiche"
                :src="(event.image_banniere || event.image_affiche).startsWith('http') ? (event.image_banniere || event.image_affiche) : '/storage/events/bannieres/' + (event.image_banniere || event.image_affiche)"
                :alt="event.titre"
                class="w-full h-full object-cover"
                @error="handleImageError"
                @load="console.log('Image chargée:', $event.target.src)"
              >
              <img 
                v-else
                src="/images/default-event.jpg"
                :alt="event.titre"
                class="w-full h-full object-cover"
              >
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
              
              <!-- Badge de statut -->
              <div class="absolute top-4 right-4">
                <span :class="getEventStatus(event.date_fin).class" class="px-3 py-1 text-xs rounded-full font-medium">
                  {{ getEventStatus(event.date_fin).text }}
                </span>
              </div>
              
              <!-- Informations de la salle -->
              <div class="absolute bottom-4 left-4 text-white">
                <h4 class="font-bold text-lg">{{ event.titre }}</h4>
                <p class="text-white/90 text-sm">{{ event.salle?.nom }} • {{ event.salle?.ville }}</p>
              </div>
            </div>
            
            <!-- Corps de l'événement -->
            <div class="p-6 space-y-4">
              <!-- Type d'événement -->
              <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">
                  {{ event.type || 'Événement' }}
                </span>
              </div>
              
              <!-- Description -->
              <p class="text-gray-600 text-sm line-clamp-2">
                {{ event.description }}
              </p>
              
              <!-- Date et heure -->
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2 text-purple-600">
                  <i class="fas fa-calendar"></i>
                  <span class="font-medium">{{ formatDate(event.date_debut) }}</span>
                </div>
                <div class="flex items-center gap-2 text-gray-500">
                  <i class="fas fa-clock"></i>
                  <span>{{ formatTime(event.date_debut) }} - {{ formatTime(event.date_fin) }}</span>
                </div>
              </div>
              
              <!-- Informations supplémentaires limitées -->
              <div class="flex items-center justify-between text-xs text-gray-500">
                <div class="flex items-center gap-1">
                  <i class="fas fa-map-marker-alt"></i>
                  <span>{{ event.lieu || 'Lieu à définir' }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <i class="fas fa-users"></i>
                  <span>{{ event.capacite_max || '0' }} places</span>
                </div>
              </div>
              
              <!-- Boutons d'action -->
              <div class="flex gap-3">
                <Link 
                  :href="'/client/evenements/' + event.id"
                  class="flex-1 px-4 py-2 bg-white border border-purple-600 text-purple-600 text-center font-medium rounded-lg hover:bg-purple-50 transition-all duration-300"
                >
                  <i class="fas fa-info-circle mr-2"></i>
                  Voir détails
                </Link>
                
                <button 
                  v-if="event.places_disponibles > 0"
                  @click="reserverPlace(event)"
                  class="flex-1 px-4 py-2 bg-green-600 text-white text-center font-medium rounded-lg hover:bg-green-700 transition-all duration-300"
                >
                  <i class="fas fa-user-plus mr-2"></i>
                  S'inscrire
                </button>
                
                <button 
                  v-else
                  disabled
                  class="flex-1 px-4 py-2 bg-gray-400 text-white text-center font-medium rounded-lg cursor-not-allowed"
                >
                  <i class="fas fa-times mr-2"></i>
                  Complet
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="evenements.data.length > 0" class="mt-8 flex justify-center">
          <div class="flex items-center gap-2">
            <Link 
              v-if="evenements.prev_page_url"
              :href="evenements.prev_page_url"
              class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <i class="fas fa-chevron-left"></i>
            </Link>
            
            <span class="px-4 py-2 text-gray-700">
              Page {{ evenements.current_page }} sur {{ evenements.last_page }}
            </span>
            
            <Link 
              v-if="evenements.next_page_url"
              :href="evenements.next_page_url"
              class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <i class="fas fa-chevron-right"></i>
            </Link>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal de réservation -->
    <div v-if="showReservationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <!-- Header du modal -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
          <h3 class="text-xl font-bold text-gray-900">Inscription à l'événement</h3>
          <button @click="closeReservationModal" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <!-- Contenu du modal -->
        <div class="p-6 space-y-6">
          <!-- Informations de l'événement -->
          <div v-if="selectedEvent" class="bg-purple-50 rounded-lg p-4">
            <h4 class="font-semibold text-purple-900 mb-2">{{ selectedEvent.titre }}</h4>
            <div class="text-sm text-purple-700 space-y-1">
              <div class="flex items-center gap-2">
                <i class="fas fa-calendar"></i>
                <span>{{ formatDate(selectedEvent.date_debut) }}</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="fas fa-clock"></i>
                <span>{{ formatTime(selectedEvent.date_debut) }} - {{ formatTime(selectedEvent.date_fin) }}</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ selectedEvent.lieu || 'Lieu à définir' }}</span>
              </div>
            </div>
          </div>

          <!-- Formulaire de réservation -->
          <form @submit.prevent="submitReservation" class="space-y-4">
            <!-- Nom -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Nom complet <span class="text-red-500">*</span>
              </label>
              <input 
                v-model="reservationForm.nom"
                type="text" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                placeholder="Entrez votre nom complet"
                :class="{'border-red-500': reservationErrors.nom}"
              >
              <p v-if="reservationErrors.nom" class="mt-1 text-sm text-red-600">{{ reservationErrors.nom }}</p>
            </div>

            <!-- WhatsApp -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Numéro WhatsApp <span class="text-red-500">*</span>
              </label>
              <input 
                v-model="reservationForm.whatsapp"
                type="tel" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                placeholder="+221 77 123 45 67"
                :class="{'border-red-500': reservationErrors.whatsapp}"
              >
              <p v-if="reservationErrors.whatsapp" class="mt-1 text-sm text-red-600">{{ reservationErrors.whatsapp }}</p>
              <p class="mt-1 text-xs text-gray-500">Format: +221 XX XXX XX XX</p>
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Email <span class="text-red-500">*</span>
              </label>
              <input 
                v-model="reservationForm.email"
                type="email" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                placeholder="votre.email@example.com"
                :class="{'border-red-500': reservationErrors.email}"
              >
              <p v-if="reservationErrors.email" class="mt-1 text-sm text-red-600">{{ reservationErrors.email }}</p>
            </div>

            <!-- Informations supplémentaires -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="font-medium text-gray-900 mb-2">Informations importantes</h4>
              <ul class="text-sm text-gray-600 space-y-1">
                <li class="flex items-start gap-2">
                  <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                  <span>Confirmation par WhatsApp dans les 24h</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                  <span>Annulation gratuite jusqu'à 48h avant</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                  <span>Paiement sur place ou par Mobile Money</span>
                </li>
              </ul>
            </div>
          </form>
        </div>

        <!-- Actions du modal -->
        <div class="flex gap-3 p-6 border-t border-gray-200">
          <button 
            @click="closeReservationModal"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
          >
            Annuler
          </button>
          <button 
            @click="console.log('Bouton cliqué'); submitReservation()"
            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            <i class="fas fa-check mr-2"></i>
            Confirmer l'inscription
          </button>
        </div>
      </div>
    </div>
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
</style>

<!-- Notification Modal -->
<NotificationModal
  :show="showNotificationModal"
  :type="notificationType"
  :title="notificationTitle"
  :message="notificationMessage"
  @close="showNotificationModal = false"
/>
