<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

import GoogleMap from '../../Components/GoogleMap.vue';
import PromoterLayout from '@/Layouts/PromoterLayout.vue';
defineOptions({ layout: PromoterLayout });

const page = usePage();
const user = computed(() => page.props.auth?.user);

const props = defineProps({
    salle: Object,
    coordinates: Object
});

const showCreateForm = ref(false);
const isEditing = ref(false);
const showImagesModal = ref(false);
const currentStep = ref(1);
const totalSteps = ref(6);

// Variables pour les modales
const showSuccessModal = ref(false);
const showErrorModal = ref(false);
const showValidationModal = ref(false);
const showDeleteModal = ref(false);
const errorMessage = ref('');

// Empêcher le scroll de la page quand les modales sont ouvertes
watch([showImagesModal, showSuccessModal, showErrorModal, showValidationModal, showDeleteModal], (newValues) => {
    if (newValues.some(val => val)) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
});

const newVenue = ref({
    nom: '',
    description: '',
    categorie: '',
    type_salle: '',
    pays: '',
    ville: '',
    quartier: '',
    rue: '',
    latitude: '',
    longitude: '',
    jours_ouverture: {
        lundi: { ouvert: false, ouverture: '', fermeture: '' },
        mardi: { ouvert: false, ouverture: '', fermeture: '' },
        mercredi: { ouvert: false, ouverture: '', fermeture: '' },
        jeudi: { ouvert: false, ouverture: '', fermeture: '' },
        vendredi: { ouvert: false, ouverture: '', fermeture: '' },
        samedi: { ouvert: false, ouverture: '', fermeture: '' },
        dimanche: { ouvert: false, ouverture: '', fermeture: '' }
    },
    telephone: '',
    email: '',
    whatsapp: '',
    facebook: '',
    instagram: '',
    site_web: '',
    tarif_minimum: '',
    tarif_maximum: '',
    services: {
        playstation_4: false,
        playstation_5: false,
        xbox_series: false,
        nintendo_switch: false,
        pc_gaming: false,
        racing_sim: false,
        flight_sim: false,
        vr_gaming: false,
        arcade_cabinets: false,
        wifi: false,
        climatisation: false,
        snack_bar: false,
        parking: false,
        streaming_setup: false,
        tournament_area: false,
        lounge_area: false
    },
    banniere_file: null,
    galerie_files: [null, null, null, null, null],
    logo_file: null
});

// Validation des étapes
const validateStep = (step) => {
    switch(step) {
        case 1:
            return newVenue.value.nom && newVenue.value.categorie && 
                   newVenue.value.type_salle && newVenue.value.description;
        case 2:
            return newVenue.value.pays && newVenue.value.ville && newVenue.value.rue;
        case 3:
            return Object.values(newVenue.value.jours_ouverture).some(jour => jour.ouvert) &&
                   Object.values(newVenue.value.jours_ouverture).filter(jour => jour.ouvert).every(jour => jour.ouverture && jour.fermeture);
        case 4:
            return newVenue.value.telephone && newVenue.value.email;
        case 5:
            return newVenue.value.tarif_minimum;
        case 6:
            // Pour le moment, on ne valide pas les fichiers
            return true;
        default:
            return false;
    }
};

const createVenue = () => {
    // Validation finale
    if (!validateStep(6)) {
        showValidationModal.value = true;
        return;
    }

    // Préparer les données selon si on est en mode édition ou création
    const venueData = {
        // Champs de base - exactement comme le backend attend
        nom: newVenue.value.nom || '',
        description: newVenue.value.description || '',
        type: newVenue.value.type_salle || 'arcade',
        categorie: newVenue.value.categorie || 'espace_jeux',
        
        // Adresse - le backend attend 'adresse' pas 'rue'
        adresse: newVenue.value.rue || '',
        code_postal: newVenue.value.code_postal || '00000',  // Valeur par défaut
        ville: newVenue.value.ville || 'Dakar',
        pays: newVenue.value.pays || 'Sénégal',
        quartier: newVenue.value.quartier || '',
        
        // Coordonnées
        latitude: parseFloat(newVenue.value.latitude) || 14.6928,
        longitude: parseFloat(newVenue.value.longitude) || -17.4467,
        
        // Contact
        telephone: newVenue.value.telephone || '',
        whatsapp: newVenue.value.whatsapp || '',
        email: newVenue.value.email || '',
        site_web: newVenue.value.site_web || '',
        facebook: newVenue.value.facebook || '',
        instagram: newVenue.value.instagram || '',
        
        // Capacité - le backend attend 'capacite' pas 'capacite_max'
        capacite: parseInt(newVenue.value.capacite) || 10,
        
        // Tarifs
        prix_heure: parseFloat(newVenue.value.tarif_minimum) || 15.00,
        prix_journee: parseFloat(newVenue.value.tarif_maximum) || 25.00,
        
        // Horaires et services - s'assurer qu'ils sont définis
        horaires: newVenue.value.jours_ouverture || {},
        services: newVenue.value.services || {},
        
        // Promoteur ID - important pour la liaison
        promoter_id: user.value?.id,
        
        // Statut
        statut: 'actif',
        valide_par_admin: false
    };

    // Choisir la bonne route et méthode
    const route = isEditing.value ? `/promoter/venues/${props.salle.id}` : '/promoter/venues';
    const method = 'POST'; // Toujours POST pour FormData
    
    console.log('Route:', route);
    console.log('Method:', method);
    console.log('Salle ID:', props.salle?.id);

    // Créer FormData pour les fichiers uploadés
    const formData = new FormData();
    
    // Ajouter le champ _method pour PUT si édition
    if (isEditing.value) {
        formData.append('_method', 'PUT');
    }
    
    // Ajouter tous les champs texte et nombres
    Object.keys(venueData).forEach(key => {
        if (key !== 'services' && key !== 'horaires' && typeof venueData[key] !== 'object') {
            formData.append(key, venueData[key]);
        }
    });
    
    // Ajouter les objets comme tableaux (pas JSON)
    formData.append('horaires', JSON.stringify(venueData.horaires));
    formData.append('services', JSON.stringify(venueData.services));
    
    // Ajouter les images de la galerie comme tableau avec chemins corrects
    if (venueData.images && Array.isArray(venueData.images)) {
        venueData.images.forEach((image, index) => {
            // S'assurer que le chemin commence par /uploads/ pour les images locales
            let imagePath = image;
            if (image && image.startsWith('salles/')) {
                imagePath = `/uploads/${image}`;
            }
            formData.append(`images[${index}]`, imagePath);
        });
    }
    
    // Ajouter l'image de la bannière avec chemin correct
    if (venueData.image_url) {
        let bannerPath = venueData.image_url;
        if (bannerPath && bannerPath.startsWith('salles/')) {
            bannerPath = `/uploads/${bannerPath}`;
        }
        formData.append('image_url', bannerPath);
    }
    
    // Ajouter les fichiers si présents
    if (newVenue.value.banniere_file) {
        formData.append('banniere_file', newVenue.value.banniere_file);
    }
    
    if (newVenue.value.logo_file) {
        formData.append('logo_file', newVenue.value.logo_file);
    }
    
    // Ajouter le logo avec chemin correct SEULEMENT si aucun nouveau fichier n'est uploadé
    if (!newVenue.value.logo_file && venueData.logo) {
        let logoPath = venueData.logo;
        if (logoPath && logoPath.startsWith('salles/')) {
            logoPath = `/uploads/${logoPath}`;
        }
        formData.append('logo', logoPath);
    }
    
    // Ajouter les fichiers de la galerie
    newVenue.value.galerie_files.forEach((file, index) => {
        if (file) {
            formData.append(`galerie_files[${index}]`, file);
        }
    });

    // Debug: voir les chemins des images
    console.log('=== MODE ÉDITION ===');
    console.log('isEditing:', isEditing.value);
    console.log('Images de la salle:', props.salle?.images);
    console.log('Image URL:', props.salle?.image_url);
    console.log('venueData:', venueData);
    console.log('horaires:', venueData.horaires);
    console.log('services:', venueData.services);
    console.log('FormData entries:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    // Envoi des données au backend
    router.visit(route, {
        method: method,
        data: formData,
        onSuccess: (response) => {
            showSuccessModal.value = true;
        },
        onError: (errors) => {
            let errorMsg = isEditing.value ? 
                'Une erreur est survenue lors de la mise à jour de votre salle:\n\n' :
                'Une erreur est survenue lors de la création de votre salle:\n\n';
            
            if (typeof errors === 'object') {
                Object.keys(errors).forEach(key => {
                    errorMsg += `${key}: ${errors[key]}\n`;
                });
            } else {
                errorMsg += errors;
            }
            
            errorMessage.value = errorMsg;
            showErrorModal.value = true;
        }
    });
};

const redirectToVenues = () => {
    window.location.href = '/promoter/venues';
};

// Fonction pour convertir les clés de jours en noms français
const getDayName = (key) => {
    const days = {
        'lundi': 'Lun',
        'mardi': 'Mar',
        'mercredi': 'Mer',
        'jeudi': 'Jeu',
        'vendredi': 'Ven',
        'samedi': 'Sam',
        'dimanche': 'Dim',
        'monday': 'Lun',
        'tuesday': 'Mar',
        'wednesday': 'Mer',
        'thursday': 'Jeu',
        'friday': 'Ven',
        'saturday': 'Sam',
        'sunday': 'Dim'
    };
    return days[key] || key;
};

// Propriété calculée pour forcer la réactivité
const getDisplayHours = (key) => {
    const jour = newVenue.value.jours_ouverture[key];
    if (!jour || !jour.ouvert) return '';
    return `${jour.ouverture || '09:00'} - ${jour.fermeture || '22:00'}`;
};

const resetForm = () => {
    newVenue.value = {
        nom: '',
        description: '',
        categorie: '',
        type_salle: '',
        pays: '',
        ville: '',
        quartier: '',
        rue: '',
        code_postal: '',
        latitude: '',
        longitude: '',
        telephone: '',
        email: '',
        whatsapp: '',
        facebook: '',
        instagram: '',
        site_web: '',
        tarif_minimum: '',
        tarif_maximum: '',
        capacite: '',
        heure_ouverture: '',
        heure_fermeture: '',
        jours_ouverture: {
            lundi: { ouvert: false, ouverture: '', fermeture: '' },
            mardi: { ouvert: false, ouverture: '', fermeture: '' },
            mercredi: { ouvert: false, ouverture: '', fermeture: '' },
            jeudi: { ouvert: false, ouverture: '', fermeture: '' },
            vendredi: { ouvert: false, ouverture: '', fermeture: '' },
            samedi: { ouvert: false, ouverture: '', fermeture: '' },
            dimanche: { ouvert: false, ouverture: '', fermeture: '' }
        },
        services: {
            playstation_4: false,
            playstation_5: false,
            xbox_series: false,
            nintendo_switch: false,
            pc_gaming: false,
            racing_sim: false,
            flight_sim: false,
            vr_gaming: false,
            arcade_cabinets: false,
            wifi: false,
            climatisation: false,
            snack_bar: false,
            parking: false,
            streaming_setup: false,
            tournament_area: false,
            lounge_area: false
        },
        banniere_file: null,
        galerie_files: [null, null, null, null, null],
        logo_file: null
    };
    currentStep.value = 1;
};

const editVenue = () => {
    if (props.salle) {
        console.log('Données brutes de la salle:', props.salle);
        console.log('Horaires bruts:', props.salle?.horaires);
        
        // Extraire les jours d'ouverture correctement
        const horairesData = props.salle?.horaires;
        // Préparer les jours d'ouverture avec la nouvelle structure
        let joursOuverture = {
            lundi: { ouvert: false, ouverture: '09:00', fermeture: '22:00' },
            mardi: { ouvert: false, ouverture: '09:00', fermeture: '22:00' },
            mercredi: { ouvert: false, ouverture: '09:00', fermeture: '22:00' },
            jeudi: { ouvert: false, ouverture: '09:00', fermeture: '22:00' },
            vendredi: { ouvert: false, ouverture: '09:00', fermeture: '22:00' },
            samedi: { ouvert: false, ouverture: '09:00', fermeture: '22:00' },
            dimanche: { ouvert: false, ouverture: '09:00', fermeture: '22:00' }
        };
        
        // Si les horaires sont au format {lundi: {ouvert: true, ouverture: '09:00', fermeture: '18:00'}, ...}
        if (horairesData && typeof horairesData === 'object') {
            if (horairesData.jours) {
                joursOuverture = {...joursOuverture, ...horairesData.jours};
            } else {
                // Si les jours sont directement dans horaires avec la nouvelle structure
                Object.keys(horairesData).forEach(jour => {
                    if (typeof horairesData[jour] === 'object' && horairesData[jour] !== null) {
                        joursOuverture[jour] = {
                            ouvert: true,
                            ouverture: horairesData[jour].ouverture || '09:00',
                            fermeture: horairesData[jour].fermeture || '22:00'
                        };
                    } else if (typeof horairesData[jour] === 'boolean') {
                        // Ancien format {lundi: true, mardi: false, ...}
                        joursOuverture[jour] = {
                            ouvert: horairesData[jour],
                            ouverture: horairesData.ouverture || '09:00',
                            fermeture: horairesData.fermeture || '22:00'
                        };
                    }
                });
            }
        }
        
        console.log('Jours d\'ouverture mappés:', joursOuverture);
        
        // Pré-remplir le formulaire avec les données de la salle existante
        newVenue.value = {
            nom: props.salle.nom || '',
            description: props.salle.description || '',
            categorie: props.salle.categorie || 'espace_jeux',
            type_salle: props.salle.type || 'arcade',
            pays: props.salle.pays || 'Sénégal',
            ville: props.salle.ville || 'Dakar',
            quartier: props.salle.quartier || '',
            rue: props.salle.adresse || '',
            code_postal: props.salle.code_postal || '',
            latitude: props.salle.latitude?.toString() || '14.6928',
            longitude: props.salle.longitude?.toString() || '-17.4467',
            telephone: props.salle.telephone || '',
            email: props.salle.email || '',
            whatsapp: props.salle.whatsapp || '',
            facebook: props.salle.facebook || '',
            instagram: props.salle.instagram || '',
            site_web: props.salle.site_web || '',
            tarif_minimum: props.salle.prix_heure?.toString() || '15.00',
            tarif_maximum: props.salle.prix_journee?.toString() || '25.00',
            capacite: props.salle.capacite?.toString() || '10',
            heure_ouverture: horairesData?.ouverture || '09:00',
            heure_fermeture: horairesData?.fermeture || '22:00',
            jours_ouverture: joursOuverture,
            services: props.salle.services || {
                playstation_4: false,
                playstation_5: false,
                xbox_series: false,
                nintendo_switch: false,
                pc_gaming: false,
                racing_sim: false,
                flight_sim: false,
                vr_gaming: false,
                arcade_cabinets: false,
                wifi: false,
                climatisation: false,
                snack_bar: false,
                parking: false,
                streaming_setup: false,
                tournament_area: false,
                lounge_area: false
            },
            banniere_file: null,
            galerie_files: [null, null, null, null, null],
            logo_file: null,
            // Garder les URLs des images existantes avec chemins corrects
            banniere_url: props.salle.image_url && props.salle.image_url.startsWith('salles/') ? `/uploads/${props.salle.image_url}` : props.salle.image_url || null,
            galerie_urls: (props.salle.images || []).map(img => img && img.startsWith('salles/') ? `/uploads/${img}` : img),
            logo_url: props.salle.logo && props.salle.logo.startsWith('salles/') ? `/uploads/${props.salle.logo}` : props.salle.logo || null
        };
        
        console.log('Formulaire pré-rempli:', newVenue.value);
        
        isEditing.value = true;
        showCreateForm.value = true;
        currentStep.value = 1;
    }
};

const cancelEdit = () => {
    isEditing.value = false;
    showCreateForm.value = false;
    resetForm();
};

const handleBanniereUpload = (event) => {
    newVenue.value.banniere_file = event.target.files[0];
};

const handleGalerieUpload = (index, event) => {
    newVenue.value.galerie_files[index] = event.target.files[0];
};

const handleLogoUpload = (event) => {
    newVenue.value.logo_file = event.target.files[0];
};

const nextStep = () => {
    if (currentStep.value < totalSteps.value) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const goToStep = (step) => {
    if (step >= 1 && step <= totalSteps.value) {
        currentStep.value = step;
    }
};

const getProgressPercentage = () => {
    return (currentStep.value / totalSteps.value) * 100;
};

// Fonctions pour la suppression de salle
const confirmDeleteVenue = () => {
    showDeleteModal.value = true;
};

const deleteVenue = () => {
    if (!props.salle?.id) {
        console.error('ID de salle non trouvé');
        return;
    }

    router.delete(`/promoter/venues/${props.salle.id}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
            // Rediriger vers la liste des venues
            window.location.href = '/promoter/venues';
        },
        onError: (errors) => {
            console.error('Erreur lors de la suppression:', errors);
            showDeleteModal.value = false;
            errorMessage.value = 'Une erreur est survenue lors de la suppression de la salle.';
            showErrorModal.value = true;
        }
    });
};

const cancelDelete = () => {
    showDeleteModal.value = false;
};

const isStepCompleted = (step) => {
    return currentStep.value > step;
};

const getStepTitle = (step) => {
    const titles = {
        1: 'Général',
        2: 'Adresse',
        3: 'Horaires',
        4: 'Contact',
        5: 'Services',
        6: 'Médias'
    };
    return titles[step] || '';
};
</script>

<template>
  <Head title="Ma Salle" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex min-h-screen w-full bg-gray-50 font-display text-gray-800">
    <!-- Sidebar Component -->

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto transition-all duration-300">
      <div class="p-8">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
          <div>
            <p class="text-gray-900 text-4xl font-black leading-tight tracking-[-0.033em]">Ma Salle</p>
            <p class="text-gray-600 mt-2">Gérez les informations de votre salle</p>
          </div>
        </div>

        <!-- État : Pas de salle -->
        <div v-if="!props.salle && !showCreateForm" class="flex flex-col items-center justify-center py-20">
          <div class="text-center max-w-md mx-auto">
            <!-- Icône -->
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" clip-rule="evenodd"/>
                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
              </svg>
            </div>
            
            <!-- Message -->
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
              Vous n'avez pas encore de salle
            </h2>
            <p class="text-gray-600 mb-8">
              Créez votre première salle pour commencer à attirer des clients dans votre espace de gaming.
            </p>
            
            <!-- Bouton d'action -->
            <button 
              @click="showCreateForm = true"
              class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-500 text-white font-semibold rounded-lg hover:from-gray-500 hover:to-gray-400 transition-all duration-300 transform hover:scale-105"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
              </svg>
              <span>Créer ma salle maintenant</span>
            </button>
          </div>
        </div>

        <!-- État : Salle existe -->
        <div v-else-if="props.salle && !showCreateForm" class="space-y-8">
          <!-- Header Section -->
          <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
            <!-- Hero Image Section -->
            <div class="relative h-80 bg-gray-100">
              <img 
                v-if="props.salle.image_url" 
                :src="props.salle.image_url.startsWith('salles/') ? `/uploads/${props.salle.image_url}` : props.salle.image_url" 
                :alt="props.salle.nom"
                class="w-full h-full object-cover"
              >
              <div v-else class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                <div class="text-center">
                  <div class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-store text-gray-500 text-2xl"></i>
                  </div>
                  <p class="text-gray-500">Aucune image principale</p>
                </div>
              </div>
              
              <!-- Overlay Actions -->
              <div class="absolute top-6 right-6 flex gap-3">
                <button 
                  @click="editVenue" 
                  class="bg-white/90 backdrop-blur-sm text-gray-700 px-4 py-2 rounded-lg shadow-lg hover:bg-white transition-all duration-200 flex items-center gap-2"
                >
                  <i class="fas fa-edit text-sm"></i>
                  <span class="font-medium">Modifier</span>
                </button>
                <button 
                  @click="confirmDeleteVenue"
                  class="bg-white/90 backdrop-blur-sm text-red-600 px-4 py-2 rounded-lg shadow-lg hover:bg-white transition-all duration-200 flex items-center gap-2"
                >
                  <i class="fas fa-trash text-sm"></i>
                  <span class="font-medium">Supprimer</span>
                </button>
              </div>
              
              <!-- Status Badge -->
              <div class="absolute bottom-6 left-6">
                <span :class="props.salle.valide ? 'bg-green-100 text-green-800 border-green-200' : 'bg-yellow-100 text-yellow-800 border-yellow-200'" 
                      class="backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium border">
                  {{ props.salle.valide ? 'Actif' : 'En attente de validation' }}
                </span>
              </div>
            </div>
            
            <!-- Venue Info -->
            <div class="p-8">
              <div class="mb-8">
                <h1 class="text-3xl font-light text-gray-900 mb-3">{{ props.salle.nom }}</h1>
                <div class="flex items-center gap-6 text-sm text-gray-600">
                  <span class="flex items-center gap-2">
                    <i class="fas fa-tag text-gray-400"></i>
                    {{ props.salle.categorie }}
                  </span>
                  <span class="flex items-center gap-2">
                    <i class="fas fa-gamepad text-gray-400"></i>
                    {{ props.salle.type }}
                  </span>
                </div>
              </div>
              
              <!-- Description -->
              <div class="mb-8">
                <p class="text-gray-700 leading-relaxed">{{ props.salle.description }}</p>
              </div>
              
              <!-- Contact, Address and Hours in responsive layout -->
              <div class="space-y-4 sm:space-y-6 mb-8">
                <!-- Contact -->
                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 sm:p-6">
                  <h3 class="text-base sm:text-lg font-semibold text-slate-900 dark:text-white mb-3 sm:mb-4 flex items-center gap-2">
                    <i class="fas fa-phone text-primary text-sm sm:text-base"></i>
                    <span class="truncate">Contact</span>
                  </h3>
                  <div class="space-y-2 sm:space-y-3">
                    <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-white dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                      <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-phone text-blue-600 dark:text-blue-400 text-xs sm:text-sm"></i>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-slate-900 dark:text-white font-medium text-xs sm:text-sm truncate">{{ props.salle.telephone || 'Non spécifié' }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Téléphone</p>
                      </div>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-white dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                      <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-envelope text-green-600 dark:text-green-400 text-xs sm:text-sm"></i>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-slate-900 dark:text-white font-medium text-xs sm:text-sm truncate">{{ props.salle.email || 'Non spécifié' }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Email</p>
                      </div>
                    </div>
                    <div v-if="props.salle.site_web" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-white dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                      <div class="w-6 h-6 sm:w-8 sm:h-8 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-globe text-purple-600 dark:text-purple-400 text-xs sm:text-sm"></i>
                      </div>
                      <div class="flex-1 min-w-0">
                        <a :href="props.salle.site_web" target="_blank" class="text-slate-900 dark:text-white font-medium text-xs sm:text-sm hover:text-primary transition-colors truncate block">
                          {{ props.salle.site_web }}
                        </a>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Site web</p>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Address -->
                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 sm:p-6">
                  <h3 class="text-base sm:text-lg font-semibold text-slate-900 dark:text-white mb-3 sm:mb-4 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-primary text-sm sm:text-base"></i>
                    <span class="truncate">Adresse</span>
                  </h3>
                  <div class="space-y-2 sm:space-y-3">
                    <div class="flex items-start gap-2 sm:gap-3 p-2 sm:p-3 bg-white dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                      <div class="w-6 h-6 sm:w-8 sm:h-8 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-home text-red-600 dark:text-red-400 text-xs sm:text-sm"></i>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-slate-900 dark:text-white font-medium text-xs sm:text-sm break-words">{{ props.salle.adresse || 'Adresse non spécifiée' }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Adresse</p>
                      </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-white dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                      <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-map-pin text-orange-600 dark:text-orange-400 text-sm"></i>
                      </div>
                      <div class="flex-1">
                        <p class="text-slate-900 dark:text-white font-medium text-sm">
                          {{ props.salle.code_postal || '00000' }} {{ props.salle.ville || 'Ville' }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Code postal & Ville</p>
                      </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-white dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                      <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-flag text-indigo-600 dark:text-indigo-400 text-sm"></i>
                      </div>
                      <div class="flex-1">
                        <p class="text-slate-900 dark:text-white font-medium text-sm">{{ props.salle.pays || 'Sénégal' }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pays</p>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Hours -->
                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4">
                  <h3 class="text-sm font-medium text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-clock text-primary"></i>
                    Horaires d'ouverture
                  </h3>
                  <div v-if="props.salle.horaires && typeof props.salle.horaires === 'object'">
                    <!-- Desktop: Compact circular design -->
                    <div class="hidden lg:block">
                      <div class="bg-white dark:bg-slate-700 rounded-lg p-4 border border-slate-200 dark:border-slate-600">
                        <!-- Compact week circle -->
                        <div class="relative w-48 h-48 mx-auto">
                          <!-- Center circle -->
                          <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-primary to-blue-600 rounded-full flex flex-col items-center justify-center text-white shadow-lg">
                              <i class="fas fa-store text-lg mb-1"></i>
                              <span class="text-xs font-semibold">Semaine</span>
                            </div>
                          </div>
                          
                          <!-- Day circles around -->
                          <div class="absolute inset-0">
                            <!-- Lundi - Top -->
                            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                              <div class="relative group">
                                <div v-if="props.salle.horaires.lundi && props.salle.horaires.lundi.ouvert" 
                                     class="w-10 h-10 bg-green-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">LUN</span>
                                </div>
                                <div v-else 
                                     class="w-10 h-10 bg-red-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">LUN</span>
                                </div>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10">
                                  <div class="bg-slate-900 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                    <span v-if="props.salle.horaires.lundi && props.salle.horaires.lundi.ouvert">
                                      {{ props.salle.horaires.lundi.ouverture }} - {{ props.salle.horaires.lundi.fermeture }}
                                    </span>
                                    <span v-else>Fermé</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            <!-- Mardi - Top Right -->
                            <div class="absolute top-4 right-4">
                              <div class="relative group">
                                <div v-if="props.salle.horaires.mardi && props.salle.horaires.mardi.ouvert" 
                                     class="w-10 h-10 bg-green-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">MAR</span>
                                </div>
                                <div v-else 
                                     class="w-10 h-10 bg-red-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">MAR</span>
                                </div>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10">
                                  <div class="bg-slate-900 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                    <span v-if="props.salle.horaires.mardi && props.salle.horaires.mardi.ouvert">
                                      {{ props.salle.horaires.mardi.ouverture }} - {{ props.salle.horaires.mardi.fermeture }}
                                    </span>
                                    <span v-else>Fermé</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            <!-- Mercredi - Right -->
                            <div class="absolute top-1/2 right-0 transform -translate-y-1/2 translate-x-1/2">
                              <div class="relative group">
                                <div v-if="props.salle.horaires.mercredi && props.salle.horaires.mercredi.ouvert" 
                                     class="w-10 h-10 bg-green-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">MER</span>
                                </div>
                                <div v-else 
                                     class="w-10 h-10 bg-red-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">MER</span>
                                </div>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10">
                                  <div class="bg-slate-900 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                    <span v-if="props.salle.horaires.mercredi && props.salle.horaires.mercredi.ouvert">
                                      {{ props.salle.horaires.mercredi.ouverture }} - {{ props.salle.horaires.mercredi.fermeture }}
                                    </span>
                                    <span v-else>Fermé</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            <!-- Jeudi - Bottom Right -->
                            <div class="absolute bottom-4 right-4">
                              <div class="relative group">
                                <div v-if="props.salle.horaires.jeudi && props.salle.horaires.jeudi.ouvert" 
                                     class="w-10 h-10 bg-green-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">JEU</span>
                                </div>
                                <div v-else 
                                     class="w-10 h-10 bg-red-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">JEU</span>
                                </div>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10">
                                  <div class="bg-slate-900 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                    <span v-if="props.salle.horaires.jeudi && props.salle.horaires.jeudi.ouvert">
                                      {{ props.salle.horaires.jeudi.ouverture }} - {{ props.salle.horaires.jeudi.fermeture }}
                                    </span>
                                    <span v-else>Fermé</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            <!-- Vendredi - Bottom -->
                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2">
                              <div class="relative group">
                                <div v-if="props.salle.horaires.vendredi && props.salle.horaires.vendredi.ouvert" 
                                     class="w-10 h-10 bg-green-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">VEN</span>
                                </div>
                                <div v-else 
                                     class="w-10 h-10 bg-red-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">VEN</span>
                                </div>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10">
                                  <div class="bg-slate-900 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                    <span v-if="props.salle.horaires.vendredi && props.salle.horaires.vendredi.ouvert">
                                      {{ props.salle.horaires.vendredi.ouverture }} - {{ props.salle.horaires.vendredi.fermeture }}
                                    </span>
                                    <span v-else>Fermé</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            <!-- Samedi - Bottom Left -->
                            <div class="absolute bottom-4 left-4">
                              <div class="relative group">
                                <div v-if="props.salle.horaires.samedi && props.salle.horaires.samedi.ouvert" 
                                     class="w-10 h-10 bg-green-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">SAM</span>
                                </div>
                                <div v-else 
                                     class="w-10 h-10 bg-red-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">SAM</span>
                                </div>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10">
                                  <div class="bg-slate-900 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                    <span v-if="props.salle.horaires.samedi && props.salle.horaires.samedi.ouvert">
                                      {{ props.salle.horaires.samedi.ouverture }} - {{ props.salle.horaires.samedi.fermeture }}
                                    </span>
                                    <span v-else>Fermé</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            <!-- Dimanche - Left -->
                            <div class="absolute top-1/2 left-0 transform -translate-y-1/2 -translate-x-1/2">
                              <div class="relative group">
                                <div v-if="props.salle.horaires.dimanche && props.salle.horaires.dimanche.ouvert" 
                                     class="w-10 h-10 bg-green-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">DIM</span>
                                </div>
                                <div v-else 
                                     class="w-10 h-10 bg-red-500 rounded-full flex flex-col items-center justify-center text-white shadow hover:scale-110 transition-transform cursor-pointer">
                                  <span class="text-xs font-bold">DIM</span>
                                </div>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10">
                                  <div class="bg-slate-900 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                    <span v-if="props.salle.horaires.dimanche && props.salle.horaires.dimanche.ouvert">
                                      {{ props.salle.horaires.dimanche.ouverture }} - {{ props.salle.horaires.dimanche.fermeture }}
                                    </span>
                                    <span v-else>Fermé</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Legend -->
                        <div class="flex justify-center gap-4 text-xs mt-4">
                          <div class="flex items-center gap-1">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-slate-600 dark:text-slate-400">Ouvert</span>
                          </div>
                          <div class="flex items-center gap-1">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-slate-600 dark:text-slate-400">Fermé</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Mobile/Tablet: Card layout -->
                    <div class="lg:hidden space-y-2">
                      <template v-for="(jour, key) in props.salle.horaires" :key="key">
                        <div v-if="jour.ouvert && jour.ouverture && jour.fermeture" 
                             class="bg-white dark:bg-slate-700 rounded-lg p-2 border border-slate-200 dark:border-slate-600">
                          <div class="flex items-center justify-between">
                            <span class="font-semibold text-slate-900 dark:text-white text-xs">{{ getDayName(key) }}</span>
                            <span class="text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 px-2 py-0.5 rounded-full">
                              Ouvert
                            </span>
                          </div>
                          <p class="text-slate-600 dark:text-slate-400 text-xs mt-0.5">
                            <i class="fas fa-door-open mr-1"></i>
                            {{ jour.ouverture }} - {{ jour.fermeture }}
                          </p>
                        </div>
                        <div v-else-if="!jour.ouvert" 
                             class="bg-white dark:bg-slate-700 rounded-lg p-2 border border-slate-200 dark:border-slate-600">
                          <div class="flex items-center justify-between">
                            <span class="font-semibold text-slate-900 dark:text-white text-xs">{{ getDayName(key) }}</span>
                            <span class="text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 px-2 py-0.5 rounded-full">
                              Fermé
                            </span>
                          </div>
                        </div>
                      </template>
                    </div>
                  </div>
                  <p v-else class="text-slate-500 dark:text-slate-400 text-xs">Horaires non spécifiés</p>
                </div>
              </div>
              
              <!-- Services et Équipements -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Services -->
                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6">
                  <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-concierge-bell text-primary"></i>
                    Services disponibles
                  </h3>
                  <div v-if="props.salle.services && Object.keys(props.salle.services).length > 0" class="flex flex-wrap gap-2">
                    <span v-for="(service, key) in props.salle.services" :key="key" v-show="service === true"
                          class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800">
                      <i class="fas fa-check-circle mr-1.5 text-xs"></i>
                      {{ (typeof key === 'string' ? key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : key) }}
                    </span>
                  </div>
                  <p v-else class="text-slate-500 dark:text-slate-400 text-sm">Aucun service spécifié</p>
                </div>

                <!-- Équipements -->
                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6">
                  <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-gamepad text-primary"></i>
                    Équipements
                  </h3>
                  <div v-if="props.salle.equipements && Array.isArray(props.salle.equipements) && props.salle.equipements.length > 0" class="flex flex-wrap gap-2">
                    <span v-for="equipement in props.salle.equipements" :key="equipement" 
                          class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                      <i class="fas fa-cube mr-1.5 text-xs"></i>
                      {{ equipement }}
                    </span>
                  </div>
                  <p v-else class="text-slate-500 dark:text-slate-400 text-sm">Aucun équipement spécifié</p>
                </div>
              </div>
              
              <!-- Map Section -->
              <div v-if="props.coordinates" class="mb-8">
                <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center gap-2">
                  <i class="fas fa-map text-gray-400"></i>
                  Localisation
                </h3>
                <div class="bg-gray-50 rounded-lg overflow-hidden" style="height: 300px;">
                  <iframe
                    :src="`https://www.openstreetmap.org/export/embed.html?bbox=${props.coordinates.lng - 0.005},${props.coordinates.lat - 0.005},${props.coordinates.lng + 0.005},${props.coordinates.lat + 0.005}&layer=mapnik&marker=${props.coordinates.lat},${props.coordinates.lng}`"
                    width="100%"
                    height="300"
                    frameborder="0"
                    class="border-0"
                  ></iframe>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Gallery Section -->
          <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
            <div class="p-8">
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-light text-gray-900">Galerie</h2>
                <button 
                  @click="showImagesModal = true"
                  class="text-gray-600 hover:text-gray-900 transition-colors flex items-center gap-2"
                >
                  <i class="fas fa-expand"></i>
                  <span class="text-sm">Voir tout</span>
                </button>
              </div>
              
              <div v-if="props.salle.images && props.salle.images.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div v-for="(image, index) in props.salle.images.slice(0, 8)" :key="index" 
                     class="aspect-square bg-gray-100 rounded-lg overflow-hidden group cursor-pointer"
                     @click="showImagesModal = true">
                  <img 
                    :src="image.startsWith('salles/') ? `/uploads/${image}` : image" 
                    :alt="`${props.salle.nom} - Image ${index + 1}`"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  >
                </div>
                
                <!-- Show More Button if more than 8 images -->
                <div v-if="props.salle.images.length > 8" 
                     class="aspect-square bg-gray-100 rounded-lg overflow-hidden group cursor-pointer flex items-center justify-center"
                     @click="showImagesModal = true">
                  <div class="text-center">
                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-gray-300 transition-colors">
                      <i class="fas fa-plus text-gray-500"></i>
                    </div>
                    <p class="text-sm text-gray-600">+{{ props.salle.images.length - 8 }}</p>
                  </div>
                </div>
              </div>
              
              <div v-else class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <i class="fas fa-images text-gray-400 text-xl"></i>
                </div>
                <p class="text-gray-500">Aucune image dans la galerie</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Formulaire de création -->
        <div v-else class="max-w-4xl mx-auto">
          <!-- Header du formulaire -->
          <div class="mb-8">
            <button 
              @click="cancelEdit"
              class="inline-flex items-center gap-2 text-gray-400 hover:text-gray-600 transition-colors mb-4"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
              </svg>
              Retour
            </button>
            
            <div>
              <h2 class="text-2xl font-bold text-gray-900">
                {{ isEditing ? 'Modifier ma salle' : 'Créer ma salle' }}
              </h2>
              <p class="text-gray-600 mt-2">
                {{ isEditing ? 'Modifiez les informations de votre salle' : 'Remplissez ce formulaire pour créer votre salle' }}
              </p>
            </div>
          </div>

          <!-- Barre de progression -->
          <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">Étape {{ currentStep }} sur {{ totalSteps }}</span>
                <span class="text-sm text-gray-500">({{ Math.round(getProgressPercentage()) }}%)</span>
              </div>
            </div>
            
            <!-- Progress bar -->
            <div class="w-full bg-gray-200 rounded-full h-3 mb-6">
              <div 
                class="bg-gradient-to-r from-blue-600 to-blue-500 h-3 rounded-full transition-all duration-500 ease-out"
                :style="{ width: getProgressPercentage() + '%' }"
              ></div>
            </div>

            <!-- Étapes -->
            <div class="flex justify-between">
              <button 
                v-for="step in totalSteps" 
                :key="step"
                @click="goToStep(step)"
                class="flex flex-col items-center cursor-pointer group"
                :disabled="step > currentStep"
              >
                <div 
                  class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300 mb-2"
                  :class="{
                    'bg-blue-600 text-white': step === currentStep,
                    'bg-green-600 text-white': isStepCompleted(step),
                    'bg-gray-600 text-gray-400': step > currentStep,
                    'hover:bg-blue-500': step === currentStep,
                    'hover:bg-green-500': isStepCompleted(step)
                  }"
                >
                  <i v-if="isStepCompleted(step)" class="fas fa-check"></i>
                  <span v-else>{{ step }}</span>
                </div>
                <span 
                  class="text-xs font-medium transition-colors"
                  :class="{
                    'text-blue-400': step === currentStep,
                    'text-green-400': isStepCompleted(step),
                    'text-gray-500': step > currentStep
                  }"
                >
                  {{ getStepTitle(step) }}
                </span>
              </button>
            </div>
          </div>

          <form @submit.prevent="currentStep === totalSteps ? createVenue() : nextStep()" class="space-y-8" novalidate>
            <!-- Étape 1: Informations générales -->
            <div v-show="currentStep === 1" class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
              <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-sm font-bold">1</span>
                Informations générales
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nom de la salle <span class="text-red-500">*</span>
                  </label>
                  <input 
                    v-model="newVenue.nom" 
                    type="text" 
                    name="nom"
                    required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="YOUPIHUB Arena"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Catégorie <span class="text-red-500">*</span>
                  </label>
                  <select 
                    v-model="newVenue.categorie" 
                    name="categorie"
                    required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                  >
                    <option value="">Sélectionner une catégorie</option>
                    <option value="salle_gaming">Salle de gaming</option>
                    <option value="centre_esport">Centre e-sport</option>
                    <option value="arcade">Salle d'arcade</option>
                    <option value="vr_centre">Centre VR</option>
                    <option value="retro_gaming">Retro gaming</option>
                    <option value="gaming_lounge">Gaming lounge</option>
                    <option value="cybercafe">Cybercafé</option>
                    <option value="gaming_bar">Gaming bar</option>
                    <option value="complexe_jeux">Complexe de jeux</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Type de salle <span class="text-red-500">*</span>
                  </label>
                  <select 
                    v-model="newVenue.type_salle" 
                    name="type_salle"
                    required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                  >
                    <option value="">Sélectionner un type</option>
                    <option value="pc_gaming">PC Gaming</option>
                    <option value="console_gaming">Console Gaming</option>
                    <option value="vr_gaming">VR Gaming</option>
                    <option value="retro_gaming">Retro Gaming</option>
                    <option value="esports_arena">E-sports Arena</option>
                    <option value="gaming_hub">Gaming Hub</option>
                    <option value="simulation">Simulation</option>
                    <option value="mixed_gaming">Gaming Mixte</option>
                  </select>
                </div>
              </div>
              <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Description complète <span class="text-red-500">*</span>
                </label>
                <textarea 
                  v-model="newVenue.description" 
                  name="description"
                  required
                  rows="4"
                  class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 resize-none"
                  placeholder="Décrivez votre salle, l'ambiance, les équipements..."
                ></textarea>
              </div>
            </div>

            <!-- Étape 2: Adresse complète -->
            <div v-show="currentStep === 2" class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
              <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-sm font-bold">2</span>
                Adresse complète
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pays <span class="text-red-500">*</span>
                  </label>
                  <select 
                    v-model="newVenue.pays" 
                    name="pays"
                    required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                  >
                    <option value="">Sélectionner un pays</option>
                    <option value="Sénégal">Sénégal</option>
                    <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                    <option value="Mali">Mali</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Niger">Niger</option>
                    <option value="Togo">Togo</option>
                    <option value="Bénin">Bénin</option>
                    <option value="Guinée">Guinée</option>
                    <option value="Guinée-Bissau">Guinée-Bissau</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Libéria">Libéria</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Cameroun">Cameroun</option>
                    <option value="Tchad">Tchad</option>
                    <option value="Centrafrique">Centrafrique</option>
                    <option value="Congo-Brazzaville">Congo-Brazzaville</option>
                    <option value="Congo-Kinshasa">Congo-Kinshasa</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Guinée Équatoriale">Guinée Équatoriale</option>
                    <option value="Angola">Angola</option>
                    <option value="Zambie">Zambie</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Afrique du Sud">Afrique du Sud</option>
                    <option value="Namibie">Namibie</option>
                    <option value="Eswatini">Eswatini</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Maurice">Maurice</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Comores">Comores</option>
                    <option value="Éthiopie">Éthiopie</option>
                    <option value="Érythrée">Érythrée</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Somalie">Somalie</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Ouganda">Ouganda</option>
                    <option value="Tanzanie">Tanzanie</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Égypte">Égypte</option>
                    <option value="Libye">Libye</option>
                    <option value="Tunisie">Tunisie</option>
                    <option value="Algérie">Algérie</option>
                    <option value="Maroc">Maroc</option>
                    <option value="Soudan">Soudan</option>
                    <option value="Soudan du Sud">Soudan du Sud</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Ville <span class="text-red-500">*</span>
                  </label>
                  <input 
                    v-model="newVenue.ville" 
                    type="text" 
                    name="ville"
                    required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="Dakar"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Quartier
                  </label>
                  <input 
                    v-model="newVenue.quartier" 
                    type="text"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="Plateau"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Rue / indications d'accès <span class="text-red-500">*</span>
                  </label>
                  <input 
                    v-model="newVenue.rue" 
                    type="text" 
                    name="rue"
                    required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="123 Rue de la République"
                  >
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Latitude (optionnel)
                  </label>
                  <input 
                    v-model="newVenue.latitude" 
                    type="number" 
                    step="any"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="14.6928"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Longitude (optionnel)
                  </label>
                  <input 
                    v-model="newVenue.longitude" 
                    type="number" 
                    step="any"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="-17.4467"
                  >
                </div>
              </div>
            </div>

            <!-- Étape 3: Horaires d'ouverture -->
            <div v-show="currentStep === 3" class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
              <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-sm font-bold">3</span>
                Horaires d'ouverture
              </h3>
              
              <div class="space-y-4">
                <div v-for="(jour, key) in newVenue.jours_ouverture" :key="key" 
                     class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                  <div class="flex items-center justify-between mb-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                      <input 
                        v-model="jour.ouvert" 
                        type="checkbox"
                        class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                      >
                      <span class="font-medium text-gray-900 capitalize">{{ getDayName(key) }}</span>
                    </label>
                    
                    <div v-if="jour.ouvert" class="flex items-center gap-2 text-sm text-gray-500">
                      <i class="fas fa-clock"></i>
                      <span>{{ getDisplayHours(key) }}</span>
                    </div>
                  </div>
                  
                  <div v-if="jour.ouvert" class="grid grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">
                        Ouverture
                      </label>
                      <input 
                        v-model="jour.ouverture" 
                        type="time" 
                        :name="`${key}_ouverture`"
                        class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                      >
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">
                        Fermeture
                      </label>
                      <input 
                        v-model="jour.fermeture" 
                        type="time" 
                        :name="`${key}_fermeture`"
                        class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                      >
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-700">
                  <i class="fas fa-info-circle mr-2"></i>
                  Cochez les jours d'ouverture et définissez les horaires spécifiques pour chaque jour.
                </p>
              </div>
            </div>

            <!-- Étape 4: Contact -->
            <div v-show="currentStep === 4" class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6 shadow-sm">
              <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6 flex items-center gap-2">
                <span class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-600 rounded-lg flex items-center justify-center text-xs sm:text-sm font-bold flex-shrink-0">4</span>
                <span class="truncate">Contact</span>
              </h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                  <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                    Numéro de téléphone <span class="text-red-500">*</span>
                  </label>
                  <input 
                    v-model="newVenue.telephone" 
                    type="tel" 
                    name="telephone"
                    required
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm sm:text-base"
                    placeholder="+221 33 123 45 67"
                  >
                </div>
                <div>
                  <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                    Adresse email professionnelle <span class="text-red-500">*</span>
                  </label>
                  <input 
                    v-model="newVenue.email" 
                    type="email" 
                    name="email"
                    required
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm sm:text-base"
                    placeholder="contact@gamingarena.com"
                  >
                </div>
                <div>
                  <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                    WhatsApp (optionnel)
                  </label>
                  <input 
                    v-model="newVenue.whatsapp" 
                    type="tel" 
                    name="whatsapp"
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm sm:text-base"
                    placeholder="+221 77 123 45 67"
                  >
                </div>
                <div>
                  <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                    Page Facebook (optionnel)
                  </label>
                  <input 
                    v-model="newVenue.facebook" 
                    type="url"
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm sm:text-base"
                    placeholder="https://facebook.com/gamingarena"
                  >
                </div>
                <div>
                  <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                    Instagram (optionnel)
                  </label>
                  <input 
                    v-model="newVenue.instagram" 
                    type="url"
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm sm:text-base"
                    placeholder="https://instagram.com/gamingarena"
                  >
                </div>
                <div>
                  <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                    Site web (optionnel)
                  </label>
                  <input 
                    v-model="newVenue.site_web" 
                    type="url"
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm sm:text-base"
                    placeholder="https://gamingarena.com"
                  >
                </div>
              </div>
            </div>

            <!-- Étape 5: Tarification & Services -->
            <div v-show="currentStep === 5" class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
              <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-sm font-bold">5</span>
                Tarification & Services
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tarif horaire (FCFA) <span class="text-red-500">*</span>
                  </label>
                  <input 
                    v-model="newVenue.tarif_minimum" 
                    type="number" 
                    step="100"
                    name="tarif_minimum"
                    required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="5000"
                  >
                  <p class="text-xs text-gray-500 mt-1">Prix par heure en Francs CFA</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Capacité maximum <span class="text-red-500">*</span>
                  </label>
                  <input 
                    v-model="newVenue.capacite" 
                    type="number" 
                    min="1"
                    name="capacite"
                    required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="20"
                  >
                  <p class="text-xs text-gray-500 mt-1">Nombre maximum de joueurs</p>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-4">
                  Équipements et services disponibles
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                  <!-- Consoles -->
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.playstation_4" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">PlayStation 4</span>
                  </label>
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.playstation_5" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">PlayStation 5</span>
                  </label>
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.xbox_series" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">Xbox Series</span>
                  </label>
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.nintendo_switch" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">Nintendo Switch</span>
                  </label>
                  
                  <!-- PC -->
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.pc_gaming" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">PC Gaming</span>
                  </label>
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.racing_sim" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">Simulateur de course</span>
                  </label>
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.flight_sim" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">Simulateur de vol</span>
                  </label>
                  
                  <!-- VR -->
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.vr_gaming" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">VR Gaming</span>
                  </label>
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.arcade_cabinets" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">Borne d'arcade</span>
                  </label>
                  
                  <!-- Services -->
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.wifi" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">WiFi</span>
                  </label>
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.climatisation" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">Climatisation</span>
                  </label>
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.snack_bar" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">Snack Bar</span>
                  </label>
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.parking" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">Parking</span>
                  </label>
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.streaming_setup" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">Setup Streaming</span>
                  </label>
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.tournament_area" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">Zone Tournoi</span>
                  </label>
                  <label class="flex items-center gap-2 text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input 
                      v-model="newVenue.services.lounge_area" 
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm">Espace détente</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Étape 6: Images & Médias -->
            <div v-show="currentStep === 6" class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
              <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-sm font-bold">6</span>
                Images & Médias
              </h3>
              <div class="space-y-6">
                <!-- Bannière -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Bannière principale <span class="text-red-500">*</span>
                  </label>
                  
                  <!-- Afficher l'image existante si en mode édition -->
                  <div v-if="isEditing && newVenue.banniere_url" class="mb-4">
                    <img 
                      :src="newVenue.banniere_url" 
                      alt="Bannière actuelle" 
                      class="w-full h-48 object-cover rounded-lg border border-gray-300"
                    >
                    <p class="text-sm text-gray-500 mt-2">Image actuelle</p>
                  </div>
                  
                  <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                    <input 
                      @change="handleBanniereUpload"
                      type="file" 
                      accept="image/*"
                      name="banniere"
                      :required="!isEditing"
                      class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    >
                    <p class="text-sm text-gray-500 mt-2">
                      {{ newVenue.banniere_file ? newVenue.banniere_file.name : (isEditing ? 'Changer l\'image (optionnel)' : 'Sélectionner une image') }}
                    </p>
                  </div>
                </div>
                
                <!-- Galerie -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Galerie d'images <span class="text-red-500">*</span>
                  </label>
                  
                  <!-- Afficher les images existantes si en mode édition -->
                  <div v-if="isEditing && newVenue.galerie_urls && newVenue.galerie_urls.length > 0" class="mb-4 grid grid-cols-2 md:grid-cols-3 gap-2">
                    <div v-for="(imageUrl, index) in newVenue.galerie_urls" :key="'existing-' + index" class="relative">
                      <img 
                        :src="imageUrl" 
                        :alt="'Image ' + (index + 1)" 
                        class="w-full h-24 object-cover rounded border border-gray-300"
                      >
                      <p class="text-xs text-gray-500 mt-1 text-center">Image {{ index + 1 }}</p>
                    </div>
                  </div>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="(file, index) in newVenue.galerie_files" :key="index">
                      <input 
                        @change="handleGalerieUpload(index, $event)"
                        type="file" 
                        accept="image/*"
                        :name="'galerie_' + index"
                        :required="!isEditing"
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                      >
                      <p class="text-sm text-gray-500 mt-1">
                        {{ file ? file.name : (isEditing ? 'Changer image ' + (index + 1) + ' (optionnel)' : 'Image ' + (index + 1)) }}
                      </p>
                    </div>
                  </div>
                </div>
                
                <!-- Logo -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Logo (optionnel)
                  </label>
                  
                  <!-- Afficher le logo existant si en mode édition -->
                  <div v-if="isEditing && newVenue.logo_url" class="mb-4">
                    <img 
                      :src="newVenue.logo_url" 
                      alt="Logo actuel" 
                      class="w-32 h-32 object-contain rounded-lg border border-gray-300"
                    >
                    <p class="text-sm text-gray-500 mt-2">Logo actuel</p>
                  </div>
                  
                  <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <input 
                      @change="handleLogoUpload"
                      type="file" 
                      accept="image/*"
                      name="logo"
                      class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    >
                    <p class="text-sm text-gray-500 mt-2">
                      {{ newVenue.logo_file ? newVenue.logo_file.name : (isEditing ? 'Changer le logo (optionnel)' : 'Sélectionner un logo') }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
              <button 
                type="button"
                @click="showCreateForm = false"
                class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors"
              >
                Annuler
              </button>
              
              <div class="flex gap-4">
                <button 
                  v-if="currentStep > 1"
                  type="button"
                  @click="prevStep"
                  class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors"
                >
                  Précédent
                </button>
                
                <button 
                  v-if="currentStep < totalSteps"
                  type="button"
                  @click="nextStep"
                  class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold rounded-lg hover:from-blue-500 hover:to-blue-400 transition-all duration-300 transform hover:scale-105"
                >
                  Suivant
                </button>
                
                <button 
                  v-if="currentStep === totalSteps"
                  type="submit"
                  class="group relative px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold rounded-lg hover:from-blue-500 hover:to-blue-400 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center gap-2"
                >
                  <svg v-if="!isEditing" class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                  </svg>
                  <svg v-else class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                  </svg>
                  <span>{{ isEditing ? 'Mettre à jour' : 'Créer' }}</span>
                  <div class="absolute inset-0 rounded-lg bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                </button>
                <p v-if="currentStep === totalSteps" class="text-sm text-gray-500 mt-3">
                  {{ isEditing ? 'Les modifications seront appliquées immédiatement' : 'Votre salle sera visible après validation' }}
                </p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </main>

    <!-- Modal pour voir les images -->
    <div v-if="showImagesModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-[9999] p-4">
      <div class="bg-white rounded-2xl max-w-6xl w-full max-h-[95vh] overflow-hidden shadow-2xl" @click.stop>
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b bg-white sticky top-0 z-10">
          <h3 class="text-2xl font-bold text-gray-900">Images de la salle</h3>
          <button 
            @click="showImagesModal = false"
            class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-lg"
          >
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
          </button>
        </div>

        <!-- Contenu -->
        <div class="p-6 overflow-y-auto max-h-[calc(95vh-100px)] bg-gray-50">
          <!-- Bannière -->
          <div class="mb-8">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Bannière principale</h4>
            <div v-if="props.salle?.image_url && props.salle.image_url !== 'placeholder_banniere.jpg'" class="relative group">
              <img 
                :src="props.salle.image_url.startsWith('http') ? props.salle.image_url : `/uploads/${props.salle.image_url}`" 
                alt="Bannière" 
                class="w-full h-64 object-cover rounded-lg shadow-lg"
              >
              <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center">
                <button class="opacity-0 group-hover:opacity-100 bg-blue-600 text-white px-4 py-2 rounded-lg transition-all">
                  <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                  </svg>
                  Modifier
                </button>
              </div>
            </div>
            <div v-else class="w-full h-64 bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg flex items-center justify-center text-gray-500 shadow-lg">
              <div class="text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>
                <p class="font-medium">Aucune bannière</p>
                <p class="text-sm text-gray-400 mt-1">Ajoutez une bannière pour votre salle</p>
              </div>
            </div>
          </div>

          <!-- Logo -->
          <div class="mb-8">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Logo</h4>
            <div v-if="props.salle?.logo" class="relative inline-block group">
              <img 
                :src="props.salle.logo" 
                alt="Logo" 
                class="w-32 h-32 object-contain rounded-lg border-2 border-gray-200 shadow-lg bg-white p-2"
              >
              <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center">
                <button class="opacity-0 group-hover:opacity-100 bg-blue-600 text-white p-2 rounded-lg transition-all">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                  </svg>
                </button>
              </div>
            </div>
            <div v-else class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center text-gray-400 shadow-lg border-2 border-dashed border-gray-300">
              <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
              </svg>
            </div>
          </div>

          <!-- Galerie -->
          <div>
            <div class="flex items-center justify-between mb-6">
              <h4 class="text-lg font-medium text-gray-900">Galerie d'images</h4>
              <button class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                Ajouter des images
              </button>
            </div>
            
            <!-- Afficher les images réelles de la salle -->
            <div v-if="props.salle?.images && props.salle.images.length > 0" class="space-y-6">
              <!-- Image mise en avant -->
              <div class="relative group overflow-hidden rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300">
                <img 
                  :src="props.salle.images[0].startsWith('salles/') ? `/uploads/${props.salle.images[0]}` : props.salle.images[0]" 
                  alt="Image principale" 
                  class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-300"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent">
                  <div class="absolute bottom-6 left-6 right-6">
                    <h5 class="text-white text-xl font-semibold mb-2">Vue principale de la salle</h5>
                    <p class="text-white/80 text-sm mb-4">Cliquez sur les actions pour modifier cette image</p>
                    <div class="flex gap-3">
                      <button class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                        Modifier
                      </button>
                      <button 
                        @click="confirmDeleteVenue"
                        class="bg-red-500/80 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors flex items-center gap-2"
                      >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Supprimer
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Galerie en grille -->
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div v-for="(image, index) in props.salle.images.slice(1)" :key="index" class="relative group overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                  <img 
                    :src="image.startsWith('salles/') ? `/uploads/${image}` : image" 
                    :alt="'Image ' + (index + 2)" 
                    class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300"
                  >
                  <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                    <button class="bg-white/90 backdrop-blur-sm text-gray-700 p-2 rounded-lg hover:bg-white transition-colors">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                      </svg>
                    </button>
                    <button class="bg-white/90 backdrop-blur-sm text-red-600 p-2 rounded-lg hover:bg-white transition-colors">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                      </svg>
                    </button>
                  </div>
                </div>
                
                <!-- Bouton ajouter -->
                <div class="border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center hover:border-blue-400 transition-colors cursor-pointer group">
                  <div class="text-center p-4">
                    <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-500 transition-colors mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-gray-600 text-sm font-medium group-hover:text-blue-600 transition-colors">Ajouter</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Si aucune image réelle, afficher un message -->
            <div v-else class="text-center py-12">
              <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>
              </div>
              <h5 class="text-xl font-semibold text-gray-700 mb-3">Aucune image dans la galerie</h5>
              <p class="text-gray-500 mb-6 max-w-md mx-auto">
                Ajoutez des photos pour présenter votre salle et attirer plus de clients.
              </p>
              <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Ajouter des images
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de succès -->
  <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-100">
      <div class="text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-check text-green-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">
          {{ isEditing ? 'Salle mise à jour !' : 'Salle créée avec succès !' }}
        </h3>
        <p class="text-gray-600 mb-6">
          {{ isEditing ? 'Votre salle a été mise à jour avec succès.' : 'Votre salle a été ajoutée et est maintenant visible.' }}
        </p>
        <button 
          @click="redirectToVenues" 
          class="w-full bg-gradient-to-r from-green-600 to-green-500 text-white font-medium py-3 px-6 rounded-lg hover:from-green-500 hover:to-green-400 transition-all duration-300 transform hover:scale-105"
        >
          Voir ma salle
        </button>
      </div>
    </div>
  </div>

  <!-- Modal d'erreur -->
  <div v-if="showErrorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-100">
      <div class="text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Erreur</h3>
        <p class="text-gray-600 mb-6">{{ errorMessage }}</p>
        <button 
          @click="showErrorModal = false" 
          class="w-full bg-gradient-to-r from-red-600 to-red-500 text-white font-medium py-3 px-6 rounded-lg hover:from-red-500 hover:to-red-400 transition-all duration-300 transform hover:scale-105"
        >
          Fermer
        </button>
      </div>
    </div>
  </div>

  <!-- Modal de validation -->
  <div v-if="showValidationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-100">
      <div class="text-center">
        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-info-circle text-yellow-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Validation requise</h3>
        <p class="text-gray-600 mb-6">Veuillez remplir tous les champs obligatoires avant de continuer.</p>
        <button 
          @click="showValidationModal = false" 
          class="w-full bg-gradient-to-r from-yellow-600 to-yellow-500 text-white font-medium py-3 px-6 rounded-lg hover:from-yellow-500 hover:to-yellow-400 transition-all duration-300 transform hover:scale-105"
        >
          Compris
        </button>
      </div>
    </div>
  </div>

  <!-- Modal de confirmation de suppression -->
  <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-100">
      <div class="text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Confirmer la suppression</h3>
        <p class="text-gray-600 mb-6">
          Êtes-vous sûr de vouloir supprimer la salle "<strong>{{ props.salle?.nom || 'cette salle' }}</strong>" ? 
          Cette action est irréversible et toutes les données associées seront perdues.
        </p>
        <div class="flex gap-3">
          <button 
            @click="cancelDelete" 
            class="flex-1 bg-gray-200 text-gray-800 font-medium py-3 px-6 rounded-lg hover:bg-gray-300 transition-colors"
          >
            Annuler
          </button>
          <button 
            @click="deleteVenue" 
            class="flex-1 bg-red-600 text-white font-medium py-3 px-6 rounded-lg hover:bg-red-700 transition-colors"
          >
            Supprimer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
                                                                            
