<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

import PromoterLayout from '@/Layouts/PromoterLayout.vue';
defineOptions({ layout: PromoterLayout });
import NotificationModal from '../../Components/NotificationModal.vue';

// État du formulaire
const currentStep = ref(1);
const totalSteps = 4;
const errors = ref({});
const isSubmitting = ref(false);

// États pour les modaux
const showNotificationModal = ref(false);
const notificationType = ref('error');
const notificationTitle = ref('');
const notificationMessage = ref('');

const uploadAlert = ref({ type: '', message: '' });
const setUploadAlert = (type, message) => {
    uploadAlert.value = { type, message };
};
const clearUploadAlert = () => {
    uploadAlert.value = { type: '', message: '' };
};

// Données du nouvel événement
const newEvent = ref({
    // Étape 1: Informations de base
    titre: '',
    description: '',
    type: 'tournament',
    categorie: 'jeux_video',
    date_debut: '',
    date_fin: '',
    heure_debut: '09:00',
    heure_fin: '17:00',
    
    // Étape 2: Lieu et localisation
    lieu: '',
    adresse: '',
    ville: 'Dakar',
    pays: 'Sénégal',
    
    // Étape 3: Tarifs et capacité
    capacite_max: '100',
    prix_base: '15.00',
    devise: 'XOF',
    gratuit: false,
    visibilite: 'public',
    
    // Étape 4: Contact
    telephone: '',
    email: '',
    site_web: '',
    
    // Services jeux vidéo (customisés par le promoteur)
    services: {},
    
    // Fichiers
    banniere_file: null,
    galerie_files: [null, null, null, null, null],
    
    // URLs pour aperçu
    banniere_url: null,
    galerie_urls: []
});

// Validation par étape
const stepValidation = computed(() => {
    switch (currentStep.value) {
        case 1:
            return newEvent.value.titre && 
                   newEvent.value.description && 
                   newEvent.value.date_debut &&
                   newEvent.value.heure_debut &&
                   newEvent.value.date_fin &&
                   newEvent.value.heure_fin;
        case 2:
            return newEvent.value.lieu && 
                   newEvent.value.adresse && 
                   newEvent.value.ville && 
                   newEvent.value.pays;
        case 3:
            return newEvent.value.capacite_max;
        case 4:
            return newEvent.value.email && newEvent.value.email.includes('@');
        default:
            return false;
    }
});

// Labels pour les services gaming (legacy)
const getServiceLabel = (key) => {
  const labels = {
    console_gaming: 'Console Gaming',
    pc_gaming: 'PC Gaming',
    mobile_gaming: 'Mobile Gaming',
    vr_gaming: 'VR Gaming',
    streaming: 'Streaming',
    commentateur: 'Commentateur',
    prizes: 'Prix/Gains',
    refreshments: 'Rafraîchements'
  };
  return labels[key] || key;
};

// Services personnalisés ajoutés par le promoteur
const customServices = ref([]); // { name, active }
const newServiceName = ref('');

const addCustomService = () => {
  const name = (newServiceName.value || '').trim();
  if (!name) return;
  customServices.value.push({ name, active: true });
  newServiceName.value = '';
};

const removeCustomService = (index) => {
  customServices.value.splice(index, 1);
};

// Navigation entre étapes
const nextStep = () => {
    if (stepValidation.value && currentStep.value < totalSteps) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

// Gestion des fichiers
const validateImageFile = (file) => {
    const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    const maxSize = 10 * 1024 * 1024; // 10MB

    if (!allowedTypes.includes(file.type)) {
        setUploadAlert('error', 'Format invalide : seuls les fichiers PNG et JPG sont autorisés.');
        return false;
    }

    if (file.size > maxSize) {
        setUploadAlert('error', 'Taille trop grande : le fichier doit être inférieur à 10MB.');
        return false;
    }

    clearUploadAlert();
    return true;
};

const handleBanniereUpload = (event) => {
    const file = event.target.files[0];
    if (file && validateImageFile(file)) {
        newEvent.value.banniere_file = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            newEvent.value.banniere_url = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleGalerieUpload = (index, event) => {
    const file = event.target.files[0];
    if (file && validateImageFile(file)) {
        newEvent.value.galerie_files[index] = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            newEvent.value.galerie_urls[index] = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

// Combiner date et heure au format ISO
const formatDateTime = (date, time) => {
    if (!date || !time) return '';
    return `${date}T${time}:00`;
};

// Créer l'événement
const createEvent = () => {
  // small UX: remove alert and rely on console for debugging
    
    // Créer FormData
    const formData = new FormData();
    
    // Ajouter tous les champs essentiels pour jeux vidéo
    const eventData = {
        titre: newEvent.value.titre,
        description: newEvent.value.description,
        type: newEvent.value.type,
        categorie: newEvent.value.categorie,
        date_debut: formatDateTime(newEvent.value.date_debut, newEvent.value.heure_debut),
        date_fin: formatDateTime(newEvent.value.date_fin, newEvent.value.heure_fin),
        lieu: newEvent.value.lieu,
        adresse: newEvent.value.adresse,
        ville: newEvent.value.ville,
        pays: newEvent.value.pays,
        capacite_max: parseInt(newEvent.value.capacite_max),
        prix_base: parseFloat(newEvent.value.prix_base),
        devise: newEvent.value.devise,
        gratuit: newEvent.value.gratuit ? '1' : '0',
        visibilite: newEvent.value.visibilite,
        contact_email: newEvent.value.email,
        contact_telephone: newEvent.value.telephone || null,
        site_web: newEvent.value.site_web || null,
        // envoyer les services personnalisés ajoutés par le promoteur
        services: { custom: customServices.value.filter(s => s.active).map(s => s.name) },
        statut: 'actif',
        valide_par_admin: false
    };

    // Afficher les données dans la console
    // === DONNÉES ENVOYÉES DU FRONTEND ===
    // eventData: eventData
    // newEvent.value: newEvent.value
    
    // (debug) summary available in console
    
    Object.keys(eventData).forEach(key => {
        if (key !== 'services' && typeof eventData[key] !== 'object') {
            formData.append(key, eventData[key]);
            // Debug: key, eventData[key]
        }
    });
    
    formData.append('services', JSON.stringify(eventData.services));
    // Debug: services (JSON)
    
    // Ajouter les fichiers
    if (newEvent.value.banniere_file) {
        formData.append('image_banniere', newEvent.value.banniere_file);
        // Debug: image_banniere
    }
    
    newEvent.value.galerie_files.forEach((file, index) => {
        if (file) {
            formData.append(`galerie_files[${index}]`, file);
            // Debug: galerie_files[index]
        }
    });

    // Afficher le FormData complet
    // === FORMDATA COMPLET ===
    for (let [key, value] of formData.entries()) {
        // Debug FormData: key, value
    }

    // Démarrer la soumission
    isSubmitting.value = true;

    // Envoyer la requête
    router.post('/promoter/events', formData, {
        onSuccess: () => {
            // Afficher une modal de succès pendant quelques secondes
            notificationType.value = 'success';
            notificationTitle.value = 'Succès';
            notificationMessage.value = 'Événement créé avec succès ! Il est maintenant en brouillon.';
            showNotificationModal.value = true;
            
            // Rediriger après 3 secondes
            setTimeout(() => {
                router.visit('/promoter/events');
            }, 3000);
        },
        onError: (serverErrors) => {
            console.error('Erreurs de validation:', serverErrors);
            errors.value = serverErrors;

            let message = 'Veuillez corriger les champs en erreur.';
            if (serverErrors.image_banniere) {
                message = 'Erreur image bannière : ' + serverErrors.image_banniere[0] + '. Essayez avec une image plus petite (moins de 10MB) ou compressez-la.';
            } else {
                const details = Object.entries(serverErrors)
                    .map(([field, messages]) => `${field} : ${Array.isArray(messages) ? messages.join(', ') : messages}`)
                    .join('\n');
                if (details) {
                    message = details;
                }
            }

            notificationType.value = 'error';
            notificationTitle.value = 'Erreur de validation';
            notificationMessage.value = message;
            showNotificationModal.value = true;
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};

// Annuler
const cancel = () => {
    router.visit('/promoter/events');
};
</script>

<template>
  <Head title="Créer un événement" />
  
  <div class="flex h-screen bg-gray-50">
    
    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
      <div class="w-full px-3 sm:px-4 md:px-8 py-4 sm:py-6 md:py-8">
        <!-- Header -->
        <div class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-2 xs:gap-4 mb-6 sm:mb-8">
          <div class="min-w-0 flex-1">
            <h1 class="text-2xl xs:text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white break-words">Créer un événement</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1 sm:mt-2 text-xs sm:text-sm">Remplissez les informations pour créer</p>
          </div>
          <button @click="cancel" class="text-gray-400 hover:text-gray-600 flex-shrink-0 p-2">
            <i class="fas fa-times text-lg sm:text-xl"></i>
          </button>
        </div>

        <!-- Progression -->
        <div class="bg-white dark:bg-[#19202e] rounded-lg sm:rounded-2xl shadow-xl p-4 sm:p-6 md:p-8 mb-6">
          <div class="mb-6 sm:mb-8">
            <div class="flex items-center justify-between mb-3 sm:mb-4 overflow-x-auto pb-2">
              <div v-for="step in totalSteps" :key="step" class="flex items-center flex-shrink-0">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-xs sm:text-sm font-medium transition-colors flex-shrink-0"
                     :class="step <= currentStep ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'">
                  <i v-if="step < currentStep" class="fas fa-check text-xs sm:text-sm"></i>
                  <span v-else>{{ step }}</span>
                </div>
                <div v-if="step < totalSteps" class="w-8 sm:w-16 h-1 mx-1 sm:mx-2 transition-colors flex-shrink-0"
                     :class="step < currentStep ? 'bg-blue-600' : 'bg-gray-200'">
                </div>
              </div>
            </div>
            <div class="flex justify-between text-xs text-gray-600 gap-1 overflow-x-auto pb-2">
              <span class="flex-shrink-0">Infos</span>
              <span class="flex-shrink-0">Lieu</span>
              <span class="flex-shrink-0">Tarifs</span>
              <span class="flex-shrink-0">Médias</span>
            </div>
          </div>

          <!-- Étape 1: Informations de base -->
          <div v-if="currentStep === 1" class="space-y-4 sm:space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-6">
              <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Nom de l'événement *</label>
                <input v-model="newEvent.titre" type="text" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-sm" placeholder="Ex: Tournoi FIFA">
              </div>
              <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Type de tournoi *</label>
                <select v-model="newEvent.type" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-sm">
                  <option value="tournament">Tournoi</option>
                  <option value="lan_party">LAN Party</option>
                  <option value="showmatch">Showmatch</option>
                  <option value="casual_gaming">Gaming Casual</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Description *</label>
              <textarea v-model="newEvent.description" rows="3" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-sm" placeholder="Décrivez votre événement..."></textarea>
            </div>

            <!-- Début -->
            <div class="space-y-3 sm:space-y-0">
              <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Événement commence le *</label>
              <div class="grid grid-cols-1 xs:grid-cols-2 gap-2 xs:gap-3">
                <div>
                  <input v-model="newEvent.date_debut" type="date" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-xs sm:text-sm">
                </div>
                <div>
                  <input v-model="newEvent.heure_debut" type="time" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-xs sm:text-sm">
                </div>
              </div>
            </div>

            <!-- Fin -->
            <div class="space-y-3 sm:space-y-0">
              <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Événement se termine le *</label>
              <div class="grid grid-cols-1 xs:grid-cols-2 gap-2 xs:gap-3">
                <div>
                  <input v-model="newEvent.date_fin" type="date" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-xs sm:text-sm">
                </div>
                <div>
                  <input v-model="newEvent.heure_fin" type="time" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-xs sm:text-sm">
                </div>
              </div>
            </div>
          </div>

          <!-- Étape 2: Lieu et localisation -->
          <div v-if="currentStep === 2" class="space-y-4 sm:space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-6">
              <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Nom du lieu *</label>
                <input v-model="newEvent.lieu" type="text" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-sm" placeholder="Ex: Cyber Café">
              </div>
              <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Adresse *</label>
                <input v-model="newEvent.adresse" type="text" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-sm" placeholder="Ex: Rue 123">
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-6">
              <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Ville *</label>
                <input v-model="newEvent.ville" type="text" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-sm" placeholder="Dakar">
              </div>
              <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Pays *</label>
                <input v-model="newEvent.pays" type="text" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-sm" placeholder="Sénégal">
              </div>
            </div>
          </div>

          <!-- Étape 3: Tarifs et capacité -->
          <div v-if="currentStep === 3" class="space-y-4 sm:space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-6">
              <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Prix (FCFA) *</label>
                <input v-model="newEvent.prix_base" type="number" step="0.01" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-sm" placeholder="5000">
              </div>
              <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Capacité max *</label>
                <input v-model="newEvent.capacite_max" type="number" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-sm" placeholder="50">
              </div>
            </div>

            <div class="flex items-center gap-2">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="newEvent.gratuit" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">Tournoi gratuit</span>
              </label>
            </div>

            <!-- Services gaming -->
            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 sm:mb-3">Services</label>

              <div class="flex flex-col xs:flex-row gap-2 mb-3">
                <input v-model="newServiceName" type="text" placeholder="Ajouter un service" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-[#1a1f2e]" />
                <button type="button" @click="addCustomService" class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg text-xs sm:text-sm font-medium">Ajouter</button>
              </div>

              <div v-if="customServices.length" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <label v-for="(s, idx) in customServices" :key="idx" class="flex items-center justify-between p-2 sm:p-3 bg-white dark:bg-[#0f1724] border border-gray-200 dark:border-gray-700 rounded-lg text-xs sm:text-sm">
                  <div class="flex items-center gap-2">
                    <input type="checkbox" v-model="s.active" class="w-4 h-4 text-blue-600 border-gray-300 rounded" />
                    <span class="text-gray-700 dark:text-gray-300 break-words">{{ s.name }}</span>
                  </div>
                  <button type="button" @click="removeCustomService(idx)" class="text-red-500 hover:text-red-600 ml-2 flex-shrink-0">×</button>
                </label>
              </div>

              <p v-else class="text-xs text-gray-500">Aucun service</p>
            </div>
          </div>

          <!-- Étape 4: Médias et contact -->
          <div v-if="currentStep === 4" class="space-y-4 sm:space-y-6">
            <!-- Image de bannière -->
            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 sm:mb-3">Image de bannière</label>
              <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 sm:p-6 text-center hover:border-gray-400 transition-colors">
                <div v-if="!newEvent.banniere_url" class="space-y-2 sm:space-y-4">
                  <i class="fas fa-image text-gray-400 text-3xl"></i>
                  <div>
                    <label for="banniere-upload" class="cursor-pointer inline-block bg-blue-50 text-blue-600 px-3 sm:px-4 py-2 rounded-lg hover:bg-blue-100 text-xs sm:text-sm font-medium transition-colors">
                      Choisir une image
                    </label>
                      <input id="banniere-upload" type="file" @change="handleBanniereUpload" accept="image/png, image/jpeg" class="hidden">
                    </div>
                    <p class="text-xs text-gray-500">Formats autorisés : PNG, JPG · Taille max 10MB</p>
                    <div v-if="uploadAlert.message" role="alert" class="rounded-lg border px-3 py-2 text-sm mt-2 w-full transition-colors"
                         :class="uploadAlert.type === 'error' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-green-50 border-green-200 text-green-700'">
                      {{ uploadAlert.message }}
                    </div>
                </div>
                <div v-else class="relative">
                  <img :src="newEvent.banniere_url" alt="Bannière" class="w-full h-32 sm:h-48 object-cover rounded-lg">
                  <button @click="newEvent.banniere_url = null; newEvent.banniere_file = null" class="absolute top-2 right-2 bg-red-500 text-white p-1.5 rounded-full hover:bg-red-600">
                    <i class="fas fa-times text-sm"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Galerie d'images -->
            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 sm:mb-3">Galerie</label>
              <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 sm:gap-4">
                <div v-for="(url, index) in newEvent.galerie_urls" :key="index" class="aspect-square">
                  <div v-if="url" class="relative w-full h-full">
                    <img :src="url" :alt="`Image ${index + 1}`" class="w-full h-full object-cover rounded-lg">
                    <button @click="newEvent.galerie_urls[index] = null; newEvent.galerie_files[index] = null" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full hover:bg-red-600">
                      <i class="fas fa-times text-xs"></i>
                    </button>
                  </div>
                  <div v-else class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg h-full flex flex-col items-center justify-center hover:border-gray-400 transition-colors">
                    <label :for="`galerie-${index}`" class="cursor-pointer text-center w-full h-full flex flex-col items-center justify-center">
                      <i class="fas fa-image text-gray-400 text-lg mb-1"></i>
                      <span class="text-xs text-gray-500 px-1">Ajouter</span>
                    </label>
                    <input :id="`galerie-${index}`" type="file" @change="handleGalerieUpload(index, $event)" accept="image/png, image/jpeg" class="hidden">
                  </div>
                </div>
              </div>
            </div>

            <!-- Contact -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-6">
              <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Téléphone</label>
                <input v-model="newEvent.telephone" type="tel" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-sm" placeholder="+221 77 12 34 56">
              </div>
              <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Email *</label>
                <input v-model="newEvent.email" type="email" required class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-sm" :class="{'border-red-500': errors.contact_email}">
                <p v-if="errors.contact_email" class="mt-0.5 text-xs text-red-600">{{ errors.contact_email }}</p>
              </div>
            </div>

            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Site web</label>
              <input v-model="newEvent.site_web" type="url" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e] text-sm" placeholder="https://evenement.com">
            </div>
          </div>

          <!-- Navigation -->
          <div class="flex flex-col xs:flex-row gap-2 xs:gap-3 mt-6 sm:mt-8">
            <button
              @click="prevStep"
              :disabled="currentStep === 1"
              class="flex items-center justify-center gap-1 xs:gap-2 px-3 sm:px-6 py-2 sm:py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-xs sm:text-sm font-medium order-2 xs:order-1 flex-1 xs:flex-none"
            >
              <i class="fas fa-chevron-left"></i>
              <span class="hidden sm:inline">Précédent</span>
              <span class="sm:hidden">Préc.</span>
            </button>

            <button
              @click="cancel"
              class="px-3 sm:px-6 py-2 sm:py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-xs sm:text-sm font-medium order-3 flex-1 xs:flex-none"
            >
              Annuler
            </button>

            <button
              v-if="currentStep < totalSteps"
              @click="nextStep"
              :disabled="!stepValidation"
              class="flex items-center justify-center gap-1 xs:gap-2 bg-blue-600 text-white px-3 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-xs sm:text-sm font-medium order-1 xs:order-3 flex-1 xs:flex-none"
            >
              <span class="hidden sm:inline">Suivant</span>
              <span class="sm:hidden">Suiv.</span>
              <i class="fas fa-chevron-right"></i>
            </button>

            <button
              v-if="currentStep === totalSteps"
              @click="createEvent"
              class="bg-green-600 text-white px-3 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-green-700 transition-colors text-xs sm:text-sm font-medium order-1 xs:order-3 flex-1 xs:flex-none"
            >
              Créer
            </button>
          </div>
        </div>
      </div>
    </main>
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
