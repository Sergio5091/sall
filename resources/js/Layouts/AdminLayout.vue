<template>
  <div class="flex h-screen w-full">
    <!-- Mobile Menu Overlay -->
    <div v-if="showMobileMenu" class="fixed inset-0 bg-black/50 z-40 lg:hidden" @click="toggleMobileMenu"></div>
    
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
        <Link
          :href="route('admin.dashboard')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group"
          :class="{ 'bg-primary/10 text-primary dark:text-blue-400': route().current('admin.dashboard') }"
        >
          <i class="fas fa-tachometer-alt group-hover:text-primary transition-colors"></i>
          <span class="text-sm font-medium">Tableau de bord</span>
        </Link>
        
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
        
        <div class="my-2 border-t border-slate-100 dark:border-slate-800"></div>
        
        <Link
          :href="route('admin.settings')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group"
          :class="{ 'bg-primary/10 text-primary dark:text-blue-400': route().current('admin.settings') }"
        >
          <i class="fas fa-cog group-hover:text-primary transition-colors"></i>
          <span class="text-sm font-medium">Paramètres</span>
        </Link>
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
            @click="toggleMobileMenu"
            class="text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 lg:hidden"
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
  </div>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted } from 'vue'

const page = usePage()
const searchQuery = ref('')
const showUserMenu = ref(false)
const isDarkMode = ref(false)
const showMobileMenu = ref(false)

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

const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value
}

const logout = () => {
  router.post(route('logout'))
}

const closeMenus = (e) => {
  // Close user menu if clicking outside
  if (!e.target.closest('.relative')) {
    showUserMenu.value = false
  }
  
  // Close mobile menu if clicking outside on mobile
  if (window.innerWidth < 1024 && !e.target.closest('aside') && !e.target.closest('button')) {
    showMobileMenu.value = false
  }
}

// Initialiser le mode sombre
onMounted(() => {
  const savedDarkMode = localStorage.getItem('darkMode')
  isDarkMode.value = savedDarkMode === 'true'
  document.documentElement.classList.toggle('dark', isDarkMode.value)
  
  // Écouter les clics pour fermer les menus
  document.addEventListener('click', closeMenus)
  
  // Handle resize
  const handleResize = () => {
    if (window.innerWidth >= 1024) {
      showMobileMenu.value = false
    }
  }
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  document.removeEventListener('click', closeMenus)
  window.removeEventListener('resize', handleResize)
})

// Raccourci clavier pour la recherche (Ctrl+K)
const handleKeydown = (e) => {
  if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
    e.preventDefault()
    document.querySelector('input[placeholder*="Rechercher"]')?.focus()
  }
  
  // ESC to close mobile menu
  if (e.key === 'Escape') {
    showMobileMenu.value = false
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
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
