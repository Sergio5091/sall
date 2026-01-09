<template>
  <div class="flex h-screen w-full">
    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" @click="closeMobileMenu"></div>
    
    <!-- SideNavBar -->
    <aside 
      class="fixed lg:relative w-64 flex-shrink-0 h-full bg-white dark:bg-slate-850 border-r border-slate-200 dark:border-slate-800 flex flex-col transition-all duration-300 z-50"
      :class="showMobileMenu ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
      <div class="p-6 flex items-center gap-3">
        <div class="bg-primary size-10 rounded-full flex items-center justify-center text-white">
          <i class="fas fa-gamepad"></i>
        </div>
        <h1 class="text-slate-900 dark:text-white text-lg font-bold tracking-tight">GameAdmin</h1>
      </div>
      
      <nav class="flex-1 overflow-y-auto custom-scrollbar px-4 py-2 flex flex-col gap-2">
        <button
          @click="openDashboardModal"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group w-full text-left"
          :class="{ 'bg-primary/10 text-primary dark:text-blue-400': route().current('admin.dashboard') }"
        >
          <i class="fas fa-tachometer-alt group-hover:text-primary transition-colors"></i>
          <span class="text-sm font-medium">Tableau de bord</span>
        </button>
        
        <Link
          :href="route('admin.clients.index')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group"
          :class="{ 'bg-primary/10 text-primary dark:text-blue-400': route().current('admin.clients.*') }"
        >
          <i class="fas fa-users group-hover:text-primary transition-colors"></i>
          <span class="text-sm font-medium">Clients</span>
        </Link>
        
        <Link
          :href="route('admin.promoteurs.index')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group"
          :class="{ 'bg-primary/10 text-primary dark:text-blue-400': route().current('admin.promoteurs.*') }"
        >
          <i class="fas fa-user-friends group-hover:text-primary transition-colors"></i>
          <span class="text-sm font-medium">Promoteurs</span>
        </Link>
        
        <Link
          :href="route('admin.salles.index')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group"
          :class="{ 'bg-primary/10 text-primary dark:text-blue-400': route().current('admin.salles.*') }"
        >
          <i class="fas fa-store group-hover:text-primary transition-colors"></i>
          <span class="text-sm font-medium">Salles</span>
        </Link>
        
        <Link
          :href="route('admin.events.index')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group"
          :class="{ 'bg-primary/10 text-primary dark:text-blue-400': route().current('admin.events.*') }"
        >
          <i class="fas fa-calendar group-hover:text-primary transition-colors"></i>
          <span class="text-sm font-medium">Événements</span>
        </Link>
        
        <Link
          :href="route('admin.news.index')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group"
          :class="{ 'bg-primary/10 text-primary dark:text-blue-400': route().current('admin.news.*') }"
        >
          <i class="fas fa-newspaper group-hover:text-primary transition-colors"></i>
          <span class="text-sm font-medium">Actualités</span>
        </Link>
        
        <Link
          :href="route('admin.sub-admins.index')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group"
          :class="{ 'bg-primary/10 text-primary dark:text-blue-400': route().current('admin.sub-admins.*') }"
        >
          <i class="fas fa-user-shield group-hover:text-primary transition-colors"></i>
          <span class="text-sm font-medium">Sous-admins</span>
        </Link>
        
        <Link
          :href="route('admin.standalone-events.index')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group"
          :class="{ 'bg-primary/10 text-primary dark:text-blue-400': route().current('admin.standalone-events.*') }"
        >
          <i class="fas fa-calendar-star group-hover:text-primary transition-colors"></i>
          <span class="text-sm font-medium">Événements Ponctuels</span>
        </Link>
        
        <div class="my-2 border-t border-slate-100 dark:border-slate-800"></div>
        
        <Link
          :href="route('admin.profile')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group"
          :class="{ 'bg-primary/10 text-primary dark:text-blue-400': route().current('admin.profile') }"
        >
          <i class="fas fa-user group-hover:text-primary transition-colors"></i>
          <span class="text-sm font-medium">Profil</span>
        </Link>
        
        <Link
          :href="route('admin.settings')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group"
          :class="{ 'bg-primary/10 text-primary dark:text-blue-400': route().current('admin.settings') }"
        >
          <i class="fas fa-cog group-hover:text-primary transition-colors"></i>
          <span class="text-sm font-medium">Paramètres</span>
        </Link>
        
        <form @submit.prevent="logout" class="mt-auto">
          <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors group">
            <i class="fas fa-sign-out-alt group-hover:text-red-700 dark:group-hover:text-red-300 transition-colors"></i>
            <span class="text-sm font-medium">Déconnexion</span>
          </button>
        </form>
      </nav>
      
      <div class="p-4 border-t border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
          <div class="size-9 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
            <i class="fas fa-user text-slate-600 dark:text-slate-400"></i>
          </div>
          <div class="flex flex-col">
            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $page.props.auth.user.name }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $page.props.auth.user.email }}</p>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content Wrapper -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
      <!-- TopNavBar -->
      <header class="h-16 flex items-center justify-between px-4 sm:px-6 bg-white dark:bg-slate-850 border-b border-slate-200 dark:border-slate-800 z-10">
        <div class="flex items-center gap-4 flex-1">
          <button 
            @click.stop="toggleMobileMenu"
            class="text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 lg:hidden p-2"
          >
            <i class="fas fa-bars"></i>
          </button>
          
          <!-- Search Bar -->
          <div class="hidden md:flex items-center max-w-md w-full bg-background-light dark:bg-slate-900 rounded-lg h-10 px-3 border border-transparent focus-within:border-primary/50 transition-all">
            <i class="fas fa-search text-slate-400 icon-sm"></i>
            <input
              v-model="searchQuery"
              @keyup.enter="handleSearch"
              class="bg-transparent border-none focus:ring-0 text-sm w-full text-slate-900 dark:text-white placeholder:text-slate-400"
              placeholder="Rechercher (Ctrl+K)"
              type="text"
            />
          </div>
        </div>
        
        <div class="flex items-center gap-2 sm:gap-3">
          <button class="relative p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors">
            <i class="fas fa-bell icon-sm"></i>
            <span v-if="unreadNotifications > 0" class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-850"></span>
          </button>
          
          <button class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors">
            <i class="fas fa-question-circle icon-sm"></i>
          </button>
          
          <div class="hidden sm:block h-8 w-px bg-slate-200 dark:bg-slate-700 mx-1"></div>
          
          <button @click="toggleDarkMode" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors">
            <i class="fas fa-moon" v-if="!isDarkMode"></i>
            <i class="fas fa-sun" v-else></i>
          </button>
          
          <!-- User Menu -->
          <div class="relative">
            <button @click="toggleUserMenu" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors">
              <i class="fas fa-user-circle icon-sm"></i>
            </button>
            
            <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-850 rounded-lg shadow-lg border border-slate-200 dark:border-slate-800 py-2 z-50">
              <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                Mon profil
              </Link>
              <form @submit.prevent="logout">
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                  Déconnexion
                </button>
              </form>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <div class="flex-1 overflow-y-auto custom-scrollbar">
        <div class="p-4 sm:p-6 md:p-10">
          <slot />
        </div>
      </div>
    </main>

    <!-- Dashboard Modal -->
    <div 
      v-if="showDashboardModal" 
      class="fixed inset-0 z-[9999] flex items-center justify-center"
      @click.self="closeDashboardModal"
    >
      <!-- Overlay sombre -->
      <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
      
      <!-- Contenu du modal -->
      <div class="relative bg-white dark:bg-slate-850 rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-100">
        <!-- En-tête du modal -->
        <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-800">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Tableau de bord</h2>
          <button 
            @click="closeDashboardModal"
            class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
          >
            <i class="fas fa-times text-slate-500 dark:text-slate-400"></i>
          </button>
        </div>
        
        <!-- Contenu du dashboard -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Cartes statistiques -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-blue-100 text-sm">Total Clients</p>
                  <p class="text-2xl font-bold">1,234</p>
                </div>
                <i class="fas fa-users text-3xl text-blue-200"></i>
              </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-green-100 text-sm">Total Salles</p>
                  <p class="text-2xl font-bold">456</p>
                </div>
                <i class="fas fa-store text-3xl text-green-200"></i>
              </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-purple-100 text-sm">Événements</p>
                  <p class="text-2xl font-bold">789</p>
                </div>
                <i class="fas fa-calendar text-3xl text-purple-200"></i>
              </div>
            </div>
            
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-4 text-white">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-orange-100 text-sm">Promoteurs</p>
                  <p class="text-2xl font-bold">321</p>
                </div>
                <i class="fas fa-user-friends text-3xl text-orange-200"></i>
              </div>
            </div>
          </div>
          
          <!-- Actions rapides -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-6">
              <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Actions rapides</h3>
              <div class="space-y-3">
                <Link 
                  :href="route('admin.clients.index')"
                  @click="closeDashboardModal"
                  class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                >
                  <i class="fas fa-user-plus text-blue-500"></i>
                  <span class="text-slate-700 dark:text-slate-300">Ajouter un client</span>
                </Link>
                <Link 
                  :href="route('admin.salles.index')"
                  @click="closeDashboardModal"
                  class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                >
                  <i class="fas fa-plus-circle text-green-500"></i>
                  <span class="text-slate-700 dark:text-slate-300">Créer une salle</span>
                </Link>
                <Link 
                  :href="route('admin.events.index')"
                  @click="closeDashboardModal"
                  class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                >
                  <i class="fas fa-calendar-plus text-purple-500"></i>
                  <span class="text-slate-700 dark:text-slate-300">Nouvel événement</span>
                </Link>
              </div>
            </div>
            
            <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-6">
              <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Activité récente</h3>
              <div class="space-y-3">
                <div class="flex items-center gap-3 p-3">
                  <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                  <div class="flex-1">
                    <p class="text-sm text-slate-700 dark:text-slate-300">Nouveau client inscrit</p>
                    <p class="text-xs text-slate-500 dark:text-slate-500">Il y a 5 minutes</p>
                  </div>
                </div>
                <div class="flex items-center gap-3 p-3">
                  <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                  <div class="flex-1">
                    <p class="text-sm text-slate-700 dark:text-slate-300">Salle mise à jour</p>
                    <p class="text-xs text-slate-500 dark:text-slate-500">Il y a 1 heure</p>
                  </div>
                </div>
                <div class="flex items-center gap-3 p-3">
                  <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                  <div class="flex-1">
                    <p class="text-sm text-slate-700 dark:text-slate-300">Événement créé</p>
                    <p class="text-xs text-slate-500 dark:text-slate-500">Il y a 2 heures</p>
                  </div>
                </div>
              </div>
            </div>
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
        class="bg-white dark:bg-slate-850 rounded-lg shadow-xl p-6 w-96"
        @click.stop
      >
        <div class="text-center mb-4">
          <i class="fas fa-sign-out-alt text-red-600 text-2xl mb-3"></i>
          <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">
            Confirmer la déconnexion
          </h4>
          <p class="text-sm text-slate-600 dark:text-slate-400">
            Êtes-vous sûr de vouloir vous déconnecter ?
          </p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="showLogoutModal = false"
            class="flex-1 bg-slate-200 text-slate-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-slate-300 transition-colors"
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

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted } from 'vue'

const page = usePage()
const searchQuery = ref('')
const showUserMenu = ref(false)
const isDarkMode = ref(false)
const showMobileMenu = ref(false) // S'assurer que c'est bien false par défaut
const showDashboardModal = ref(false) // Modal du dashboard

const unreadNotifications = computed(() => {
  return page.props.unreadNotifications || 0
})

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    // Rediriger vers la page de recherche globale
    router.get(route('admin.search'), { q: searchQuery.value })
  }
}

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value
  document.documentElement.classList.toggle('dark', isDarkMode.value)
  localStorage.setItem('darkMode', isDarkMode.value)
}

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value
}

const showLogoutModal = ref(false)

const logout = () => {
  showLogoutModal.value = true
}

const confirmLogout = () => {
  router.post(route('logout'))
}

// Fonctions pour le menu mobile
const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value
}

const closeMobileMenu = () => {
  showMobileMenu.value = false
}

// Fonctions pour le modal dashboard
const openDashboardModal = () => {
  showDashboardModal.value = true
  document.body.style.overflow = 'hidden' // Empêcher le scroll en arrière-plan
}

const closeDashboardModal = () => {
  showDashboardModal.value = false
  document.body.style.overflow = 'auto' // Réactiver le scroll
}

const closeMenus = (e) => {
  // Close user menu if clicking outside
  if (!e.target.closest('.relative')) {
    showUserMenu.value = false
  }
  
  // Close mobile menu if clicking outside on mobile (but not on hamburger button)
  if (window.innerWidth < 1024 && !e.target.closest('aside') && !e.target.closest('[data-mobile-menu-toggle]')) {
    showMobileMenu.value = false
  }
}

const handleKeydown = (e) => {
  // Ctrl+K for search
  if (e.ctrlKey && e.key === 'k') {
    e.preventDefault()
    const searchInput = document.querySelector('input[placeholder="Rechercher (Ctrl+K)"]')
    if (searchInput) {
      searchInput.focus()
    }
  }
  
  // Escape to close mobile menu
  if (e.key === 'Escape' && showMobileMenu.value) {
    showMobileMenu.value = false
  }
  
  // Escape to close dashboard modal
  if (e.key === 'Escape' && showDashboardModal.value) {
    closeDashboardModal()
  }
}

const handleResize = () => {
  if (window.innerWidth >= 1024) {
    showMobileMenu.value = false
  }
}

// Initialiser le mode sombre
onMounted(() => {
  const savedDarkMode = localStorage.getItem('darkMode')
  isDarkMode.value = savedDarkMode === 'true'
  document.documentElement.classList.toggle('dark', isDarkMode.value)
  
  // Make toggleMobileMenu available globally
  window.toggleMobileMenu = toggleMobileMenu;
  
  // Écouter les clics pour fermer les menus
  document.addEventListener('click', closeMenus)
  
  // Handle resize
  window.addEventListener('resize', handleResize)
  
  // Raccourci clavier pour la recherche (Ctrl+K)
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  document.removeEventListener('click', closeMenus)
  window.removeEventListener('resize', handleResize)
  document.removeEventListener('keydown', handleKeydown)
})
</script>

<style scoped>
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
  font-size: 24px;
}

.icon-fill {
  font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

.icon-sm {
  font-size: 20px;
}

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
