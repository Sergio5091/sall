<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';

// État du formulaire
const currentStep = ref(1);
const totalSteps = 4;
const errors = ref({});

// Données du nouvel événement
const newEvent = ref({
    // Étape 1: Informations de base
    titre: '',
    description: '',
    type: 'tournament',
    categorie: 'jeux_video',
    date_debut: '',
    date_fin: '',
    
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
                   newEvent.value.date_fin;
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
const handleBanniereUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        newEvent.value.banniere_file = file;
        // Créer un aperçu
        const reader = new FileReader();
        reader.onload = (e) => {
            newEvent.value.banniere_url = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleGalerieUpload = (index, event) => {
    const file = event.target.files[0];
    if (file) {
        newEvent.value.galerie_files[index] = file;
        // Créer un aperçu
        const reader = new FileReader();
        reader.onload = (e) => {
            newEvent.value.galerie_urls[index] = e.target.result;
        };
        reader.readAsDataURL(file);
    }
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
        date_debut: newEvent.value.date_debut,
        date_fin: newEvent.value.date_fin,
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
    console.log('=== DONNÉES ENVOYÉES DU FRONTEND ===');
    console.log('eventData:', eventData);
    console.log('newEvent.value:', newEvent.value);
    
    // (debug) summary available in console
    
    Object.keys(eventData).forEach(key => {
        if (key !== 'services' && typeof eventData[key] !== 'object') {
            formData.append(key, eventData[key]);
            console.log(`${key}:`, eventData[key]);
        }
    });
    
    formData.append('services', JSON.stringify(eventData.services));
    console.log('services (JSON):', JSON.stringify(eventData.services));
    
    // Ajouter les fichiers
    if (newEvent.value.banniere_file) {
        formData.append('image_banniere', newEvent.value.banniere_file);
        console.log('image_banniere:', newEvent.value.banniere_file);
    }
    
    newEvent.value.galerie_files.forEach((file, index) => {
        if (file) {
            formData.append(`galerie_files[${index}]`, file);
            console.log(`galerie_files[${index}]:`, file);
        }
    });

    // Afficher le FormData complet
    console.log('=== FORMDATA COMPLET ===');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}:`, value);
    }

    // Envoyer la requête
    router.post('/promoter/events', formData, {
        onSuccess: () => {
            router.visit('/promoter/events');
        },
        onError: (errors) => {
            console.error('Erreurs de validation:', errors);
            errors.value = errors;
            
            // Afficher un message d'erreur plus clair
            if (errors.contact_email) {
                alert('L\'adresse email est obligatoire et doit être valide.');
            } else {
                alert('Erreur lors de la création de l\'événement. Veuillez vérifier tous les champs.');
            }
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
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <!-- Sidebar Component -->
    <Sidebar current-route="promoter.events" />
    
    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto transition-all duration-300">
      <div class="p-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Créer un nouvel événement</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Remplissez les informations pour créer votre événement</p>
          </div>
          <button @click="cancel" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <!-- Progression -->
        <div class="bg-white dark:bg-[#19202e] rounded-2xl shadow-xl p-8 mb-6">
          <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
              <div v-for="step in totalSteps" :key="step" class="flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium transition-colors"
                     :class="step <= currentStep ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'">
                  <i v-if="step < currentStep" class="fas fa-check"></i>
                  <span v-else>{{ step }}</span>
                </div>
                <div v-if="step < totalSteps" class="w-16 h-1 mx-2 transition-colors"
                     :class="step < currentStep ? 'bg-blue-600' : 'bg-gray-200'">
                </div>
              </div>
            </div>
            <div class="flex justify-between text-xs text-gray-600">
              <span>Informations</span>
              <span>Lieu</span>
              <span>Tarifs</span>
              <span>Médias</span>
            </div>
          </div>

          <!-- Étape 1: Informations de base -->
          <div v-if="currentStep === 1" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nom de l'événement *</label>
                <input v-model="newEvent.titre" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="Ex: Tournoi FIFA 2025">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type de tournoi *</label>
                <select v-model="newEvent.type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]">
                  <option value="tournament">Tournoi</option>
                  <option value="lan_party">LAN Party</option>
                  <option value="showmatch">Showmatch</option>
                  <option value="casual_gaming">Gaming Casual</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
              <textarea v-model="newEvent.description" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="Décrivez votre événement..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date de début *</label>
                <input v-model="newEvent.date_debut" type="datetime-local" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date de fin *</label>
                <input v-model="newEvent.date_fin" type="datetime-local" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]">
              </div>
            </div>
          </div>

          <!-- Étape 2: Lieu et localisation -->
          <div v-if="currentStep === 2" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nom du lieu *</label>
                <input v-model="newEvent.lieu" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="Ex: Cyber Café Dakar">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adresse *</label>
                <input v-model="newEvent.adresse" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="Ex: Rue 123, Plateau">
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ville *</label>
                <input v-model="newEvent.ville" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="Dakar">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pays *</label>
                <input v-model="newEvent.pays" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="Sénégal">
              </div>
            </div>
          </div>

          <!-- Étape 3: Tarifs et capacité -->
          <div v-if="currentStep === 3" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prix de base (FCFA) *</label>
                <input v-model="newEvent.prix_base" type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="5000">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Capacité maximale *</label>
                <input v-model="newEvent.capacite_max" type="number" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="50">
              </div>
            </div>

            <div class="flex items-center space-x-4">
              <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" v-model="newEvent.gratuit" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="text-sm text-gray-700 dark:text-gray-300">Tournoi gratuit</span>
              </label>
            </div>

            <!-- Services gaming (personnalisables par le promoteur) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Services et équipements</label>

              <div class="flex items-center gap-2 mb-4">
                <input v-model="newServiceName" type="text" placeholder="Ajouter un service (ex: PC + écran)" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#1a1f2e]" />
                <button type="button" @click="addCustomService" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Ajouter</button>
              </div>

              <div v-if="customServices.length" class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <label v-for="(s, idx) in customServices" :key="idx" class="flex items-center justify-between p-2 bg-white dark:bg-[#0f1724] border border-gray-200 dark:border-gray-700 rounded-lg">
                  <div class="flex items-center gap-3">
                    <input type="checkbox" v-model="s.active" class="w-4 h-4 text-blue-600 border-gray-300 rounded" />
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ s.name }}</span>
                  </div>
                  <button type="button" @click="removeCustomService(idx)" class="text-red-500 hover:text-red-600">Supprimer</button>
                </label>
              </div>

              <p v-else class="text-sm text-gray-500">Aucun service ajouté — ajoutez les services/équipements fournis pour cet événement.</p>
            </div>
          </div>

          <!-- Étape 4: Médias et contact -->
          <div v-if="currentStep === 4" class="space-y-6">
            <!-- Image de bannière -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Image de bannière</label>
              <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                <div v-if="!newEvent.banniere_url" class="space-y-4">
                  <i class="fas fa-image text-gray-400 mx-auto"></i>
                  <div>
                    <label for="banniere-upload" class="cursor-pointer bg-blue-50 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-100 transition-colors">
                      Choisir une image
                    </label>
                    <input id="banniere-upload" type="file" @change="handleBanniereUpload" accept="image/*" class="hidden">
                  </div>
                  <p class="text-sm text-gray-500">PNG, JPG jusqu'à 10MB</p>
                </div>
                <div v-else class="relative">
                  <img :src="newEvent.banniere_url" alt="Bannière" class="w-full h-48 object-cover rounded-lg">
                  <button @click="newEvent.banniere_url = null; newEvent.banniere_file = null" class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Galerie d'images -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Galerie d'images</label>
              <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div v-for="(url, index) in newEvent.galerie_urls" :key="index" class="aspect-square">
                  <div v-if="url" class="relative w-full h-full">
                    <img :src="url" :alt="`Image ${index + 1}`" class="w-full h-full object-cover rounded-lg">
                    <button @click="newEvent.galerie_urls[index] = null; newEvent.galerie_files[index] = null" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full hover:bg-red-600">
                      <i class="fas fa-times text-xs"></i>
                    </button>
                  </div>
                  <div v-else class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-2 text-center hover:border-gray-400 transition-colors">
                    <label :for="`galerie-${index}`" class="cursor-pointer">
                      <i class="fas fa-image text-gray-400 mx-auto mb-1"></i>
                      <span class="text-xs text-gray-500">Ajouter</span>
                    </label>
                    <input :id="`galerie-${index}`" type="file" @change="handleGalerieUpload(index, $event)" accept="image/*" class="hidden">
                  </div>
                </div>
              </div>
            </div>

            <!-- Contact -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Téléphone</label>
                <input v-model="newEvent.telephone" type="tel" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="+221 77 123 45 67">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                <input v-model="newEvent.email" type="email" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="contact@evenement.com" :class="{'border-red-500': errors.contact_email}">
                <p v-if="errors.contact_email" class="mt-1 text-sm text-red-600">{{ errors.contact_email }}</p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site web</label>
                <input v-model="newEvent.site_web" type="url" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="https://evenement.com">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Facebook</label>
                <input v-model="newEvent.facebook" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="@evenement">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Instagram</label>
                <input v-model="newEvent.instagram" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="@evenement">
              </div>
            </div>
          </div>

          <!-- Navigation -->
          <div class="flex justify-between mt-8">
            <button
              @click="prevStep"
              :disabled="currentStep === 1"
              class="flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i class="fas fa-chevron-left"></i>
              Précédent
            </button>

            <div class="flex gap-4">
              <button
                @click="cancel"
                class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
              >
                Annuler
              </button>

              <button
                v-if="currentStep < totalSteps"
                @click="nextStep"
                :disabled="!stepValidation"
                class="flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Suivant
                <i class="fas fa-chevron-right"></i>
              </button>

              <button
                v-if="currentStep === totalSteps"
                @click="createEvent"
                class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors"
              >
                Créer l'événement
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
