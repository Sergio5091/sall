<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import NotificationModal from '../../Components/NotificationModal.vue';

const props = defineProps({
    salle: Object,
    sallesSimilaires: Array,
    aReserve: Boolean
});

// États pour les modaux
const showNotificationModal = ref(false);
const notificationType = ref('error');
const notificationTitle = ref('');
const notificationMessage = ref('');

const form = ref({
    date_heure: '',
    duree: 1,
    nombre_personnes: 1,
    message: '',
    type_evenement: 'gaming',
    besoins_speciaux: '',
    contact_telephone: '',
    accepte_conditions: false
});

const typesEvenement = [
    { value: 'gaming', label: 'Session Gaming' },
    { value: 'tournoi', label: 'Tournoi' },
    { value: 'soiree', label: 'Soirée Gaming' },
    { value: 'formation', label: 'Formation/Atelier' },
    { value: 'anniversaire', label: 'Anniversaire' },
    { value: 'autre', label: 'Autre' }
];

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

// Formater la date complète
const formatDateComplet = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Calculer le prix total
const prixTotal = ref(0);

watch(() => form.value.duree, (newDuree) => {
    prixTotal.value = props.salle.prix_heure * newDuree;
});

// Soumettre la réservation
const reserver = () => {
    // Validation côté client
    if (!form.value.accepte_conditions) {
        notificationType.value = 'error';
        notificationTitle.value = 'Conditions requises';
        notificationMessage.value = 'Vous devez accepter les conditions générales pour continuer.';
        showNotificationModal.value = true;
        return;
    }

    if (!form.value.date_heure) {
        notificationType.value = 'error';
        notificationTitle.value = 'Champ requis';
        notificationMessage.value = 'Veuillez sélectionner une date et heure de début.';
        showNotificationModal.value = true;
        return;
    }

    const formData = new FormData();
    formData.append('date_heure', form.value.date_heure);
    formData.append('duree', form.value.duree);
    formData.append('nombre_personnes', form.value.nombre_personnes);
    formData.append('message', form.value.message);
    formData.append('type_evenement', form.value.type_evenement);
    formData.append('besoins_speciaux', form.value.besoins_speciaux);
    formData.append('contact_telephone', form.value.contact_telephone);
    formData.append('accepte_conditions', form.value.accepte_conditions);

    router.post(`/client/salles/${props.salle.id}/reserver`, formData, {
        onStart: () => {
            // Afficher un indicateur de chargement
            const button = document.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Réservation en cours...';
            }
        },
        onSuccess: () => {
            // Rediriger vers la page de détails avec un message de succès
            router.reload();
        },
        onError: (errors) => {
            // Gérer les erreurs de validation
            console.error('Erreurs de validation:', errors);
            
            // Réactiver le bouton
            const button = document.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-calendar-check mr-2"></i>Confirmer la réservation';
            }
            
            // Afficher les erreurs spécifiques
            if (errors.date_heure) {
                notificationType.value = 'error';
                notificationTitle.value = 'Erreur de date';
                notificationMessage.value = 'Erreur de date: ' + errors.date_heure[0];
                showNotificationModal.value = true;
            } else if (errors.nombre_personnes) {
                notificationType.value = 'error';
                notificationTitle.value = 'Erreur de participants';
                notificationMessage.value = 'Erreur de participants: ' + errors.nombre_personnes[0];
                showNotificationModal.value = true;
            } else if (errors.capacite) {
                notificationType.value = 'error';
                notificationTitle.value = 'Erreur de capacité';
                notificationMessage.value = 'Erreur de capacité: ' + errors.capacite[0];
                showNotificationModal.value = true;
            } else {
                notificationType.value = 'error';
                notificationTitle.value = 'Erreur de réservation';
                notificationMessage.value = 'Une erreur est survenue lors de la réservation. Veuillez réessayer.';
                showNotificationModal.value = true;
            }
        }
    });
};
</script>

<template>
  <Head :title="`${salle.nom} - GameOn`" />
  
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden bg-background-light font-display">
    <!-- Header - Same as Dashboard page -->
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
            <a class="text-black text-sm font-medium text-accent-cyan" href="/client/salles">Salles</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/evenements">Événements</a>
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
            <Link href="/client/salles" class="text-text-light/50 hover:text-text-light">
              Salles
            </Link>
            <span class="mx-2 text-text-light/50">/</span>
            <span class="text-text-light">{{ salle.nom }}</span>
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
                v-if="salle.image_url"
                :src="salle.image_url.startsWith('http') ? salle.image_url : `/storage/${salle.image_url}`" 
                :alt="salle.nom"
                class="w-full h-full object-cover"
              >
              <div v-else class="w-full h-full flex items-center justify-center">
                <i class="fas fa-gamepad text-8xl text-white/50"></i>
              </div>
              
              <!-- Badge de favoris -->
              <div class="absolute top-4 right-4">
                <button class="p-3 bg-white/90 backdrop-blur-sm rounded-full shadow-lg hover:bg-white transition-colors">
                  <i class="fas fa-heart text-primary"></i>
                </button>
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

          <!-- Informations de la salle -->
          <div class="bg-content-light rounded-xl p-6 mb-8">
            <h1 class="text-3xl font-bold text-text-light mb-4">{{ salle.nom }}</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="flex items-center text-text-light/70">
                <i class="fas fa-map-marker-alt mr-3 text-primary"></i>
                <div>
                  <div class="font-medium">{{ salle.ville }}, {{ salle.pays }}</div>
                  <div class="text-sm">{{ salle.adresse }}</div>
                </div>
              </div>
              
              <div class="flex items-center text-text-light/70">
                <i class="fas fa-user mr-3 text-primary"></i>
                <div>
                  <div class="font-medium">Propriétaire</div>
                  <div class="text-sm">{{ salle.promoter?.name || 'Non spécifié' }}</div>
                </div>
              </div>
            </div>

            <div class="prose max-w-none text-text-light/70 mb-8">
              <p>{{ salle.description || 'Salle de gaming moderne équipée du meilleur matériel pour vos sessions de gaming.' }}</p>
            </div>

            <!-- Équipements et caractéristiques détaillées -->
            <div class="space-y-6 mb-8">
              <h3 class="text-xl font-bold text-text-light mb-4">Caractéristiques et équipements</h3>
              
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-subtle-light rounded-lg">
                  <i class="fas fa-users text-2xl text-primary mb-2"></i>
                  <div class="font-medium text-text-light">{{ formatCapacity(salle.capacite_max) }}</div>
                  <div class="text-sm text-text-light/50">Places</div>
                </div>
                
                <div class="text-center p-4 bg-subtle-light rounded-lg">
                  <i class="fas fa-ruler-combined text-2xl text-primary mb-2"></i>
                  <div class="font-medium text-text-light">{{ salle.surface || 'Non spécifiée' }} m²</div>
                  <div class="text-sm text-text-light/50">Surface</div>
                </div>
                
                <div class="text-center p-4 bg-subtle-light rounded-lg">
                  <i class="fas fa-wifi text-2xl text-primary mb-2"></i>
                  <div class="font-medium text-text-light">WiFi</div>
                  <div class="text-sm text-text-light/50">Inclus</div>
                </div>
                
                <div class="text-center p-4 bg-subtle-light rounded-lg">
                  <i class="fas fa-parking text-2xl text-primary mb-2"></i>
                  <div class="font-medium text-text-light">Parking</div>
                  <div class="text-sm text-text-light/50">{{ salle.parking ? 'Disponible' : 'Non disponible' }}</div>
                </div>
                
                <div class="text-center p-4 bg-subtle-light rounded-lg">
                  <i class="fas fa-shield-alt text-2xl text-primary mb-2"></i>
                  <div class="font-medium text-text-light">Sécurisé</div>
                  <div class="text-sm text-text-light/50">24/7</div>
                </div>
                
                <div class="text-center p-4 bg-subtle-light rounded-lg">
                  <i class="fas fa-snowflake text-2xl text-primary mb-2"></i>
                  <div class="font-medium text-text-light">Climatisation</div>
                  <div class="text-sm text-text-light/50">{{ salle.air_conditionne ? 'Oui' : 'Non' }}</div>
                </div>
                
                <div class="text-center p-4 bg-subtle-light rounded-lg">
                  <i class="fas fa-tv text-2xl text-primary mb-2"></i>
                  <div class="font-medium text-text-light">Écrans</div>
                  <div class="text-sm text-text-light/50">{{ salle.ecrans || 'Non spécifié' }}</div>
                </div>
                
                <div class="text-center p-4 bg-subtle-light rounded-lg">
                  <i class="fas fa-gamepad text-2xl text-primary mb-2"></i>
                  <div class="font-medium text-text-light">Consoles</div>
                  <div class="text-sm text-text-light/50">{{ salle.consoles || 'Non spécifié' }}</div>
                </div>
                
                <div class="text-center p-4 bg-subtle-light rounded-lg">
                  <i class="fas fa-volume-up text-2xl text-primary mb-2"></i>
                  <div class="font-medium text-text-light">Système audio</div>
                  <div class="text-sm text-text-light/50">{{ salle.systeme_audio || 'Non spécifié' }}</div>
                </div>
              </div>
            </div>

            <!-- Tous les événements -->
            <div class="mb-8">
              <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                  Tous les événements
                </h3>
                <div class="flex items-center gap-2">
                  <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm rounded-full font-medium">
                    {{ salle.evenements?.length || 0 }} événements
                  </span>
                </div>
              </div>

              <!-- Message si aucun événement -->
              <div v-if="!salle.evenements || salle.evenements.length === 0" class="text-center py-12">
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8">
                  <i class="fas fa-calendar-xmark text-4xl text-purple-300 mb-4"></i>
                  <h4 class="text-lg font-medium text-gray-700 mb-2">Aucun événement prévu</h4>
                  <p class="text-gray-500">Cette salle n'a pas d'événements programmés pour le moment.</p>
                </div>
              </div>

              <!-- Grille d'événements -->
              <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div 
                  v-for="event in salle.evenements" 
                  :key="event.id"
                  class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-purple-100"
                >
                  <!-- Header de l'événement -->
                  <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-4 text-white">
                    <div class="flex justify-between items-start mb-2">
                      <h4 class="font-bold text-lg flex-1">{{ event.titre }}</h4>
                      <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs rounded-full font-medium">
                        {{ event.statut || 'Actif' }}
                      </span>
                    </div>
                    <p class="text-white/90 text-sm">{{ event.description }}</p>
                  </div>
                  
                  <!-- Corps de l'événement -->
                  <div class="p-6 space-y-4">
                    <!-- Date et heure -->
                    <div class="flex items-center gap-3 text-sm">
                      <div class="flex items-center gap-2 text-purple-600">
                        <i class="fas fa-calendar"></i>
                        <span class="font-medium">{{ formatDateComplet(event.date_debut) }}</span>
                      </div>
                      <div v-if="event.date_fin" class="flex items-center gap-2 text-gray-500">
                        <i class="fas fa-clock"></i>
                        <span>{{ formatDateComplet(event.date_fin) }}</span>
                      </div>
                    </div>
                    
                    <!-- Informations complémentaires -->
                    <div class="flex items-center justify-between text-sm">
                      <div class="flex items-center gap-4 text-gray-600">
                        <div v-if="event.participants_max" class="flex items-center gap-1">
                          <i class="fas fa-users text-purple-500"></i>
                          <span>{{ event.participants_max }} max</span>
                        </div>
                        <div class="flex items-center gap-1">
                          <i class="fas fa-user-tie text-purple-500"></i>
                          <span>{{ event.organisateur || 'Propriétaire' }}</span>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Bouton d'action -->
                    <div class="flex gap-3">
                      <Link 
                        :href="`/events/${event.id}`"
                        class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300 text-center"
                      >
                        <i class="fas fa-eye mr-2"></i>
                        Voir détails
                      </Link>
                      <button class="px-4 py-2 bg-purple-100 text-purple-700 text-sm font-medium rounded-lg hover:bg-purple-200 transition-colors">
                        <i class="fas fa-share mr-2"></i>
                        Partager
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Salles similaires -->
          <div v-if="sallesSimilaires && sallesSimilaires.length > 0">
            <h3 class="text-xl font-bold text-text-light mb-4">Salles similaires</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div 
                v-for="salleSimilaire in sallesSimilaires" 
                :key="salleSimilaire.id"
                class="bg-content-light rounded-lg overflow-hidden hover:shadow-lg transition-shadow cursor-pointer"
                @click="$inertia.visit(`/client/salles/${salleSimilaire.id}`)"
              >
                <!-- Image de la salle similaire -->
                <div class="h-40 bg-gradient-to-br from-primary to-primary/70 relative">
                  <img 
                    v-if="salleSimilaire.image_url"
                    :src="salleSimilaire.image_url.startsWith('http') ? salleSimilaire.image_url : `/storage/${salleSimilaire.image_url}`" 
                    :alt="salleSimilaire.nom"
                    class="w-full h-full object-cover"
                  >
                  <div v-else class="w-full h-full flex items-center justify-center">
                    <i class="fas fa-gamepad text-3xl text-white/50"></i>
                  </div>
                </div>
                
                <div class="p-4">
                  <h4 class="font-medium text-text-light mb-2">{{ salleSimilaire.nom }}</h4>
                  <div class="text-sm text-text-light/70 mb-3">{{ salleSimilaire.ville }}</div>
                  
                  <!-- Caractéristiques principales -->
                  <div class="flex items-center gap-4 text-sm text-text-light/60 mb-3">
                    <div class="flex items-center gap-1">
                      <i class="fas fa-users text-xs"></i>
                      <span>{{ formatCapacity(salleSimilaire.capacite_max) }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                      <i class="fas fa-wifi text-xs"></i>
                      <span>WiFi</span>
                    </div>
                  </div>
                  
                  <div class="flex justify-between items-center">
                    <div>
                      <span class="text-primary font-bold text-lg">{{ formatPrice(salleSimilaire.prix_heure) }}</span>
                      <span class="text-text-light/50 text-xs">/heure</span>
                    </div>
                    <button class="px-3 py-1 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary/90 transition-colors">
                      Voir détails
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
          <!-- Informations de contact -->
          <div class="bg-content-light rounded-xl p-6 sticky top-6 shadow-lg">
            <div class="mb-6">
              <div class="text-3xl font-bold text-text-light">{{ formatPrice(salle.prix_heure) }}</div>
              <div class="text-sm text-text-light/50">par heure</div>
            </div>

            <!-- Message si déjà réservé -->
            <div v-if="aReserve" class="mb-6 p-4 bg-primary/10 rounded-lg">
              <div class="flex items-center text-primary">
                <i class="fas fa-check-circle mr-2"></i>
                <span>Vous avez déjà réservé cette salle</span>
              </div>
            </div>

            
            <!-- Caractéristiques rapides -->
            <div class="border-t border-border-light pt-4 mt-6">
              <h3 class="text-lg font-medium text-text-light mb-3">Caractéristiques</h3>
              <div class="space-y-2 text-sm text-text-light/70">
                <div class="flex items-center">
                  <i class="fas fa-users mr-2 text-primary"></i>
                  <span>Capacité : {{ formatCapacity(salle.capacite_max) }} personnes</span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-wifi mr-2 text-primary"></i>
                  <span>WiFi inclus</span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-parking mr-2 text-primary"></i>
                  <span>Parking disponible</span>
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

<style scoped>
/* Exact colors from the design */
.bg-background-light { background-color: #f6f8f6; }
.bg-content-light { background-color: #ffffff; }
.bg-subtle-light { background-color: #f0f4f2; }
.text-text-light { color: #111813; }
.text-text-light\/70 { color: #111813; opacity: 0.7; }
.text-text-light\/50 { color: #111813; opacity: 0.5; }
.text-primary { color: #3b82f6; }
.bg-primary { background-color: #3b82f6; }
.border-border-light { border-color: #dbe6df; }
.hover\:bg-border-light:hover { background-color: #dbe6df; }

/* Accent cyan color */
.text-accent-cyan { color: #06b6d4; }
.hover\:text-accent-cyan:hover { color: #06b6d4; }

/* Material Icons configuration */
.material-symbols-outlined {
  font-variation-settings:
    'FILL' 0,
    'wght' 400,
    'GRAD' 0,
    'opsz' 24;
  vertical-align: middle;
}

.material-icons-round {
  font-family: 'Material Icons Round';
  font-weight: normal;
  font-style: normal;
  font-size: 24px;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-block;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  font-feature-settings: 'liga';
  -webkit-font-smoothing: antialiased;
}

/* Font family */
.font-display {
  font-family: "Plus Jakarta Sans", sans-serif;
}

/* Custom border radius values */
.rounded-lg {
  border-radius: 1rem;
}

.rounded-full {
  border-radius: 9999px;
}

/* Tracking utility */
.tracking-light {
  letter-spacing: -0.025em;
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
