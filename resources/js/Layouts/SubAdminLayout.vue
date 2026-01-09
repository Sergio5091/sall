<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const page = usePage();
const showUserMenu = ref(false);
const isDarkMode = ref(false);
const showLogoutModal = ref(false);

const user = computed(() => page.props.auth.user);

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    document.documentElement.classList.toggle('dark', isDarkMode.value);
    localStorage.setItem('darkMode', isDarkMode.value);
};

const toggleUserMenu = () => {
    showUserMenu.value = !showUserMenu.value;
};

const logout = () => {
    showLogoutModal.value = true;
};

const confirmLogout = () => {
    router.post(route('logout'));
};

const closeMenus = (e) => {
    if (!e.target.closest('.relative')) {
        showUserMenu.value = false;
    }
};

// Initialiser le mode sombre
onMounted(() => {
    const savedDarkMode = localStorage.getItem('darkMode');
    isDarkMode.value = savedDarkMode === 'true';
    document.documentElement.classList.toggle('dark', isDarkMode.value);
    document.addEventListener('click', closeMenus);
    
    // Synchroniser le mode sombre à chaque chargement de page
    window.addEventListener('popstate', () => {
        const savedDarkMode = localStorage.getItem('darkMode');
        isDarkMode.value = savedDarkMode === 'true';
        document.documentElement.classList.toggle('dark', isDarkMode.value);
    });
});

onUnmounted(() => {
    document.removeEventListener('click', closeMenus);
});
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo et titre -->
          <div class="flex items-center">
            <div class="flex-shrink-0 flex items-center">
              <div class="bg-indigo-600 h-8 w-8 rounded-lg flex items-center justify-center">
                <i class="fas fa-shield-alt text-white"></i>
              </div>
              <div class="ml-3">
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Espace Sous-Admin</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                  {{ user?.country }}{{ user?.city ? ', ' + user.city : '' }}
                </p>
              </div>
            </div>
          </div>
          
          <!-- Navigation -->
          <nav class="hidden md:flex space-x-8">
            <Link 
              :href="route('admin.sub-admins.dashboard')"
              preserve-state="false"
              class="text-indigo-600 dark:text-indigo-400 px-3 py-2 rounded-md text-sm font-medium"
              :class="{ 'bg-indigo-50 dark:bg-indigo-50': route().current('admin.sub-admins.dashboard') }"
            >
              Tableau de bord
            </Link>
            <Link 
              :href="route('admin.admin.sub-admin.salles.index')"
              preserve-state="false"
              class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium"
              :class="{ 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-50': route().current('admin.admin.sub-admin.salles.index') }"
            >
              Centres
            </Link>
            <Link 
              :href="route('admin.admin.sub-admin.promoters.index')"
              preserve-state="false"
              class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium"
              :class="{ 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-50': route().current('admin.admin.sub-admin.promoters.index') }"
            >
              Promoteurs
            </Link>
          </nav>
          
          <!-- Menu utilisateur -->
          <div class="flex items-center gap-4">
            <!-- Bouton mode sombre -->
            <button @click="toggleDarkMode" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400">
              <i class="fas fa-moon" v-if="!isDarkMode"></i>
              <i class="fas fa-sun" v-else></i>
            </button>
            
            <!-- Notifications -->
            <button class="relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400">
              <i class="fas fa-bell"></i>
            </button>
            
            <!-- Menu utilisateur -->
            <div class="relative">
              <button @click="toggleUserMenu" class="flex items-center gap-2 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                  <i class="fas fa-user text-indigo-600 text-sm"></i>
                </div>
                <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300">
                  {{ user?.name }}
                </span>
              </button>
              
              <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                  <p class="text-sm font-medium text-gray-900 dark:text-white">{{ user?.name }}</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">{{ user?.email }}</p>
                </div>
                <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                  Mon profil
                </Link>
                <form @submit.prevent="logout">
                  <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Déconnexion
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <slot />
    </main>

    <!-- Modal de confirmation de déconnexion -->
    <div 
      v-if="showLogoutModal" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
      @click="showLogoutModal = false"
    >
      <div 
        class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-96"
        @click.stop
      >
        <div class="text-center mb-4">
          <i class="fas fa-sign-out-alt text-red-600 text-2xl mb-3"></i>
          <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            Confirmer la déconnexion
          </h4>
          <p class="text-sm text-gray-600 dark:text-gray-400">
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
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 20px;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #334155;
}
</style>
