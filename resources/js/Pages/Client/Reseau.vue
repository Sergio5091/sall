<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
  user: Object,
  referralLink: String,
  directReferrals: Array,
  generations: Array,
  totalCommunity: Number,
});

const copyState = ref('idle');

const referral = computed(() => props.referralLink || '');

const copyReferralLink = async () => {
  if (!referral.value) return;

  try {
    await navigator.clipboard.writeText(referral.value);
    copyState.value = 'copied';
    setTimeout(() => (copyState.value = 'idle'), 1500);
  } catch (e) {
    try {
      const el = document.createElement('textarea');
      el.value = referral.value;
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

const totalDirect = computed(() => (props.directReferrals || []).length);
const generationsCount = computed(() => (props.generations || []).length);
</script>

<template>
  <Head title="Mon Réseau - GameOn" />

  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden bg-background-light font-display">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center backdrop-blur-sm shadow-sm">
      <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-2 text-black">
            <a href="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
              <i class="fas fa-gamepad text-3xl text-accent-cyan"></i>
              <h2 class="text-black text-2xl font-display font-bold">GameOn</h2>
            </a>
          </div>
          <nav class="hidden md:flex items-center gap-6">
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/dashboard">Dashboard</a>
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

    <main class="layout-container flex h-full grow flex-col pt-20">
      <div class="px-4 sm:px-8 lg:px-16 2xl:px-40 flex flex-1 justify-center py-6">
        <div class="layout-content-container flex flex-col w-full max-w-screen-xl flex-1 gap-8">
          <div class="flex flex-wrap justify-between gap-4 items-center">
            <div>
              <h1 class="text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em]">Mon Réseau</h1>
              <p class="text-text-light/70 mt-1">Construisez votre communauté grâce au parrainage.</p>
            </div>
            <Link
              href="/client/dashboard"
              class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold rounded-full bg-subtle-light hover:bg-border-light"
            >
              <i class="fas fa-arrow-left"></i>
              <span>Retour</span>
            </Link>
          </div>

          <!-- Stats -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex flex-col justify-between gap-2 rounded-lg p-6 bg-content-light border border-border-light min-h-[120px]">
              <div>
                <p class="text-base font-medium">Filleuls directs</p>
                <p class="tracking-light text-3xl font-bold mt-2">{{ totalDirect }}</p>
              </div>
              <div class="text-xs text-text-light/60">
                <i class="fas fa-user-friends text-sm"></i>
              </div>
            </div>
            <div class="flex flex-col justify-between gap-2 rounded-lg p-6 bg-content-light border border-border-light min-h-[120px]">
              <div>
                <p class="text-base font-medium">Générations</p>
                <p class="tracking-light text-3xl font-bold mt-2">{{ generationsCount }}</p>
              </div>
              <div class="text-xs text-text-light/60">
                <i class="fas fa-layer-group text-sm"></i>
              </div>
            </div>
            <div class="flex flex-col justify-between gap-2 rounded-lg p-6 bg-content-light border border-border-light min-h-[120px]">
              <div>
                <p class="text-base font-medium">Taille de la communauté</p>
                <p class="tracking-light text-3xl font-bold mt-2">{{ totalCommunity }}</p>
              </div>
              <div class="text-xs text-text-light/60">
                <i class="fas fa-users text-sm"></i>
              </div>
            </div>
          </div>

          <!-- Referral link -->
          <div class="bg-content-light rounded-lg p-6 border border-border-light">
            <div class="flex items-start justify-between gap-4">
              <div>
                <h2 class="text-xl font-bold">Mon lien de parrainage</h2>
                <p class="text-sm mt-1 text-text-light/70">Partagez ce lien. Toute inscription via ce lien sera placée directement sous vous.</p>
              </div>
              <div class="text-sm font-medium text-text-light/70">{{ props.user?.name }}</div>
            </div>

            <div class="relative mt-4">
              <input
                class="w-full h-12 pr-14 rounded-full border-border-light bg-subtle-light text-sm px-4"
                readonly
                type="text"
                :value="referral"
              />
              <button
                type="button"
                @click="copyReferralLink"
                class="absolute top-1/2 right-2 -translate-y-1/2 p-2.5 rounded-full bg-primary text-white"
              >
                <i class="fas fa-copy text-sm"></i>
              </button>
            </div>

            <p v-if="copyState === 'copied'" class="text-sm text-green-700 mt-2">Lien copié !</p>
            <p v-else-if="copyState === 'error'" class="text-sm text-red-700 mt-2">Impossible de copier. Copiez manuellement.</p>
          </div>

          <!-- Direct referrals -->
          <div class="bg-content-light rounded-lg p-6 border border-border-light">
            <h2 class="text-xl font-bold">👥 Filleuls directs (Génération 1)</h2>
            <div v-if="!directReferrals || directReferrals.length === 0" class="text-text-light/70 mt-3">
              Aucun filleul direct pour le moment.
            </div>
            <div v-else class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div v-for="u in directReferrals" :key="u.id" class="p-4 rounded-lg bg-subtle-light border border-border-light">
                <div class="font-bold">{{ u.name }}</div>
                <div class="text-sm text-text-light/70">{{ u.email }}</div>
              </div>
            </div>
          </div>

          <!-- Generations -->
          <div class="bg-content-light rounded-lg p-6 border border-border-light">
            <h2 class="text-xl font-bold">🌱 Filleuls indirects (par génération)</h2>

            <div v-if="!generations || generations.length === 0" class="text-text-light/70 mt-3">
              Aucune génération pour le moment.
            </div>

            <div v-else class="mt-4 space-y-6">
              <div v-for="g in generations" :key="g.level" class="rounded-lg border border-border-light overflow-hidden">
                <div class="px-4 py-3 bg-subtle-light flex items-center justify-between">
                  <div class="font-bold">Génération {{ g.level }}</div>
                  <div class="text-sm text-text-light/70">{{ g.count }} utilisateur(s)</div>
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                  <div v-for="u in g.users" :key="u.id" class="p-4 rounded-lg bg-content-light border border-border-light">
                    <div class="font-bold">{{ u.name }}</div>
                    <div class="text-sm text-text-light/70">{{ u.email }}</div>
                  </div>
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
.bg-background-light { background-color: #f6f8f6; }
.bg-content-light { background-color: #ffffff; }
.bg-subtle-light { background-color: #f0f4f2; }
.text-text-light { color: #111813; }
.text-text-light\/70 { color: #111813; opacity: 0.7; }
.text-primary { color: #3b82f6; }
.bg-primary { background-color: #3b82f6; }
.border-border-light { border-color: #dbe6df; }
.hover\:bg-border-light:hover { background-color: #dbe6df; }
.text-accent-cyan { color: #06b6d4; }
.hover\:text-accent-cyan:hover { color: #06b6d4; }
.font-display { font-family: "Plus Jakarta Sans", sans-serif; }
.rounded-lg { border-radius: 1rem; }
.rounded-full { border-radius: 9999px; }
.tracking-light { letter-spacing: -0.025em; }
</style>
