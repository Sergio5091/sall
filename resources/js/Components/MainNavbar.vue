<template>
  <!-- Navigation -->
  <nav class="absolute top-0 left-0 w-full z-50 transition-all duration-300 bg-black/20 backdrop-blur-sm">
    <div class="max-w-[1320px] mx-auto px-6 h-20 flex items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center gap-2">
        <div class="size-8 flex items-center justify-center bg-white rounded-lg text-primary">
          <span class="material-symbols-outlined text-[24px]">stadia_controller</span>
        </div>
        <h1 class="text-white text-xl font-bold tracking-tight">YOUPIHUB</h1>
      </div>
      
      <!-- Desktop Menu -->
      <div class="hidden md:flex items-center gap-10">
        <a class="text-white/90 hover:text-white text-sm font-medium transition-colors" href="/">Accueil</a>
        <a class="text-white/70 hover:text-white text-sm font-medium transition-colors" href="/search/rooms">Salles</a>
        <a class="text-white/70 hover:text-white text-sm font-medium transition-colors" href="/events">Événements</a>
        <a class="text-white/70 hover:text-white text-sm font-medium transition-colors" href="/products">Boutique</a>
        <a class="text-white/70 hover:text-white text-sm font-medium transition-colors" href="/about">À propos</a>
      </div>
      
      <!-- Desktop Right Actions -->
      <div class="hidden md:flex items-center gap-4">
        <a href="/login" class="text-white hover:text-white/80 text-sm font-semibold px-4 py-2 transition-colors">
          Connexion
        </a>
        <a href="/register" class="flex items-center justify-center rounded-full bg-primary hover:bg-blue-600 text-white text-sm font-bold px-6 py-2.5 transition-all shadow-lg shadow-primary/20">
          Inscription
        </a>
      </div>

      <!-- Mobile Menu Button -->
      <button 
        @click="isMobileMenuOpen = !isMobileMenuOpen"
        class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-colors"
      >
        <span class="material-symbols-outlined text-2xl text-white">
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
      <div class="py-4 border-t border-white/20">
        <div class="flex flex-col gap-4">
          <a href="/" class="text-white/90 hover:text-white text-sm font-medium transition-colors py-2 text-center">Accueil</a>
          <a href="/search/rooms" class="text-white/70 hover:text-white text-sm font-medium transition-colors py-2 text-center">Salles</a>
          <a href="/events" class="text-white/70 hover:text-white text-sm font-medium transition-colors py-2 text-center">Événements</a>
          <a href="/products" class="text-white/70 hover:text-white text-sm font-medium transition-colors py-2 text-center">Boutique</a>
          <a href="/about" class="text-white/70 hover:text-white text-sm font-medium transition-colors py-2 text-center">À propos</a>
          <div class="flex gap-3 pt-4 border-t border-white/20">
            <a href="/login" class="flex-1 text-center text-white hover:text-white/80 text-sm font-semibold py-2 border border-white/20 rounded-lg">Connexion</a>
            <a href="/register" class="flex-1 text-center bg-primary hover:bg-blue-600 text-white text-sm font-bold py-2 rounded-lg transition-all shadow-lg shadow-primary/20">Inscription</a>
          </div>
        </div>
      </div>
    </div>
  </nav>
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
}

.text-primary {
  color: var(--primary);
}

.bg-primary {
  background-color: var(--primary);
}

/* Material Symbols */
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
