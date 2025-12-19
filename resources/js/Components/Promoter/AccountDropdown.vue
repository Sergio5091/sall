<template>
  <div class="relative">
    <!-- Bouton déclencheur -->
    <button 
      @click="toggleDropdown"
      class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
    >
      <i class="fas fa-users text-indigo-600"></i>
      <i class="fas fa-chevron-down text-xs text-gray-400"></i>
    </button>

    <!-- Menu déroulant -->
    <div 
      v-if="showDropdown"
      class="absolute bottom-full left-0 mb-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
      @click.stop
    >
      <!-- Compte actuel -->
      <div class="p-3 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
          <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center">
            <i class="fas fa-user text-indigo-600 text-xs"></i>
          </div>
          <div class="flex-1">
            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ activeAccount?.name || 'Compte' }}</p>
            <p class="text-xs text-indigo-600">Actif</p>
          </div>
        </div>
      </div>

      <!-- Comptes liés -->
      <div v-if="linkedAccounts.length > 0" class="py-2">
        <div
          v-for="account in linkedAccounts"
          :key="account.id"
          @click="confirmSwitch(account)"
          class="px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors"
        >
          <p class="text-sm text-gray-900 dark:text-white">
            {{ account.pivot?.nickname || account.name }}
          </p>
        </div>
      </div>

      <!-- Ajouter un compte -->
      <div class="border-t border-gray-200 dark:border-gray-700 p-2">
        <button
          @click="goToAddAccount"
          class="w-full flex items-center justify-center gap-2 p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
          :title="!isMainAccount ? 'Seul le compte principal peut ajouter des comptes' : ''"
          :disabled="!isMainAccount"
          :class="!isMainAccount ? 'opacity-50 cursor-not-allowed' : ''"
        >
          <i class="fas fa-plus text-sm"></i>
          <span class="text-sm">Ajouter un compte</span>
        </button>
      </div>
    </div>

    <!-- Confirmation de changement -->
    <div 
      v-if="confirmModal.show"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
      @click="confirmModal.show = false"
    >
      <div 
        class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 w-64"
        @click.stop
      >
        <div class="text-center mb-4">
          <i class="fas fa-exchange-alt text-indigo-600 text-xl mb-2"></i>
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

    <!-- Overlay pour fermer au clic extérieur -->
    <div 
      v-if="showDropdown"
      class="fixed inset-0 z-40"
      @click="closeDropdown"
    ></div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  mainAccount: Object,
  allAccounts: Array,
  activeAccount: Object,
  isMainAccount: Boolean,
});

// Debug temporaire
// Props reçues: {
//   isMainAccount: props.isMainAccount,
//   mainAccount: props.mainAccount,
//   activeAccount: props.activeAccount,
//   allAccounts: props.allAccounts
// }

// Debug supplémentaire
// Condition bouton: {
//   isMainAccount: props.isMainAccount,
//   disabled: !props.isMainAccount,
//   buttonShouldBeEnabled: props.isMainAccount === true
// }

const showDropdown = ref(false);
const confirmModal = ref({
  show: false,
  accountName: '',
  account: null,
});

const linkedAccounts = computed(() => {
  if (!props.allAccounts || !props.mainAccount) return [];
  return props.allAccounts.filter(account => account.id !== props.mainAccount.id);
});

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value;
};

const closeDropdown = () => {
  showDropdown.value = false;
};

const confirmSwitch = (account) => {
  confirmModal.value = {
    show: true,
    accountName: account.pivot?.nickname || account.name,
    account: account,
  };
  closeDropdown();
};

const performSwitch = async () => {
  try {
    await router.post(`/promoter/accounts/switch/${confirmModal.value.account.id}`);
    confirmModal.value.show = false;
    window.location.reload();
  } catch (error) {
    console.error('Erreur lors du changement de compte:', error);
  }
};

const goToAddAccount = () => {
  closeDropdown();
  // Stocker l'ID du compte principal en session Laravel via une requête API
  router.post('/promoter/accounts/store-link-session', {
    mainAccountId: props.mainAccount.id
  }).then(() => {
    // Rediriger vers la page de connexion pour lier un compte
    router.visit('/login?link_account=true');
  });
};

// Fermer le dropdown au clic extérieur
const handleClickOutside = (event) => {
  if (!event.target.closest('.relative')) {
    closeDropdown();
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
