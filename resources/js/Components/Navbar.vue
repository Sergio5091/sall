<template>
  <nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center bg-background-dark/80 backdrop-blur-sm shadow-sm">
    <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
      <!-- Logo -->
      <div class="flex items-center gap-8">
        <Link :href="route('welcome')" class="flex items-center gap-2 text-white">
          <span class="material-icons-round text-3xl text-accent-cyan">sports_esports</span>
          <h2 class="text-white text-2xl font-display font-bold">GameOn</h2>
        </Link>
      </div>

      <!-- Barre de recherche (masquée sur mobile et sur les pages d'authentification) -->
      <div v-if="!['/login', '/register'].includes($page.url)" class="hidden md:flex flex-1 justify-center">
        <label class="flex flex-col w-full max-w-sm h-11">
          <div class="flex w-full flex-1 items-stretch rounded-full h-full">
            <div class="text-[#6b7280] flex bg-[#e5e7eb] items-center justify-center pl-4 rounded-l-full">
              <span class="material-symbols-outlined text-xl">search</span>
            </div>
            <input 
              class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden text-black focus:outline-0 focus:ring-0 border-none bg-[#e5e7eb] h-full placeholder:text-[#6b7280] px-4 rounded-r-full text-base font-normal leading-normal" 
              placeholder="Rechercher une salle, un jeu..." 
              v-model="searchQuery"
              @keyup.enter="searchRooms"
            />
          </div>
        </label>
      </div>
      <div v-else class="flex-1"></div> <!-- Espace vide pour maintenir l'alignement -->

      <!-- Boutons de connexion/inscription -->
      <div class="flex items-center gap-3">
        <Link 
          :href="route('login')" 
          class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-transparent border-2 border-white/20 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-white/10 transition-colors"
          :class="{ 'bg-white/10': $page.url.startsWith('/login') }"
        >
          <span class="truncate">Connexion</span>
        </Link>
        <Link 
          :href="route('register')" 
          class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors"
          style="background-color: var(--primary);"
        >
          <span class="truncate">S'inscrire</span>
        </Link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const searchQuery = ref('');

const searchRooms = () => {
  if (searchQuery.value.trim()) {
    // Implémentez la logique de recherche ici
  }
};
</script>
