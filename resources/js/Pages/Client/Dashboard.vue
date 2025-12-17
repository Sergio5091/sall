<script setup>
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import Navbar from '@/Components/Navbar.vue';

const props = defineProps({
  user: Object,
  stats: Object,
  activity: Array,
  newVenues: Array,
  upcoming: Array,
  nearby: Array,
  referral: String,
  notifications: Array,
});

const userName = computed(() => props.user?.name || 'Alex');
const nextReservations = computed(() => props.stats?.next_reservations ?? props.stats?.nextReservations ?? 2);
const invitationsPending = computed(() => props.stats?.invitations_pending ?? props.stats?.invitationsPending ?? 3);
const credits = computed(() => props.stats?.credits ?? 150);

const activityList = computed(() => (props.activity && props.activity.length) ? props.activity : [
  {
    type: 'event',
    title: 'Tournoi Super Smash',
    subtitle: "Rejoignez-nous pour une compétition amicale ce week-end ! De nombreux lots à gagner.",
    location: 'Pixel Play',
    distance: '2.5 km',
    image: 'https://picsum.photos/seed/tournament/800/600'
  }
]);

const newVenuesList = computed(() => (props.newVenues && props.newVenues.length) ? props.newVenues : [
  {
    title: 'VR Universe',
    subtitle: 'Découvrez nos nouvelles stations de réalité virtuelle avec les derniers titres du moment.',
    location: 'VR Universe',
    distance: '1.2 km',
    image: 'https://picsum.photos/seed/venue2/800/600'
  }
]);

const upcomingList = computed(() => (props.upcoming && props.upcoming.length) ? props.upcoming : [
  {
    dayShort: 'MAR',
    dayNum: '25',
    title: 'Soirée LAN à Neo Arcade',
    time: '20:00 - 23:00',
    avatars: 3
  }
]);

const nearbyList = computed(() => (props.nearby && props.nearby.length) ? props.nearby : []);
const referralLink = computed(() => props.referral || 'gamecenter.com/invite/alex123');
</script>

<template>
  <Head title="Tableau de bord Client" />
  
  <!-- Add Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet"/>
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden bg-background-light font-display">
    <!-- Header - Same as Welcome page but with client navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center backdrop-blur-sm shadow-sm">
      <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-2 text-black">
            <a href="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
              <i class="fas fa-gamepad text-3xl text-accent-cyan"></i>
              <h2 class="text-black text-2xl font-display font-bold">GameOn</h2>
            </a>
          </div>
          <!-- Client Navigation -->
          <nav class="hidden md:flex items-center gap-6">
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/dashboard">Dashboard</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/salles">Salles</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="#">Événements</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="#">Mes Réservations</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/profile">Profil</a>
          </nav>
        </div>
        <div class="hidden md:flex flex-1 justify-center">
          <label class="flex flex-col w-full max-w-sm h-11">
            <div class="flex w-full flex-1 items-stretch rounded-full h-full">
              <div class="text-[#6b7280] flex bg-[#e5e7eb] items-center justify-center pl-4 rounded-l-full">
                <i class="fas fa-search text-xl"></i>
              </div>
              <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden text-black focus:outline-0 focus:ring-0 border-none bg-[#e5e7eb] h-full placeholder:text-[#6b7280] px-4 rounded-r-full text-base font-normal leading-normal" placeholder="Rechercher une salle, un jeu..."/>
            </div>
          </label>
        </div>
        <div class="flex items-center gap-3">
          <button class="flex relative cursor-pointer items-center justify-center overflow-hidden rounded-full size-10 bg-[#e5e7eb] text-black gap-2">
            <i class="fas fa-bell"></i>
            <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
            </span>
          </button>
          <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 bg-gray-300"></div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="layout-container flex h-full grow flex-col pt-20">
      <div class="px-4 sm:px-8 lg:px-16 2xl:px-40 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col w-full max-w-screen-xl flex-1 gap-8">
          <!-- Welcome Section -->
          <div class="flex flex-wrap justify-between gap-4 items-center">
            <h1 class="text-4xl font-black leading-tight tracking-[-0.033em]">Bienvenue, {{ userName }}</h1>
            <div class="flex items-center gap-2">
              <button class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold rounded-full bg-subtle-light hover:bg-border-light">
                <i class="fas fa-calendar text-lg"></i>
                <span>Réserver une salle</span>
              </button>
              <button class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold rounded-full bg-subtle-light hover:bg-border-light">
                <i class="fas fa-user-plus text-lg"></i>
                <span>Inviter des amis</span>
              </button>
              <button class="hidden md:flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold rounded-full bg-subtle-light hover:bg-border-light">
                <i class="fas fa-history text-lg"></i>
                <span>Voir mon historique</span>
              </button>
            </div>
          </div>

          <!-- Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex flex-col justify-between gap-2 rounded-lg p-6 bg-content-light border border-border-light min-h-[120px]">
              <div>
                <p class="text-base font-medium">Prochaines Réservations</p>
                <p class="tracking-light text-3xl font-bold mt-2">{{ nextReservations }}</p>
              </div>
              <div class="text-xs text-text-light/60">
                <i class="fas fa-calendar-alt text-sm"></i>
              </div>
            </div>
            <div class="flex flex-col justify-between gap-2 rounded-lg p-6 bg-content-light border border-border-light min-h-[120px]">
              <div>
                <p class="text-base font-medium">Invitations en attente</p>
                <p class="tracking-light text-3xl font-bold mt-2">{{ invitationsPending }}</p>
              </div>
              <div class="text-xs text-text-light/60">
                <i class="fas fa-user-plus text-sm"></i>
              </div>
            </div>
            <div class="flex flex-col justify-between gap-2 rounded-lg p-6 bg-content-light border border-border-light min-h-[120px]">
              <div>
                <p class="text-base font-medium">Mes Crédits</p>
                <p class="tracking-light text-3xl font-bold mt-2">{{ credits }}</p>
              </div>
              <div class="text-xs text-text-light/60">
                <i class="fas fa-wallet text-sm"></i>
              </div>
            </div>
          </div>

          <!-- Main Content Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Activity Feed -->
            <div class="lg:col-span-2 flex flex-col gap-6">
              <h2 class="text-2xl font-bold tracking-[-0.015em]">Flux d'activité personnalisé</h2>
              
              <div class="flex flex-col gap-6">
                <template v-for="(item, idx) in activityList.slice(0,2)" :key="idx">
                  <div v-if="item.type === 'event' || !item.type" class="flex flex-col sm:flex-row gap-6 p-4 rounded-lg bg-content-light border border-border-light">
                    <div class="w-full sm:w-48 h-48 sm:h-auto bg-cover bg-center rounded" :style="`background-image: url('${item.image || 'https://picsum.photos/seed/tournament/800/600'}')`"></div>
                    <div class="flex flex-col justify-between flex-1">
                      <div>
                        <p class="text-xs font-bold uppercase text-primary">Événement Recommandé</p>
                        <h3 class="text-xl font-bold mt-1">{{ item.title }}</h3>
                        <p class="text-sm mt-2 text-text-light/70">{{ item.subtitle }}</p>
                        <p class="text-sm font-medium mt-2">📍 {{ item.location }} | {{ item.distance || '' }}</p>
                      </div>
                      <div class="flex gap-2 mt-4">
                        <button class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-full w-full sm:w-auto">S'inscrire</button>
                        <button class="px-4 py-2 text-sm font-bold bg-subtle-light rounded-full w-full sm:w-auto">Voir les détails</button>
                      </div>
                    </div>
                  </div>

                  <div v-else-if="item.type === 'venue'" class="flex flex-col sm:flex-row gap-6 p-4 rounded-lg bg-content-light border border-border-light">
                    <div class="w-full sm:w-48 h-48 sm:h-auto bg-cover bg-center rounded" :style="`background-image: url('${item.image || 'https://picsum.photos/seed/venue2/800/600'}')`"></div>
                    <div class="flex flex-col justify-between flex-1">
                      <div>
                        <p class="text-xs font-bold uppercase text-primary">Nouveauté près de chez vous</p>
                        <h3 class="text-xl font-bold mt-1">{{ item.title }}</h3>
                        <p class="text-sm mt-2 text-text-light/70">{{ item.subtitle }}</p>
                        <p class="text-sm font-medium mt-2">📍 {{ item.location }} | {{ item.distance || '' }}</p>
                      </div>
                      <div class="flex gap-2 mt-4">
                        <button class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-full w-full sm:w-auto">Réserver</button>
                        <button class="px-4 py-2 text-sm font-bold bg-subtle-light rounded-full w-full sm:w-auto">Voir la salle</button>
                      </div>
                    </div>
                  </div>
                </template>
              </div>

              <!-- Upcoming Appointments -->
              <h2 class="text-2xl font-bold tracking-[-0.015em] pt-4">Mes Prochains Rendez-vous</h2>
              <div class="flex flex-col gap-3">
                <template v-for="(appt, i) in upcomingList" :key="i">
                  <div class="flex items-center p-4 rounded-lg bg-content-light border border-border-light">
                    <div class="pr-4 border-r border-border-light text-center">
                      <p class="text-sm font-bold text-primary">{{ appt.dayShort }}</p>
                      <p class="text-2xl font-extrabold">{{ appt.dayNum }}</p>
                    </div>
                    <div class="flex-1 pl-4">
                      <p class="font-bold">{{ appt.title }}</p>
                      <p class="text-sm text-text-light/70">{{ appt.time }}</p>
                    </div>
                    <div class="flex -space-x-2">
                      <div v-for="n in (appt.avatars || 3)" :key="n" class="inline-block size-8 rounded-full ring-2 ring-content-light bg-gray-300"></div>
                    </div>
                  </div>
                </template>
              </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="flex flex-col gap-6">
              <!-- Nearby Venues -->
              <div class="bg-content-light rounded-lg p-6 border border-border-light">
                <h3 class="text-xl font-bold">Salles proches</h3>
                <div class="mt-4 grid grid-cols-1 gap-3">
                  <template v-if="nearbyList.length">
                    <div v-for="(v, idx) in nearbyList.slice(0,3)" :key="idx" class="aspect-square w-full bg-cover bg-center rounded" :style="`background-image: url('${v.image || 'https://picsum.photos/seed/nearby/400/400'}')`"></div>
                  </template>
                  <template v-else>
                    <div class="aspect-square w-full bg-cover bg-center rounded bg-gray-200"></div>
                  </template>
                </div>
              </div>

              <!-- Referral Link -->
              <div class="bg-content-light rounded-lg p-6 border border-border-light">
                <h3 class="text-xl font-bold">Mon Lien de Recommandation</h3>
                <p class="text-sm mt-2 text-text-light/70">Partagez ce lien et gagnez des crédits pour chaque ami qui s'inscrit !</p>
                <div class="relative mt-4">
                  <input 
                    class="w-full h-10 pr-12 rounded-full border-border-light bg-subtle-light text-sm px-4" 
                    readonly 
                    type="text" 
                    :value="referralLink"
                  />
                  <button class="absolute top-1/2 right-2 -translate-y-1/2 p-1.5 rounded-full bg-primary text-white">
                    <i class="fas fa-copy text-sm"></i>
                  </button>
                </div>
                <div class="flex justify-center gap-4 mt-4">
                  <button class="flex size-10 items-center justify-center rounded-full bg-subtle-light text-lg">f</button>
                  <button class="flex size-10 items-center justify-center rounded-full bg-subtle-light text-lg">X</button>
                  <button class="flex size-10 items-center justify-center rounded-full bg-subtle-light">
                    <i class="fas fa-comments"></i>
                  </button>
                </div>
                <div class="mt-4 text-center">
                  <p class="text-sm font-medium"><span class="font-bold text-primary">5</span> amis invités | <span class="font-bold text-primary">75</span> crédits gagnés</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
/* Exact colors from the design */
.bg-background-light { background-color: #f6f8f6; }
.bg-content-light { background-color: #ffffff; }
.bg-subtle-light { background-color: #f0f4f2; }
.text-text-light { color: #111813; }
.text-text-light\/70 { color: #111813; opacity: 0.7; }
.text-primary { color: #13ec5b; }
.bg-primary { background-color: #13ec5b; }
.border-border-light { border-color: #dbe6df; }
.hover\:bg-border-light:hover { background-color: #dbe6df; }

/* Accent cyan color */
.text-accent-cyan { color: #06b6d4; }
.hover\:text-accent-cyan:hover { color: #06b6d4; }

/* Material Icons configuration */
.material-symbols-outlined {
  font-variation-settings:
    'FILL' 0,
    'wght' 400,
    'GRAD' 0,
    'opsz' 24;
  vertical-align: middle;
}

.material-icons-round {
  font-family: 'Material Icons Round';
  font-weight: normal;
  font-style: normal;
  font-size: 24px;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-block;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  font-feature-settings: 'liga';
  -webkit-font-smoothing: antialiased;
}

/* Font family */
.font-display {
  font-family: "Plus Jakarta Sans", sans-serif;
}

/* Custom border radius values */
.rounded-lg {
  border-radius: 1rem;
}

.rounded-full {
  border-radius: 9999px;
}

/* Tracking utility */
.tracking-light {
  letter-spacing: -0.025em;
}
</style>
