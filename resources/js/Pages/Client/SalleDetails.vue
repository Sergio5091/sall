<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
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
const showReservationModal = ref(false);
const isFavorite = ref(false);
const showAllPhotos = ref(false);

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
    { value: 'gaming', label: 'Session Gaming', icon: 'fa-gamepad' },
    { value: 'tournoi', label: 'Tournoi', icon: 'fa-trophy' },
    { value: 'soiree', label: 'Soirée Gaming', icon: 'fa-users' },
    { value: 'formation', label: 'Formation', icon: 'fa-graduation-cap' },
    { value: 'anniversaire', label: 'Anniversaire', icon: 'fa-birthday-cake' },
    { value: 'autre', label: 'Autre', icon: 'fa-ellipsis-h' }
];

// Formater le prix
const formatPrice = (prix) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(prix || 0);
};

// Formater la capacité
const formatCapacity = (capacite) => {
    return new Intl.NumberFormat('fr-FR').format(capacite || 0);
};

// Formater la date
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

// Formater l'heure
const formatTime = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Images de la galerie
const galleryImages = computed(() => {
    const images = [];
    
    if (props.salle?.image_couverture) {
        images.push(props.salle.image_couverture);
    }
    
    if (props.salle?.images_galerie && Array.isArray(props.salle.images_galerie)) {
        images.push(...props.salle.images_galerie);
    }
    
    if (props.salle?.images && Array.isArray(props.salle.images)) {
        images.push(...props.salle.images);
    }
    
    // Ajouter des images par défaut si aucune image
    if (images.length === 0) {
        images.push(
            'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1534423861386-85a16f5d13fd?auto=format&fit=crop&w-800&q=80',
            'https://images.unsplash.com/photo-1593305841991-05c297ba4575?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80'
        );
    }
    
    return images;
});

const mainImageIndex = ref(0);
const mainImageSrc = computed(() => {
    if (!galleryImages.value[mainImageIndex.value]) return '';
    const img = galleryImages.value[mainImageIndex.value];
    return img.startsWith('http') ? img : `/storage/${img}`;
});

// Calculer le prix total
const prixTotal = computed(() => {
    const prixHeure = parseInt(props.salle?.prix_heure) || 0;
    const duree = form.value.duree || 1;
    const fraisService = 1500;
    const fraisNettoyage = 1000;
    return (prixHeure * duree) + fraisService + fraisNettoyage;
});

// Services disponibles
const services = computed(() => {
    const serv = [];
    if (props.salle?.snack_bar) serv.push('Snack bar');
    if (props.salle?.restaurant) serv.push('Restaurant');
    if (props.salle?.bar) serv.push('Bar');
    if (props.salle?.terrasse) serv.push('Terrasse');
    if (props.salle?.vestiaires) serv.push('Vestiaires');
    if (props.salle?.accessibilite_pmr) serv.push('Accessible PMR');
    if (props.salle?.wifi) serv.push('WiFi haut débit');
    if (props.salle?.climatisation) serv.push('Climatisation');
    return serv;
});

// Équipements gaming
const equipements = computed(() => {
    const equip = [];
    if (props.salle?.pc_gaming) equip.push(`${props.salle.pc_gaming} PC Gaming`);
    if (props.salle?.consoles_retro) equip.push(`${props.salle.consoles_retro} consoles rétro`);
    if (props.salle?.casques_vr) equip.push(`${props.salle.casques_vr} casques VR`);
    if (props.salle?.machines_arcade) equip.push(`${props.salle.machines_arcade} machines arcade`);
    if (props.salle?.ecrans) equip.push('Écrans gaming');
    if (props.salle?.son) equip.push('Système audio');
    return equip;
});

// Toggle favori
const toggleFavorite = () => {
    isFavorite.value = !isFavorite.value;
    // API call pour sauvegarder
    showNotification('success', 'Favori', 
        isFavorite.value ? 'Salle ajoutée aux favoris' : 'Salle retirée des favoris'
    );
};

// Soumettre la réservation
const submitReservation = async () => {
    if (!form.value.accepte_conditions) {
        showNotification('error', 'Conditions requises', 
            'Vous devez accepter les conditions générales pour continuer.'
        );
        return;
    }

    if (!form.value.date_heure) {
        showNotification('error', 'Champ requis', 
            'Veuillez sélectionner une date et heure de début.'
        );
        return;
    }

    const formData = new FormData();
    Object.keys(form.value).forEach(key => {
        formData.append(key, form.value[key]);
    });

    try {
        await router.post(`/client/salles/${props.salle.id}/reserver`, formData, {
            onSuccess: () => {
                showNotification('success', 'Réservation envoyée', 
                    'Votre demande de réservation a été envoyée. Vous recevrez une confirmation par email.'
                );
                showReservationModal.value = false;
                form.value = {
                    date_heure: '',
                    duree: 1,
                    nombre_personnes: 1,
                    message: '',
                    type_evenement: 'gaming',
                    besoins_speciaux: '',
                    contact_telephone: '',
                    accepte_conditions: false
                };
            },
            onError: (errors) => {
                if (errors.date_heure) {
                    showNotification('error', 'Erreur de date', errors.date_heure[0]);
                } else if (errors.capacite) {
                    showNotification('error', 'Erreur de capacité', errors.capacite[0]);
                } else {
                    showNotification('error', 'Erreur', 
                        'Une erreur est survenue. Veuillez réessayer.'
                    );
                }
            }
        });
    } catch (error) {
        showNotification('error', 'Erreur', 'Une erreur est survenue.');
    }
};

// Afficher une notification
const showNotification = (type, title, message) => {
    notificationType.value = type;
    notificationTitle.value = title;
    notificationMessage.value = message;
    showNotificationModal.value = true;
};

// Partager la salle
const shareSalle = () => {
    if (navigator.share) {
        navigator.share({
            title: props.salle.nom,
            text: `Découvrez ${props.salle.nom} sur GameOn !`,
            url: window.location.href,
        }).catch(() => {
            copyToClipboard(window.location.href);
            showNotification('success', 'Lien copié', 
                'Le lien a été copié dans le presse-papier.'
            );
        });
    } else {
        copyToClipboard(window.location.href);
        showNotification('success', 'Lien copié', 
            'Le lien a été copié dans le presse-papier.'
        );
    }
};

// Copier dans le presse-papier
const copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text);
    } catch (err) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    }
};

// Note moyenne calculée
const averageRating = computed(() => {
    const avis = props.salle?.avis || [];
    if (avis.length === 0) return 0;
    const sum = avis.reduce((acc, avis) => acc + avis.note, 0);
    return (sum / avis.length).toFixed(1);
});

// Horaires formatés
const horairesFormatted = computed(() => {
    const horaires = props.salle?.horaires_ouverture || {};
    const jours = {
        'lundi': 'Lun',
        'mardi': 'Mar',
        'mercredi': 'Mer',
        'jeudi': 'Jeu',
        'vendredi': 'Ven',
        'samedi': 'Sam',
        'dimanche': 'Dim'
    };
    
    return Object.entries(horaires).map(([jour, heures]) => ({
        jour: jours[jour] || jour,
        heures: heures || 'Fermé'
    }));
});
</script>

<template>
  <Head :title="`${salle?.nom || 'Salle'} - GameOn`" />
  
  <div class="min-h-screen bg-gray-50 font-sans">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center gap-8">
            <Link href="/client/dashboard" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
              <i class="fas fa-gamepad text-xl text-blue-600"></i>
              <span class="text-lg font-bold text-gray-900">GameOn</span>
            </Link>
            <nav class="hidden md:flex items-center gap-6">
              <Link href="/client/dashboard" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">Dashboard</Link>
              <Link href="/client/salles" class="text-sm font-medium text-blue-600">Salles</Link>
              <Link href="/client/evenements" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">Événements</Link>
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
      <!-- Hero Section -->
      <div class="relative bg-gradient-to-br from-blue-600 to-purple-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <!-- Breadcrumb -->
          <nav class="flex items-center text-sm text-white/90 mb-4">
            <Link href="/client/salles" class="hover:text-white transition-colors">
              Salles
            </Link>
            <i class="fas fa-chevron-right mx-2 text-xs opacity-70"></i>
            <span class="font-medium">{{ salle?.nom || 'Détails' }}</span>
          </nav>

          <!-- Galerie -->
          <div class="flex flex-col lg:flex-row gap-6">
            <!-- Image principale -->
            <div class="lg:w-2/3">
              <div class="relative h-64 md:h-80 lg:h-96 rounded-xl overflow-hidden shadow-2xl">
                <img 
                  :src="mainImageSrc" 
                  :alt="salle?.nom"
                  class="w-full h-full object-cover"
                />
                
                <!-- Navigation des images -->
                <button 
                  @click="mainImageIndex = (mainImageIndex - 1 + galleryImages.length) % galleryImages.length"
                  class="absolute left-4 top-1/2 transform -translate-y-1/2 p-2 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors"
                >
                  <i class="fas fa-chevron-left"></i>
                </button>
                <button 
                  @click="mainImageIndex = (mainImageIndex + 1) % galleryImages.length"
                  class="absolute right-4 top-1/2 transform -translate-y-1/2 p-2 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors"
                >
                  <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Indicateurs -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
                  <div 
                    v-for="(_, index) in galleryImages" 
                    :key="index"
                    @click="mainImageIndex = index"
                    :class="[
                      'w-2 h-2 rounded-full transition-all cursor-pointer',
                      mainImageIndex === index ? 'bg-white w-8' : 'bg-white/50 hover:bg-white/70'
                    ]"
                  />
                </div>
              </div>
              
              <!-- Miniatures -->
              <div class="mt-4 grid grid-cols-4 gap-2">
                <button 
                  v-for="(image, index) in galleryImages.slice(0, 4)" 
                  :key="index"
                  @click="mainImageIndex = index"
                  :class="[
                    'relative h-20 rounded-lg overflow-hidden border-2 transition-all',
                    mainImageIndex === index 
                      ? 'border-blue-500 ring-2 ring-blue-500 ring-opacity-50' 
                      : 'border-transparent hover:border-gray-300'
                  ]"
                >
                  <img 
                    :src="image.startsWith('http') ? image : `/storage/${image}`"
                    :alt="`Image ${index + 1}`"
                    class="w-full h-full object-cover"
                  />
                  <div 
                    v-if="index === 3 && galleryImages.length > 4"
                    class="absolute inset-0 bg-black/60 flex items-center justify-center text-white font-bold"
                  >
                    +{{ galleryImages.length - 4 }}
                  </div>
                </button>
              </div>
            </div>
            
            <!-- Infos rapides -->
            <div class="lg:w-1/3">
              <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-start justify-between mb-4">
                  <h1 class="text-2xl font-bold text-gray-900">{{ salle?.nom }}</h1>
                  <button 
                    @click="toggleFavorite"
                    :class="[
                      'p-2 rounded-full transition-colors',
                      isFavorite ? 'text-red-500 hover:text-red-600' : 'text-gray-400 hover:text-gray-600'
                    ]"
                  >
                    <i class="fas fa-heart text-xl"></i>
                  </button>
                </div>
                
                <!-- Rating -->
                <div class="flex items-center gap-2 mb-6">
                  <div class="flex">
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star-half-alt text-yellow-400"></i>
                  </div>
                  <span class="font-medium text-gray-900">{{ averageRating }}</span>
                  <span class="text-gray-500">({{ salle?.avis?.length || 0 }} avis)</span>
                </div>
                
                <!-- Localisation -->
                <div class="flex items-center gap-3 mb-6">
                  <div class="p-2 rounded-lg bg-blue-100 text-blue-600">
                    <i class="fas fa-map-marker-alt"></i>
                  </div>
                  <div>
                    <div class="font-medium text-gray-900">{{ salle?.ville }}, {{ salle?.pays }}</div>
                    <div class="text-sm text-gray-600">{{ salle?.adresse }}</div>
                  </div>
                </div>
                
                <!-- Capacité -->
                <div class="flex items-center gap-3 mb-6">
                  <div class="p-2 rounded-lg bg-purple-100 text-purple-600">
                    <i class="fas fa-users"></i>
                  </div>
                  <div>
                    <div class="font-medium text-gray-900">Capacité</div>
                    <div class="text-sm text-gray-600">{{ formatCapacity(salle?.capacite_max) }} personnes max</div>
                  </div>
                </div>
                
                <!-- Prix -->
                <div class="flex items-center gap-3 mb-6">
                  <div class="p-2 rounded-lg bg-green-100 text-green-600">
                    <i class="fas fa-tag"></i>
                  </div>
                  <div>
                    <div class="font-medium text-gray-900">Tarif horaire</div>
                    <div class="text-2xl font-bold text-gray-900">{{ formatPrice(salle?.prix_heure) }}</div>
                  </div>
                </div>
                
                <!-- Bouton réservation -->
                <button 
                  @click="showReservationModal = true"
                  class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all transform hover:-translate-y-0.5 shadow-lg shadow-blue-500/30"
                >
                  <i class="fas fa-calendar-plus mr-2"></i>
                  Réserver maintenant
                </button>
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
              <div class="prose prose-blue max-w-none">
                <p class="text-gray-700 leading-relaxed">
                  {{ salle?.description || 'Cette salle de gaming moderne est équipée des dernières technologies pour offrir une expérience immersive exceptionnelle. Parfaite pour les tournois, les soirées gaming ou les sessions entre amis.' }}
                </p>
              </div>
            </div>

            <!-- Équipements -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">Équipements</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="equipement in equipements" :key="equipement" class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                  <i class="fas fa-check-circle text-green-500"></i>
                  <span class="text-gray-700">{{ equipement }}</span>
                </div>
              </div>
            </div>

            <!-- Services -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">Services inclus</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="service in services" :key="service" class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                  <i class="fas fa-check text-blue-500"></i>
                  <span class="text-gray-700">{{ service }}</span>
                </div>
              </div>
            </div>

            <!-- Horaires -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">Horaires d'ouverture</h2>
              <div class="space-y-3">
                <div v-for="horaire in horairesFormatted" :key="horaire.jour" class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                  <span class="font-medium text-gray-700">{{ horaire.jour }}</span>
                  <span class="font-bold text-gray-900">{{ horaire.heures }}</span>
                </div>
              </div>
            </div>

            <!-- Localisation -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">Localisation</h2>
              <div class="space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                  <div class="flex items-start gap-3">
                    <i class="fas fa-map-marker-alt text-blue-500 mt-1"></i>
                    <div>
                      <div class="font-medium text-gray-700 mb-1">Adresse</div>
                      <div class="text-gray-900">{{ salle?.adresse }}</div>
                      <div class="text-gray-600">{{ salle?.ville }}, {{ salle?.pays }}</div>
                    </div>
                  </div>
                </div>
                
                <!-- Carte -->
                <div class="h-64 bg-gray-200 rounded-lg overflow-hidden">
                  <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-green-100">
                    <div class="text-center">
                      <i class="fas fa-map-marker-alt text-4xl text-blue-500 mb-4"></i>
                      <div class="text-gray-700">{{ salle?.ville }}, {{ salle?.pays }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Colonne latérale -->
          <div class="space-y-6">
            <!-- Contact -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h3 class="text-xl font-bold text-gray-900 mb-4">Contact</h3>
              <div class="space-y-4">
                <div v-if="salle?.telephone" class="flex items-center gap-3">
                  <div class="p-2 rounded-lg bg-blue-100 text-blue-600">
                    <i class="fas fa-phone"></i>
                  </div>
                  <div>
                    <div class="text-sm text-gray-600">Téléphone</div>
                    <a :href="`tel:${salle.telephone}`" class="font-medium text-gray-900 hover:text-blue-600 transition-colors">
                      {{ salle.telephone }}
                    </a>
                  </div>
                </div>
                
                <div v-if="salle?.email" class="flex items-center gap-3">
                  <div class="p-2 rounded-lg bg-green-100 text-green-600">
                    <i class="fas fa-envelope"></i>
                  </div>
                  <div>
                    <div class="text-sm text-gray-600">Email</div>
                    <a :href="`mailto:${salle.email}`" class="font-medium text-gray-900 hover:text-blue-600 transition-colors">
                      {{ salle.email }}
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Partage -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h3 class="text-xl font-bold text-gray-900 mb-4">Partager</h3>
              <div class="flex gap-3">
                <button @click="shareSalle" class="flex-1 p-3 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                  <i class="fas fa-share-alt"></i>
                </button>
                <button @click="toggleFavorite" class="flex-1 p-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>

            <!-- Salles similaires -->
            <div v-if="sallesSimilaires?.length" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h3 class="text-xl font-bold text-gray-900 mb-4">Salles similaires</h3>
              <div class="space-y-4">
                <Link 
                  v-for="salleSim in sallesSimilaires.slice(0, 3)" 
                  :key="salleSim.id"
                  :href="`/client/salles/${salleSim.id}`"
                  class="group flex gap-3 p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all"
                >
                  <div class="w-16 h-16 flex-shrink-0 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <i class="fas fa-gamepad text-white text-lg"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors truncate">
                      {{ salleSim.nom }}
                    </h4>
                    <div class="flex items-center gap-2 mt-1">
                      <i class="fas fa-map-marker-alt text-gray-400 text-xs"></i>
                      <span class="text-sm text-gray-600 truncate">{{ salleSim.ville }}</span>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                      <span class="text-blue-600 font-medium">{{ formatPrice(salleSim.prix_heure) }}</span>
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

    <!-- Modal de réservation -->
    <div v-if="showReservationModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showReservationModal = false"></div>
      <div class="relative w-full max-w-2xl rounded-xl bg-white shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold text-gray-900">Réservation</h3>
              <p class="text-sm text-gray-600 mt-1">{{ salle?.nom }}</p>
            </div>
            <button @click="showReservationModal = false" class="p-2 rounded-lg hover:bg-gray-100">
              <i class="fas fa-times text-gray-500"></i>
            </button>
          </div>
        </div>
        
        <div class="p-6">
          <form @submit.prevent="submitReservation" class="space-y-6">
            <!-- Informations de base -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date et heure *</label>
                <input 
                  v-model="form.date_heure" 
                  type="datetime-local" 
                  required
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durée (heures) *</label>
                <select 
                  v-model="form.duree" 
                  required
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option :value="1">1 heure</option>
                  <option :value="2">2 heures</option>
                  <option :value="3">3 heures</option>
                  <option :value="4">4 heures</option>
                  <option :value="5">5 heures</option>
                  <option :value="6">6 heures</option>
                  <option :value="8">8 heures</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de personnes *</label>
                <input 
                  v-model="form.nombre_personnes" 
                  type="number" 
                  min="1" 
                  :max="salle?.capacite_max"
                  required
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
                <p class="text-xs text-gray-500 mt-2">Capacité max : {{ formatCapacity(salle?.capacite_max) }} personnes</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type d'événement *</label>
                <select 
                  v-model="form.type_evenement" 
                  required
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option v-for="type in typesEvenement" :key="type.value" :value="type.value">
                    <i :class="type.icon"></i> {{ type.label }}
                  </option>
                </select>
              </div>
            </div>

            <!-- Contact -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone de contact *</label>
              <input 
                v-model="form.contact_telephone" 
                type="tel" 
                required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="+221 77 123 45 67"
              />
            </div>

            <!-- Message -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Message au propriétaire</label>
              <textarea 
                v-model="form.message" 
                rows="3"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Précisez vos besoins..."
              ></textarea>
            </div>

            <!-- Besoins spéciaux -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Besoins spécifiques (optionnel)</label>
              <textarea 
                v-model="form.besoins_speciaux" 
                rows="2"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Équipements supplémentaires, configuration spéciale..."
              ></textarea>
            </div>

            <!-- Récapitulatif -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="font-semibold text-gray-900 mb-3">Récapitulatif</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">{{ formatPrice(salle?.prix_heure) }} × {{ form.duree }} heure(s)</span>
                  <span class="font-medium">{{ formatPrice((salle?.prix_heure || 0) * form.duree) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Frais de service</span>
                  <span class="font-medium">{{ formatPrice(1500) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Frais de nettoyage</span>
                  <span class="font-medium">{{ formatPrice(1000) }}</span>
                </div>
                <div class="border-t border-gray-200 pt-2 mt-2">
                  <div class="flex justify-between font-bold text-lg">
                    <span>Total estimé</span>
                    <span>{{ formatPrice(prixTotal) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Conditions -->
            <div class="border-t border-gray-200 pt-4">
              <label class="flex items-start gap-3">
                <input 
                  v-model="form.accepte_conditions" 
                  type="checkbox" 
                  required
                  class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                />
                <span class="text-sm text-gray-700">
                  J'accepte les conditions générales de réservation. Cette demande sera soumise à validation par le propriétaire.
                </span>
              </label>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
              <button 
                type="button"
                @click="showReservationModal = false"
                class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium"
              >
                Annuler
              </button>
              <button 
                type="submit"
                class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
              >
                <i class="fas fa-paper-plane mr-2"></i>
                Envoyer la demande
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Notification Modal -->
    <NotificationModal 
      v-if="showNotificationModal"
      :type="notificationType"
      :title="notificationTitle"
      :message="notificationMessage"
      @close="showNotificationModal = false"
    />
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

/* Custom scrollbar for modal */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>