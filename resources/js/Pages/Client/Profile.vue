<script setup>
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Navigation from '../../Components/Navigation.vue';
import AlertModal from '../../Components/AlertModal.vue';
import { useAlert } from '../../Composables/useAlert.js';

const props = defineProps({
  auth: Object,
  user: Object
});

// Alert composable
const { alertState, showSuccess, showError, showConfirm } = useAlert();

// États pour le formulaire
const form = ref({
  name: props.user?.name || props.auth?.user?.name || '',
  email: props.user?.email || props.auth?.user?.email || '',
  telephone: props.user?.telephone || props.auth?.user?.telephone || '',
  date_naissance: props.user?.date_naissance || props.auth?.user?.date_naissance || '',
  newsletter: props.user?.newsletter || props.auth?.user?.newsletter || false,
  notifications_email: props.user?.notifications_email || props.auth?.user?.notifications_email || true,
  partage_profil: props.user?.partage_profil || props.auth?.user?.partage_profil || false
});

// États pour les préférences
const preferences = ref({
  jeux_preferes: [],
  types_salles_preferes: []
});

// États UI
const isSaving = ref(false);
const showChangePasswordModal = ref(false);
const showDeleteConfirmModal = ref(false);
const showLogoutModal = ref(false);
const passwordForm = ref({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
});

// Initiales pour l'avatar
const userInitials = computed(() => {
  const name = props.user?.name || props.auth?.user?.name || '';
  return name ? name.charAt(0).toUpperCase() : 'U';
});

// Fonction de déconnexion
const logout = () => {
  showLogoutModal.value = true;
};

const confirmLogout = () => {
  router.post('/logout');
};

// Fonction de sauvegarde du profil
const updateProfile = async () => {
  isSaving.value = true;
  
  try {
    await router.patch('/client/profile', form.value);
    await showSuccess('Profil mis à jour', 'Vos informations ont été enregistrées avec succès.');
  } catch (error) {
    await showError('Erreur', 'Une erreur est survenue lors de la mise à jour de votre profil.');
  } finally {
    isSaving.value = false;
  }
};

// Fonction de changement de mot de passe
const changePassword = async () => {
  try {
    await router.put('/client/password', passwordForm.value, {
      preserveScroll: true,
      onSuccess: () => {
        showChangePasswordModal.value = false;
        passwordForm.value = {
          current_password: '',
          new_password: '',
          new_password_confirmation: ''
        };
        showSuccess('Mot de passe changé', 'Votre mot de passe a été mis à jour avec succès.');
      },
      onError: () => {
        showError('Erreur', 'Le mot de passe actuel est incorrect ou les nouveaux mots de passe ne correspondent pas.');
      }
    });
  } catch (error) {
    showError('Erreur', 'Une erreur est survenue lors du changement de mot de passe.');
  }
};

// Fonction de téléchargement des données
const downloadData = async () => {
  try {
    const response = await router.get('/client/download-data');
    const blob = new Blob([JSON.stringify(response.props.data, null, 2)], { type: 'application/json' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'mes-donnees-gameon.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
    showSuccess('Téléchargement réussi', 'Vos données ont été téléchargées avec succès.');
  } catch (error) {
    showError('Erreur', 'Une erreur est survenue lors du téléchargement de vos données.');
  }
};

// Fonction de suppression du compte
const deleteAccount = async () => {
  const confirmed = await showConfirm(
    'Suppression du compte',
    'Êtes-vous sûr de vouloir supprimer définitivement votre compte ? Cette action est irréversible.'
  );
  
  if (!confirmed) return;

  try {
    await router.delete('/client/account', {
      preserveScroll: false,
      onSuccess: () => {
        router.push('/');
        showSuccess('Compte supprimé', 'Votre compte a été supprimé avec succès.');
      },
      onError: () => {
        showError('Erreur', 'Une erreur est survenue lors de la suppression de votre compte.');
      }
    });
  } catch (error) {
    showError('Erreur', 'Une erreur est survenue lors de la suppression de votre compte.');
  }
};

// Fonction utilitaire pour les notifications
const showToast = (message, type = 'info') => {
  // Implémentation simple - peut être remplacée par un composant toast dédié
  alert(`${type === 'success' ? '✅' : '❌'} ${message}`);
};

</script>

<template>
  <Head title="Mon Profil" />
  
  <!-- Add Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative min-h-screen bg-gray-50 font-sans">
    <!-- Navigation Component -->
    <Navigation :user="user || auth?.user" current-page="profile" />

    <!-- Main Content -->
    <main class="pt-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Mon Profil</h1>
          <p class="text-gray-600 mt-2">Gérez vos informations personnelles et vos préférences</p>
        </div>

        <!-- Profile Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Column - Profile Info -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Profile Card -->
            <div class="bg-white shadow-sm overflow-hidden rounded-lg">
              <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                  <h2 class="text-xl font-bold text-gray-900">Informations personnelles</h2>
                  <button 
                    @click="updateProfile" 
                    :disabled="isSaving"
                    class="px-4 py-2 text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors rounded-lg"
                  >
                    <i class="fas fa-save mr-2"></i>
                    {{ isSaving ? 'Sauvegarde...' : 'Sauvegarder' }}
                  </button>
                </div>
              </div>
              
              <div class="p-6">
                <!-- Avatar Section -->
                <div class="flex items-center gap-6 mb-8">
                  <div class="relative">
                    <div class="w-20 h-20 bg-blue-500 flex items-center justify-center text-white text-2xl font-bold rounded-full">
                      {{ userInitials }}
                    </div>
                    <button class="absolute bottom-0 right-0 p-2 bg-white shadow hover:bg-gray-100 transition-colors rounded-full">
                      <i class="fas fa-camera text-gray-600 text-sm"></i>
                    </button>
                  </div>
                  <div>
                    <h3 class="font-semibold text-gray-900">{{ form.name }}</h3>
                    <p class="text-sm text-gray-600">{{ form.email }}</p>
                    <p class="text-xs text-gray-500 mt-1">Membre depuis Jan 2024</p>
                  </div>
                </div>

                <!-- Form Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Nom complet <span class="text-red-500">*</span>
                    </label>
                    <input 
                      v-model="form.name" 
                      type="text" 
                      class="w-full px-4 py-2.5 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors rounded-lg"
                      placeholder="Votre nom complet"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Email <span class="text-red-500">*</span>
                    </label>
                    <input 
                      v-model="form.email" 
                      type="email" 
                      class="w-full px-4 py-2.5 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors rounded-lg"
                      placeholder="email@exemple.com"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Téléphone
                    </label>
                    <input 
                      v-model="form.telephone" 
                      type="tel" 
                      class="w-full px-4 py-2.5 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors rounded-lg"
                      placeholder="+221 XX XXX XX XX"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Date de naissance
                    </label>
                    <input 
                      v-model="form.date_naissance" 
                      type="date" 
                      class="w-full px-4 py-2.5 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors rounded-lg"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Notifications Card -->
            <div class="bg-white shadow-sm rounded-lg">
              <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Notifications et confidentialité</h2>
              </div>
              
              <div class="p-6 space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors rounded-lg">
                  <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                      <i class="fas fa-envelope text-blue-600"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900">Notifications par email</p>
                      <p class="text-sm text-gray-600">Recevoir des rappels de réservations</p>
                    </div>
                  </div>
                  <button 
                    @click="form.notifications_email = !form.notifications_email"
                    :class="[
                      'relative inline-flex h-6 w-11 items-center transition-colors rounded-full',
                      form.notifications_email ? 'bg-blue-600' : 'bg-gray-300'
                    ]"
                  >
                    <span 
                      :class="[
                        'inline-block h-4 w-4 transform bg-white transition-transform rounded-full',
                        form.notifications_email ? 'translate-x-6' : 'translate-x-1'
                      ]"
                    ></span>
                  </button>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors rounded-lg">
                  <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 rounded-lg">
                      <i class="fas fa-newspaper text-green-600"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900">Newsletter</p>
                      <p class="text-sm text-gray-600">Nouveautés et événements spéciaux</p>
                    </div>
                  </div>
                  <button 
                    @click="form.newsletter = !form.newsletter"
                    :class="[
                      'relative inline-flex h-6 w-11 items-center transition-colors rounded-full',
                      form.newsletter ? 'bg-green-600' : 'bg-gray-300'
                    ]"
                  >
                    <span 
                      :class="[
                        'inline-block h-4 w-4 transform bg-white transition-transform rounded-full',
                        form.newsletter ? 'translate-x-6' : 'translate-x-1'
                      ]"
                    ></span>
                  </button>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors rounded-lg">
                  <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-100 rounded-lg">
                      <i class="fas fa-user-friends text-purple-600"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900">Partage de profil</p>
                      <p class="text-sm text-gray-600">Autoriser les autres joueurs à voir votre profil</p>
                    </div>
                  </div>
                  <button 
                    @click="form.partage_profil = !form.partage_profil"
                    :class="[
                      'relative inline-flex h-6 w-11 items-center transition-colors rounded-full',
                      form.partage_profil ? 'bg-purple-600' : 'bg-gray-300'
                    ]"
                  >
                    <span 
                      :class="[
                        'inline-block h-4 w-4 transform bg-white transition-transform rounded-full',
                        form.partage_profil ? 'translate-x-6' : 'translate-x-1'
                      ]"
                    ></span>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-6">
            <!-- Security Card -->
            <div class="bg-white shadow-sm rounded-lg">
              <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Sécurité</h2>
                <p class="text-sm text-gray-600 mt-1">Protégez votre compte</p>
              </div>
              
              <div class="p-6 space-y-4">
                <button 
                  @click="showChangePasswordModal = true"
                  class="w-full flex items-center justify-between p-4 border border-gray-200 hover:bg-gray-50 transition-colors rounded-lg"
                >
                  <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                      <i class="fas fa-lock text-blue-600"></i>
                    </div>
                    <div class="text-left">
                      <p class="font-medium text-gray-900">Mot de passe</p>
                      <p class="text-sm text-gray-600">Changer régulièrement</p>
                    </div>
                  </div>
                  <i class="fas fa-chevron-right text-gray-400"></i>
                </button>
                
                <button class="w-full flex items-center justify-between p-4 border border-gray-200 hover:bg-gray-50 transition-colors rounded-lg">
                  <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 rounded-lg">
                      <i class="fas fa-shield-alt text-green-600"></i>
                    </div>
                    <div class="text-left">
                      <p class="font-medium text-gray-900">2FA</p>
                      <p class="text-sm text-gray-600">Renforcer la sécurité</p>
                    </div>
                  </div>
                  <button class="px-3 py-1 text-xs font-medium bg-green-600 text-white rounded-lg">
                    Activer
                  </button>
                </button>
              </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow-sm rounded-lg">
              <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Actions</h2>
              </div>
              
              <div class="p-6 space-y-3">
                <button 
                  @click="downloadData"
                  class="w-full flex items-center gap-3 px-4 py-3 border border-gray-200 hover:bg-gray-50 transition-colors rounded-lg"
                >
                  <i class="fas fa-download text-gray-600"></i>
                  <span class="font-medium text-gray-700">Télécharger mes données</span>
                </button>
                
                <button 
                  @click="logout"
                  class="w-full flex items-center gap-3 px-4 py-3 border border-gray-200 hover:bg-gray-50 transition-colors rounded-lg"
                >
                  <i class="fas fa-sign-out-alt text-gray-600"></i>
                  <span class="font-medium text-gray-700">Se déconnecter</span>
                </button>
              </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-white shadow-sm border border-gray-300 rounded-lg">
              <div class="p-6 border-b bg-gray-50">
                <h2 class="text-xl font-bold text-gray-700">Zone de danger</h2>
              </div>
              
              <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">
                  La suppression de votre compte est définitive. Toutes vos données seront supprimées.
                </p>
                <button 
                  @click="showDeleteConfirmModal = true"
                  class="w-full px-4 py-2.5 text-sm font-medium border border-gray-400 text-gray-700 hover:bg-gray-50 transition-colors rounded-lg"
                >
                  Supprimer mon compte
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal: Change Password -->
    <div v-if="showChangePasswordModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showChangePasswordModal = false"></div>
      <div class="relative w-full max-w-md bg-white shadow-2xl rounded-lg">
        <div class="p-6 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Changer le mot de passe</h3>
            <button @click="showChangePasswordModal = false" class="p-2 hover:bg-gray-100 rounded-lg">
              <i class="fas fa-times text-gray-500"></i>
            </button>
          </div>
        </div>
        
        <div class="p-6">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Mot de passe actuel
              </label>
              <input 
                v-model="passwordForm.current_password"
                type="password"
                class="w-full px-4 py-2.5 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg"
                placeholder="••••••••"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Nouveau mot de passe
              </label>
              <input 
                v-model="passwordForm.new_password"
                type="password"
                class="w-full px-4 py-2.5 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg"
                placeholder="••••••••"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Confirmer le nouveau mot de passe
              </label>
              <input 
                v-model="passwordForm.new_password_confirmation"
                type="password"
                class="w-full px-4 py-2.5 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg"
                placeholder="••••••••"
              />
            </div>
          </div>
        </div>
        
        <div class="p-6 border-t border-gray-200 flex justify-end gap-3">
          <button @click="showChangePasswordModal = false" class="px-4 py-2.5 text-sm font-medium border border-gray-300 hover:bg-gray-50 rounded-lg">
            Annuler
          </button>
          <button @click="changePassword" class="px-4 py-2.5 text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 rounded-lg">
            Confirmer
          </button>
        </div>
      </div>
    </div>

    <!-- Modal: Confirm Delete -->
    <div v-if="showDeleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showDeleteConfirmModal = false"></div>
      <div class="relative w-full max-w-md bg-white shadow-2xl rounded-lg">
        <div class="p-6 border-b bg-gray-50">
          <div class="flex items-center gap-3">
            <div class="p-2 bg-red-100 rounded-lg">
              <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-red-700">Supprimer le compte</h3>
          </div>
        </div>
        
        <div class="p-6">
          <p class="text-gray-700 mb-4">
            Êtes-vous sûr de vouloir supprimer définitivement votre compte ? Cette action est irréversible.
          </p>
          <p class="text-sm text-gray-600 mb-6">
            Tapez <span class="font-mono font-bold bg-gray-100 px-2 py-1 rounded">SUPPRIMER MON COMPTE</span> pour confirmer
          </p>
          
          <input 
            type="text" 
            id="deleteConfirm"
            class="w-full px-4 py-2.5 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 mb-4 rounded-lg"
            placeholder="Tapez la phrase de confirmation..."
          />
          
          <div class="flex justify-end gap-3">
            <button @click="showDeleteConfirmModal = false" class="px-4 py-2.5 text-sm font-medium border border-gray-300 hover:bg-gray-50 rounded-lg">
              Annuler
            </button>
            <button 
              @click="deleteAccount"
              :disabled="document.getElementById('deleteConfirm')?.value !== 'SUPPRIMER MON COMPTE'"
              class="px-4 py-2.5 text-sm font-medium bg-red-600 text-white hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed rounded-lg"
            >
              Supprimer définitivement
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation de déconnexion -->
    <div 
      v-if="showLogoutModal" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
      @click="showLogoutModal = false"
    >
      <div 
        class="bg-white rounded-lg shadow-xl p-6 w-96"
        @click.stop
      >
        <div class="text-center mb-4">
          <i class="fas fa-sign-out-alt text-red-600 text-2xl mb-3"></i>
          <h4 class="text-lg font-semibold text-gray-900 mb-2">
            Confirmer la déconnexion
          </h4>
          <p class="text-sm text-gray-600">
            Êtes-vous sûr de vouloir vous déconnecter ?
          </p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="showLogoutModal = false"
            class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-300 transition-colors"
          >
            Annuler
          </button>
          <button
            @click="confirmLogout"
            class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 transition-colors"
          >
            Se déconnecter
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Alert Modal -->
  <AlertModal
    :show="alertState.show"
    :type="alertState.type"
    :title="alertState.title"
    :message="alertState.message"
    :confirm-text="alertState.confirmText"
    :cancel-text="alertState.cancelText"
    :show-cancel="alertState.showCancel"
    @close="alertState.show = false"
    @confirm="alertState.resolve"
  />
</template>

<style scoped>
/* Custom styles */
.font-sans {
  font-family: 'Inter', sans-serif;
}

.transition-colors {
  transition: all 0.2s ease;
}

/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>