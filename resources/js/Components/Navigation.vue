<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
  user: Object,
  currentPage: String
});

const isMobileMenuOpen = ref(false);
const showLogoutModal = ref(false);

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false;
};

const logout = () => {
  showLogoutModal.value = true;
};

const confirmLogout = () => {
  router.post('/logout');
};

const userInitials = computed(() => {
  const name = props.user?.name || '';
  return name ? name.charAt(0).toUpperCase() : 'U';
});
</script>

<template>
  <!-- Header -->
  <header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Logo and Desktop Navigation -->
        <div class="flex items-center gap-8">
          <Link href="/client/dashboard" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
            <i class="fas fa-gamepad text-xl text-blue-600"></i>
            <span class="text-lg font-bold text-gray-900">GameOn</span>
          </Link>
          
          <!-- Desktop Navigation -->
          <nav class="hidden md:flex items-center gap-6">
            <Link 
              href="/client/dashboard" 
              class="text-sm font-medium transition-colors"
              :class="currentPage === 'dashboard' ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'"
            >
              Dashboard
            </Link>
            <Link 
              href="/client/salles" 
              class="text-sm font-medium transition-colors"
              :class="currentPage === 'salles' ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'"
            >
              Salles
            </Link>
            <Link 
              href="/client/evenements" 
              class="text-sm font-medium transition-colors"
              :class="currentPage === 'evenements' ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'"
            >
              Événements
            </Link>
            <Link 
              href="/client/profile" 
              class="text-sm font-medium transition-colors"
              :class="currentPage === 'profile' ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'"
            >
              Profil
            </Link>
          </nav>
        </div>

        <!-- Right side items -->
        <div class="flex items-center gap-3">
          <!-- Notifications -->
          <button class="relative p-2 hover:bg-gray-100 transition-colors rounded-lg hidden sm:block">
            <i class="fas fa-bell text-gray-600"></i>
            <span class="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full"></span>
          </button>
          
          <!-- User Avatar -->
          <Link 
            href="/client/profile" 
            class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white font-semibold rounded-full hover:opacity-80 transition-opacity"
          >
            {{ userInitials }}
          </Link>

          <!-- Mobile Menu Button -->
          <button 
            @click="toggleMobileMenu"
            class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <i v-if="!isMobileMenuOpen" class="fas fa-bars text-gray-600 text-xl"></i>
            <i v-else class="fas fa-times text-gray-600 text-xl"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div 
      v-if="isMobileMenuOpen" 
      class="md:hidden absolute top-16 left-0 right-0 bg-white border-b border-gray-200 shadow-lg"
    >
      <nav class="px-4 py-3 space-y-1">
        <Link 
          href="/client/dashboard" 
          @click="closeMobileMenu"
          class="block px-3 py-2 text-base font-medium transition-colors rounded-lg"
          :class="currentPage === 'dashboard' ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'"
        >
          <div class="flex items-center gap-3">
            <i class="fas fa-home w-5"></i>
            Dashboard
          </div>
        </Link>
        
        <Link 
          href="/client/salles" 
          @click="closeMobileMenu"
          class="block px-3 py-2 text-base font-medium transition-colors rounded-lg"
          :class="currentPage === 'salles' ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'"
        >
          <div class="flex items-center gap-3">
            <i class="fas fa-building w-5"></i>
            Salles
          </div>
        </Link>
        
        <Link 
          href="/client/evenements" 
          @click="closeMobileMenu"
          class="block px-3 py-2 text-base font-medium transition-colors rounded-lg"
          :class="currentPage === 'evenements' ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'"
        >
          <div class="flex items-center gap-3">
            <i class="fas fa-calendar w-5"></i>
            Événements
          </div>
        </Link>
        
        <Link 
          href="/client/profile" 
          @click="closeMobileMenu"
          class="block px-3 py-2 text-base font-medium transition-colors rounded-lg"
          :class="currentPage === 'profile' ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'"
        >
          <div class="flex items-center gap-3">
            <i class="fas fa-user w-5"></i>
            Profil
          </div>
        </Link>
        
        <!-- Mobile Notifications -->
        <div class="border-t border-gray-200 pt-3">
          <button class="w-full flex items-center gap-3 px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 transition-colors rounded-lg">
            <i class="fas fa-bell w-5"></i>
            Notifications
            <span class="ml-auto h-2 w-2 bg-red-500 rounded-full"></span>
          </button>
        </div>
        
        <!-- Mobile Logout -->
        <div class="border-t border-gray-200 pt-3 mt-3">
          <button 
            @click="logout"
            class="w-full flex items-center gap-3 px-3 py-2 text-base font-medium text-red-600 hover:bg-red-50 transition-colors rounded-lg"
          >
            <i class="fas fa-sign-out-alt w-5"></i>
            Se déconnecter
          </button>
        </div>
      </nav>
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
  </header>
</template>

<style scoped>
/* Custom styles for mobile menu */
.transition-colors {
  transition: all 0.2s ease;
}
</style>
