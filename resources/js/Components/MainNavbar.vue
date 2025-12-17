<template>
  <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center backdrop-blur-sm shadow-sm bg-white/90">
    <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
      <div class="flex items-center gap-8">
        <div class="flex items-center gap-2 text-gray-800">
          <i class="fas fa-gamepad text-3xl text-gray-400"></i>
          <h2 class="text-gray-800 text-2xl font-display font-bold">GameOn</h2>
        </div>
        <nav class="hidden md:flex space-x-8">
          <a href="/" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Accueil</a>
          <a href="/salles" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Salles</a>
          <a href="/events" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Événements</a>
        </nav>
      </div>
      <div class="flex items-center gap-3">
        <a href="/login" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-200 text-gray-800 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-300 transition-colors">
          <span class="truncate">Connexion</span>
        </a>
        <a href="/register" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-800 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-700 transition-all">
          <span class="truncate">S'inscrire</span>
        </a>
      </div>
    </div>
  </header>
</template>

<script setup>
// Pas de logique nécessaire pour ce composant statique
</script>

<style scoped>
/* Styles additionnels si nécessaire */
</style>
