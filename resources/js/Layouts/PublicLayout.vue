<template>
  <div class="public-layout bg-white text-soft-black font-display antialiased selection:bg-primary/20 selection:text-primary">
    <!-- Page Transition Wrapper -->
    <PageTransition>
      <!-- Navigation -->
      <nav class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-white/95 backdrop-blur-sm border-b border-gray-100">
        <div class="max-w-[1320px] mx-auto px-6 h-20 flex items-center justify-between">
          <!-- Logo -->
          <Link href="/" class="flex items-center gap-2 group">
            <div class="size-8 flex items-center justify-center bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg text-white transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">
              <i class="fas fa-gamepad text-[20px]"></i>
            </div>
            <h1 class="text-soft-black text-xl font-bold tracking-tight transform transition-all duration-300 group-hover:text-primary">YOUPIHUB</h1>
          </Link>
          
          <!-- Desktop Menu -->
          <div class="hidden md:flex items-center gap-10">
            <Link 
              href="/" 
              :class="[
                'text-sm font-medium transition-all duration-300 relative group',
                $page.url === '/' ? 'text-primary' : 'text-medium-grey hover:text-soft-black'
              ]"
            >
              Accueil
              <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
            </Link>
            <Link 
              href="/search/rooms" 
              :class="[
                'text-sm font-medium transition-all duration-300 relative group',
                $page.url.startsWith('/search') ? 'text-primary' : 'text-medium-grey hover:text-soft-black'
              ]"
            >
              Salles
              <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
            </Link>
            <Link 
              href="/events" 
              :class="[
                'text-sm font-medium transition-all duration-300 relative group',
                $page.url === '/events' ? 'text-primary' : 'text-medium-grey hover:text-soft-black'
              ]"
            >
              Événements
              <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
            </Link>
            <Link 
              href="/products" 
              :class="[
                'text-sm font-medium transition-all duration-300 relative group',
                $page.url === '/products' ? 'text-primary' : 'text-medium-grey hover:text-soft-black'
              ]"
            >
              Boutique
              <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
            </Link>
            <Link 
              href="/about" 
              :class="[
                'text-sm font-medium transition-all duration-300 relative group',
                $page.url === '/about' ? 'text-primary' : 'text-medium-grey hover:text-soft-black'
              ]"
            >
              À propos
              <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
            </Link>
          </div>
          
          <!-- Desktop Right Actions -->
          <div class="hidden md:flex items-center gap-4">
            <Link 
              v-if="!isLoggedIn"
              href="/login" 
              class="text-medium-grey hover:text-soft-black text-sm font-semibold px-4 py-2 rounded-lg border border-gray-200 hover:border-primary hover:text-primary transition-all duration-300 transform hover:scale-105"
            >
              Connexion
            </Link>
            <Link 
              v-if="!isLoggedIn"
              href="/register" 
              class="bg-primary hover:bg-primary/90 text-white text-sm font-semibold px-6 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
            >
              S'inscrire
            </Link>
            
            <!-- User Menu (if logged in) -->
            <div v-if="isLoggedIn" class="flex items-center gap-3">
              <Link 
                href="/client/dashboard" 
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-all duration-300"
              >
                <i class="fas fa-user text-gray-600"></i>
                <span class="text-sm font-medium text-gray-700">Mon espace</span>
              </Link>
            </div>
          </div>
          
          <!-- Mobile Menu Button -->
          <button 
            @click="toggleMobileMenu"
            class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 transition-all duration-300"
          >
            <i :class="isMobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-gray-700"></i>
          </button>
        </div>
        
        <!-- Mobile Menu -->
        <Transition name="mobile-menu">
          <div v-if="isMobileMenuOpen" class="md:hidden bg-white border-t border-gray-100">
            <div class="px-6 py-4 space-y-3">
              <Link 
                href="/" 
                class="block py-2 text-sm font-medium transition-colors"
                :class="$page.url === '/' ? 'text-primary' : 'text-medium-grey hover:text-soft-black'"
                @click="closeMobileMenu"
              >
                Accueil
              </Link>
              <Link 
                href="/search/rooms" 
                class="block py-2 text-sm font-medium transition-colors"
                :class="$page.url.startsWith('/search') ? 'text-primary' : 'text-medium-grey hover:text-soft-black'"
                @click="closeMobileMenu"
              >
                Salles
              </Link>
              <Link 
                href="/events" 
                class="block py-2 text-sm font-medium transition-colors"
                :class="$page.url === '/events' ? 'text-primary' : 'text-medium-grey hover:text-soft-black'"
                @click="closeMobileMenu"
              >
                Événements
              </Link>
              <Link 
                href="/products" 
                class="block py-2 text-sm font-medium transition-colors"
                :class="$page.url === '/products' ? 'text-primary' : 'text-medium-grey hover:text-soft-black'"
                @click="closeMobileMenu"
              >
                Boutique
              </Link>
              <Link 
                href="/about" 
                class="block py-2 text-sm font-medium transition-colors"
                :class="$page.url === '/about' ? 'text-primary' : 'text-medium-grey hover:text-soft-black'"
                @click="closeMobileMenu"
              >
                À propos
              </Link>
              
              <div v-if="!isLoggedIn" class="pt-4 border-t border-gray-100 space-y-3">
                <Link 
                  href="/login" 
                  class="block w-full text-center py-2 text-sm font-medium text-medium-grey hover:text-soft-black border border-gray-200 rounded-lg hover:border-primary hover:text-primary transition-all duration-300"
                  @click="closeMobileMenu"
                >
                  Connexion
                </Link>
                <Link 
                  href="/register" 
                  class="block w-full text-center py-2 text-sm font-medium bg-primary hover:bg-primary/90 text-white rounded-lg transition-all duration-300"
                  @click="closeMobileMenu"
                >
                  S'inscrire
                </Link>
              </div>
              
              <div v-else class="pt-4 border-t border-gray-100">
                <Link 
                  href="/client/dashboard" 
                  class="block w-full text-center py-2 text-sm font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all duration-300"
                  @click="closeMobileMenu"
                >
                  Mon espace
                </Link>
              </div>
            </div>
          </div>
        </Transition>
      </nav>

      <!-- Main Content -->
      <main class="pt-20">
        <slot />
      </main>

      <!-- Footer -->
      <MainFooter />
    </PageTransition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import PageTransition from '@/Components/PageTransition.vue'
import MainFooter from '@/Components/MainFooter.vue'

const page = usePage()
const isMobileMenuOpen = ref(false)

// Check if user is logged in
const isLoggedIn = computed(() => !!page.props.auth?.user)

// Mobile menu functions
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
}

// Close mobile menu on escape key
const handleEscape = (e) => {
  if (e.key === 'Escape' && isMobileMenuOpen.value) {
    closeMobileMenu()
  }
}

// Close mobile menu on resize
const handleResize = () => {
  if (window.innerWidth >= 768 && isMobileMenuOpen.value) {
    closeMobileMenu()
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleEscape)
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleEscape)
  window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
/* Mobile menu animation */
.mobile-menu-enter-active,
.mobile-menu-leave-active {
  transition: all 0.3s ease;
}

.mobile-menu-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}

.mobile-menu-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Custom scrollbar */
.public-layout ::-webkit-scrollbar {
  width: 8px;
}

.public-layout ::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.public-layout ::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

.public-layout ::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
