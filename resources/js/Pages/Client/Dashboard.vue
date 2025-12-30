<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Navigation from '../../Components/Navigation.vue';
import AlertModal from '../../Components/AlertModal.vue';
import { useAlert } from '../../Composables/useAlert.js';

// Alert composable
const { alertState, showSuccess, showError, showConfirm } = useAlert();

// Fonction de déconnexion
const logout = async () => {
  const confirmed = await showConfirm(
    'Déconnexion',
    'Êtes-vous sûr de vouloir vous déconnecter ?'
  );
  
  if (confirmed) {
    router.post('/logout');
  }
};

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
const referralPoints = computed(() => props.stats?.referral_points ?? '0');

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
const howItWorksOpen = ref(false);
const mobileMenuOpen = ref(false);

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

const openHowItWorks = () => {
  howItWorksOpen.value = true;
};

const closeHowItWorks = () => {
  howItWorksOpen.value = false;
};

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

const closeMobileMenu = () => {
  mobileMenuOpen.value = false;
};
</script>

<template>
  <Head title="Tableau de bord Client" />
  
  <!-- Add Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden bg-gray-50 font-sans">
    <!-- Navigation Component -->
    <Navigation :user="user" current-page="dashboard" />

    <!-- Main Content -->
    <main class="pt-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="flex flex-wrap justify-between gap-6 items-center mb-8">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Bonjour, {{ userName }} 👋</h1>
            <p class="text-gray-600 mt-1">Voici votre activité récente</p>
          </div>
          <div class="flex items-center gap-3">
            <button class="flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors">
              <i class="fas fa-calendar-plus"></i>
              <span>Réserver</span>
            </button>
            <Link href="/client/reseau" class="flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors">
              <i class="fas fa-user-plus"></i>
              <span>Inviter</span>
            </Link>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
          <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm font-medium">Prochaines Réservations</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ nextReservations }}</p>
              </div>
              <div class="p-3 rounded-md bg-blue-50">
                <i class="fas fa-calendar-alt text-blue-600"></i>
              </div>
            </div>
          </div>
          
          <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm font-medium">Filleuls directs</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ invitationsPending }}</p>
              </div>
              <div class="p-3 rounded-md bg-green-50">
                <i class="fas fa-users text-green-600"></i>
              </div>
            </div>
          </div>
          
          <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm font-medium">Mes Points</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ referralPoints }}</p>
              </div>
              <div class="p-3 rounded-md bg-purple-50">
                <i class="fas fa-star text-purple-600"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Column - Activity Feed -->
          <div class="lg:col-span-2 space-y-8">
            <!-- Recommended Events -->
            <div>
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Événements recommandés</h2>
                <a href="/client/evenements" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                  Voir tout →
                </a>
              </div>
              
              <div class="space-y-6">
                <template v-for="(evt, idx) in recommendedEvents" :key="evt.id ?? idx">
                  <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row">
                      <div class="sm:w-48 h-48 sm:h-auto bg-cover bg-center" :style="`background-image: url('${evt.image || 'https://picsum.photos/seed/tournament/800/600'}')`"></div>
                      <div class="flex-1 p-6">
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 mb-3">
                          Événement
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ evt.title }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ evt.subtitle }}</p>
                        <div class="flex items-center text-sm text-gray-500 mb-6">
                          <i class="fas fa-map-marker-alt mr-2"></i>
                          <span>{{ evt.location }}</span>
                        </div>
                        <div class="flex gap-3">
                          <a :href="evt.href" class="flex-1 px-4 py-2.5 text-center text-sm font-medium rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                            Détails
                          </a>
                          <a :href="evt.href" class="flex-1 px-4 py-2.5 text-center text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                            Réserver
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </template>
              </div>
            </div>

                      </div>

          <!-- Right Column - Sidebar -->
          <div class="space-y-8">
            <!-- Referral Link -->
            <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
              <div class="flex items-start justify-between mb-4">
                <div>
                  <h3 class="text-lg font-bold text-gray-900 mb-1">Parrainez vos amis</h3>
                  <p class="text-sm text-gray-600">Gagnez des points en invitant vos amis</p>
                </div>
                <button
                  @click="openHowItWorks"
                  class="p-2 rounded-md hover:bg-gray-100 transition-colors"
                  title="Comment ça marche ?"
                >
                  <i class="fas fa-question-circle text-gray-400"></i>
                </button>
              </div>
              
              <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                  <span class="text-sm font-medium text-gray-700">Lien de parrainage</span>
                  <span v-if="copyState === 'copied'" class="text-xs text-green-600">
                    <i class="fas fa-check mr-1"></i>Copié
                  </span>
                </div>
                <div class="relative">
                  <input 
                    class="w-full h-11 pl-4 pr-12 rounded-md border border-gray-300 bg-gray-50 text-sm text-gray-700"
                    readonly 
                    type="text" 
                    :value="referralLink"
                  />
                  <button 
                    @click="copyReferralLink"
                    class="absolute top-1/2 right-2 -translate-y-1/2 p-2.5 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors"
                    title="Copier le lien"
                  >
                    <i class="fas fa-copy"></i>
                  </button>
                </div>
              </div>

              <div class="bg-gray-50 rounded-md p-4 mb-6">
                <div class="grid grid-cols-2 gap-4 text-center">
                  <div>
                    <div class="text-2xl font-bold text-gray-900">{{ directReferralsCount }}</div>
                    <div class="text-xs text-gray-600">Amis invités</div>
                  </div>
                  <div>
                    <div class="text-2xl font-bold text-gray-900">{{ communitySize }}</div>
                    <div class="text-xs text-gray-600">Communauté</div>
                  </div>
                </div>
              </div>

              <div class="space-y-3">
                <p class="text-sm font-medium text-gray-700">Partager sur :</p>
                <div class="flex gap-2">
                  <button class="flex-1 py-2.5 rounded-md border border-gray-300 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fab fa-facebook text-blue-600"></i>
                  </button>
                  <button class="flex-1 py-2.5 rounded-md border border-gray-300 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fab fa-twitter text-sky-500"></i>
                  </button>
                  <button class="flex-1 py-2.5 rounded-md border border-gray-300 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fab fa-whatsapp text-green-500"></i>
                  </button>
                  <button class="flex-1 py-2.5 rounded-md border border-gray-300 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fas fa-envelope text-gray-600"></i>
                  </button>
                </div>
              </div>
            </div>

                      </div>
        </div>
      </div>
    </main>

    <!-- Modal: Comment ça marche -->
    <div
      v-if="howItWorksOpen"
      class="fixed inset-0 z-50 flex items-center justify-center px-4"
      @click.self="closeHowItWorks"
    >
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
      <div class="relative w-full max-w-2xl rounded-lg bg-white shadow-2xl max-h-[85vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
          <div>
            <h3 class="text-xl font-bold text-gray-900">Comment ça marche ?</h3>
            <p class="text-sm text-gray-600 mt-1">Le parrainage et le calcul des points</p>
          </div>
          <button @click="closeHowItWorks" class="p-2 rounded-md hover:bg-gray-100 transition-colors">
            <i class="fas fa-times text-gray-500"></i>
          </button>
        </div>
        
        <div class="p-6 space-y-4 overflow-y-auto" style="max-height: calc(85vh - 140px);">
          <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
            <h4 class="font-semibold text-gray-900 mb-2">1. Votre lien de parrainage</h4>
            <p class="text-sm text-gray-600">Partagez votre lien unique. Chaque inscription via ce lien devient votre filleul direct (génération 1).</p>
          </div>

          <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
            <h4 class="font-semibold text-gray-900 mb-2">2. Générations de votre réseau</h4>
            <p class="text-sm text-gray-600">Les personnes invitées par vos filleuls forment la génération 2, et ainsi de suite.</p>
          </div>

          <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
            <h4 class="font-semibold text-gray-900 mb-2">3. Calcul des points</h4>
            <p class="text-sm text-gray-600">Les points sont calculés uniquement sur les personnes actives dans votre réseau.</p>
            <div class="mt-3 p-3 bg-white rounded-md border border-gray-200 font-mono text-sm">
              Points par génération = Nombre de personnes × (0.5)^N
            </div>
          </div>

          <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
            <h4 class="font-semibold text-gray-900 mb-2">4. Exemple concret</h4>
            <p class="text-sm text-gray-600 mb-3">Si vous avez 5 filleuls directs et qu'ils invitent 10 personnes :</p>
            <ul class="text-sm text-gray-600 space-y-2">
              <li class="flex items-center">
                <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mr-2">1</div>
                Génération 1 : 5 × 0.5 = 2.5 points
              </li>
              <li class="flex items-center">
                <div class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold mr-2">2</div>
                Génération 2 : 10 × 0.25 = 2.5 points
              </li>
              <li class="flex items-center">
                <div class="w-6 h-6 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs font-bold mr-2">∑</div>
                Total : 5 points
              </li>
            </ul>
          </div>
        </div>
        
        <div class="p-6 border-t border-gray-200">
          <button @click="closeHowItWorks" class="w-full px-4 py-3 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors">
            J'ai compris, merci !
          </button>
        </div>
      </div>
    </div>
    </div>

    <!-- Alert Modal -->
    <AlertModal
      :show="alertState.show"
      :type="alertState.type"
      :title="alertState.title"
      :message="alertState.message"
      :confirm-text="alertState.confirmText"
      :cancel-text="alertState.cancelText"
      :show-cancel="alertState.showCancel"
      @close="alertState.show = false"
      @confirm="alertState.resolve"
    />
</template>

<style scoped>
/* Font family */
.font-sans {
  font-family: 'Inter', sans-serif;
}

/* Smooth transitions */
.transition-colors {
  transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
}

.transition-shadow {
  transition: box-shadow 0.2s ease;
}

.transition-opacity {
  transition: opacity 0.2s ease;
}
</style>