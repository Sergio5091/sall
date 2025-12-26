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
const showReservationModal = ref(false);

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

// Labels pour les types et catégories
const typesSalle = {
    'arcade': 'Salle d\'arcade',
    'vr': 'Centre VR',
    'retro': 'Retro gaming',
    'esports': 'E-sport',
    'mixed': 'Mixte',
    'bowling': 'Bowling',
    'billard': 'Billard',
    'laser': 'Laser game',
    'escape': 'Escape game',
    'karaoke': 'Karaoke',
};

const categoriesSalle = {
    'bar': 'Bar',
    'restaurant': 'Restaurant',
    'club': 'Club',
    'centre_commercial': 'Centre commercial',
    'hotel': 'Hôtel',
    'complexe_sportif': 'Complexe sportif',
    'espace_jeux': 'Espace de jeux',
    'loisir': 'Centre de loisirs',
    'autre': 'Autre',
};

const getTypeLabel = (type) => {
    return typesSalle[type] || type;
};

const getCategorieLabel = (categorie) => {
    return categoriesSalle[categorie] || categorie;
};

// Gestion des images
const mainImage = ref(null);

// Obtenir les images de la galerie
const getGalleryImages = () => {
    const images = [];
    
    // Ajouter l'image de couverture d'abord
    if (props.salle.image_couverture) {
        images.push(props.salle.image_couverture);
    } else if (props.salle.image_url) {
        images.push(props.salle.image_url);
    }
    
    // Ajouter les images de la galerie
    if (props.salle.images_galerie && Array.isArray(props.salle.images_galerie)) {
        images.push(...props.salle.images_galerie);
    }
    
    // Ajouter les images supplémentaires si disponibles
    if (props.salle.images && Array.isArray(props.salle.images)) {
        images.push(...props.salle.images);
    }
    
    // Limiter à 4 images maximum pour les miniatures
    return images.slice(0, 4);
};

// Sélectionner l'image principale
const selectMainImage = (image) => {
    mainImage.value = image;
};

// Obtenir l'image principale à afficher
const getMainImageSrc = () => {
    if (mainImage.value) {
        return mainImage.value.startsWith('http') ? mainImage.value : `/storage/${mainImage.value}`;
    }
    
    if (props.salle.image_couverture) {
        return props.salle.image_couverture.startsWith('http') ? props.salle.image_couverture : `/storage/${props.salle.image_couverture}`;
    }
    
    if (props.salle.image_url) {
        return props.salle.image_url.startsWith('http') ? props.salle.image_url : `/storage/${props.salle.image_url}`;
    }
    
    return null;
};

// Calculer le prix total
const prixTotal = ref(0);

watch(() => form.value.duree, (newDuree) => {
    prixTotal.value = props.salle.prix_heure * newDuree;
});

// Soumettre la réservation
// Vérifier si la salle a des services
const hasServices = () => {
    return props.salle.snack_bar || props.salle.restaurant || props.salle.bar || 
           props.salle.terrasse || props.salle.vestiaires || props.salle.accessibilite_pmr;
};

// Vérifier si la salle a des équipements gaming
const hasGamingEquipment = () => {
    return props.salle.pc_gaming || props.salle.consoles_retro || 
           props.salle.casques_vr || props.salle.machines_arcade;
};

// Vérifier si la salle a des caractéristiques principales
const hasMainFeatures = () => {
    return hasServices() || hasGamingEquipment() || props.salle.point_repere || 
           props.salle.adresse || props.salle.categorie || props.salle.description;
};

const submitReservation = () => {
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
        onSuccess: (page) => {
            // Afficher un message de succès
            notificationType.value = 'success';
            notificationTitle.value = 'Réservation envoyée';
            notificationMessage.value = 'Votre demande de réservation a été envoyée avec succès. Vous recevrez une notification dès qu\'elle sera validée.';
            showNotificationModal.value = true;
            
            // Fermer le modal et réinitialiser le formulaire
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
            // Gérer les erreurs de validation
            console.error('Erreurs de validation:', errors);
            
            // Réactiver le bouton
            const button = document.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = false;
                button.innerHTML = 'Envoyer la demande';
            }
            
            // Afficher les erreurs spécifiques
            if (errors.date_heure) {
                notificationType.value = 'error';
                notificationTitle.value = 'Erreur de date';
                notificationMessage.value = errors.date_heure[0];
                showNotificationModal.value = true;
            } else if (errors.nombre_personnes) {
                notificationType.value = 'error';
                notificationTitle.value = 'Erreur de participants';
                notificationMessage.value = errors.nombre_personnes[0];
                showNotificationModal.value = true;
            } else if (errors.capacite) {
                notificationType.value = 'error';
                notificationTitle.value = 'Erreur de capacité';
                notificationMessage.value = errors.capacite[0];
                showNotificationModal.value = true;
            } else if (errors.message) {
                notificationType.value = 'error';
                notificationTitle.value = 'Erreur de soumission';
                notificationMessage.value = errors.message[0];
                showNotificationModal.value = true;
            } else {
                notificationType.value = 'error';
                notificationTitle.value = 'Erreur de réservation';
                notificationMessage.value = 'Une erreur est survenue. Veuillez vérifier tous les champs et réessayer.';
                showNotificationModal.value = true;
            }
        },
        onFinish: () => {
            // Réactiver le bouton dans tous les cas
            const button = document.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = false;
                button.innerHTML = 'Envoyer la demande';
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
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Hero Section avec galerie -->
        <div class="mb-8 lg:mb-12">
          <!-- Galerie d'images -->
          <section class="rounded-lg lg:rounded-xl overflow-hidden flex flex-col gap-3 lg:gap-4 mb-6 lg:mb-8">
            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg lg:rounded-xl cursor-pointer hover:opacity-95 transition-opacity relative group" :style="getMainImageSrc() ? `background-image: url(${getMainImageSrc()})` : ''">
              <div v-if="!getMainImageSrc()" class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary to-primary/70">
                <i class="fas fa-gamepad text-6xl lg:text-9xl text-white/50"></i>
              </div>
              
              <!-- Badge de favoris -->
              <div class="absolute top-4 lg:top-6 right-4 lg:right-6">
                <button class="p-2 lg:p-3 bg-white/90 backdrop-blur-sm rounded-full shadow-xl hover:bg-white transition-all duration-300 hover:scale-110">
                  <i class="fas fa-heart text-primary text-lg lg:text-xl"></i>
                </button>
              </div>
              
              <!-- Overlay d'informations -->
              <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 lg:p-8">
                <h1 class="text-2xl lg:text-5xl font-bold text-white mb-2 lg:mb-3">{{ salle.nom }}</h1>
                <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-8 text-white/90">
                  <div class="flex items-center gap-2 lg:gap-3">
                    <i class="fas fa-map-marker-alt text-sm lg:text-xl"></i>
                    <span class="text-sm lg:text-lg">{{ salle.ville }}, {{ salle.pays }}</span>
                  </div>
                  <div class="flex items-center gap-2 lg:gap-3">
                    <i class="fas fa-users text-sm lg:text-xl"></i>
                    <span class="text-sm lg:text-lg">{{ formatCapacity(salle.capacite_max) }} personnes</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <i class="fas fa-star text-yellow-400 text-sm lg:text-xl"></i>
                    <span class="text-sm lg:text-lg font-bold">4.92</span>
                    <span class="hidden lg:block text-lg underline cursor-pointer hover:text-white">(128 avis)</span>
                  </div>
                </div>
              </div>
              
              <!-- Bouton voir toutes les photos -->
              <div class="absolute bottom-2 lg:bottom-4 right-2 lg:right-4 bg-white/90 dark:bg-black/70 px-2 lg:px-4 py-1 lg:py-2 rounded-lg lg:rounded-xl text-xs lg:text-sm font-bold shadow-sm backdrop-blur-sm flex items-center gap-1 lg:gap-2">
                <i class="fas fa-images text-[14px] lg:text-[18px]"></i>
                <span class="hidden lg:inline">Voir toutes les photos</span>
                <span class="lg:hidden">Photos</span>
              </div>
            </div>
            
            <!-- Miniatures d'images -->
            <div class="grid grid-cols-4 lg:grid-cols-6 gap-2 lg:gap-3">
              <div 
                v-for="(image, index) in getGalleryImages()" 
                :key="index"
                class="w-full bg-center bg-no-repeat aspect-[4/3] bg-cover rounded-md lg:rounded-lg cursor-pointer hover:opacity-80 transition-opacity relative"
                :style="image ? `background-image: url(${image.startsWith('http') ? image : `/storage/${image}`})` : ''"
                @click="selectMainImage(image)"
              >
                <div v-if="!image" class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/20 to-primary/10">
                  <i class="fas fa-image text-primary/40 text-xl lg:text-2xl"></i>
                </div>
                <!-- Afficher +X si c'est la dernière miniature et qu'il y a plus d'images -->
                <div v-if="index === 3 && getGalleryImages().length > 4" class="absolute inset-0 bg-black/40 flex items-center justify-center rounded-md lg:rounded-lg">
                  <span class="text-white font-bold text-sm lg:text-lg">+{{ getGalleryImages().length - 4 }}</span>
                </div>
              </div>
            </div>
          </section>

          <!-- Navigation rapide (desktop uniquement) -->
          <div class="hidden lg:flex items-center gap-6 mb-8">
            <a href="#contact" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
              <i class="fas fa-phone text-primary"></i>
              <span class="font-medium">Contact</span>
            </a>
            <a href="#events" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
              <i class="fas fa-calendar text-primary"></i>
              <span class="font-medium">Événements</span>
            </a>
            <a href="#details" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
              <i class="fas fa-info-circle text-primary"></i>
              <span class="font-medium">Détails</span>
            </a>
          </div>
        </div>

        <!-- Layout principal -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
          <!-- Colonne principale -->
          <div class="lg:col-span-8 space-y-6 lg:space-y-8">
            <!-- Contact Information -->
            <section id="contact" class="bg-white rounded-lg lg:rounded-xl shadow-lg p-4 lg:p-8">
              <h3 class="text-xl lg:text-2xl font-bold mb-4 lg:mb-6 text-gray-900 flex items-center">
                <i class="fas fa-phone-alt mr-2 lg:mr-3 text-primary"></i>
                <span class="text-lg lg:text-xl">Contactez le propriétaire</span>
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3 lg:gap-4">
                <div v-if="salle.telephone" class="flex items-center gap-3 lg:gap-4 p-3 lg:p-4 bg-blue-50 rounded-lg lg:rounded-xl border border-blue-200">
                  <div class="p-2 lg:p-3 bg-blue-500 rounded-full">
                    <i class="fas fa-phone text-white text-sm lg:text-xl"></i>
                  </div>
                  <div>
                    <p class="text-xs lg:text-sm text-blue-600 font-medium">Téléphone</p>
                    <p class="font-bold text-gray-900 text-sm lg:text-base">{{ salle.telephone }}</p>
                  </div>
                </div>
                
                <div v-if="salle.whatsapp" class="flex items-center gap-3 lg:gap-4 p-3 lg:p-4 bg-green-50 rounded-lg lg:rounded-xl border border-green-200">
                  <div class="p-2 lg:p-3 bg-green-500 rounded-full">
                    <i class="fab fa-whatsapp text-white text-sm lg:text-xl"></i>
                  </div>
                  <div>
                    <p class="text-xs lg:text-sm text-green-600 font-medium">WhatsApp</p>
                    <p class="font-bold text-gray-900 text-sm lg:text-base">{{ salle.whatsapp }}</p>
                  </div>
                </div>
                
                <div v-if="salle.email" class="flex items-center gap-3 lg:gap-4 p-3 lg:p-4 bg-purple-50 rounded-lg lg:rounded-xl border border-purple-200">
                  <div class="p-2 lg:p-3 bg-purple-500 rounded-full">
                    <i class="fas fa-envelope text-white text-sm lg:text-xl"></i>
                  </div>
                  <div>
                    <p class="text-xs lg:text-sm text-purple-600 font-medium">Email</p>
                    <p class="font-bold text-gray-900 text-sm lg:text-base">{{ salle.email }}</p>
                  </div>
                </div>
                
                <div v-if="salle.site_web" class="flex items-center gap-3 lg:gap-4 p-3 lg:p-4 bg-orange-50 rounded-lg lg:rounded-xl border border-orange-200">
                  <div class="p-2 lg:p-3 bg-orange-500 rounded-full flex-shrink-0">
                    <i class="fas fa-globe text-white text-sm lg:text-xl"></i>
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-xs lg:text-sm text-orange-600 font-medium">Site web</p>
                    <a :href="salle.site_web" target="_blank" class="font-bold text-blue-600 hover:underline text-sm lg:text-base truncate block">{{ salle.site_web }}</a>
                  </div>
                </div>
              </div>
              
              <!-- Message si aucun contact -->
              <div v-if="!salle.telephone && !salle.whatsapp && !salle.email && !salle.site_web" class="text-center py-8 lg:py-12">
                <div class="bg-gray-50 rounded-lg lg:rounded-xl p-6 lg:p-8">
                  <i class="fas fa-phone-slash text-gray-300 text-2xl lg:text-4xl mb-2 lg:mb-4"></i>
                  <h4 class="text-base lg:text-lg font-medium text-gray-700 mb-1 lg:mb-2">Aucune information de contact disponible</h4>
                  <p class="text-gray-500 text-sm lg:text-base">Contactez-nous pour obtenir plus d'informations.</p>
                </div>
              </div>
            </section>

            <!-- Description -->
            <section class="bg-white rounded-lg lg:rounded-xl shadow-lg p-4 lg:p-8">
              <h3 class="text-xl lg:text-2xl font-bold mb-4 lg:mb-6 text-gray-900">À propos de cet espace</h3>
              <p class="text-gray-700 text-base lg:text-lg leading-relaxed">
                {{ salle.description || 'Plongez-vous dans un environnement de gaming de pointe conçu pour les professionnels et les passionnés. Cette suite VIP dispose d\'un matériel haut de gamme, incluant des stations RTX 4090 doubles, une zone VR dédiée et une insonorisation acoustique pour des sessions ininterrompues. Que vous diffusiez, concouriez ou vous détendiez, notre éclairage thématique cyberpunk crée l\'ambiance parfaite.' }}
              </p>
            </section>

            <!-- Événements -->
            <section id="events" class="bg-white rounded-lg lg:rounded-xl shadow-lg p-4 lg:p-8">
              <div class="flex items-center justify-between mb-4 lg:mb-6">
                <h3 class="text-xl lg:text-2xl font-bold text-gray-900">Événements à venir ici</h3>
                <a class="text-primary text-sm font-bold hover:underline" href="#">Voir tout</a>
              </div>
              
              <!-- Message si aucun événement -->
              <div v-if="!salle.evenements || salle.evenements.length === 0" class="text-center py-8 lg:py-12">
                <div class="bg-gray-50 rounded-lg lg:rounded-xl p-6 lg:p-8">
                  <i class="fas fa-calendar-xmark text-gray-300 text-2xl lg:text-4xl mb-2 lg:mb-4"></i>
                  <h4 class="text-base lg:text-lg font-medium text-gray-700 mb-1 lg:mb-2">Aucun événement prévu</h4>
                  <p class="text-gray-500 text-sm lg:text-base">Cette salle n'a pas d'événements programmés pour le moment.</p>
                </div>
              </div>
              
              <!-- Grille d'événements -->
              <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                <div v-for="event in salle.evenements" :key="event.id" class="group bg-gray-50 rounded-lg lg:rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300">
                  <!-- Image de l'événement -->
                  <div class="h-32 lg:h-48 bg-cover bg-center relative">
                    <img 
                      v-if="event.image_url"
                      :src="event.image_url.startsWith('http') ? event.image_url : `/storage/${event.image_url}`" 
                      :alt="event.titre"
                      class="w-full h-full object-cover"
                    >
                    <div v-else class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500"></div>
                    
                    <!-- Badge de statut -->
                    <div class="absolute top-3 lg:top-4 right-3 lg:right-4">
                      <span class="px-2 lg:px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-medium rounded-full">
                        {{ event.statut || 'Actif' }}
                      </span>
                    </div>
                  </div>
                  
                  <div class="p-4 lg:p-6">
                    <!-- Date et heure -->
                    <div class="flex items-center gap-2 text-xs lg:text-sm text-gray-500 mb-2 lg:mb-3">
                      <i class="fas fa-calendar"></i>
                      <span>{{ formatDateComplet(event.date_debut) }}</span>
                      <span v-if="event.date_fin">• {{ formatDateComplet(event.date_fin) }}</span>
                    </div>
                    
                    <!-- Titre et description -->
                    <h4 class="font-bold text-lg lg:text-xl text-gray-900 mb-2 lg:mb-3">{{ event.titre }}</h4>
                    <p class="text-gray-600 text-sm lg:text-base mb-3 lg:mb-4 line-clamp-2 lg:line-clamp-none">{{ event.description }}</p>
                    
                    <!-- Informations supplémentaires -->
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                      <div class="flex items-center gap-2 lg:gap-4 text-gray-500">
                        <div v-if="event.participants_max" class="flex items-center gap-1 lg:gap-2">
                          <i class="fas fa-users text-xs lg:text-sm"></i>
                          <span class="text-xs lg:text-sm">{{ event.participants_max }} max</span>
                        </div>
                        <div v-if="event.lieu" class="flex items-center gap-1 lg:gap-2">
                          <i class="fas fa-map-marker-alt text-xs lg:text-sm"></i>
                          <span class="text-xs lg:text-sm">{{ event.lieu }}</span>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Prix et actions -->
                    <div class="flex items-center justify-between">
                      <div v-if="event.prix" class="text-lg lg:text-2xl font-bold text-primary">
                        {{ formatPrice(event.prix) }}
                      </div>
                      <div v-else class="text-sm text-gray-500">Gratuit</div>
                      
                      <Link 
                        :href="`/client/evenements/${event.id}`"
                        class="px-3 lg:px-4 py-1 lg:py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary/90 transition-colors"
                      >
                        Voir détails
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <!-- Informations détaillées -->
            <section id="details" class="bg-white rounded-lg lg:rounded-xl shadow-lg p-4 lg:p-8">
              <h3 class="text-xl lg:text-2xl font-bold mb-4 lg:mb-6 text-gray-900">Informations détaillées</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                <!-- Services -->
                <div v-if="hasServices()" class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg lg:rounded-xl p-4 lg:p-6">
                  <h4 class="font-semibold text-gray-900 mb-3 lg:mb-4 flex items-center">
                    <i class="fas fa-concierge-bell mr-2 lg:mr-3 text-orange-600 text-lg lg:text-xl"></i>
                    <span class="text-base lg:text-lg">Services</span>
                  </h4>
                  <div class="space-y-2">
                    <div v-if="salle.snack_bar" class="flex items-center text-gray-700 p-2 rounded-lg hover:bg-orange-200 transition-colors">
                      <i class="fas fa-check-circle mr-3 text-green-500"></i>
                      <span class="font-medium text-sm">Snack bar</span>
                    </div>
                    <div v-if="salle.restaurant" class="flex items-center text-gray-700 p-2 rounded-lg hover:bg-orange-200 transition-colors">
                      <i class="fas fa-check-circle mr-3 text-green-500"></i>
                      <span class="font-medium text-sm">Restaurant</span>
                    </div>
                    <div v-if="salle.bar" class="flex items-center text-gray-700 p-2 rounded-lg hover:bg-orange-200 transition-colors">
                      <i class="fas fa-check-circle mr-3 text-green-500"></i>
                      <span class="font-medium text-sm">Bar</span>
                    </div>
                  </div>
                </div>

                <!-- Équipements -->
                <div v-if="hasGamingEquipment()" class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg lg:rounded-xl p-4 lg:p-6">
                  <h4 class="font-semibold text-gray-900 mb-3 lg:mb-4 flex items-center">
                    <i class="fas fa-gamepad mr-2 lg:mr-3 text-purple-600 text-lg lg:text-xl"></i>
                    <span class="text-base lg:text-lg">Équipements</span>
                  </h4>
                  <div class="space-y-2 lg:space-y-3">
                    <div v-if="salle.pc_gaming" class="bg-white p-2 lg:p-3 rounded-lg text-center hover:bg-purple-200 transition-colors">
                      <div class="text-lg lg:text-2xl font-bold text-purple-600">{{ salle.pc_gaming }}</div>
                      <div class="text-xs lg:text-sm text-gray-600 font-medium">PC Gaming</div>
                    </div>
                    <div v-if="salle.consoles_retro" class="bg-white p-2 lg:p-3 rounded-lg text-center hover:bg-purple-200 transition-colors">
                      <div class="text-lg lg:text-2xl font-bold text-purple-600">{{ salle.consoles_retro }}</div>
                      <div class="text-xs lg:text-sm text-gray-600 font-medium">Consoles rétro</div>
                    </div>
                  </div>
                </div>

                <!-- Détails pratiques -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg lg:rounded-xl p-4 lg:p-6">
                  <h4 class="font-semibold text-gray-900 mb-3 lg:mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 lg:mr-3 text-blue-600 text-lg lg:text-xl"></i>
                    <span class="text-base lg:text-lg">Détails</span>
                  </h4>
                  <div class="space-y-2 lg:space-y-3">
                    <div class="flex justify-between p-2 bg-white rounded-lg">
                      <span class="text-gray-700 font-medium text-sm">Capacité</span>
                      <span class="font-bold text-gray-900 text-sm">{{ formatCapacity(salle.capacite_max) }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-white rounded-lg">
                      <span class="text-gray-700 font-medium text-sm">Tarif</span>
                      <span class="font-bold text-gray-900 text-sm">{{ formatPrice(salle.prix_heure) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <!-- Horaires d'ouverture -->
            <section v-if="salle.horaires_ouverture" class="bg-white rounded-lg lg:rounded-xl shadow-lg p-4 lg:p-8">
              <h3 class="text-xl lg:text-2xl font-bold mb-4 lg:mb-6 text-gray-900 flex items-center">
                <i class="fas fa-clock mr-2 lg:mr-3 text-green-600 text-lg lg:text-xl"></i>
                <span class="text-base lg:text-lg">Horaires d'ouverture</span>
              </h3>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3 lg:gap-4">
                <div v-for="(horaire, jour) in salle.horaires_ouverture" :key="jour" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <span class="font-medium text-gray-700 capitalize">{{ jour }}</span>
                  <span class="font-bold text-gray-900">{{ horaire }}</span>
                </div>
              </div>
            </section>

            <!-- Services (si disponibles) -->
            <section v-if="salle.services && Object.keys(salle.services).length > 0" class="bg-white rounded-lg lg:rounded-xl shadow-lg p-4 lg:p-8">
              <h3 class="text-xl lg:text-2xl font-bold mb-4 lg:mb-6 text-gray-900 flex items-center">
                <i class="fas fa-concierge-bell mr-2 lg:mr-3 text-purple-600 text-lg lg:text-xl"></i>
                <span class="text-base lg:text-lg">Services disponibles</span>
              </h3>
              
              <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 lg:gap-4">
                <div v-for="(disponible, service) in salle.services" :key="service" v-show="disponible" class="flex items-center gap-2 p-3 bg-green-50 rounded-lg border border-green-200">
                  <i class="fas fa-check-circle text-green-600"></i>
                  <span class="text-sm font-medium text-gray-700 capitalize">{{ service.replace('_', ' ') }}</span>
                </div>
              </div>
              
              <!-- Message si aucun service disponible -->
              <div v-if="!Object.values(salle.services).some(v => v)" class="text-center py-6 bg-gray-50 rounded-lg">
                <i class="fas fa-info-circle text-gray-400 text-2xl mb-2"></i>
                <p class="text-gray-500">Aucun service spécifique disponible</p>
              </div>
            </section>

            <!-- Localisation Google Maps -->
            <section class="bg-white rounded-lg lg:rounded-xl shadow-lg p-4 lg:p-8">
              <h3 class="text-xl lg:text-2xl font-bold mb-4 lg:mb-6 text-gray-900 flex items-center">
                <i class="fas fa-map mr-2 lg:mr-3 text-blue-600 text-lg lg:text-xl"></i>
                <span class="text-base lg:text-lg">Localisation</span>
              </h3>
              
              <!-- Adresse complète -->
              <div class="p-3 lg:p-4 bg-gray-50 rounded-lg lg:rounded-xl mb-4 lg:mb-6">
                <p class="text-gray-700 font-medium mb-2 text-sm lg:text-base">Adresse</p>
                <p class="text-gray-600 text-sm lg:text-base">{{ salle.adresse || 'Non spécifiée' }}</p>
                <p class="text-gray-600 text-sm lg:text-base">{{ salle.ville }}, {{ salle.pays }}</p>
                <p v-if="salle.code_postal" class="text-gray-600 text-sm lg:text-base">{{ salle.code_postal }}</p>
              </div>
              
              <!-- Carte Google Maps -->
              <div class="relative">
                <div class="w-full h-64 lg:h-96 bg-gray-200 rounded-lg lg:rounded-xl overflow-hidden">
                  <iframe 
                    v-if="salle.latitude && salle.longitude"
                    :src="`https://maps.google.com/maps?q=${salle.latitude},${salle.longitude}&z=15&output=embed`"
                    class="w-full h-full border-0"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                  </iframe>
                  <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-green-100">
                    <div class="text-center">
                      <i class="fas fa-map-marked-alt text-3xl lg:text-5xl text-blue-500 mb-2 lg:mb-4"></i>
                      <p class="text-gray-600 text-sm lg:text-base">Carte non disponible</p>
                    </div>
                  </div>
                </div>
                
                <!-- Bouton pour ouvrir dans Google Maps -->
                <a 
                  v-if="salle.latitude && salle.longitude"
                  :href="`https://www.google.com/maps/search/?api=1&query=${salle.latitude},${salle.longitude}`"
                  target="_blank"
                  class="absolute bottom-4 right-4 lg:bottom-6 lg:right-6 px-4 py-2 lg:px-6 lg:py-3 bg-white shadow-lg rounded-lg lg:rounded-xl text-sm lg:text-base font-medium hover:bg-gray-50 transition-colors flex items-center gap-2 lg:gap-3"
                >
                  <i class="fas fa-external-link-alt text-blue-600 text-sm lg:text-base"></i>
                  <span>Ouvrir dans Maps</span>
                </a>
              </div>
              
              <!-- Point de repère -->
              <div v-if="salle.point_repere" class="mt-4 lg:mt-6 p-3 lg:p-4 bg-blue-50 rounded-lg lg:rounded-xl">
                <div class="flex items-start gap-2 lg:gap-3">
                  <i class="fas fa-landmark text-blue-500 mt-1 text-sm lg:text-base"></i>
                  <div>
                    <p class="text-gray-700 font-medium mb-1 text-sm lg:text-base">Point de repère</p>
                    <p class="text-gray-600 text-sm lg:text-base">{{ salle.point_repere }}</p>
                  </div>
                </div>
              </div>
            </section>
          </div>

          <!-- Colonne latérale -->
          <div class="lg:col-span-4">
            <div class="sticky top-4 lg:top-8 space-y-4 lg:space-y-6">
              <!-- Reservation Card -->
              <div class="bg-white rounded-lg lg:rounded-xl shadow-lg lg:shadow-xl border border-gray-200 p-4 lg:p-8">
                <div class="flex items-end gap-2 mb-4 lg:mb-6">
                  <span class="text-2xl lg:text-4xl font-bold text-primary">{{ formatPrice(salle.prix_heure) }}</span>
                  <span class="text-gray-500 mb-1 text-sm lg:text-base">/ heure</span>
                </div>
                
                <div class="space-y-3 lg:space-y-4 mb-4 lg:mb-6">
                  <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="grid grid-cols-2">
                      <div class="p-2 lg:p-3 border-r border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer" @click="showReservationModal = true">
                        <label class="text-xs font-bold uppercase text-gray-500 block mb-1">Date</label>
                        <span class="text-xs lg:text-sm font-bold text-gray-900">{{ form.date_heure ? formatDateComplet(form.date_heure) : 'Choisir une date' }}</span>
                      </div>
                      <div class="p-2 lg:p-3 hover:bg-gray-50 transition-colors cursor-pointer" @click="showReservationModal = true">
                        <label class="text-xs font-bold uppercase text-gray-500 block mb-1">Heure</label>
                        <span class="text-xs lg:text-sm font-bold text-gray-900">{{ form.date_heure ? new Date(form.date_heure).toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'}) : 'Choisir une heure' }}</span>
                      </div>
                    </div>
                    <div class="p-2 lg:p-3 border-t border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer" @click="showReservationModal = true">
                      <label class="text-xs font-bold uppercase text-gray-500 block mb-1">Durée</label>
                      <span class="text-xs lg:text-sm font-bold text-gray-900">{{ form.duree }} Heure(s)</span>
                    </div>
                  </div>
                  
                  <div class="p-2 lg:p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer" @click="showReservationModal = true">
                    <label class="text-xs font-bold uppercase text-gray-500 block mb-1">Invités</label>
                    <span class="text-xs lg:text-sm font-bold text-gray-900">{{ form.nombre_personnes }} Personne(s)</span>
                  </div>
                </div>
                
                <button @click="showReservationModal = true" class="w-full bg-primary hover:bg-blue-600 text-white font-bold py-3 lg:py-4 rounded-lg lg:rounded-xl text-base lg:text-lg transition-all shadow-lg shadow-blue-500/30 mb-3 lg:mb-4">
                  Demander une réservation
                </button>
                
                <p class="text-center text-xs text-gray-500 mb-4 lg:mb-6">Vous ne serez pas encore débité</p>
                
                <div class="border-t border-gray-200 pt-3 lg:pt-4">
                  <div class="flex justify-between text-xs lg:text-sm text-gray-700 mb-2">
                    <span>{{ formatPrice(salle.prix_heure) }} x 2 heures</span>
                    <span>{{ formatPrice(parseInt(salle.prix_heure.replace(/[^0-9]/g, '')) * 2) }}</span>
                  </div>
                  <div class="flex justify-between text-xs lg:text-sm text-gray-700 mb-2">
                    <span>Frais de service</span>
                    <span>15€</span>
                  </div>
                  <div class="flex justify-between text-xs lg:text-sm text-gray-700 mb-3 lg:mb-4">
                    <span>Frais de nettoyage</span>
                    <span>10€</span>
                  </div>
                  
                  <hr class="my-3 lg:my-4 border-gray-200"/>
                  
                  <div class="flex justify-between font-bold text-lg lg:text-xl text-gray-900">
                    <span>Total</span>
                    <span>{{ formatPrice(parseInt(salle.prix_heure.replace(/[^0-9]/g, '')) * 2 + 25) }}</span>
                  </div>
                </div>
              </div>
              
              <!-- Actions -->
              <div class="flex justify-center gap-3 lg:gap-4">
                <button class="flex items-center gap-2 px-3 lg:px-4 py-2 lg:py-3 bg-gray-100 hover:bg-gray-200 rounded-lg lg:rounded-xl transition-colors">
                  <i class="fas fa-share text-gray-700"></i>
                  <span class="font-medium text-gray-700 text-sm">Partager</span>
                </button>
                <button class="flex items-center gap-2 px-3 lg:px-4 py-2 lg:py-3 bg-gray-100 hover:bg-gray-200 rounded-lg lg:rounded-xl transition-colors">
                  <i class="fas fa-heart text-gray-700"></i>
                  <span class="font-medium text-gray-700 text-sm">Sauvegarder</span>
                </button>
              </div>
              
              <!-- Signaler -->
              <div class="text-center">
                <button class="text-gray-500 hover:text-gray-700 transition-colors underline text-sm">
                  Signaler cette annonce
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

  <!-- Modal de réservation -->
  <div v-if="showReservationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-[#19202e] rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white">Demander une réservation</h3>
          <button @click="showReservationModal = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <form @submit.prevent="submitReservation" class="space-y-4">
          <!-- Date et heure -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date *</label>
              <input 
                v-model="form.date_heure" 
                type="datetime-local" 
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Durée *</label>
              <select 
                v-model="form.duree" 
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
              >
                <option :value="1">1 heure</option>
                <option :value="2">2 heures</option>
                <option :value="3">3 heures</option>
                <option :value="4">4 heures</option>
                <option :value="5">5 heures</option>
                <option :value="6">6 heures</option>
                <option :value="8">8 heures (journée complète)</option>
              </select>
            </div>
          </div>

          <!-- Nombre de personnes et type d'événement -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre de personnes *</label>
              <input 
                v-model="form.nombre_personnes" 
                type="number" 
                min="1" 
                :max="salle.capacite_max"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
              >
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maximum: {{ salle.capacite_max }} personnes</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type d'événement *</label>
              <select 
                v-model="form.type_evenement" 
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
              >
                <option v-for="type in typesEvenement" :key="type.value" :value="type.value">{{ type.label }}</option>
              </select>
            </div>
          </div>

          <!-- Contact -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Téléphone de contact *</label>
            <input 
              v-model="form.contact_telephone" 
              type="tel" 
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
              placeholder="+221 33 123 45 67"
            >
          </div>

          <!-- Message -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message (optionnel)</label>
            <textarea 
              v-model="form.message" 
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
              placeholder="Décrivez votre projet, vos besoins spécifiques..."
            ></textarea>
          </div>

          <!-- Besoins spéciaux -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Besoins spéciaux (optionnel)</label>
            <textarea 
              v-model="form.besoins_speciaux" 
              rows="2"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
              placeholder="Équipements spécifiques, accessibilité, etc."
            ></textarea>
          </div>

          <!-- Conditions -->
          <div>
            <label class="flex items-start gap-3">
              <input 
                v-model="form.accepte_conditions" 
                type="checkbox" 
                required
                class="mt-1 rounded border-gray-300 dark:border-gray-700 text-primary focus:ring-primary/50"
              >
              <span class="text-sm text-gray-700 dark:text-gray-300">
                J'accepte les conditions générales de réservation et comprends que cette demande est soumise à validation par le propriétaire de la salle.
              </span>
            </label>
          </div>

          <!-- Récapitulatif du prix -->
          <div class="bg-gray-50 dark:bg-[#2d3748] p-4 rounded-lg">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Récapitulatif du prix</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">{{ formatPrice(salle.prix_heure) }} × {{ form.duree }} heure(s)</span>
                <span class="text-gray-900 dark:text-white font-medium">{{ formatPrice(parseInt(salle.prix_heure.replace(/[^0-9]/g, '')) * form.duree) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Frais de service</span>
                <span class="text-gray-900 dark:text-white font-medium">{{ formatPrice(1500) }}</span>
              </div>
              <div class="border-t border-gray-200 dark:border-gray-600 pt-2 mt-2">
                <div class="flex justify-between font-semibold">
                  <span class="text-gray-900 dark:text-white">Total estimé</span>
                  <span class="text-gray-900 dark:text-white">{{ formatPrice(parseInt(salle.prix_heure.replace(/[^0-9]/g, '')) * form.duree + 1500) }}</span>
                </div>
              </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Le paiement ne sera demandé qu'après acceptation de votre réservation</p>
          </div>

          <!-- Actions -->
          <div class="flex gap-3 pt-4">
            <button 
              type="button"
              @click="showReservationModal = false"
              class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
            >
              Annuler
            </button>
            <button 
              type="submit"
              class="flex-1 px-4 py-2 bg-primary hover:bg-blue-600 text-white rounded-lg transition-colors"
            >
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
