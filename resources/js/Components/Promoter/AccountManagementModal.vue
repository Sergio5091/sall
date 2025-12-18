<template>
  <div>
    <!-- Bouton déclencheur -->
    <button 
      @click="showModal = true"
      class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-300 hover:translate-x-1 w-full"
    >
      <i class="fas fa-users"></i>
      <span>Gestion des Comptes</span>
      <i class="fas fa-chevron-down text-xs ml-auto"></i>
    </button>

    <!-- Modal -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-2"
      @click="showModal = false"
    >
      <div 
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-sm max-h-[90vh] overflow-hidden flex flex-col"
        @click.stop
      >
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Gestion des Comptes</h3>
          <button 
            @click="showModal = false"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Contenu -->
        <div class="flex-1 overflow-y-auto p-4">
          <!-- Compte Principal -->
          <div class="mb-4">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2 flex items-center">
              <i class="fas fa-crown text-yellow-500 mr-2"></i>
              Compte Principal
            </h4>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                  <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-blue-600 text-sm"></i>
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="font-medium text-gray-900 text-sm truncate">{{ mainAccount?.name || 'Non trouvé' }}</p>
                    <p class="text-xs text-gray-600 truncate">{{ mainAccount?.email || '' }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <span 
                    v-if="activeAccount?.id === mainAccount?.id"
                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800"
                  >
                    <i class="fas fa-check mr-1"></i>
                    Actif
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Comptes Liés -->
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-sm font-medium text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-link text-indigo-500 mr-2"></i>
                Comptes Liés ({{ linkedAccounts.length }})
              </h4>
              <button
                @click="showAddForm = !showAddForm"
                class="text-indigo-600 hover:text-indigo-800 text-xs font-medium"
                :disabled="!isMainAccount"
              >
                <i class="fas fa-plus mr-1"></i>
                Ajouter
              </button>
            </div>

            <!-- Formulaire d'ajout -->
            <div v-if="showAddForm && isMainAccount" class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-3">
              <h5 class="text-sm font-medium text-gray-900 mb-2">Ajouter un compte lié</h5>
              <form @submit.prevent="addAccount" class="space-y-2">
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                  <input
                    type="email"
                    v-model="newAccount.email"
                    required
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="email@example.com"
                  />
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Nom d'affichage</label>
                  <input
                    type="text"
                    v-model="newAccount.nickname"
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Optionnel"
                  />
                </div>
                <div class="flex space-x-2">
                  <button
                    type="submit"
                    class="flex-1 bg-indigo-600 text-white px-3 py-1.5 text-sm rounded-md hover:bg-indigo-700"
                  >
                    Ajouter
                  </button>
                  <button
                    type="button"
                    @click="showAddForm = false; newAccount = {}"
                    class="flex-1 bg-gray-300 text-gray-700 px-3 py-1.5 text-sm rounded-md hover:bg-gray-400"
                  >
                    Annuler
                  </button>
                </div>
              </form>
            </div>

            <!-- Liste des comptes liés -->
            <div v-if="linkedAccounts.length > 0" class="space-y-2">
              <div
                v-for="account in linkedAccounts"
                :key="account.id"
                class="border rounded-lg p-3 transition-all duration-200"
                :class="[
                  'hover:shadow-sm',
                  activeAccount?.id === account.id 
                    ? 'border-indigo-500 bg-indigo-50' 
                    : 'border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800'
                ]"
              >
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                      <i class="fas fa-user text-gray-600 text-sm"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                      <p class="font-medium text-gray-900 text-sm truncate">
                        {{ account.pivot?.nickname || account.name }}
                      </p>
                      <p class="text-xs text-gray-600 truncate">{{ account.email }}</p>
                    </div>
                  </div>
                  <div class="flex items-center space-x-1">
                    <!-- Badge actif -->
                    <span 
                      v-if="activeAccount?.id === account.id"
                      class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800"
                    >
                      <i class="fas fa-check mr-1"></i>
                      Actif
                    </span>
                    
                    <!-- Actions -->
                    <div v-if="isMainAccount" class="flex space-x-1">
                      <button
                        @click="switchToAccount(account)"
                        :disabled="activeAccount?.id === account.id"
                        class="p-1.5 text-indigo-600 hover:text-indigo-800 disabled:text-gray-400"
                        :title="activeAccount?.id === account.id ? 'Compte actuel' : 'Switcher vers ce compte'"
                      >
                        <i class="fas fa-exchange-alt text-xs"></i>
                      </button>
                      <button
                        @click="confirmRemoveAccount(account)"
                        class="p-1.5 text-red-600 hover:text-red-800"
                        title="Retirer ce compte"
                      >
                        <i class="fas fa-unlink text-xs"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Message si aucun compte lié -->
            <div v-else class="text-center py-6">
              <i class="fas fa-users text-gray-400 text-3xl mb-3"></i>
              <p class="text-gray-500 text-sm mb-2">Aucun compte lié</p>
              <p class="text-xs text-gray-400">
                Ajoutez des comptes promoteurs pour les gérer depuis ici
              </p>
            </div>
          </div>

          <!-- Actions rapides -->
          <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
            <div class="flex space-x-2">
              <button
                @click="confirmLogout"
                class="flex-1 bg-red-600 text-white px-3 py-2 text-sm rounded-md hover:bg-red-700"
              >
                <i class="fas fa-sign-out-alt mr-1 text-xs"></i>
                Déconnexion
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation -->
    <div 
      v-if="confirmModal.show" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-2"
      @click="confirmModal.show = false"
    >
      <div 
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-xs w-full mx-2 p-4"
        @click.stop
      >
        <div class="flex items-center mb-3">
          <i 
            :class="[
              'fas text-xl mr-2',
              confirmModal.type === 'switch' ? 'fa-exchange-alt text-indigo-600' : 
              confirmModal.type === 'remove' ? 'fa-unlink text-red-600' : 
              'fa-sign-out-alt text-red-600'
            ]"
          ></i>
          <div>
            <h4 class="text-base font-semibold text-gray-900 dark:text-white">
              {{ confirmModal.title }}
            </h4>
            <p class="text-xs text-gray-600 mt-1">
              {{ confirmModal.message }}
            </p>
          </div>
        </div>
        <div class="flex space-x-2">
          <button
            @click="confirmModal.show = false"
            class="flex-1 bg-gray-300 text-gray-700 px-3 py-2 text-sm rounded-md hover:bg-gray-400"
          >
            Annuler
          </button>
          <button
            @click="executeConfirmAction"
            class="flex-1 bg-red-600 text-white px-3 py-2 text-sm rounded-md hover:bg-red-700"
          >
            Confirmer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  mainAccount: Object,
  allAccounts: Array,
  activeAccount: Object,
  isMainAccount: Boolean,
});

const showModal = ref(false);
const showAddForm = ref(false);
const newAccount = ref({
  email: '',
  nickname: '',
});

const confirmModal = ref({
  show: false,
  type: '',
  title: '',
  message: '',
  action: null,
});

const linkedAccounts = computed(() => {
  if (!props.allAccounts || !props.mainAccount) return [];
  return props.allAccounts.filter(account => account.id !== props.mainAccount.id);
});

const addAccount = async () => {
  try {
    await router.post('/promoter/accounts/add', newAccount.value);
    showAddForm.value = false;
    newAccount.value = { email: '', nickname: '' };
    // Recharger la page pour mettre à jour les données
    window.location.reload();
  } catch (error) {
    console.error('Erreur lors de l\'ajout du compte:', error);
  }
};

const switchToAccount = (account) => {
  if (props.activeAccount?.id === account.id) return;
  
  confirmModal.value = {
    show: true,
    type: 'switch',
    title: 'Changer de compte',
    message: `Voulez-vous vraiment passer au compte "${account.pivot?.nickname || account.name}" ?`,
    action: () => performSwitch(account),
  };
};

const performSwitch = async (account) => {
  try {
    await router.post(`/promoter/accounts/switch/${account.id}`);
    confirmModal.value.show = false;
    window.location.reload();
  } catch (error) {
    console.error('Erreur lors du changement de compte:', error);
  }
};

const confirmRemoveAccount = (account) => {
  confirmModal.value = {
    show: true,
    type: 'remove',
    title: 'Retirer le compte',
    message: `Voulez-vous vraiment retirer le compte "${account.pivot?.nickname || account.name}" ? Cette action est irréversible.`,
    action: () => performRemove(account),
  };
};

const performRemove = async (account) => {
  try {
    await router.delete(`/promoter/accounts/${account.id}`);
    confirmModal.value.show = false;
    window.location.reload();
  } catch (error) {
    console.error('Erreur lors du retrait du compte:', error);
  }
};

const confirmLogout = () => {
  confirmModal.value = {
    show: true,
    type: 'logout',
    title: 'Déconnexion',
    message: 'Voulez-vous vraiment vous déconnecter ?',
    action: () => router.post('/logout'),
  };
};

const executeConfirmAction = () => {
  if (confirmModal.value.action) {
    confirmModal.value.action();
  }
};

const goToFullManagement = () => {
  // Rediriger vers le dashboard car la page /promoter/accounts n'existe plus
  router.visit('/promoter/dashboard');
};
</script>
