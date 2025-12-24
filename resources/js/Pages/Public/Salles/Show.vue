<template>
  <div class="min-h-screen bg-gray-50">
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
          
          <!-- Central Menu -->
          <div class="hidden md:flex items-center gap-10">
            <a class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors" href="/">Accueil</a>
            <a class="text-primary hover:text-primary text-sm font-medium transition-colors" href="/search/rooms">Salles</a>
            <a class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors" href="/events">Événements</a>
          </div>
          
          <!-- Auth Buttons -->
          <div class="flex items-center gap-3">
            <a href="/login" class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors">Connexion</a>
            <a href="/register" class="flex items-center justify-center rounded-full bg-primary hover:bg-blue-600 text-white text-sm font-bold px-6 py-2.5 transition-all shadow-lg shadow-primary/20">
              Inscription
            </a>
          </div>
        </div>
      </div>
    </header>

    <!-- Room Details -->
    <main class="pt-20">
      <!-- Hero Section with Image -->
      <section class="relative h-96 bg-gray-200 overflow-hidden">
        <img 
          :src="salle.image || `https://picsum.photos/seed/room-${salle.id}/1200/400.jpg`" 
          :alt="salle.nom"
          class="w-full h-full object-cover"
        />
        
        <!-- Back Button -->
        <div class="absolute top-24 left-6">
          <a 
            href="/search/rooms" 
            class="bg-white/90 backdrop-blur-sm p-3 rounded-xl hover:bg-white transition-all shadow-lg"
          >
            <span class="material-symbols-outlined text-soft-black">arrow_back</span>
          </a>
        </div>
        
        <!-- Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
        
        <!-- Room Name Overlay -->
        <div class="absolute bottom-0 left-0 right-0 p-6">
          <div class="max-w-[1320px] mx-auto">
            <h1 class="text-white text-4xl font-black mb-2">{{ salle.nom }}</h1>
            <div class="flex items-center gap-4 text-white/90">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">location_on</span>
                <span>{{ salle.adresse }}, {{ salle.ville }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">groups</span>
                <span>{{ salle.capacite_max || 10 }} personnes</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Content Section -->
      <section class="py-12">
        <div class="max-w-[1320px] mx-auto px-6">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
              <!-- Description -->
              <div class="bg-white rounded-2xl p-8 shadow-sm mb-8">
                <h2 class="text-2xl font-bold text-soft-black mb-4">Description</h2>
                <p class="text-medium-grey leading-relaxed">
                  {{ salle.description || 'Découvrez cette salle de gaming exceptionnelle, parfaitement équipée pour vos événements et sessions de jeu.' }}
                </p>
              </div>

              <!-- Equipment -->
              <div class="bg-white rounded-2xl p-8 shadow-sm mb-8">
                <h2 class="text-2xl font-bold text-soft-black mb-6">Équipements</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                  <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <span class="material-symbols-outlined text-primary">wifi</span>
                    <span class="text-soft-black font-medium">WiFi</span>
                  </div>
                  <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <span class="material-symbols-outlined text-primary">ac_unit</span>
                    <span class="text-soft-black font-medium">Climatisation</span>
                  </div>
                  <div v-if="salle.parking" class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <span class="material-symbols-outlined text-primary">local_parking</span>
                    <span class="text-soft-black font-medium">Parking</span>
                  </div>
                  <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <span class="material-symbols-outlined text-primary">devices</span>
                    <span class="text-soft-black font-medium">Équipements gaming</span>
                  </div>
                  <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <span class="material-symbols-outlined text-primary">tv</span>
                    <span class="text-soft-black font-medium">Écrans</span>
                  </div>
                  <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <span class="material-symbols-outlined text-primary">chair</span>
                    <span class="text-soft-black font-medium">Sièges confortables</span>
                  </div>
                </div>
              </div>

              <!-- Location -->
              <div class="bg-white rounded-2xl p-8 shadow-sm">
                <h2 class="text-2xl font-bold text-soft-black mb-6">Localisation</h2>
                <div class="bg-gray-100 rounded-xl h-64 flex items-center justify-center">
                  <div class="text-center">
                    <span class="material-symbols-outlined text-4xl text-medium-grey mb-2">map</span>
                    <p class="text-medium-grey">Carte interactive bientôt disponible</p>
                  </div>
                </div>
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                  <p class="text-soft-black font-medium">{{ salle.adresse }}</p>
                  <p class="text-medium-grey">{{ salle.ville }}</p>
                </div>
              </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
              <div class="bg-white rounded-2xl p-8 shadow-sm sticky top-24">
                <!-- Price -->
                <div class="text-center mb-8">
                  <div class="text-4xl font-black text-primary mb-2">
                    {{ formatPrice(salle.prix_heure) }}
                  </div>
                  <div class="text-medium-grey">par heure</div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4 mb-8">
                  <button 
                    @click="reserveRoom"
                    class="w-full bg-primary hover:bg-blue-600 text-white py-4 rounded-xl font-bold text-lg transition-all shadow-lg shadow-primary/20"
                  >
                    Réserver maintenant
                  </button>
                  <button 
                    @click="contactOwner"
                    class="w-full border-2 border-gray-200 text-soft-black py-4 rounded-xl font-bold text-lg hover:border-primary hover:text-primary transition-all"
                  >
                    Contacter le propriétaire
                  </button>
                </div>

                <!-- Contact Info -->
                <div class="border-t border-gray-100 pt-6">
                  <h3 class="font-bold text-soft-black mb-4">Informations</h3>
                  <div class="space-y-3">
                    <div class="flex items-center gap-3 text-medium-grey">
                      <span class="material-symbols-outlined text-primary">schedule</span>
                      <span>Disponible 24/7</span>
                    </div>
                    <div class="flex items-center gap-3 text-medium-grey">
                      <span class="material-symbols-outlined text-primary">groups</span>
                      <span>Jusqu'à {{ salle.capacite_max || 10 }} personnes</span>
                    </div>
                    <div v-if="salle.parking" class="flex items-center gap-3 text-medium-grey">
                      <span class="material-symbols-outlined text-primary">local_parking</span>
                      <span>Parking disponible</span>
                    </div>
                  </div>
                </div>

                <!-- Share -->
                <div class="border-t border-gray-100 pt-6 mt-6">
                  <h3 class="font-bold text-soft-black mb-4">Partager</h3>
                  <div class="flex gap-3">
                    <button class="w-10 h-10 bg-gray-100 hover:bg-primary hover:text-white rounded-lg flex items-center justify-center transition-all">
                      <span class="material-symbols-outlined text-sm">share</span>
                    </button>
                    <button class="w-10 h-10 bg-gray-100 hover:bg-primary hover:text-white rounded-lg flex items-center justify-center transition-all">
                      <span class="material-symbols-outlined text-sm">favorite</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  salle: Object
});

const formatPrice = (prix) => {
  if (!prix) return 'Gratuit';
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XOF',
    minimumFractionDigits: 0
  }).format(prix);
};

const reserveRoom = () => {
  window.location.href = `/salles/${props.salle.id}/reserver`;
};

const contactOwner = () => {
  // Implémenter la fonctionnalité de contact
  console.log('Contacter le propriétaire');
};
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap');

.font-display {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

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

.bg-soft-black {
  background-color: var(--soft-black);
}

/* Material Symbols */
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
