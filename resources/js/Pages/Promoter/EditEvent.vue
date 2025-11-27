<script setup>
import { ref, watch } from 'vue';

import { Head, Link, useForm } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const props = defineProps({
    event: {
        type: Object,
        required: true
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

// Initialiser le formulaire avec les données de l'événement
const formData = useForm({
    // Informations de base
    titre: props.event.titre || '',
    description: props.event.description || '',
    
    // Dates
    date_debut: props.event.date_debut ? new Date(props.event.date_debut).toISOString().slice(0, 16) : '',
    date_fin: props.event.date_fin ? new Date(props.event.date_fin).toISOString().slice(0, 16) : '',
    date_limite_inscription: props.event.date_limite_inscription ? new Date(props.event.date_limite_inscription).toISOString().slice(0, 10) : '',
    
    // Tarifs
    prix_base: props.event.prix_base || 0,
    prix_vip: props.event.prix_vip || null,
    prix_groupe: props.event.prix_groupe || null,
    devise: props.event.devise || 'XOF',
    gratuit: props.event.gratuit === true,
    
    // Capacité
    capacite_max: props.event.capacite_max || null,
    limite_inscription: props.event.limite_inscription === true,
    
    // Catégorie et type
    categorie: props.event.categorie || 'tournoi',
    type: props.event.type || 'offline',
    tags: Array.isArray(props.event.tags) ? props.event.tags : [],
    
    // Public cible
    public_cible: props.event.public_cible || 'tous',
    age_minimum: props.event.age_minimum || null,
    
    // Programme et activités
    programme: Array.isArray(props.event.programme) ? props.event.programme : [],
    activites: Array.isArray(props.event.activites) ? props.event.activites : [],
    
    // Contact
    contact_email: props.event.contact_email || '',
    contact_telephone: props.event.contact_telephone || '',
    contact_whatsapp: props.event.contact_whatsapp || '',
    reseaux_sociaux: typeof props.event.reseaux_sociaux === 'object' ? props.event.reseaux_sociaux : {
        facebook: '',
        instagram: '',
        twitter: '',
        youtube: '',
        twitch: ''
    },
    
    // Configuration
    inscription_obligatoire: props.event.inscription_obligatoire !== false,
    paiement_en_ligne: props.event.paiement_en_ligne === true,
    certificat_participation: props.event.certificat_participation === true,
    streaming: props.event.streaming === true,
    url_streaming: props.event.url_streaming || '',
    
    // Visibilité
    visibilite: props.event.visibilite || 'public',
    
    // SEO
    meta_titre: props.event.meta_titre || '',
    meta_description: props.event.meta_description || '',
    mots_cles: Array.isArray(props.event.mots_cles) ? props.event.mots_cles : [],
    
    // Images
    image_affiche: null,
    image_banniere: null,
    
    // Statut
    statut: props.event.statut || 'brouillon',
});

const tagInput = ref('');
const programmeItem = ref({ titre: '', heure: '', description: '' });
const activiteItem = ref({ nom: '', description: '', duree: '' });
const motCleInput = ref('');

const errors = ref({});

const validateForm = () => {
    const newErrors = {};
    
    // Validation titre (toujours requis)
    if (!formData.titre.trim()) {
        newErrors.titre = 'Le titre est obligatoire';
    } else if (formData.titre.length < 3) {
        newErrors.titre = 'Le titre doit contenir au moins 3 caractères';
    }
    
    // Validation description (toujours requise)
    if (!formData.description.trim()) {
        newErrors.description = 'La description est obligatoire';
    } else if (formData.description.length < 10) {
        newErrors.description = 'La description doit contenir au moins 10 caractères';
    }
    
    // Validation dates (toujours requises)
    if (!formData.date_debut) {
        newErrors.date_debut = 'La date de début est obligatoire';
    }
    
    if (!formData.date_fin) {
        newErrors.date_fin = 'La date de fin est obligatoire';
    } else if (formData.date_debut && formData.date_fin && new Date(formData.date_fin) <= new Date(formData.date_debut)) {
        newErrors.date_fin = 'La date de fin doit être après la date de début';
    }
    
    // Validation date limite d'inscription (optionnelle)
    if (formData.date_limite_inscription && formData.date_debut) {
        const dateLimite = new Date(formData.date_limite_inscription);
        const dateDebut = new Date(formData.date_debut);
        if (dateLimite > dateDebut) {
            newErrors.date_limite_inscription = 'La date limite d\'inscription doit être avant ou égale à la date de début';
        }
    }
    
    // Validation prix (seulement si non gratuit)
    if (!formData.gratuit && (!formData.prix_base || formData.prix_base < 0)) {
        newErrors.prix_base = 'Le prix de base est obligatoire et doit être positif';
    }
    
    // Validation devise (toujours requise)
    if (!formData.devise) {
        newErrors.devise = 'La devise est obligatoire';
    }
    
    // Validation catégorie et type (toujours requises)
    if (!formData.categorie) {
        newErrors.categorie = 'La catégorie est obligatoire';
    }
    
    if (!formData.type) {
        newErrors.type = 'Le type est obligatoire';
    }
    
    // Validation visibilité (toujours requise)
    if (!formData.visibilite) {
        newErrors.visibilite = 'La visibilité est obligatoire';
    }
    
    // Validation statut (toujours requise)
    if (!formData.statut) {
        newErrors.statut = 'Le statut est obligatoire';
    }
    
    errors.value = newErrors;
    
    // Afficher les erreurs dans la console pour débogage
    if (Object.keys(newErrors).length > 0) {
        console.log('Erreurs de validation client:', newErrors);
        const errorMessages = Object.values(newErrors);
        const errorMessage = errorMessages.join(', ');
        alert(`Veuillez corriger les erreurs suivantes:\n\n${errorMessage}`);
        
        // Scroller vers le premier champ en erreur
        setTimeout(() => {
            const firstErrorField = document.querySelector('.border-red-500');
            if (firstErrorField) {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstErrorField.focus();
            }
        }, 100);
    }
    
    return Object.keys(newErrors).length === 0;
};

const submitForm = () => {
    // Validation côté client
    if (!validateForm()) {
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

    formData.put(`/promoter/events/${props.event.id}`, {
        forceFormData: true,
        onSuccess: () => {
            // Redirection automatique gérée par Inertia
        },
        onError: (serverErrors) => {
            console.error('Erreurs de validation:', serverErrors);
            // Convertir les erreurs serveur en format client
            const formattedErrors = {};
            Object.keys(serverErrors).forEach(key => {
                formattedErrors[key] = Array.isArray(serverErrors[key]) ? serverErrors[key][0] : serverErrors[key];
            });
            errors.value = formattedErrors;
            
            // Afficher un message d'erreur détaillé
            const errorCount = Object.keys(formattedErrors).length;
            const errorMessages = Object.values(formattedErrors);
            const errorMessage = errorMessages.join(', ');
            
            console.log('Détail des erreurs:', formattedErrors);
            alert(`Erreur lors de la modification de l'événement. ${errorCount} champ(s) à vérifier:\n\n${errorMessage}`);
            
            // Scroller vers le premier champ en erreur
            const firstErrorField = document.querySelector('.border-red-500');
            if (firstErrorField) {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstErrorField.focus();
            }
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
    if (programmeItem.value.titre.trim()) {
        formData.programme.push({ ...programmeItem.value });
        programmeItem.value = { titre: '', heure: '', description: '' };
    }
};

const removeProgrammeItem = (index) => {
    formData.programme.splice(index, 1);
};

const addActivite = () => {
    if (activiteItem.value.nom.trim()) {
        formData.activites.push({ ...activiteItem.value });
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

watch(() => formData.titre, updateSeoFields);
watch(() => formData.description, updateSeoFields);
</script>

<template>
  <Head title="Modifier l'événement" />
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <!-- Sidebar Component -->
    <Sidebar current-route="promoter.events.edit" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto transition-all duration-300">
      <div class="p-8">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
          <div>
            <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Modifier l'événement</p>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Modifiez les informations de votre événement : {{ event.titre }}</p>
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
                  :class="{ 'border-red-500 dark:border-red-500': errors.titre }"
                  placeholder="Tournoi de FIFA 23 - CyberZone Arena"
                >
                <p v-if="errors.titre" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.titre }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                <textarea 
                  v-model="formData.description" 
                  rows="4" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                  :class="{ 'border-red-500 dark:border-red-500': errors.description }"
                  placeholder="Décrivez votre événement, les règles, les prix, les participants attendus..."
                ></textarea>
                <p v-if="errors.description" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.description }}</p>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catégorie *</label>
                  <select 
                    v-model="formData.categorie" 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                    :class="{ 'border-red-500 dark:border-red-500': errors.categorie }"
                  >
                    <option v-for="(nom, code) in categories" :key="code" :value="code">{{ nom }}</option>
                  </select>
                  <p v-if="errors.categorie" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.categorie }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type *</label>
                  <select 
                    v-model="formData.type" 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                    :class="{ 'border-red-500 dark:border-red-500': errors.type }"
                  >
                    <option v-for="(nom, code) in types" :key="code" :value="code">{{ nom }}</option>
                  </select>
                  <p v-if="errors.type" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.type }}</p>
                </div>
              </div>

              <!-- Tags -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags</label>
                <div class="flex flex-wrap gap-2 mb-2">
                  <span v-for="(tag, index) in formData.tags" :key="index" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm">
                    {{ tag }}
                    <button type="button" @click="removeTag(index)" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">
                      <i class="fas fa-times text-xs"></i>
                    </button>
                  </span>
                </div>
                <div class="flex gap-2">
                  <input 
                    v-model="tagInput" 
                    @keyup.enter="addTag"
                    type="text" 
                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" 
                    placeholder="Ajouter un tag et presser Entrée"
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
                  :class="{ 'border-red-500 dark:border-red-500': errors.date_debut }"
                >
                <p v-if="errors.date_debut" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.date_debut }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date et heure de fin *</label>
                <input 
                  v-model="formData.date_fin" 
                  type="datetime-local" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                  :class="{ 'border-red-500 dark:border-red-500': errors.date_fin }"
                >
                <p v-if="errors.date_fin" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.date_fin }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date limite d'inscription</label>
                <input 
                  v-model="formData.date_limite_inscription" 
                  type="date" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                  :class="{ 'border-red-500 dark:border-red-500': errors.date_limite_inscription }"
                >
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Laissez vide pour ne pas limiter les inscriptions</p>
                <p v-if="errors.date_limite_inscription" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.date_limite_inscription }}</p>
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
                    :class="{ 'border-red-500 dark:border-red-500': errors.prix_base }"
                    placeholder="5000"
                  >
                  <p v-if="errors.prix_base" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.prix_base }}</p>
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
                    placeholder="7500"
                  >
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Devise *</label>
                <select 
                  v-model="formData.devise" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                  :class="{ 'border-red-500 dark:border-red-500': errors.devise }"
                >
                  <option v-for="(nom, code) in devises" :key="code" :value="code">{{ nom }}</option>
                </select>
                <p v-if="errors.devise" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.devise }}</p>
              </div>
            </div>
          </section>

          <!-- Statut et visibilité -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">
              <i class="fas fa-eye mr-2"></i>Statut et visibilité
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Statut *</label>
                <select 
                  v-model="formData.statut" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                  :class="{ 'border-red-500 dark:border-red-500': errors.statut }"
                >
                  <option v-for="(nom, code) in statuts" :key="code" :value="code">{{ nom }}</option>
                </select>
                <p v-if="errors.statut" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.statut }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Visibilité *</label>
                <select 
                  v-model="formData.visibilite" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                  :class="{ 'border-red-500 dark:border-red-500': errors.visibilite }"
                >
                  <option v-for="(nom, code) in visibilites" :key="code" :value="code">{{ nom }}</option>
                </select>
                <p v-if="errors.visibilite" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.visibilite }}</p>
              </div>
            </div>
          </section>

          <!-- Images -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">
              <i class="fas fa-image mr-2"></i>Images
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Image d'affiche</label>
                <input 
                  type="file" 
                  @change="handleImageUpload($event, 'image_affiche')"
                  accept="image/*"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                >
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Formats: JPEG, PNG, JPG, GIF (Max: 2MB)</p>
                <div v-if="event.image_affiche_url" class="mt-2">
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Image actuelle:</p>
                  <img :src="event.image_affiche_url" alt="Image actuelle" class="w-32 h-32 object-cover rounded">
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bannière</label>
                <input 
                  type="file" 
                  @change="handleImageUpload($event, 'image_banniere')"
                  accept="image/*"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                >
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Formats: JPEG, PNG, JPG, GIF (Max: 4MB)</p>
                <div v-if="event.image_banniere_url" class="mt-2">
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Bannière actuelle:</p>
                  <img :src="event.image_banniere_url" alt="Bannière actuelle" class="w-full h-20 object-cover rounded">
                </div>
              </div>
            </div>
          </section>

          <!-- Actions -->
          <div class="flex justify-end gap-4">
            <Link href="/promoter/events" class="px-6 py-3 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
              Annuler
            </Link>
            <button 
              type="submit" 
              :disabled="formData.processing"
              class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <i v-if="formData.processing" class="fas fa-spinner fa-spin mr-2"></i>
              {{ formData.processing ? 'Modification en cours...' : 'Modifier l\'événement' }}
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>
