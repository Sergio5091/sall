<script setup>
import { ref, watch, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';
import GoogleMap from '../../Components/GoogleMap.vue';
import ConfirmModal from '../../Components/ConfirmModal.vue';
import NotificationModal from '../../Components/NotificationModal.vue';

const props = defineProps({
  salle: Object,
  coordinates: Object
});

const showCreateForm = ref(false);
const isEditing = ref(false);
const showImagesModal = ref(false);

// États pour les modaux
const showNotificationModal = ref(false);
const notificationType = ref('success');
const notificationTitle = ref('');
const notificationMessage = ref('');

// S'assurer que la modal est fermée au chargement
onMounted(() => {
  showImagesModal.value = false;
});
const currentStep = ref(1);
const totalSteps = ref(6);

// Empêcher le scroll de la page quand la modal est ouverte
watch(showImagesModal, (newValue) => {
  if (newValue) {
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
    lundi: { active: false, ouverture: '09:00', fermeture: '18:00' },
    mardi: { active: false, ouverture: '09:00', fermeture: '18:00' },
    mercredi: { active: false, ouverture: '09:00', fermeture: '18:00' },
    jeudi: { active: false, ouverture: '09:00', fermeture: '18:00' },
    vendredi: { active: false, ouverture: '09:00', fermeture: '18:00' },
    samedi: { active: false, ouverture: '09:00', fermeture: '18:00' },
    dimanche: { active: false, ouverture: '09:00', fermeture: '18:00' }
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
    pc_gaming: false,
    simulateur_voiture: false,
    vr: false,
    billard: false,
    baby_foot: false,
    jeux_societe: false,
    tournois_evenements: false
  },
  banniere_file: null,
  galerie_files: [null, null, null, null, null],
  logo_file: null
});

// Services personnalisés ajoutés par l'utilisateur (objet { name, active })
const customServices = ref([]);
const newServiceName = ref('');

const addCustomService = () => {
  const name = newServiceName.value && newServiceName.value.trim();
  if (!name) return;
  if (!customServices.value.some(s => s.name.toLowerCase() === name.toLowerCase())) {
    customServices.value.push({ name, active: true });
  }
  newServiceName.value = '';
};

const removeCustomService = (index) => {
  customServices.value.splice(index, 1);
};

// Validation des étapes
const validateStep = (step) => {
  switch (step) {
    case 1:
      return newVenue.value.nom && newVenue.value.categorie &&
        newVenue.value.type_salle && newVenue.value.description;
    case 2:
      return newVenue.value.pays && newVenue.value.ville && newVenue.value.rue;
    case 3:
      // At least one day must be active and each active day must have opening and closing times
      const jours = Object.values(newVenue.value.jours_ouverture || {});
      if (!jours.some(d => d.active)) return false;
      return jours.every(d => !d.active || (d.ouverture && d.fermeture));
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
    notificationType.value = 'error';
    notificationTitle.value = 'Erreur de validation';
    notificationMessage.value = 'Veuillez remplir tous les champs obligatoires';
    showNotificationModal.value = true;
    return;
  }

  // Préparer les données selon si on est en mode édition ou création
  const venueData = {
    // Champs de base - exactement comme le backend attend
    nom: newVenue.value.nom || '',
    description: newVenue.value.description || '',
    type: newVenue.value.type_salle || '',
    categorie: newVenue.value.categorie || '',

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
    services: Object.assign({}, newVenue.value.services || {}, { custom: customServices.value.filter(s => s.active).map(s => s.name) }),

    // Statut
    statut: 'actif',
    valide_par_admin: false
  };

  // Choisir la bonne route et méthode
  const route = isEditing.value ? `/promoter/venues/${props.salle.id}` : '/promoter/venues';
  const method = 'POST'; // Toujours POST pour FormData

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
      // S'assurer que le chemin commence par /storage/ pour les images locales
      let imagePath = image;
      if (image && image.startsWith('salles/')) {
        imagePath = `/storage/${image}`;
      }
      formData.append(`images[${index}]`, imagePath);
    });
  }

  // Ajouter l'image de la bannière avec chemin correct
  if (venueData.image_url) {
    let bannerPath = venueData.image_url;
    if (bannerPath && bannerPath.startsWith('salles/')) {
      bannerPath = `/storage/${bannerPath}`;
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
      logoPath = `/storage/${logoPath}`;
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
  // === MODE ÉDITION ===
  // isEditing: isEditing.value
  // Images de la salle: props.salle?.images
  // Image URL: props.salle?.image_url
  // venueData: venueData
  // horaires: venueData.horaires
  // services: venueData.services
  // FormData entries:
  for (let [key, value] of formData.entries()) {
    // Debug entry: key, value
  }

  // Envoi des données au backend
  router.visit(route, {
    method: method,
    data: formData,
    onSuccess: (response) => {
      notificationType.value = 'success';
      notificationTitle.value = 'Succès';
      notificationMessage.value = isEditing.value ? 'Salle mise à jour avec succès !' : 'Salle créée avec succès !';
      showNotificationModal.value = true;
      
      // Redirection vers la page des salles pour voir les modifications
      setTimeout(() => {
        window.location.href = '/promoter/venues';
      }, 2000);
    },
    onError: (errors) => {
      let errorMessage = isEditing.value ?
        'Une erreur est survenue lors de la mise à jour de votre salle:\n\n' :
        'Une erreur est survenue lors de la création de votre salle:\n\n';

      if (typeof errors === 'object') {
        Object.keys(errors).forEach(key => {
          errorMessage += `${key}: ${errors[key]}\n`;
        });
      } else {
        errorMessage += errors;
      }

      notificationType.value = 'error';
      notificationTitle.value = 'Erreur';
      notificationMessage.value = errorMessage;
      showNotificationModal.value = true;
    }
  });
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
    jours_ouverture: {
      lundi: { active: false, ouverture: '09:00', fermeture: '18:00' },
      mardi: { active: false, ouverture: '09:00', fermeture: '18:00' },
      mercredi: { active: false, ouverture: '09:00', fermeture: '18:00' },
      jeudi: { active: false, ouverture: '09:00', fermeture: '18:00' },
      vendredi: { active: false, ouverture: '09:00', fermeture: '18:00' },
      samedi: { active: false, ouverture: '09:00', fermeture: '18:00' },
      dimanche: { active: false, ouverture: '09:00', fermeture: '18:00' }
    },
    services: {
      wifi: false,
      parking: false,
      climatisation: false,
      accessibilite: false,
      surveillance: false,
      snack_bar: false,
      restaurant: false,
      bar: false,
      terrasse: false,
      espace_fumeur: false,
      vestiaires: false
    },
    banniere_file: null,
    galerie_files: [null, null, null, null, null],
    logo_file: null
  };
  currentStep.value = 1;
  customServices.value = [];
};

const editVenue = () => {
  if (props.salle) {
    // Données brutes de la salle: props.salle
    // Horaires bruts: props.salle?.horaires

    // Extraire les jours d'ouverture correctement
    const horairesData = props.salle?.horaires;
    let joursOuverture = {
      lundi: { active: false, ouverture: '09:00', fermeture: '18:00' },
      mardi: { active: false, ouverture: '09:00', fermeture: '18:00' },
      mercredi: { active: false, ouverture: '09:00', fermeture: '18:00' },
      jeudi: { active: false, ouverture: '09:00', fermeture: '18:00' },
      vendredi: { active: false, ouverture: '09:00', fermeture: '18:00' },
      samedi: { active: false, ouverture: '09:00', fermeture: '18:00' },
      dimanche: { active: false, ouverture: '09:00', fermeture: '18:00' }
    };

    if (horairesData && typeof horairesData === 'object') {
      const source = horairesData.jours ? horairesData.jours : horairesData;
      Object.keys(joursOuverture).forEach(day => {
        const val = source[day];
        if (val) {
          if (typeof val === 'object') {
            joursOuverture[day] = {
              active: true,
              ouverture: val.ouverture || val.start || '09:00',
              fermeture: val.fermeture || val.end || '18:00'
            };
          } else if (typeof val === 'boolean') {
            joursOuverture[day].active = val;
            joursOuverture[day].ouverture = source.ouverture || joursOuverture[day].ouverture;
            joursOuverture[day].fermeture = source.fermeture || joursOuverture[day].fermeture;
          }
        }
      });
    }

    // Jours d'ouverture mappés: joursOuverture

    // Pré-remplir le formulaire avec les données de la salle existante
    newVenue.value = {
      nom: props.salle.nom || '',
      description: props.salle.description || '',
      categorie: props.salle.categorie || '',
      type_salle: props.salle.type || '',
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
      jours_ouverture: joursOuverture,
      services: props.salle.services || {
        wifi: false,
        parking: false,
        climatisation: false,
        accessibilite: false,
        surveillance: false,
        snack_bar: false,
        restaurant: false,
        bar: false,
        terrasse: false,
        espace_fumeur: false,
        vestiaires: false
      },
      banniere_file: null,
      galerie_files: [null, null, null, null, null],
      logo_file: null,
      // Garder les URLs des images existantes avec chemins corrects
      banniere_url: props.salle.image_url && props.salle.image_url.startsWith('salles/') ? `/storage/${props.salle.image_url}` : props.salle.image_url || null,
      galerie_urls: (props.salle.images || []).map(img => img && img.startsWith('salles/') ? `/storage/${img}` : img),
      logo_url: props.salle.logo && props.salle.logo.startsWith('salles/') ? `/storage/${props.salle.logo}` : props.salle.logo || null
    };

    // Formulaire pré-rempli: newVenue.value
    // Pré-remplir les services personnalisés si présents
    customServices.value = [];
    const existingServices = props.salle.services;
    const knownKeys = Object.keys(newVenue.value.services || {});
    if (Array.isArray(existingServices)) {
      // s'il s'agit d'un tableau, prendre les éléments string non connus
      customServices.value = existingServices.filter(s => typeof s === 'string' && !knownKeys.includes(s)).map(s => ({ name: s, active: true }));
    } else if (existingServices && typeof existingServices === 'object') {
      if (Array.isArray(existingServices.custom)) {
        customServices.value = existingServices.custom.map(s => ({ name: s, active: true }));
      } else {
        // Extraire les clés non connues avec valeur truthy
        Object.keys(existingServices).forEach(k => {
          if (!knownKeys.includes(k) && existingServices[k]) {
            customServices.value.push({ name: k, active: true });
          }
        });
      }
    }

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

  <div class="relative flex h-screen w-full bg-gray-50 font-display text-gray-800">
    <!-- Sidebar Component -->
    <Sidebar current-route="promoter.venues" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto transition-all duration-300 pt-16 lg:pt-0">
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
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" clip-rule="evenodd" />
                <path fill-rule="evenodd"
                  d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                  clip-rule="evenodd" />
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
            <button @click="showCreateForm = true"
              class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold rounded-lg hover:from-blue-500 hover:to-blue-400 transition-all duration-300 transform hover:scale-105">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                  clip-rule="evenodd" />
              </svg>
              <span>Créer ma salle maintenant</span>
            </button>
          </div>
        </div>

        <!-- État : Salle existe -->
        <div v-else-if="props.salle && !showCreateForm" class="space-y-6">
          <!-- Carte principale de la salle avec design amélioré -->
          <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-lg">
            <!-- Header avec image de couverture -->
            <div class="relative h-64 bg-gradient-to-br from-blue-600 to-purple-600">
              <img v-if="props.salle.image_url && props.salle.image_url !== 'placeholder_banniere.jpg'"
                :src="props.salle.image_url.startsWith('http') ? props.salle.image_url : `/storage/${props.salle.image_url}`"
                :alt="props.salle.nom" class="w-full h-full object-cover">
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent"></div>

              <!-- Logo et informations superposées -->
              <div class="absolute bottom-6 left-6 right-6 flex items-end justify-between">
                <div class="flex items-end gap-4">
                  <!-- Logo -->
                  <div class="w-20 h-20 bg-white rounded-xl p-2 shadow-xl">
                    <img v-if="props.salle.logo"
                      :src="props.salle.logo.startsWith('http') ? props.salle.logo : `/storage/${props.salle.logo}`"
                      :alt="`Logo ${props.salle.nom}`" class="w-full h-full object-contain">
                    <div v-else
                      class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg flex items-center justify-center">
                      <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                      </svg>
                    </div>
                  </div>

                  <!-- Nom et statuts -->
                  <div class="text-white">
                    <h1 class="text-3xl font-bold mb-2 drop-shadow-lg">{{ props.salle.nom }}</h1>
                    <div class="flex flex-wrap gap-2">
                      <span
                        class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-full border border-white/30">
                        {{ props.salle.categorie }}
                      </span>
                      <span
                        class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-full border border-white/30">
                        {{ props.salle.type }}
                      </span>
                      <span
                        :class="[
                          'px-3 py-1 text-sm font-medium rounded-full border backdrop-blur-sm',
                          props.salle.valide_par_admin
                            ? 'bg-green-500/20 text-green-100 border-green-400/30'
                            : 'bg-yellow-500/20 text-yellow-100 border-yellow-400/30'
                        ]">
                        {{ props.salle.valide_par_admin ? 'Active' : 'En attente' }}
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                  <button @click="editVenue"
                    class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-lg">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path
                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Modifier
                  </button>
                  <button
                    class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white font-medium rounded-lg hover:bg-white/30 transition-colors border border-white/30 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path
                        d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                    </svg>
                    Partager
                  </button>
                </div>
              </div>
            </div>

            <!-- Contenu principal -->
            <div class="p-8">
              <!-- Description -->
              <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Description</h3>
                <p class="text-gray-600 leading-relaxed">{{ props.salle.description }}</p>
              </div>

              <!-- Statistiques en ligne horizontale -->
              <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Informations clés</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                  <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4">
                      <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-blue-600 font-semibold text-sm uppercase tracking-wide mb-1">Capacité</p>
                        <p class="text-3xl font-bold text-blue-900 mb-1">{{ props.salle.capacite_max ||
                          props.salle.capacite || 0 }}</p>
                        <p class="text-sm text-blue-600 font-medium">personnes maximum</p>
                      </div>
                    </div>
                  </div>

                  <div
                    class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4">
                      <div class="w-14 h-14 bg-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path
                            d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                          <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                            clip-rule="evenodd" />
                        </svg>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-green-600 font-semibold text-sm uppercase tracking-wide mb-1">Tarif/heure</p>
                        <p class="text-3xl font-bold text-green-900 mb-1">{{ props.salle.prix_heure || 0 }} FCFA</p>
                        <p class="text-sm text-green-600 font-medium">prix de base</p>
                      </div>
                    </div>
                  </div>

                  <div v-if="props.salle.prix_journee"
                    class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4">
                      <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                        </svg>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-purple-600 font-semibold text-sm uppercase tracking-wide mb-1">Tarif/journée</p>
                        <p class="text-3xl font-bold text-purple-900 mb-1">{{ props.salle.prix_journee }} FCFA</p>
                        <p class="text-sm text-purple-600 font-medium">journée complète</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Informations détaillées en grille -->
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Contact -->
                <div class="bg-gray-50 rounded-xl p-6">
                  <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                      <path
                        d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                    </svg>
                    Contact
                  </h4>
                  <div class="space-y-3">
                    <div v-if="props.salle.telephone" class="flex items-center gap-3">
                      <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                      </svg>
                      <span class="text-gray-700">{{ props.salle.telephone }}</span>
                    </div>
                    <div v-if="props.salle.email" class="flex items-center gap-3">
                      <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                      </svg>
                      <span class="text-gray-700">{{ props.salle.email }}</span>
                    </div>
                    <div v-if="props.salle.whatsapp" class="flex items-center gap-3">
                      <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                      </svg>
                      <span class="text-gray-700">{{ props.salle.whatsapp }}</span>
                    </div>
                    <div v-if="props.salle.site_web" class="flex items-center gap-3">
                      <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                          d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z"
                          clip-rule="evenodd" />
                      </svg>
                      <a :href="props.salle.site_web" target="_blank" class="text-blue-600 hover:text-blue-700">
                        {{ props.salle.site_web }}
                      </a>
                    </div>
                  </div>
                </div>

                <!-- Adresse -->
                <div class="bg-gray-50 rounded-xl p-6">
                  <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                        clip-rule="evenodd" />
                    </svg>
                    Adresse
                  </h4>
                  <div class="space-y-2">
                    <p class="text-gray-700 font-medium">{{ props.salle.adresse }}</p>
                    <p class="text-gray-600">{{ props.salle.quartier }}</p>
                    <p class="text-gray-600">{{ props.salle.code_postal }} {{ props.salle.ville }}</p>
                    <p class="text-gray-600">{{ props.salle.pays }}</p>
                  </div>
                </div>

                <!-- Réseaux sociaux -->
                <div class="bg-gray-50 rounded-xl p-6">
                  <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                      <path
                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Réseaux
                  </h4>
                  <div class="space-y-3">
                    <div v-if="props.salle.facebook" class="flex items-center gap-3">
                      <i class="fab fa-facebook text-blue-600 w-4"></i>
                      <a :href="props.salle.facebook" target="_blank" class="text-blue-600 hover:text-blue-700">
                        Facebook
                      </a>
                    </div>
                    <div v-if="props.salle.instagram" class="flex items-center gap-3">
                      <i class="fab fa-instagram text-pink-600 w-4"></i>
                      <a :href="props.salle.instagram" target="_blank" class="text-pink-600 hover:text-pink-700">
                        Instagram
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Galerie d'images preview -->
              <div v-if="props.salle.images && props.salle.images.length > 0" class="mb-8">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-xl font-bold text-gray-900">Galerie d'images</h3>
                  <button @click="showImagesModal = true"
                    class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                        clip-rule="evenodd" />
                    </svg>
                    Voir tout ({{ props.salle.images.length }})
                  </button>
                </div>

                <!-- Miniatures des images -->
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                  <div v-for="(image, index) in props.salle.images.slice(0, 6)" :key="index"
                    class="relative group cursor-pointer overflow-hidden rounded-lg aspect-square"
                    @click="showImagesModal = true">
                    <img :src="image.startsWith('http') ? image : `/storage/${image}`"
                      :alt="`Image ${index + 1} de ${props.salle.nom}`"
                      class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div
                      class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all rounded-lg flex items-center justify-center">
                      <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd"
                          d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                          clip-rule="evenodd" />
                      </svg>
                    </div>
                  </div>

                  <!-- Image "voir plus" si plus de 6 images -->
                  <div v-if="props.salle.images.length > 6"
                    class="relative group cursor-pointer overflow-hidden rounded-lg aspect-square bg-gray-100"
                    @click="showImagesModal = true">
                    <div class="absolute inset-0 bg-gray-900/80 flex flex-col items-center justify-center text-white">
                      <span class="text-2xl font-bold">+{{ props.salle.images.length - 6 }}</span>
                      <span class="text-sm">plus</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Carte de localisation -->
              <div v-if="props.coordinates" class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Localisation</h3>
                <div class="bg-gray-100 rounded-xl overflow-hidden shadow-inner" style="height: 300px;">
                  <iframe
                    :src="`https://www.openstreetmap.org/export/embed.html?bbox=${props.coordinates.lng - 0.005},${props.coordinates.lat - 0.005},${props.coordinates.lng + 0.005},${props.coordinates.lat + 0.005}&layer=mapnik&marker=${props.coordinates.lat},${props.coordinates.lng}`"
                    width="100%" height="300" frameborder="0" class="border-0"></iframe>
                </div>

                <!-- Actions de localisation -->
                <div class="mt-4 flex gap-3">
                  <a :href="`https://maps.google.com/?q=${props.coordinates.lat},${props.coordinates.lng}`"
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                        clip-rule="evenodd" />
                    </svg>
                    Google Maps
                  </a>
                  <a :href="`https://waze.com/ul?ll=${props.coordinates.lat},${props.coordinates.lng}&navigate=yes`"
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                      <path fill-rule="evenodd"
                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                        clip-rule="evenodd" />
                    </svg>
                    Waze
                  </a>
                </div>
              </div>

              <!-- Services et équipements -->
              <div v-if="props.salle.services && Object.keys(props.salle.services).length > 0" class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Services & Équipements</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                  <div v-for="(service, key) in props.salle.services" :key="key"
                    v-if="service && typeof service === 'boolean' && service"
                    class="flex items-center gap-2 p-3 bg-green-50 rounded-lg border border-green-200">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-green-800 font-medium">{{ key }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Formulaire de création -->
        <div v-else class="max-w-4xl mx-auto">
          <!-- Header du formulaire -->
          <div class="mb-8">
            <button @click="cancelEdit"
              class="inline-flex items-center gap-2 text-gray-400 hover:text-gray-600 transition-colors mb-4">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                  clip-rule="evenodd" />
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
                :style="{ width: getProgressPercentage() + '%' }"></div>
            </div>

            <!-- Étapes -->
            <div class="flex justify-between">
              <button v-for="step in totalSteps" :key="step" @click="goToStep(step)"
                class="flex flex-col items-center cursor-pointer group" :disabled="step > currentStep">
                <div
                  class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300 mb-2"
                  :class="{
                    'bg-blue-600 text-white': step === currentStep,
                    'bg-green-600 text-white': isStepCompleted(step),
                    'bg-gray-600 text-gray-400': step > currentStep,
                    'hover:bg-blue-500': step === currentStep,
                    'hover:bg-green-500': isStepCompleted(step)
                  }">
                  <i v-if="isStepCompleted(step)" class="fas fa-check"></i>
                  <span v-else>{{ step }}</span>
                </div>
                <span class="text-xs font-medium transition-colors" :class="{
                  'text-blue-400': step === currentStep,
                  'text-green-400': isStepCompleted(step),
                  'text-gray-500': step > currentStep
                }">
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
                  <input v-model="newVenue.nom" type="text" name="nom" required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="GameOn Arena">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Catégorie <span class="text-red-500">*</span>
                  </label>
                  <select v-model="newVenue.categorie" name="categorie" required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    <option value="">Sélectionner une catégorie</option>
                    <option value="action">Action</option>
                    <option value="aventure">Aventure</option>
                    <option value="rpg">Jeu de rôle (RPG)</option>
                    <option value="puzzle">Réflexion / Puzzle</option>
                    <option value="simulation">Simulation</option>
                    <option value="strategie">Stratégie</option>
                    <option value="sport_course">Sport et Course</option>
                    <option value="horreur">Horreur</option>
                    <option value="jeux_societe">Jeux de société</option>
                    <option value="jeux_cartes">Jeux de cartes</option>
                    <option value="rpg_papier">Jeux de rôle (papier)</option>
                    <option value="jeux_traditionnels">Jeux traditionnels</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Type de salle <span class="text-red-500">*</span>
                  </label>
                  <select v-model="newVenue.type_salle" name="type_salle" required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    <option value="">Sélectionner un type</option>
                    <option value="arcade">Salle d'arcade</option>
                    <option value="vr">Centre VR</option>
                    <option value="retro">Retro gaming</option>
                    <option value="esports">E-sport</option>
                    <option value="mixed">Mixte</option>
                    <option value="bowling">Bowling</option>
                    <option value="billard">Billard</option>
                    <option value="laser">Laser game</option>
                    <option value="escape">Escape game</option>
                    <option value="karaoke">Karaoke</option>
                  </select>
                </div>
              </div>
              <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Description complète <span class="text-red-500">*</span>
                </label>
                <textarea v-model="newVenue.description" name="description" required rows="4"
                  class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 resize-none"
                  placeholder="Décrivez votre salle, l'ambiance, les équipements..."></textarea>
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
                  <select v-model="newVenue.pays" name="pays" required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
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
                  <input v-model="newVenue.ville" type="text" name="ville" required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="Dakar">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Quartier
                  </label>
                  <input v-model="newVenue.quartier" type="text"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="Plateau">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Rue / indications d'accès <span class="text-red-500">*</span>
                  </label>
                  <input v-model="newVenue.rue" type="text" name="rue" required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="123 Rue de la République">
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Latitude (optionnel)
                  </label>
                  <input v-model="newVenue.latitude" type="number" step="any"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="14.6928">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Longitude (optionnel)
                  </label>
                  <input v-model="newVenue.longitude" type="number" step="any"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="-17.4467">
                </div>
              </div>
            </div>

            <!-- Étape 3: Horaires d'ouverture -->
            <div v-show="currentStep === 3" class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
              <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-sm font-bold">3</span>
                Horaires d'ouverture
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-600 mb-4">Sélectionnez les jours d'ouverture et définissez l'heure
                  d'ouverture et de fermeture pour chaque jour.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <template v-for="day in ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']"
                    :key="day">
                    <div class="flex items-center justify-between gap-4 p-3 border rounded-lg">
                      <div class="flex items-center gap-3">
                        <input type="checkbox" v-model="newVenue.jours_ouverture[day].active" :id="`day-${day}`"
                          class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500" />
                        <label :for="`day-${day}`" class="capitalize text-gray-700">{{ day }}</label>
                      </div>
                      <div class="flex items-center gap-2">
                        <input v-model="newVenue.jours_ouverture[day].ouverture" type="time"
                          :disabled="!newVenue.jours_ouverture[day].active"
                          class="px-3 py-2 border rounded-lg text-sm" />
                        <span class="text-gray-400">—</span>
                        <input v-model="newVenue.jours_ouverture[day].fermeture" type="time"
                          :disabled="!newVenue.jours_ouverture[day].active"
                          class="px-3 py-2 border rounded-lg text-sm" />
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>

            <!-- Étape 4: Contact -->
            <div v-show="currentStep === 4" class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
              <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-sm font-bold">4</span>
                Contact
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Numéro de téléphone <span class="text-red-500">*</span>
                  </label>
                  <input v-model="newVenue.telephone" type="tel" name="telephone" required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="+221 33 123 45 67">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Adresse email professionnelle <span class="text-red-500">*</span>
                  </label>
                  <input v-model="newVenue.email" type="email" name="email" required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="contact@gamingarena.com">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    WhatsApp (optionnel)
                  </label>
                  <input v-model="newVenue.whatsapp" type="tel"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="+221 77 123 45 67">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Page Facebook (optionnel)
                  </label>
                  <input v-model="newVenue.facebook" type="url"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="https://facebook.com/gamingarena">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Instagram (optionnel)
                  </label>
                  <input v-model="newVenue.instagram" type="url"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="https://instagram.com/gamingarena">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Site web (optionnel)
                  </label>
                  <input v-model="newVenue.site_web" type="url"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="https://gamingarena.com">
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
                    Tarif horaire minimum (€) <span class="text-red-500">*</span>
                  </label>
                  <input v-model="newVenue.tarif_minimum" type="number" step="0.01" name="tarif_minimum" required
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="15.00">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tarif horaire maximum (€) (optionnel)
                  </label>
                  <input v-model="newVenue.tarif_maximum" type="number" step="0.01"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="25.00">
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-4">
                  Services disponibles
                </label>
                <div>
                  <p class="text-sm text-gray-600 mb-3">Le promoteur définit ici les services disponibles pour cette
                    salle. Ajoute
                    des services puis coche ceux qui s'appliquent à cette salle.</p>
                  <div class="space-y-2">
                    <div v-if="customServices.length === 0" class="text-sm text-gray-500">Aucun service défini.</div>
                    <template v-for="(s, i) in customServices" :key="s.name">
                      <label class="flex items-center gap-3">
                        <input type="checkbox" v-model="s.active" class="w-4 h-4 text-blue-600" />
                        <span class="text-gray-700">{{ s.name }}</span>
                        <button type="button" @click="removeCustomService(i)"
                          class="text-red-500 ml-2">Supprimer</button>
                      </label>
                    </template>
                  </div>

                  <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Ajouter un service personnalisé</label>
                    <div class="flex gap-2">
                      <input v-model="newServiceName" type="text" placeholder="Ex: Espace VIP"
                        class="w-full px-3 py-2 border rounded-lg" />
                      <button type="button" @click="addCustomService"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">Ajouter</button>
                    </div>
                  </div>
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
                    <img :src="newVenue.banniere_url" alt="Bannière actuelle"
                      class="w-full h-48 object-cover rounded-lg border border-gray-300">
                    <p class="text-sm text-gray-500 mt-2">Image actuelle</p>
                  </div>


                  <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                    <input @change="handleBanniereUpload" type="file" accept="image/*" name="banniere"
                      :required="!isEditing"
                      class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    <p class="text-sm text-gray-500 mt-2">
                      {{ newVenue.banniere_file ? newVenue.banniere_file.name : (isEditing ? "Changer l'image (optionnel)" : 'Sélectionner une image') }}
                    </p>
                  </div>
                </div>

                <!-- Galerie -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Galerie d'images <span class="text-red-500">*</span>
                  </label>

                  <!-- Afficher les images existantes si en mode édition -->
                  <div v-if="isEditing && newVenue.galerie_urls && newVenue.galerie_urls.length > 0"
                    class="mb-4 grid grid-cols-2 md:grid-cols-3 gap-2">
                    <div v-for="(imageUrl, index) in newVenue.galerie_urls" :key="'existing-' + index" class="relative">
                      <img :src="imageUrl" :alt="'Image ' + (index + 1)"
                        class="w-full h-24 object-cover rounded border border-gray-300">
                      <p class="text-xs text-gray-500 mt-1 text-center">Image {{ index + 1 }}</p>
                    </div>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="(file, index) in newVenue.galerie_files" :key="index">
                      <input @change="handleGalerieUpload(index, $event)" type="file" accept="image/*"
                        :name="'galerie_' + index" :required="!isEditing"
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
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
                    <img :src="newVenue.logo_url" alt="Logo actuel"
                      class="w-32 h-32 object-contain rounded-lg border border-gray-300">
                    <p class="text-sm text-gray-500 mt-2">Logo actuel</p>
                  </div>

                  <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <input @change="handleLogoUpload" type="file" accept="image/*" name="logo"
                      class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    <p class="text-sm text-gray-500 mt-2">
                      {{ newVenue.logo_file ? newVenue.logo_file.name : (isEditing ? 'Changer le logo (optionnel)' : 'Sélectionner un logo') }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
              <button type="button" @click="showCreateForm = false"
                class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                Annuler
              </button>

              <div class="flex gap-4">
                <button v-if="currentStep > 1" type="button" @click="prevStep"
                  class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                  Précédent
                </button>

                <button v-if="currentStep < totalSteps" type="button" @click="nextStep"
                  class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold rounded-lg hover:from-blue-500 hover:to-blue-400 transition-all duration-300 transform hover:scale-105">
                  Suivant
                </button>

                <button v-if="currentStep === totalSteps" type="submit"
                  class="w-full py-4 bg-gradient-to-r from-green-600 to-green-500 text-white font-semibold rounded-lg hover:from-green-500 hover:to-green-400 transition-all duration-300 transform hover:scale-105">
                  {{ isEditing ? 'Mettre à jour ma salle' : 'Soumettre ma salle' }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </main>

    <!-- Modal pour voir les images -->
    <div v-if="showImagesModal" class="fixed inset-0 bg-black/80 flex items-center justify-center z-[9999] p-4">
      <div class="bg-white rounded-2xl max-w-7xl w-full max-h-[95vh] overflow-hidden shadow-2xl" @click.stop>
        <!-- Header amélioré -->
        <div
          class="flex items-center justify-between p-6 border-b bg-gradient-to-r from-blue-600 to-purple-600 text-white sticky top-0 z-10">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                  clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h3 class="text-2xl font-bold">Galerie multimédia</h3>
              <p class="text-blue-100 text-sm">{{ props.salle?.nom }}</p>
            </div>
          </div>
          <button @click="showImagesModal = false"
            class="text-white/80 hover:text-white hover:bg-white/20 transition-all p-3 rounded-xl">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </button>
        </div>

        <!-- Contenu avec onglets -->
        <div class="flex flex-col lg:flex-row h-[calc(95vh-100px)]">
          <!-- Sidebar avec navigation -->
          <div class="w-full lg:w-64 bg-gray-50 border-r border-gray-200 p-4">
            <div class="space-y-2">
              <button
                class="w-full text-left px-4 py-3 bg-blue-600 text-white rounded-lg font-medium flex items-center gap-3">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                    clip-rule="evenodd" />
                </svg>
                Toutes les images
              </button>
              <button
                class="w-full text-left px-4 py-3 hover:bg-gray-200 rounded-lg font-medium flex items-center gap-3 text-gray-700">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                Bannière
              </button>
              <button
                class="w-full text-left px-4 py-3 hover:bg-gray-200 rounded-lg font-medium flex items-center gap-3 text-gray-700">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                Logo
              </button>
              <button
                class="w-full text-left px-4 py-3 hover:bg-gray-200 rounded-lg font-medium flex items-center gap-3 text-gray-700">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                    clip-rule="evenodd" />
                </svg>
                Galerie ({{ props.salle?.images?.length || 0 }})
              </button>
            </div>

            <!-- Statistiques -->
            <div class="mt-8 p-4 bg-white rounded-lg border border-gray-200">
              <h4 class="font-semibold text-gray-900 mb-3">Statistiques</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Total images</span>
                  <span class="font-medium">{{ (props.salle?.image_url ? 1 : 0) + (props.salle?.logo ? 1 : 0) +
                    (props.salle?.images?.length || 0) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Bannière</span>
                  <span class="font-medium">{{ props.salle?.image_url ? '✓' : '✗' }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Logo</span>
                  <span class="font-medium">{{ props.salle?.logo ? '✓' : '✗' }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Galerie</span>
                  <span class="font-medium">{{ props.salle?.images?.length || 0 }} images</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Zone principale d'images -->
          <div class="flex-1 p-6 overflow-y-auto bg-gray-100">
            <!-- Bannière principale -->
            <div class="mb-8">
              <div class="flex items-center justify-between mb-4">
                <h4 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                  <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                        clip-rule="evenodd" />
                    </svg>
                  </div>
                  Bannière principale
                </h4>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">
                  Image de couverture
                </span>
              </div>

              <div v-if="props.salle?.image_url && props.salle.image_url !== 'placeholder_banniere.jpg'"
                class="relative group">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                  <img
                    :src="props.salle.image_url.startsWith('http') ? props.salle.image_url : `/storage/${props.salle.image_url}`"
                    :alt="`Bannière ${props.salle.nom}`" class="w-full h-96 object-cover">
                  <div
                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <div class="absolute bottom-6 left-6 right-6">
                      <div class="flex items-center justify-between">
                        <div class="text-white">
                          <p class="text-sm font-medium mb-1">Dimensions recommandées</p>
                          <p class="text-xs opacity-80">1920 x 640 pixels (3:1)</p>
                        </div>
                        <div class="flex gap-2">
                          <button
                            class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                              <path
                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Modifier
                          </button>
                          <button
                            class="bg-red-500/80 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                            </svg>
                            Supprimer
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="mt-4 p-4 bg-white rounded-lg border border-gray-200">
                  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                      <span class="text-gray-500">Type</span>
                      <p class="font-medium text-gray-900">Bannière</p>
                    </div>
                    <div>
                      <span class="text-gray-500">Format</span>
                      <p class="font-medium text-gray-900">JPG/PNG</p>
                    </div>
                    <div>
                      <span class="text-gray-500">Taille max</span>
                      <p class="font-medium text-gray-900">5 MB</p>
                    </div>
                    <div>
                      <span class="text-gray-500">Statut</span>
                      <p class="font-medium text-green-600">Active</p>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="bg-white rounded-xl p-12 text-center border-2 border-dashed border-gray-300">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                      clip-rule="evenodd" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune bannière</h3>
                <p class="text-gray-600 mb-6">Ajoutez une bannière pour donner de la visibilité à votre salle</p>
                <button
                  class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 mx-auto">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                      clip-rule="evenodd" />
                  </svg>
                  Ajouter une bannière
                </button>
              </div>
            </div>

            <!-- Logo -->
            <div class="mb-8">
              <div class="flex items-center justify-between mb-4">
                <h4 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                  <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path
                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                  </div>
                  Logo
                </h4>
                <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-medium rounded-full">
                  Identité visuelle
                </span>
              </div>

              <div v-if="props.salle?.logo" class="flex gap-8">
                <div class="relative group">
                  <div class="bg-white rounded-xl shadow-lg p-8">
                    <img :src="props.salle.logo.startsWith('http') ? props.salle.logo : `/storage/${props.salle.logo}`"
                      :alt="`Logo ${props.salle.nom}`" class="w-48 h-48 object-contain">
                    <div
                      class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all rounded-xl flex items-center justify-center">
                      <div class="opacity-0 group-hover:opacity-100 transition-all flex gap-2">
                        <button
                          class="bg-white/90 backdrop-blur-sm text-gray-700 p-2 rounded-lg hover:bg-white transition-all">
                          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                              d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                          </svg>
                        </button>
                        <button
                          class="bg-white/90 backdrop-blur-sm text-red-600 p-2 rounded-lg hover:bg-white transition-all">
                          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                              d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                              clip-rule="evenodd" />
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="flex-1 space-y-4">
                  <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <h5 class="font-semibold text-gray-900 mb-3">Informations du logo</h5>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                      <div>
                        <span class="text-gray-500">Format</span>
                        <p class="font-medium text-gray-900">PNG/SVG</p>
                      </div>
                      <div>
                        <span class="text-gray-500">Taille max</span>
                        <p class="font-medium text-gray-900">2 MB</p>
                      </div>
                      <div>
                        <span class="text-gray-500">Dimensions</span>
                        <p class="font-medium text-gray-900">512 x 512 px</p>
                      </div>
                      <div>
                        <span class="text-gray-500">Fond</span>
                        <p class="font-medium text-gray-900">Transparent</p>
                      </div>
                    </div>
                  </div>

                  <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <h5 class="font-semibold text-blue-900 mb-2">Conseils</h5>
                    <ul class="text-sm text-blue-800 space-y-1">
                      <li>• Utilisez un format PNG avec fond transparent</li>
                      <li>• Évitez les détails trop fins</li>
                      <li>• Assurez-vous que le logo est lisible même en petite taille</li>
                    </ul>
                  </div>
                </div>
              </div>

              <div v-else class="bg-white rounded-xl p-8 text-center border-2 border-dashed border-gray-300">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun logo</h3>
                <p class="text-gray-600 mb-6">Ajoutez votre logo pour renforcer votre identité visuelle</p>
                <button
                  class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2 mx-auto">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                      clip-rule="evenodd" />
                  </svg>
                  Ajouter un logo
                </button>
              </div>
            </div>

            <!-- Galerie d'images -->
            <div>
              <div class="flex items-center justify-between mb-4">
                <h4 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                  <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                        clip-rule="evenodd" />
                    </svg>
                  </div>
                  Galerie d'images
                </h4>
                <div class="flex items-center gap-3">
                  <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                    {{ props.salle?.images?.length || 0 }} images
                  </span>
                  <button
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                    </svg>
                    Ajouter des images
                  </button>
                </div>
              </div>

              <div v-if="props.salle?.images && props.salle.images.length > 0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  <div v-for="(image, index) in props.salle.images" :key="index"
                    class="group relative bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="aspect-square">
                      <img :src="image.startsWith('http') ? image : `/storage/${image}`"
                        :alt="`Image ${index + 1} de ${props.salle.nom}`"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div
                      class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                      <div class="absolute top-4 right-4 flex gap-2">
                        <button
                          class="bg-white/90 backdrop-blur-sm text-gray-700 p-2 rounded-lg hover:bg-white transition-all">
                          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                              d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                          </svg>
                        </button>
                        <button
                          class="bg-red-500/90 backdrop-blur-sm text-white p-2 rounded-lg hover:bg-red-600 transition-all">
                          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                              d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                              clip-rule="evenodd" />
                          </svg>
                        </button>
                      </div>
                      <div class="absolute bottom-4 left-4 right-4">
                        <p class="text-white font-medium mb-1">Image {{ index + 1 }}</p>
                        <div class="flex gap-2">
                          <button
                            class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded text-sm hover:bg-white/30 transition-all">
                            Définir comme principale
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="p-4 bg-white">
                      <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Image {{ index + 1 }}</span>
                        <span class="text-green-600 font-medium">Active</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Upload zone -->
                <div
                  class="mt-6 border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-gray-400 transition-colors">
                  <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">Ajouter plus d'images</h3>
                  <p class="text-gray-600 mb-4">Glissez-déposez des images ou cliquez pour parcourir</p>
                  <button class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                    Parcourir les fichiers
                  </button>
                  <p class="text-sm text-gray-500 mt-4">PNG, JPG jusqu'à 10MB (max 20 images)</p>
                </div>
              </div>

              <div v-else class="bg-white rounded-xl p-12 text-center border-2 border-dashed border-gray-300">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                      clip-rule="evenodd" />
                  </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune image dans la galerie</h3>
                <p class="text-gray-600 mb-6">Ajoutez des photos pour montrer votre salle sous son meilleur jour</p>
                <button
                  class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 mx-auto text-lg">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                      clip-rule="evenodd" />
                  </svg>
                  Commencer la galerie
                </button>
                <p class="text-sm text-gray-500 mt-4">Recommandé : 5-10 photos de haute qualité</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Galerie -->

  </div>

  <!-- Notification Modal -->
  <NotificationModal
    :show="showNotificationModal"
    :type="notificationType"
    :title="notificationTitle"
    :message="notificationMessage"
    @close="showNotificationModal = false"
  />
</template>
