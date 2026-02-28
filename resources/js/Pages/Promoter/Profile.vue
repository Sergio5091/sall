<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

import PromoterLayout from '@/Layouts/PromoterLayout.vue';
defineOptions({ layout: PromoterLayout });

const page = usePage();
const props = defineProps({
    user: Object,
    notifications: Array
});

// Messages de succès
const successMessage = ref(page.props.flash?.success || '');

// Form pour les informations personnelles
const profileForm = useForm({
    name: '',
    email: '',
    telephone: '',
});

// Form pour le changement de mot de passe
const passwordForm = useForm({
    current_password: '',
    new_password: '',
    password_confirmation: ''
});

// Form pour la photo de profil
const photoForm = useForm({
    photo: null
});

// Variables réactives
const showPasswordForm = ref(false);
const photoPreview = ref(null);

// Utiliser les données utilisateur partagées globalement
const currentUser = computed(() => page.props.auth?.user || props.user);

// Watcher pour mettre à jour le formulaire lorsque les données utilisateur changent
watch(currentUser, (newUser) => {
    if (newUser) {
        profileForm.name = newUser.name;
        profileForm.email = newUser.email;
        profileForm.telephone = newUser.telephone || '';
        photoPreview.value = newUser.profile_photo_url || null;
    }
}, { immediate: true });

// Soumettre le formulaire de profil
const updateProfile = () => {
    profileForm.put('/promoter/profile', {
        onSuccess: () => {
            successMessage.value = 'Profil mis à jour avec succès';
        }
    });
};

// Soumettre le changement de mot de passe
const updatePassword = () => {
    passwordForm.put('/promoter/password', {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            showPasswordForm.value = false;
            successMessage.value = 'Mot de passe mis à jour avec succès';
        }
    });
};

// Gérer l'upload de photo
const handlePhotoUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        photoForm.photo = file;
        
        photoForm.post('/promoter/profile/photo', {
            onSuccess: () => {
                successMessage.value = 'Photo de profil mise à jour';
                window.location.reload();
            }
        });
    }
};

// Supprimer la photo de profil
const removePhoto = () => {
    photoForm.delete('/promoter/profile/photo', {
        onSuccess: () => {
            successMessage.value = 'Photo de profil supprimée';
            photoPreview.value = null;
        }
    });
};

// Formater la date
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};
</script>

<template>
  <Head title="Profil - Promoteur" />
  
  <div class="flex h-screen bg-gray-50">
    
    <main class="flex-1 overflow-y-auto">
      <div class="p-8">
        <!-- Message de succès -->
        <div v-if="successMessage" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
          <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-2"></i>
            <p class="text-green-800">{{ successMessage }}</p>
            <button @click="successMessage = ''" class="ml-auto text-green-600 hover:text-green-800">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>

        <!-- En-tête -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Mon Profil</h1>
          <p class="text-gray-600 mt-2">Gérez vos informations personnelles et vos préférences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Colonne principale -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Photo de profil -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Photo de profil</h2>
              
              <div class="flex items-center space-x-6">
                <div class="relative">
                  <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                    <img v-if="photoPreview" :src="photoPreview" alt="Photo de profil" class="w-full h-full object-cover">
                    <i v-else class="fas fa-user text-gray-400 text-3xl"></i>
                  </div>
                  <label for="photo-upload" class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 cursor-pointer hover:bg-blue-700">
                    <i class="fas fa-camera text-xs"></i>
                  </label>
                  <input 
                    id="photo-upload"
                    type="file" 
                    class="hidden"
                    accept="image/*"
                    @change="handlePhotoUpload"
                  >
                </div>
                
                <div>
                  <p class="text-sm text-gray-600 mb-2">Cliquez sur l'icône pour changer votre photo</p>
                  <button 
                    v-if="photoPreview"
                    @click="removePhoto"
                    class="text-red-600 hover:text-red-700 text-sm"
                  >
                    <i class="fas fa-trash mr-1"></i> Supprimer la photo
                  </button>
                </div>
              </div>
            </div>

            <!-- Informations personnelles -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations personnelles</h2>
              
              <form @submit.prevent="updateProfile" class="space-y-4">
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                  <input 
                    id="name"
                    type="text" 
                    v-model="profileForm.name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                  >
                  <p v-if="profileForm.errors.name" class="mt-1 text-sm text-red-600">{{ profileForm.errors.name }}</p>
                </div>
                
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input 
                    id="email"
                    type="email" 
                    v-model="profileForm.email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                  >
                  <p v-if="profileForm.errors.email" class="mt-1 text-sm text-red-600">{{ profileForm.errors.email }}</p>
                </div>
                
                <div>
                  <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                  <input 
                    id="telephone"
                    type="tel" 
                    v-model="profileForm.telephone"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="+221 00 000 00 00"
                  >
                  <p v-if="profileForm.errors.telephone" class="mt-1 text-sm text-red-600">{{ profileForm.errors.telephone }}</p>
                </div>
                
                <div class="flex justify-end">
                  <button 
                    type="submit"
                    :disabled="profileForm.processing"
                    class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {{ profileForm.processing ? 'Enregistrement...' : 'Enregistrer les modifications' }}
                  </button>
                </div>
              </form>
            </div>

            <!-- Changement de mot de passe -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Mot de passe</h2>
                <button 
                  @click="showPasswordForm = !showPasswordForm"
                  class="text-blue-600 hover:text-blue-700 text-sm"
                >
                  {{ showPasswordForm ? 'Annuler' : 'Modifier' }}
                </button>
              </div>
              
              <form v-if="showPasswordForm" @submit.prevent="updatePassword" class="space-y-4">
                <div>
                  <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                  <input 
                    id="current_password"
                    type="password" 
                    v-model="passwordForm.current_password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                  >
                  <p v-if="passwordForm.errors.current_password" class="mt-1 text-sm text-red-600">{{ passwordForm.errors.current_password }}</p>
                </div>
                
                <div>
                  <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                  <input 
                    id="new_password"
                    type="password" 
                    v-model="passwordForm.new_password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                    minlength="8"
                  >
                  <p v-if="passwordForm.errors.new_password" class="mt-1 text-sm text-red-600">{{ passwordForm.errors.new_password }}</p>
                </div>
                
                <div>
                  <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le nouveau mot de passe</label>
                  <input 
                    id="password_confirmation"
                    type="password" 
                    v-model="passwordForm.password_confirmation"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                  >
                  <p v-if="passwordForm.errors.password_confirmation" class="mt-1 text-sm text-red-600">{{ passwordForm.errors.password_confirmation }}</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                  <button 
                    type="button"
                    @click="showPasswordForm = false"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800"
                  >
                    Annuler
                  </button>
                  <button 
                    type="submit"
                    :disabled="passwordForm.processing"
                    class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {{ passwordForm.processing ? 'Mise à jour...' : 'Mettre à jour' }}
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Colonne latérale -->
          <div class="space-y-6">
            <!-- Carte de profil -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
              <div class="text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-gray-200 flex items-center justify-center overflow-hidden mb-4">
                  <img v-if="photoPreview" :src="photoPreview" alt="Photo de profil" class="w-full h-full object-cover">
                  <i v-else class="fas fa-user text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">{{ currentUser.name }}</h3>
                <p class="text-sm text-gray-600">{{ currentUser.email }}</p>
                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full mt-2">
                  Promoteur
                </span>
              </div>
              
              <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="space-y-3">
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Membre depuis</span>
                    <span class="text-gray-900">{{ formatDate(currentUser.created_at) }}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Email vérifié</span>
                    <span :class="currentUser.email_verified_at ? 'text-green-600' : 'text-yellow-600'">
                      {{ currentUser.email_verified_at ? 'Oui' : 'Non' }}
                    </span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Statut</span>
                    <span class="text-green-600">Actif</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h3>
              <div class="space-y-3">
                <Link 
                  href="/promoter/venues/create"
                  class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                  <i class="fas fa-plus mr-2"></i> Créer une salle
                </Link>
                <Link 
                  href="/promoter/events/create"
                  class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                >
                  <i class="fas fa-calendar-plus mr-2"></i> Créer un événement
                </Link>
                <button 
                  @click="updateProfile"
                  class="block w-full text-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
                >
                  <i class="fas fa-save mr-2"></i> Sauvegarder le profil
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
