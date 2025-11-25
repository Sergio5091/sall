<script setup>
import { ref,watch } from 'vue';

import { Head, Link, useForm } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const props = defineProps({
    salle: {
        type: Object,
        default: () => ({ nom: '', adresse: '', ville: '', pays: '' })
    },
    categories: {
        type: Object,
        default: () => ({})
    },
    types: {
        type: Object,
        default: () => ({})
    },
    statuts: {
        type: Object,
        default: () => ({})
    },
    visibilites: {
        type: Object,
        default: () => ({})
    },
    devises: {
        type: Object,
        default: () => ({})
    },
    publics_cibles: {
        type: Object,
        default: () => ({})
    }
});

const formData = useForm({
    // Informations de base
    titre: '',
    description: '',
    
    // Dates
    date_debut: '',
    date_fin: '',
    date_limite_inscription: '',
    
    // Tarifs
    prix_base: 0,
    prix_vip: null,
    prix_groupe: null,
    devise: 'XOF',
    gratuit: false,
    
    // Capacité
    capacite_max: null,
    limite_inscription: false,
    
    // Catégorie et type
    categorie: 'tournoi',
    type: 'offline',
    tags: [],
    
    // Public cible
    public_cible: 'tous',
    age_minimum: null,
    
    // Programme et activités
    programme: [],
    activites: [],
    
    // Contact
    contact_email: '',
    contact_telephone: '',
    contact_whatsapp: '',
    reseaux_sociaux: {
        facebook: '',
        instagram: '',
        twitter: '',
        youtube: '',
        twitch: ''
    },
    
    // Configuration
    inscription_obligatoire: true,
    paiement_en_ligne: false,
    certificat_participation: false,
    streaming: false,
    url_streaming: '',
    
    // Visibilité
    visibilite: 'public',
    
    // SEO
    meta_titre: '',
    meta_description: '',
    mots_cles: [],
    
    // Images
    image_affiche: null,
    image_banniere: null,
});

const tagInput = ref('');
const programmeItem = ref({ titre: '', heure: '', description: '' });
const activiteItem = ref({ nom: '', description: '', duree: '' });
const motCleInput = ref('');

const submitForm = () => {
    // Validation basique
    if (!formData.titre || !formData.description) {
        alert('Veuillez remplir le titre et la description');
        return;
    }

    if (!formData.date_debut || !formData.date_fin) {
        alert('Veuillez définir les dates de début et de fin');
        return;
    }

    // Convertir les dates au format ISO
    const data = new FormData();
    
    // Ajouter tous les champs du formulaire
    Object.keys(formData).forEach(key => {
        if (key === 'image_affiche' || key === 'image_banniere') {
            if (formData[key]) {
                data.append(key, formData[key]);
            }
        } else if (typeof formData[key] === 'object' && formData[key] !== null) {
            data.append(key, JSON.stringify(formData[key]));
        } else {
            data.append(key, formData[key]);
        }
    });

    formData.post('/promoter/events', {
        forceFormData: true,
        onSuccess: () => {
            // Redirection automatique gérée par Inertia
        },
        onError: (errors) => {
            console.error('Erreurs de validation:', errors);
            alert('Erreur lors de la création de l\'événement. Veuillez vérifier les champs.');
        }
    });
};

const addTag = () => {
    if (tagInput.value.trim() && !formData.tags.includes(tagInput.value.trim())) {
        formData.tags.push(tagInput.value.trim());
        tagInput.value = '';
    }
};

const removeTag = (index) => {
    formData.tags.splice(index, 1);
};

const addProgrammeItem = () => {
    if (programmeItem.value.titre && programmeItem.value.heure) {
        formData.programme.push({...programmeItem.value});
        programmeItem.value = { titre: '', heure: '', description: '' };
    }
};

const removeProgrammeItem = (index) => {
    formData.programme.splice(index, 1);
};

const addActivite = () => {
    if (activiteItem.value.nom) {
        formData.activites.push({...activiteItem.value});
        activiteItem.value = { nom: '', description: '', duree: '' };
    }
};

const removeActivite = (index) => {
    formData.activites.splice(index, 1);
};

const addMotCle = () => {
    if (motCleInput.value.trim() && !formData.mots_cles.includes(motCleInput.value.trim())) {
        formData.mots_cles.push(motCleInput.value.trim());
        motCleInput.value = '';
    }
};

const removeMotCle = (index) => {
    formData.mots_cles.splice(index, 1);
};

const handleImageUpload = (event, type) => {
    const file = event.target.files[0];
    if (file) {
        formData[type] = file;
    }
};

// Auto-generate SEO fields
const updateSeoFields = () => {
    if (!formData.meta_titre && formData.titre) {
        formData.meta_titre = formData.titre;
    }
    if (!formData.meta_description && formData.description) {
        formData.meta_description = formData.description.substring(0, 160);
    }
};

// Watch for changes in title and description
const unwatchTitle = ref(null);
const unwatchDescription = ref(null);

unwatchTitle.value = watch(() => formData.titre, updateSeoFields);
unwatchDescription.value = watch(() => formData.description, updateSeoFields);
</script>

<template>
  <Head title="Créer un événement" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <!-- Sidebar Component -->
    <Sidebar current-route="promoter.events.create" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto transition-all duration-300">
      <div class="p-8">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
          <div>
            <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Créer un événement</p>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Organisez un événement dans votre salle : {{ props.salle?.nom || 'Votre salle' }}</p>
          </div>
          <Link href="/promoter/events" class="flex items-center gap-2 px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Retour aux événements</span>
          </Link>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submitForm" class="space-y-8">
          <!-- Informations de base -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">
              <i class="fas fa-info-circle mr-2"></i>Informations de base
            </h2>
            
            <div class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Titre de l'événement *</label>
                <input 
                  v-model="formData.titre" 
                  type="text" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" 
                  placeholder="Tournoi de FIFA 23 - CyberZone Arena"
                >
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                <textarea 
                  v-model="formData.description" 
                  rows="4" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" 
                  placeholder="Décrivez votre événement, les règles, les prix, les participants attendus..."
                ></textarea>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catégorie *</label>
                  <select v-model="formData.categorie" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                    <option v-for="(nom, code) in props.categories" :key="code" :value="code">{{ nom }}</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type *</label>
                  <select v-model="formData.type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                    <option v-for="(nom, code) in props.types" :key="code" :value="code">{{ nom }}</option>
                  </select>
                </div>
              </div>

              <!-- Tags -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags</label>
                <div class="flex flex-wrap gap-2 mb-2">
                  <span v-for="(tag, index) in formData.tags" :key="index" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm">
                    {{ tag }}
                    <button type="button" @click="removeTag(index)" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">
                      <i class="fas fa-times"></i>
                    </button>
                  </span>
                </div>
                <div class="flex gap-2">
                  <input 
                    v-model="tagInput" 
                    @keyup.enter="addTag"
                    type="text" 
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" 
                    placeholder="Ajouter un tag (ex: gaming, tournoi, FIFA)"
                  >
                  <button type="button" @click="addTag" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </section>

          <!-- Dates et horaires -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">
              <i class="fas fa-calendar-alt mr-2"></i>Dates et horaires
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date et heure de début *</label>
                <input 
                  v-model="formData.date_debut" 
                  type="datetime-local" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date et heure de fin *</label>
                <input 
                  v-model="formData.date_fin" 
                  type="datetime-local" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date limite d'inscription</label>
                <input 
                  v-model="formData.date_limite_inscription" 
                  type="date" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                >
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Laissez vide pour ne pas limiter les inscriptions</p>
              </div>
            </div>
          </section>

          <!-- Tarifs -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">
              <i class="fas fa-tag mr-2"></i>Tarifs
            </h2>
            
            <div class="space-y-6">
              <div class="flex items-center gap-3">
                <input 
                  v-model="formData.gratuit" 
                  type="checkbox" 
                  id="gratuit"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <label for="gratuit" class="text-sm font-medium text-gray-700 dark:text-gray-300">Événement gratuit</label>
              </div>

              <div v-if="!formData.gratuit" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prix de base *</label>
                  <input 
                    v-model="formData.prix_base" 
                    type="number" 
                    step="0.01"
                    min="0"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" 
                    placeholder="5000"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prix VIP</label>
                  <input 
                    v-model="formData.prix_vip" 
                    type="number" 
                    step="0.01"
                    min="0"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" 
                    placeholder="10000"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prix groupe</label>
                  <input 
                    v-model="formData.prix_groupe" 
                    type="number" 
                    step="0.01"
                    min="0"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" 
                    placeholder="4000"
                  >
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Devise *</label>
                  <select v-model="formData.devise" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                    <option v-for="(nom, code) in props.devises" :key="code" :value="code">{{ nom }}</option>
                  </select>
                </div>
              </div>
            </div>
          </section>

          <!-- Capacité -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">
              <i class="fas fa-users mr-2"></i>Capacité
            </h2>
            
            <div class="space-y-6">
              <div class="flex items-center gap-3">
                <input 
                  v-model="formData.limite_inscription" 
                  type="checkbox" 
                  id="limite_inscription"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <label for="limite_inscription" class="text-sm font-medium text-gray-700 dark:text-gray-300">Limiter le nombre de participants</label>
              </div>

              <div v-if="formData.limite_inscription">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Capacité maximale *</label>
                <input 
                  v-model="formData.capacite_max" 
                  type="number" 
                  min="1"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" 
                  placeholder="50"
                >
              </div>
            </div>
          </section>

          <!-- Images -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">
              <i class="fas fa-images mr-2"></i>Images
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Affiche de l'événement</label>
                <input 
                  type="file" 
                  @change="handleImageUpload($event, 'image_affiche')"
                  accept="image/*"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                >
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format recommandé: 1080x1350px, max 2MB</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bannière</label>
                <input 
                  type="file" 
                  @change="handleImageUpload($event, 'image_banniere')"
                  accept="image/*"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                >
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format recommandé: 1920x600px, max 4MB</p>
              </div>
            </div>
          </section>

          <!-- Configuration -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">
              <i class="fas fa-cog mr-2"></i>Configuration
            </h2>
            
            <div class="space-y-4">
              <div class="flex items-center gap-3">
                <input 
                  v-model="formData.inscription_obligatoire" 
                  type="checkbox" 
                  id="inscription_obligatoire"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <label for="inscription_obligatoire" class="text-sm font-medium text-gray-700 dark:text-gray-300">Inscription obligatoire</label>
              </div>

              <div class="flex items-center gap-3">
                <input 
                  v-model="formData.paiement_en_ligne" 
                  type="checkbox" 
                  id="paiement_en_ligne"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <label for="paiement_en_ligne" class="text-sm font-medium text-gray-700 dark:text-gray-300">Paiement en ligne</label>
              </div>

              <div class="flex items-center gap-3">
                <input 
                  v-model="formData.certificat_participation" 
                  type="checkbox" 
                  id="certificat_participation"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <label for="certificat_participation" class="text-sm font-medium text-gray-700 dark:text-gray-300">Certificat de participation</label>
              </div>

              <div class="flex items-center gap-3">
                <input 
                  v-model="formData.streaming" 
                  type="checkbox" 
                  id="streaming"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <label for="streaming" class="text-sm font-medium text-gray-700 dark:text-gray-300">Streaming en ligne</label>
              </div>

              <div v-if="formData.streaming">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">URL du streaming</label>
                <input 
                  v-model="formData.url_streaming" 
                  type="url" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" 
                  placeholder="https://twitch.tv/votre_channel"
                >
              </div>
            </div>
          </section>

          <!-- Boutons d'action -->
          <div class="flex justify-end gap-4">
            <Link href="/promoter/events" class="px-6 py-3 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
              Annuler
            </Link>
            <button 
              type="submit" 
              :disabled="formData.processing"
              class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i v-if="formData.processing" class="fas fa-spinner fa-spin mr-2"></i>
              <i v-else class="fas fa-save mr-2"></i>
              Créer l'événement
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>
