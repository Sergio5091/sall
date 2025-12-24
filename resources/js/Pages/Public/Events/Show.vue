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
            <a class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors" href="/search/rooms">Salles</a>
            <a class="text-primary hover:text-primary text-sm font-medium transition-colors" href="/events">Événements</a>
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

    <!-- Event Details -->
    <main class="pt-20">
      <!-- Hero Section with Image -->
      <section class="relative h-96 bg-gray-200 overflow-hidden">
        <img 
          :src="event.image || `https://picsum.photos/seed/event-${event.id}/1200/400.jpg`" 
          :alt="event.name"
          class="w-full h-full object-cover"
        />
        
        <!-- Back Button -->
        <div class="absolute top-24 left-6">
          <a 
            href="/events" 
            class="bg-white/90 backdrop-blur-sm p-3 rounded-xl hover:bg-white transition-all shadow-lg"
          >
            <span class="material-symbols-outlined text-soft-black">arrow_back</span>
          </a>
        </div>
        
        <!-- Status Badge -->
        <div class="absolute top-24 right-6">
          <span 
            :class="[
              'px-4 py-2 rounded-full text-sm font-bold',
              event.status === 'upcoming' ? 'bg-green-100 text-green-800' :
              event.status === 'ongoing' ? 'bg-yellow-100 text-yellow-800' :
              event.status === 'completed' ? 'bg-gray-100 text-gray-800' :
              'bg-gray-100 text-gray-800'
            ]"
          >
            {{ getStatusText(event.status) }}
          </span>
        </div>
        
        <!-- Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
        
        <!-- Event Name Overlay -->
        <div class="absolute bottom-0 left-0 right-0 p-6">
          <div class="max-w-[1320px] mx-auto">
            <h1 class="text-white text-4xl font-black mb-2">{{ event.name }}</h1>
            <div class="flex items-center gap-4 text-white/90">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">event</span>
                <span>{{ formatDate(event.date) }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">location_on</span>
                <span>{{ event.location }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">sports_esports</span>
                <span>{{ event.game_type }}</span>
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
                  {{ event.description || 'Rejoignez-nous pour cet événement gaming exceptionnel ! Une expérience inoubliable vous attend avec des compétitions intenses, des prix à gagner et une communauté passionnée.' }}
                </p>
              </div>

              <!-- Event Details -->
              <div class="bg-white rounded-2xl p-8 shadow-sm mb-8">
                <h2 class="text-2xl font-bold text-soft-black mb-6">Détails de l'événement</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <span class="material-symbols-outlined text-2xl text-primary">event</span>
                    <div>
                      <p class="text-sm text-medium-grey">Date et heure</p>
                      <p class="text-soft-black font-medium">{{ formatDate(event.date) }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <span class="material-symbols-outlined text-2xl text-primary">location_on</span>
                    <div>
                      <p class="text-sm text-medium-grey">Lieu</p>
                      <p class="text-soft-black font-medium">{{ event.location }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <span class="material-symbols-outlined text-2xl text-primary">sports_esports</span>
                    <div>
                      <p class="text-sm text-medium-grey">Type de jeu</p>
                      <p class="text-soft-black font-medium">{{ event.game_type }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <span class="material-symbols-outlined text-2xl text-primary">groups</span>
                    <div>
                      <p class="text-sm text-medium-grey">Participants</p>
                      <p class="text-soft-black font-medium">{{ event.current_participants }}/{{ event.max_participants }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Prize Pool -->
              <div v-if="event.prize_pool" class="bg-white rounded-2xl p-8 shadow-sm mb-8">
                <h2 class="text-2xl font-bold text-soft-black mb-6">Cagnotte</h2>
                <div class="flex items-center gap-4 p-6 bg-yellow-50 rounded-lg border border-yellow-200">
                  <span class="material-symbols-outlined text-4xl text-yellow-600">emoji_events</span>
                  <div>
                    <p class="text-3xl font-bold text-yellow-600">{{ event.prize_pool }}€</p>
                    <p class="text-medium-grey">À gagner</p>
                  </div>
                </div>
              </div>

              <!-- Participants -->
              <div class="bg-white rounded-2xl p-8 shadow-sm mb-8">
                <h2 class="text-2xl font-bold text-soft-black mb-6">Participants</h2>
                <div class="mb-4">
                  <div class="flex items-center justify-between mb-2">
                    <span class="text-medium-grey">Places disponibles</span>
                    <span class="text-soft-black font-medium">{{ event.max_participants - event.current_participants }}/{{ event.max_participants }}</span>
                  </div>
                  <div class="w-full bg-gray-200 rounded-full h-3">
                    <div 
                      class="bg-primary h-3 rounded-full transition-all duration-300"
                      :style="{ width: `${(event.current_participants / event.max_participants) * 100}%` }"
                    ></div>
                  </div>
                </div>
                <div class="flex flex-wrap gap-2">
                  <div v-for="i in Math.min(event.current_participants, 8)" :key="i" class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                    <span class="text-xs font-medium text-soft-black">P{{ i }}</span>
                  </div>
                  <div v-if="event.current_participants > 8" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <span class="text-xs font-medium text-medium-grey">+{{ event.current_participants - 8 }}</span>
                  </div>
                </div>
              </div>

              <!-- Location Map -->
              <div class="bg-white rounded-2xl p-8 shadow-sm">
                <h2 class="text-2xl font-bold text-soft-black mb-6">Localisation</h2>
                <div class="bg-gray-100 rounded-xl h-64 flex items-center justify-center">
                  <div class="text-center">
                    <span class="material-symbols-outlined text-4xl text-medium-grey mb-2">map</span>
                    <p class="text-medium-grey">Carte interactive bientôt disponible</p>
                  </div>
                </div>
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                  <p class="text-soft-black font-medium">{{ event.location }}</p>
                </div>
              </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
              <div class="bg-white rounded-2xl p-8 shadow-sm sticky top-24">
                <!-- Price -->
                <div class="text-center mb-8">
                  <div class="text-4xl font-black text-primary mb-2">
                    {{ formatPrice(event.price) }}
                  </div>
                  <div class="text-medium-grey">par participant</div>
                </div>

                <!-- Registration Status -->
                <div class="mb-8">
                  <div v-if="event.status === 'upcoming' && event.current_participants < event.max_participants" class="p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center gap-2 text-green-800 mb-2">
                      <span class="material-symbols-outlined">check_circle</span>
                      <span class="font-medium">Inscriptions ouvertes</span>
                    </div>
                    <p class="text-sm text-green-700">{{ event.max_participants - event.current_participants }} places disponibles</p>
                  </div>
                  <div v-else-if="event.status === 'ongoing'" class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-center gap-2 text-yellow-800 mb-2">
                      <span class="material-symbols-outlined">event_busy</span>
                      <span class="font-medium">Événement en cours</span>
                    </div>
                    <p class="text-sm text-yellow-700">Les inscriptions sont fermées</p>
                  </div>
                  <div v-else-if="event.status === 'completed'" class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-2 text-gray-800 mb-2">
                      <span class="material-symbols-outlined">event_available</span>
                      <span class="font-medium">Événement terminé</span>
                    </div>
                    <p class="text-sm text-gray-700">Cet événement est terminé</p>
                  </div>
                  <div v-else class="p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="flex items-center gap-2 text-red-800 mb-2">
                      <span class="material-symbols-outlined">event_busy</span>
                      <span class="font-medium">Complet</span>
                    </div>
                    <p class="text-sm text-red-700">Toutes les places sont prises</p>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4 mb-8">
                  <button 
                    v-if="event.status === 'upcoming' && event.current_participants < event.max_participants"
                    @click="registerForEvent"
                    class="w-full bg-primary hover:bg-blue-600 text-white py-4 rounded-xl font-bold text-lg transition-all shadow-lg shadow-primary/20"
                  >
                    S'inscrire maintenant
                  </button>
                  <button 
                    @click="contactOrganizer"
                    class="w-full border-2 border-gray-200 text-soft-black py-4 rounded-xl font-bold text-lg hover:border-primary hover:text-primary transition-all"
                  >
                    Contacter l'organisateur
                  </button>
                </div>

                <!-- Event Info -->
                <div class="border-t border-gray-100 pt-6">
                  <h3 class="font-bold text-soft-black mb-4">Informations</h3>
                  <div class="space-y-3">
                    <div class="flex items-center gap-3 text-medium-grey">
                      <span class="material-symbols-outlined text-primary">event</span>
                      <span>{{ formatDate(event.date) }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-medium-grey">
                      <span class="material-symbols-outlined text-primary">groups</span>
                      <span>{{ event.current_participants }}/{{ event.max_participants }} participants</span>
                    </div>
                    <div class="flex items-center gap-3 text-medium-grey">
                      <span class="material-symbols-outlined text-primary">sports_esports</span>
                      <span>{{ event.game_type }}</span>
                    </div>
                    <div v-if="event.prize_pool" class="flex items-center gap-3 text-medium-grey">
                      <span class="material-symbols-outlined text-primary">emoji_events</span>
                      <span>{{ event.prize_pool }}€ de cagnotte</span>
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
  event: Object
});

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const getStatusText = (status) => {
  switch (status) {
    case 'upcoming': return 'À venir';
    case 'ongoing': return 'En cours';
    case 'completed': return 'Terminé';
    default: return status;
  }
};

const formatPrice = (prix) => {
  if (!prix) return 'Gratuit';
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
    minimumFractionDigits: 0
  }).format(prix);
};

const registerForEvent = () => {
  window.location.href = '/login';
};

const contactOrganizer = () => {
  // Implémenter la fonctionnalité de contact
  console.log('Contacter l\'organisateur');
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
