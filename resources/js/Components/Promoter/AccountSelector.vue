<template>
  <div class="relative">
    <!-- Dropdown Button -->
    <button
      @click="isOpen = !isOpen"
      class="flex items-center justify-between w-full px-4 py-2 text-left bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    >
      <div class="flex items-center space-x-3">
        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
          <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
        <div class="flex-1">
          <div class="text-sm font-medium text-gray-900">
            {{ getDisplayName(activeAccount) }}
          </div>
          <div class="text-xs text-gray-500">
            {{ activeAccount?.email }}
            <span v-if="activeAccount?.id === mainAccount?.id" class="text-indigo-600 font-medium">• Principal</span>
          </div>
        </div>
      </div>
      <svg
        class="w-5 h-5 text-gray-400 transition-transform duration-200"
        :class="{ 'rotate-180': isOpen }"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
      </svg>
    </button>

    <!-- Dropdown Menu -->
    <div
      v-if="isOpen"
      class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"
    >
      <div class="p-2">
        <!-- Header -->
        <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
          Mes Comptes ({{ allAccounts.length }})
        </div>
        
        <!-- Account List -->
        <div v-if="allAccounts.length > 0">
          <button
            v-for="account in allAccounts"
            :key="account.id"
            @click="selectAccount(account)"
            class="w-full text-left px-3 py-2 rounded-md hover:bg-indigo-50 transition-colors duration-150"
            :class="{
              'bg-indigo-50 border-l-4 border-indigo-500': activeAccount?.id === account.id
            }"
          >
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </div>
              <div class="flex-1">
                <div class="text-sm font-medium text-gray-900">
                  {{ getDisplayName(account) }}
                </div>
                <div class="text-xs text-gray-500">
                  {{ account.email }}
                  <span v-if="account.id === mainAccount?.id" class="text-indigo-600 font-medium">• Principal</span>
                </div>
              </div>
              <div v-if="activeAccount?.id === account.id" class="w-5 h-5 text-indigo-500">
                <svg fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
              </div>
            </div>
          </button>
        </div>
        
        <!-- Empty State -->
        <div v-else class="text-center py-4">
          <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
          <p class="text-sm text-gray-500">Aucun compte lié</p>
        </div>
        
        <!-- Actions (only for main account) -->
        <div v-if="isMainAccount" class="mt-3 pt-3 border-t border-gray-200">
          <Link
            :href="route('promoter.accounts.index')"
            class="block w-full text-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors duration-150"
          >
            Gérer mes comptes
          </Link>
        </div>
      </div>
    </div>

    <!-- Overlay -->
    <div
      v-if="isOpen"
      @click="isOpen = false"
      class="fixed inset-0 z-40"
    ></div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const isOpen = ref(false);
const page = usePage();

const mainAccount = computed(() => page.props.mainAccount);
const allAccounts = computed(() => page.props.allAccounts || []);
const activeAccount = computed(() => page.props.activeAccount);
const isMainAccount = computed(() => page.props.isMainAccount);

const getDisplayName = (account) => {
  // Si c'est le compte principal, utiliser le nom d'origine
  if (account.id === mainAccount.value?.id) {
    return account.name;
  }
  
  // Pour les comptes liés, utiliser le nickname si disponible, sinon le nom
  const pivotData = allAccounts.value.find(a => a.id === account.id)?.pivot;
  return pivotData?.nickname || account.name;
};

const selectAccount = (account) => {
  if (activeAccount.value?.id === account.id) {
    isOpen.value = false;
    return;
  }
  
  router.post(
    route('promoter.accounts.switch', account.id),
    {},
    {
      onSuccess: () => {
        isOpen.value = false;
      },
      onError: (errors) => {
        console.error('Erreur lors du changement de compte:', errors);
      }
    }
  );
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest('.relative')) {
    isOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});
</script>
