<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
</script>

<template>
  <Head title="Mon Profil" />
  
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
          <!-- Profile Header -->
          <div class="flex flex-col gap-6">
            <h1 class="text-4xl font-black leading-tight tracking-[-0.033em]">Mon Profil</h1>
            
            <!-- Profile Card -->
            <div class="flex flex-col sm:flex-row gap-6 p-6 rounded-lg bg-content-light border border-border-light">
              <!-- Avatar Section -->
              <div class="flex flex-col items-center gap-4">
                <div class="w-32 h-32 bg-cover bg-center rounded-full bg-gray-300"></div>
                <button class="px-4 py-2 text-sm font-bold bg-primary text-white rounded-full">
                  Changer la photo
                </button>
              </div>
              
              <!-- Profile Info -->
              <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-text-light/70 mb-2">Nom complet</label>
                  <input type="text" value="Alex Johnson" class="w-full px-4 py-2 border border-border-light rounded-lg bg-subtle-light text-text-light focus:outline-none focus:ring-2 focus:ring-primary"/>
                </div>
                <div>
                  <label class="block text-sm font-medium text-text-light/70 mb-2">Email</label>
                  <input type="email" value="alex.johnson@email.com" class="w-full px-4 py-2 border border-border-light rounded-lg bg-subtle-light text-text-light focus:outline-none focus:ring-2 focus:ring-primary"/>
                </div>
                <div>
                  <label class="block text-sm font-medium text-text-light/70 mb-2">Téléphone</label>
                  <input type="tel" value="+33 6 12 34 56 78" class="w-full px-4 py-2 border border-border-light rounded-lg bg-subtle-light text-text-light focus:outline-none focus:ring-2 focus:ring-primary"/>
                </div>
                <div>
                  <label class="block text-sm font-medium text-text-light/70 mb-2">Date de naissance</label>
                  <input type="date" value="1995-06-15" class="w-full px-4 py-2 border border-border-light rounded-lg bg-subtle-light text-text-light focus:outline-none focus:ring-2 focus:ring-primary"/>
                </div>
              </div>
            </div>
          </div>

          <!-- Profile Sections Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Settings -->
            <div class="lg:col-span-2 flex flex-col gap-6">
              <!-- Preferences -->
              <div class="bg-content-light rounded-lg p-6 border border-border-light">
                <h3 class="text-xl font-bold mb-4">Préférences</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="font-medium">Notifications par email</p>
                      <p class="text-sm text-text-light/70">Recevoir des rappels de réservations</p>
                    </div>
                    <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-primary">
                      <span class="inline-block h-4 w-4 transform rounded-full bg-white transition translate-x-6"></span>
                    </button>
                  </div>
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="font-medium">Newsletter</p>
                      <p class="text-sm text-text-light/70">Nouveautés et événements spéciaux</p>
                    </div>
                    <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-300">
                      <span class="inline-block h-4 w-4 transform rounded-full bg-white transition translate-x-1"></span>
                    </button>
                  </div>
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="font-medium">Partage de profil</p>
                      <p class="text-sm text-text-light/70">Autoriser les autres joueurs à voir votre profil</p>
                    </div>
                    <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-primary">
                      <span class="inline-block h-4 w-4 transform rounded-full bg-white transition translate-x-6"></span>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Gaming Preferences -->
              <div class="bg-content-light rounded-lg p-6 border border-border-light">
                <h3 class="text-xl font-bold mb-4">Préférences de jeu</h3>
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-text-light/70 mb-2">Jeux préférés</label>
                    <div class="flex flex-wrap gap-2">
                      <span class="px-3 py-1 bg-primary text-white rounded-full text-sm">FIFA</span>
                      <span class="px-3 py-1 bg-primary text-white rounded-full text-sm">Call of Duty</span>
                      <span class="px-3 py-1 bg-primary text-white rounded-full text-sm">Mario Kart</span>
                      <button class="px-3 py-1 border border-border-light rounded-full text-sm hover:bg-subtle-light">+ Ajouter</button>
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-text-light/70 mb-2">Type de salles préférées</label>
                    <div class="flex flex-wrap gap-2">
                      <span class="px-3 py-1 bg-subtle-light rounded-full text-sm">VR</span>
                      <span class="px-3 py-1 bg-subtle-light rounded-full text-sm">Racing</span>
                      <span class="px-3 py-1 bg-subtle-light rounded-full text-sm">FPS</span>
                      <button class="px-3 py-1 border border-border-light rounded-full text-sm hover:bg-subtle-light">+ Ajouter</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Security -->
              <div class="bg-content-light rounded-lg p-6 border border-border-light">
                <h3 class="text-xl font-bold mb-4">Sécurité</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between p-4 border border-border-light rounded-lg">
                    <div class="flex items-center gap-3">
                      <i class="fas fa-lock text-primary"></i>
                      <div>
                        <p class="font-medium">Mot de passe</p>
                        <p class="text-sm text-text-light/70">Dernière modification : il y a 30 jours</p>
                      </div>
                    </div>
                    <button class="px-4 py-2 text-sm font-bold bg-subtle-light rounded-full hover:bg-border-light">
                      Modifier
                    </button>
                  </div>
                  <div class="flex items-center justify-between p-4 border border-border-light rounded-lg">
                    <div class="flex items-center gap-3">
                      <i class="fas fa-mobile-alt text-primary"></i>
                      <div>
                        <p class="font-medium">Authentification à deux facteurs</p>
                        <p class="text-sm text-text-light/70">Non configurée</p>
                      </div>
                    </div>
                    <button class="px-4 py-2 text-sm font-bold bg-primary text-white rounded-full">
                      Activer
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column - Stats & Actions -->
            <div class="flex flex-col gap-6">
              <!-- Stats Card -->
              <div class="bg-content-light rounded-lg p-6 border border-border-light">
                <h3 class="text-xl font-bold mb-4">Mes Statistiques</h3>
                <div class="space-y-4">
                  <div class="flex justify-between items-center">
                    <span class="text-text-light/70">Total des réservations</span>
                    <span class="font-bold">24</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-text-light/70">Heures de jeu</span>
                    <span class="font-bold">156</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-text-light/70">Amis invités</span>
                    <span class="font-bold">8</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-text-light/70">Crédits gagnés</span>
                    <span class="font-bold text-primary">320</span>
                  </div>
                </div>
              </div>

              <!-- Quick Actions -->
              <div class="bg-content-light rounded-lg p-6 border border-border-light">
                <h3 class="text-xl font-bold mb-4">Actions Rapides</h3>
                <div class="space-y-3">
                  <button class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold rounded-full bg-primary text-white">
                    <i class="fas fa-download"></i>
                    <span>Télécharger mes données</span>
                  </button>
                  <button class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold rounded-full bg-subtle-light hover:bg-border-light">
                    <i class="fas fa-share"></i>
                    <span>Partager mon profil</span>
                  </button>
                  <button class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold rounded-full border border-red-500 text-red-500 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Se déconnecter</span>
                  </button>
                </div>
              </div>

              <!-- Delete Account -->
              <div class="bg-content-light rounded-lg p-6 border border-border-light">
                <h3 class="text-xl font-bold mb-2 text-red-500">Danger Zone</h3>
                <p class="text-sm text-text-light/70 mb-4">La suppression de votre compte est définitive et irréversible.</p>
                <button class="w-full px-4 py-2 text-sm font-bold rounded-full border border-red-500 text-red-500 hover:bg-red-50">
                  Supprimer mon compte
                </button>
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
