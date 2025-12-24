<template>
  <!-- Navigation -->
  <header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100">
    <div class="max-w-[1320px] mx-auto px-6 py-4">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold">GB</span>
          </div>
          <h1 class="text-soft-black text-xl font-bold tracking-tight">GameBook</h1>
        </div>
        
        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-10">
          <a class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors" href="/">Accueil</a>
          <a class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors" href="/search/rooms">Salles</a>
          <a class="text-primary hover:text-primary text-sm font-medium transition-colors" href="/events">Événements</a>
        </div>
        
        <!-- Desktop Auth Buttons -->
        <div class="hidden md:flex items-center gap-3">
          <a href="/login" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-soft-black text-sm font-medium rounded-lg transition-colors">Connexion</a>
          <a href="/register" class="flex items-center justify-center rounded-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-2.5 transition-all shadow-lg shadow-blue-600/20">
            Inscription
          </a>
        </div>

        <!-- Mobile Menu Button -->
        <button 
          @click="isMobileMenuOpen = !isMobileMenuOpen"
          class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
        >
          <span class="material-symbols-outlined text-2xl text-soft-black">
            {{ isMobileMenuOpen ? 'close' : 'menu' }}
          </span>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div 
        :class="[
          'md:hidden transition-all duration-300 overflow-hidden',
          isMobileMenuOpen ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'
        ]"
      >
        <div class="py-4 border-t border-gray-100">
          <div class="flex flex-col gap-4">
            <a href="/" class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors py-2">Accueil</a>
            <a href="/search/rooms" class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors py-2">Salles</a>
            <a href="/events" class="text-primary hover:text-primary text-sm font-medium transition-colors py-2">Événements</a>
            <div class="flex gap-3 pt-4 border-t border-gray-100">
              <a href="/login" class="flex-1 text-center text-medium-grey hover:text-soft-black text-sm font-medium transition-colors py-2 border border-gray-200 rounded-lg">Connexion</a>
              <a href="/register" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 rounded-lg transition-all shadow-lg shadow-blue-600/20">Inscription</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue';

const isMobileMenuOpen = ref(false);
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap');

/* Tailwind custom colors */
:root {
  --primary: #135bec;
  --soft-black: #111827;
  --medium-grey: #6B7280;
}

.text-primary {
  color: var(--primary);
}

.bg-primary {
  background-color: var(--primary);
}

.text-soft-black {
  color: var(--soft-black);
}

.text-medium-grey {
  color: var(--medium-grey);
}

/* Material Symbols */
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
