<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
</script>

<template>
  <Head title="Tableau de bord Client" />
  
  <!-- Add Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>
  
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden bg-background-light font-display">
    <!-- Header - Same as Welcome page but with client navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center backdrop-blur-sm shadow-sm">
      <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-2 text-black">
            <a href="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
              <span class="material-icons-round text-3xl text-accent-cyan">sports_esports</span>
              <h2 class="text-black text-2xl font-display font-bold">GameOn</h2>
            </a>
          </div>
          <!-- Client Navigation -->
          <nav class="hidden md:flex items-center gap-6">
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/dashboard">Dashboard</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="#">Salles</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="#">Événements</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="#">Mes Réservations</a>
            <a class="text-black text-sm font-medium hover:text-accent-cyan transition-colors" href="/client/profile">Profil</a>
          </nav>
        </div>
        <div class="hidden md:flex flex-1 justify-center">
          <label class="flex flex-col w-full max-w-sm h-11">
            <div class="flex w-full flex-1 items-stretch rounded-full h-full">
              <div class="text-[#6b7280] flex bg-[#e5e7eb] items-center justify-center pl-4 rounded-l-full">
                <span class="material-symbols-outlined text-xl">search</span>
              </div>
              <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden text-black focus:outline-0 focus:ring-0 border-none bg-[#e5e7eb] h-full placeholder:text-[#6b7280] px-4 rounded-r-full text-base font-normal leading-normal" placeholder="Rechercher une salle, un jeu..."/>
            </div>
          </label>
        </div>
        <div class="flex items-center gap-3">
          <button class="flex relative cursor-pointer items-center justify-center overflow-hidden rounded-full size-10 bg-[#e5e7eb] text-black gap-2">
            <span class="material-symbols-outlined">notifications</span>
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
            <h1 class="text-4xl font-black leading-tight tracking-[-0.033em]">Bienvenue, Alex !</h1>
            <div class="flex items-center gap-2">
              <button class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold rounded-full bg-subtle-light hover:bg-border-light">
                <span class="material-symbols-outlined text-lg">event</span>
                <span>Réserver une salle</span>
              </button>
              <button class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold rounded-full bg-subtle-light hover:bg-border-light">
                <span class="material-symbols-outlined text-lg">person_add</span>
                <span>Inviter des amis</span>
              </button>
              <button class="hidden md:flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold rounded-full bg-subtle-light hover:bg-border-light">
                <span class="material-symbols-outlined text-lg">history</span>
                <span>Voir mon historique</span>
              </button>
            </div>
          </div>

          <!-- Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex flex-col justify-between gap-2 rounded-lg p-6 bg-content-light border border-border-light min-h-[120px]">
              <div>
                <p class="text-base font-medium">Prochaines Réservations</p>
                <p class="tracking-light text-3xl font-bold mt-2">2</p>
              </div>
              <div class="text-xs text-text-light/60">
                <span class="material-symbols-outlined text-sm">calendar_month</span>
              </div>
            </div>
            <div class="flex flex-col justify-between gap-2 rounded-lg p-6 bg-content-light border border-border-light min-h-[120px]">
              <div>
                <p class="text-base font-medium">Invitations en attente</p>
                <p class="tracking-light text-3xl font-bold mt-2">3</p>
              </div>
              <div class="text-xs text-text-light/60">
                <span class="material-symbols-outlined text-sm">person_add</span>
              </div>
            </div>
            <div class="flex flex-col justify-between gap-2 rounded-lg p-6 bg-content-light border border-border-light min-h-[120px]">
              <div>
                <p class="text-base font-medium">Mes Crédits</p>
                <p class="tracking-light text-3xl font-bold mt-2">150</p>
              </div>
              <div class="text-xs text-text-light/60">
                <span class="material-symbols-outlined text-sm">account_balance_wallet</span>
              </div>
            </div>
          </div>

          <!-- Main Content Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Activity Feed -->
            <div class="lg:col-span-2 flex flex-col gap-6">
              <h2 class="text-2xl font-bold tracking-[-0.015em]">Flux d'activité personnalisé</h2>
              
              <div class="flex flex-col gap-6">
                <!-- Event Card -->
                <div class="flex flex-col sm:flex-row gap-6 p-4 rounded-lg bg-content-light border border-border-light">
                  <div class="w-full sm:w-48 h-48 sm:h-auto bg-cover bg-center rounded" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAO6aMmeykJD_8Y3dAUhQkS0k2QK-FnToFSNyCXvQOw0b_Qhz7QQpr0G_VQYj_f_MACxkjYR-U4gqYUGvvOzq2fTSeATiJy5SmTGlgWq0GKpXduYJRyp7xuY56IidtJavV9YTLEJI2OjN-VmLsMZmbr1Z3QFwiZ_9X5Ai-51CabxCCP0-V-WwC9l9gBDBjXLWbduXDLwikEAYj6MAG7-4Z5EqjpJPHzmq059s7QKKIaWbclt7rWFrorybtE5AhfGBQYOcrVPq6srmVX')"></div>
                  <div class="flex flex-col justify-between flex-1">
                    <div>
                      <p class="text-xs font-bold uppercase text-primary">Événement Recommandé</p>
                      <h3 class="text-xl font-bold mt-1">Tournoi Super Smash</h3>
                      <p class="text-sm mt-2 text-text-light/70">Rejoignez-nous pour une compétition amicale ce week-end ! De nombreux lots à gagner.</p>
                      <p class="text-sm font-medium mt-2">📍 Pixel Play | 2.5 km</p>
                    </div>
                    <div class="flex gap-2 mt-4">
                      <button class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-full w-full sm:w-auto">S'inscrire</button>
                      <button class="px-4 py-2 text-sm font-bold bg-subtle-light rounded-full w-full sm:w-auto">Voir les détails</button>
                    </div>
                  </div>
                </div>

                <!-- New Venue Card -->
                <div class="flex flex-col sm:flex-row gap-6 p-4 rounded-lg bg-content-light border border-border-light">
                  <div class="w-full sm:w-48 h-48 sm:h-auto bg-cover bg-center rounded" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCLMWeqq4RTuw0Hs_kE-TlTbEWyHEkUvdz1gqS0Dmx3ADF2kwxPPsIXIdi2KP6nJ8-vL640t-puAqz267GdyVG6SNJFCcSMNv3UCBUXmBE_2lTsb5S5vcelqZZVIyGVR1_Y39IGHICAq7rlwSHDY8BDWj-b7b3DquKiwnjMaQw-xYSe5P4MpONpO6FSMybP2jpCDPTT4iPSqpZ1WwOAl9x-3DptrblfZI4PpJ5XZFk_pDeuOZZXSuVDd9FwilG_rggQXsvQGWQgMXpD')"></div>
                  <div class="flex flex-col justify-between flex-1">
                    <div>
                      <p class="text-xs font-bold uppercase text-primary">Nouveauté près de chez vous</p>
                      <h3 class="text-xl font-bold mt-1">VR Universe</h3>
                      <p class="text-sm mt-2 text-text-light/70">Découvrez nos nouvelles stations de réalité virtuelle avec les derniers titres du moment.</p>
                      <p class="text-sm font-medium mt-2">📍 VR Universe | 1.2 km</p>
                    </div>
                    <div class="flex gap-2 mt-4">
                      <button class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-full w-full sm:w-auto">Réserver</button>
                      <button class="px-4 py-2 text-sm font-bold bg-subtle-light rounded-full w-full sm:w-auto">Voir la salle</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Upcoming Appointments -->
              <h2 class="text-2xl font-bold tracking-[-0.015em] pt-4">Mes Prochains Rendez-vous</h2>
              <div class="flex flex-col gap-3">
                <div class="flex items-center p-4 rounded-lg bg-content-light border border-border-light">
                  <div class="pr-4 border-r border-border-light text-center">
                    <p class="text-sm font-bold text-primary">MAR</p>
                    <p class="text-2xl font-extrabold">25</p>
                  </div>
                  <div class="flex-1 pl-4">
                    <p class="font-bold">Soirée LAN à Neo Arcade</p>
                    <p class="text-sm text-text-light/70">20:00 - 23:00</p>
                  </div>
                  <div class="flex -space-x-2">
                    <div class="inline-block size-8 rounded-full ring-2 ring-content-light bg-gray-300"></div>
                    <div class="inline-block size-8 rounded-full ring-2 ring-content-light bg-gray-400"></div>
                    <div class="inline-block size-8 rounded-full ring-2 ring-content-light bg-gray-500"></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="flex flex-col gap-6">
              <!-- Nearby Venues -->
              <div class="bg-content-light rounded-lg p-6 border border-border-light">
                <h3 class="text-xl font-bold">Salles proches</h3>
                <div class="mt-4 aspect-square w-full bg-cover bg-center rounded bg-gray-200"></div>
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
                    value="gamecenter.com/invite/alex123"
                  />
                  <button class="absolute top-1/2 right-2 -translate-y-1/2 p-1.5 rounded-full bg-primary text-white">
                    <span class="material-symbols-outlined text-sm">content_copy</span>
                  </button>
                </div>
                <div class="flex justify-center gap-4 mt-4">
                  <button class="flex size-10 items-center justify-center rounded-full bg-subtle-light text-lg">f</button>
                  <button class="flex size-10 items-center justify-center rounded-full bg-subtle-light text-lg">X</button>
                  <button class="flex size-10 items-center justify-center rounded-full bg-subtle-light">
                    <span class="material-symbols-outlined">forum</span>
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
