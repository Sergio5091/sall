<template>
  <div>
    <!-- Bouton déclencheur (avatar + nom) -->
    <button 
      @click="showModal = true"
      class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 w-full"
    >
      <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
        <i class="fas fa-user text-indigo-600 text-sm"></i>
      </div>
      <span class="font-medium">{{ activeAccount?.name || 'Compte' }}</span>
      <i class="fas fa-chevron-down text-xs ml-auto"></i>
    </button>

    <!-- Modal principal -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
      @click="showModal = false"
    >
      <div 
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-80 max-h-[70vh] overflow-hidden"
        @click.stop
      >
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-base font-semibold text-gray-900 dark:text-white">Comptes</h3>
          <button 
            @click="showModal = false"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Contenu -->
        <div class="p-4 overflow-y-auto max-h-[50vh]">
          <!-- Compte actuel -->
          <div class="mb-4">
            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-3">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                  <i class="fas fa-user text-indigo-600 text-sm"></i>
                </div>
                <div class="flex-1">
                  <p class="font-medium text-gray-900 text-sm">{{ activeAccount?.name || 'Non trouvé' }}</p>
                  <p class="text-xs text-indigo-600">Actif</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Autres comptes -->
          <div v-if="linkedAccounts.length > 0" class="space-y-2">
            <div
              v-for="account in linkedAccounts"
              :key="account.id"
              @click="confirmSwitch(account)"
              class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors"
            >
              <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-gray-600 text-sm"></i>
              </div>
              <div class="flex-1">
                <p class="font-medium text-gray-900 dark:text-white text-sm">
                  {{ account.pivot?.nickname || account.name }}
                </p>
              </div>
            </div>
          </div>

          <!-- Bouton ajouter -->
          <button
            v-if="isMainAccount"
            @click="showAddForm = true"
            class="w-full mt-4 flex items-center justify-center gap-2 p-3 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:border-gray-400 hover:text-gray-700 transition-colors"
          >
            <i class="fas fa-plus"></i>
            <span class="text-sm font-medium">Ajouter un compte</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Modal confirmation switch -->
    <div 
      v-if="confirmModal.show" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
      @click="confirmModal.show = false"
    >
      <div 
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-72 p-4"
        @click.stop
      >
        <div class="text-center mb-4">
          <i class="fas fa-exchange-alt text-indigo-600 text-2xl mb-3"></i>
          <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-2">
            Changer de compte ?
          </h4>
          <p class="text-sm text-gray-600">
            Voulez-vous passer à<br>
            <span class="font-medium">{{ confirmModal.accountName }}</span> ?
          </p>
        </div>
        <div class="flex space-x-2">
          <button
            @click="confirmModal.show = false"
            class="flex-1 bg-gray-200 text-gray-700 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-300"
          >
            Annuler
          </button>
          <button
            @click="performSwitch"
            class="flex-1 bg-indigo-600 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700"
          >
            Oui
          </button>
        </div>
      </div>
    </div>

    <!-- Modal ajout -->
    <div 
      v-if="showAddForm" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
      @click="showAddForm = false"
    >
      <div 
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-72 p-4"
        @click.stop
      >
        <div class="text-center mb-4">
          <i class="fas fa-user-plus text-indigo-600 text-2xl mb-3"></i>
          <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-2">
            Ajouter un compte
          </h4>
        </div>
        <form @submit.prevent="addAccount" class="space-y-3">
          <div>
            <input
              type="email"
              v-model="newAccount.email"
              required
              placeholder="Email du compte"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <div>
            <input
              type="text"
              v-model="newAccount.nickname"
              placeholder="Nom d'affichage (optionnel)"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <div class="flex space-x-2">
            <button
              type="button"
              @click="showAddForm = false; newAccount = {}"
              class="flex-1 bg-gray-200 text-gray-700 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-300"
            >
              Annuler
            </button>
            <button
              type="submit"
              class="flex-1 bg-indigo-600 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700"
            >
              Ajouter
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';

const page = usePage();
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
  accountName: '',
  account: null,
});

const linkedAccounts = computed(() => {
  if (!props.allAccounts || !props.mainAccount) return [];
  return props.allAccounts.filter(account => account.id !== props.mainAccount.id);
});

const confirmSwitch = (account) => {
  confirmModal.value = {
    show: true,
    accountName: account.pivot?.nickname || account.name,
    account: account,
  };
};

const performSwitch = async () => {
  try {
    await router.post(`/promoter/accounts/switch/${confirmModal.value.account.id}`);
    confirmModal.value.show = false;
    showModal.value = false;
    window.location.reload();
  } catch (error) {
    console.error('Erreur lors du changement de compte:', error);
  }
};

const addAccount = async () => {
  try {
    await router.post('/promoter/accounts/add', newAccount.value);
    showAddForm.value = false;
    newAccount.value = { email: '', nickname: '' };
    window.location.reload();
  } catch (error) {
    console.error('Erreur lors de l\'ajout du compte:', error);
  }
};
</script>
