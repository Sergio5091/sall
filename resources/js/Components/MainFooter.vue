<template>
  <!-- Footer -->
  <footer class="bg-white border-t border-gray-100 py-12">
    <div class="max-w-[1320px] mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
      <div class="flex items-center gap-2">
        <div class="size-6 flex items-center justify-center bg-soft-black rounded-md text-white">
          <span class="material-symbols-outlined text-[16px]">stadia_controller</span>
        </div>
        <span class="text-soft-black font-bold tracking-tight">GameBook</span>
      </div>
      <div class="flex gap-8 text-sm text-medium-grey font-medium">
        <a class="hover:text-primary transition-colors" href="#">À propos</a>
        <a class="hover:text-primary transition-colors" href="/search/rooms">Salles</a>
        <a class="hover:text-primary transition-colors" href="#">Contact</a>
        <a class="hover:text-primary transition-colors" href="#">CGU</a>
      </div>
      <div class="text-sm text-gray-400">
        2024 GameBook. Tous droits réservés.
      </div>
    </div>
  </footer>
</template>

<script setup>
// Pas de logique nécessaire pour ce composant statique
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

.text-soft-black {
  color: var(--soft-black);
}

.text-medium-grey {
  color: var(--medium-grey);
}

.bg-soft-black {
  background-color: var(--soft-black);
}

/* Material Symbols */
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
