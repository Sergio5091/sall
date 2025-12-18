<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
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
const invitationsPending = computed(() => props.stats?.invitations_pending ?? props.stats?.invitationsPending ?? 0);
const credits = computed(() => props.stats?.credits ?? 0);

const directReferralsCount = computed(() => props.stats?.direct_referrals ?? 0);
const communitySize = computed(() => props.stats?.community_size ?? 0);

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

const recommendedEvents = computed(() => {
  const events = [];
  const source = (props.upcoming && props.upcoming.length)
    ? props.upcoming
    : ((props.activity && props.activity.length) ? props.activity : []);

  for (const item of source) {
    const looksLikeEvent = item?.type === 'event' || item?.titre || item?.title || item?.name;
    if (!looksLikeEvent) continue;

    const id = item?.id;
    events.push({
      id,
      title: item?.titre || item?.title || item?.name || 'Événement',
      subtitle: item?.subtitle || item?.description || item?.resume || '',
      location: item?.lieu || item?.location || item?.salle?.nom || '',
      image: item?.image_affiche || item?.image || item?.image_url || null,
      href: id ? `/client/evenements/${id}` : '/client/evenements'
    });
  }

  if (events.length) return events.slice(0, 3);

  return [
    {
      id: null,
      title: 'League of Legends Championship',
      subtitle: 'Tournoi compétitif avec des lots à gagner.',
      location: 'Dakar',
      image: 'https://picsum.photos/seed/lol/800/600',
      href: '/client/evenements'
    },
    {
      id: null,
      title: 'Gaming Night',
      subtitle: 'Soirée gaming multi-jeux et animations.',
      location: 'Thiès',
      image: 'https://picsum.photos/seed/gamingnight/800/600',
      href: '/client/evenements'
    },
    {
      id: null,
      title: 'Showmatch Pro Players',
      subtitle: "Matchs d'exhibition avec des joueurs invités.",
      location: 'Saint-Louis',
      image: 'https://picsum.photos/seed/showmatch/800/600',
      href: '/client/evenements'
    }
  ];
});

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
const referralLink = computed(() => props.referral || 'gamecenter.com/invite/alex123');

const copyState = ref('idle');

const copyReferralLink = async () => {
  if (!referralLink.value) return;

  try {
    await navigator.clipboard.writeText(referralLink.value);
    copyState.value = 'copied';
    setTimeout(() => (copyState.value = 'idle'), 1500);
  } catch (e) {
    try {
      const el = document.createElement('textarea');
      el.value = referralLink.value;
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
      copyState.value = 'copied';
      setTimeout(() => (copyState.value = 'idle'), 1500);
    } catch (e2) {
      copyState.value = 'error';
      setTimeout(() => (copyState.value = 'idle'), 1500);
    }
  }
};
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
            <a class="text-black text-sm font-medium text-accent-cyan" href="/client/dashboard">Dashboard</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/salles">Salles</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/evenements">Événements</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/reservations">Mes Réservations</a>
          </nav>
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
              <Link href="/client/reseau" class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold rounded-full bg-subtle-light hover:bg-border-light">
                <i class="fas fa-user-plus text-lg"></i>
                <span>Inviter des amis</span>
              </Link>
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
                <template v-for="(evt, idx) in recommendedEvents" :key="evt.id ?? idx">
                  <div class="flex flex-col sm:flex-row gap-6 p-4 rounded-lg bg-content-light border border-border-light">
                    <div class="w-full sm:w-48 h-48 sm:h-auto bg-cover bg-center rounded" :style="`background-image: url('${evt.image || 'https://picsum.photos/seed/tournament/800/600'}')`"></div>
                    <div class="flex flex-col justify-between flex-1">
                      <div>
                        <p class="text-xs font-bold uppercase text-primary">Événement disponible</p>
                        <h3 class="text-xl font-bold mt-1">{{ evt.title }}</h3>
                        <p class="text-sm mt-2 text-text-light/70">{{ evt.subtitle }}</p>
                        <p v-if="evt.location" class="text-sm font-medium mt-2">📍 {{ evt.location }}</p>
                      </div>
                      <div class="flex gap-2 mt-4">
                        <a :href="evt.href" class="px-4 py-2 text-sm font-bold bg-subtle-light rounded-full w-full sm:w-auto text-center">Voir les détails</a>
                        <a :href="evt.href" class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-full w-full sm:w-auto text-center">Réserver</a>
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
                  <button type="button" @click="copyReferralLink" class="absolute top-1/2 right-2 -translate-y-1/2 p-1.5 rounded-full bg-primary text-white">
                    <i class="fas fa-copy text-sm"></i>
                  </button>
                </div>
                <p v-if="copyState === 'copied'" class="text-sm text-green-700 mt-2">Lien copié !</p>
                <p v-else-if="copyState === 'error'" class="text-sm text-red-700 mt-2">Impossible de copier. Copiez manuellement.</p>
                <div class="flex justify-center gap-4 mt-4">
                  <button class="flex size-10 items-center justify-center rounded-full bg-subtle-light text-lg">f</button>
                  <button class="flex size-10 items-center justify-center rounded-full bg-subtle-light text-lg">X</button>
                  <button class="flex size-10 items-center justify-center rounded-full bg-subtle-light">
                    <i class="fas fa-comments"></i>
                  </button>
                </div>
                <div class="mt-4 text-center">
                  <p class="text-sm font-medium"><span class="font-bold text-primary">{{ directReferralsCount }}</span> ami(s) invité(s) | <span class="font-bold text-primary">{{ communitySize }}</span> dans la communauté</p>
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
.text-primary { color: #3b82f6; }
.bg-primary { background-color: #3b82f6; }
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
