<script setup>
import { Head, router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  auth: Object,
  user: Object
});

// États pour le formulaire
const form = ref({
  name: props.user?.name || props.auth?.user?.name || '',
  email: props.user?.email || props.auth?.user?.email || '',
  telephone: props.user?.telephone || props.auth?.user?.telephone || '',
  date_naissance: props.user?.date_naissance || props.auth?.user?.date_naissance || '',
  newsletter: props.user?.newsletter || props.auth?.user?.newsletter || false,
  notifications_email: props.user?.notifications_email || props.auth?.user?.notifications_email || true,
  partage_profil: props.user?.partage_profil || props.auth?.user?.partage_profil || false
});

// États pour les préférences
const preferences = ref({
  jeux_preferes: props.user?.jeux_preferes || props.auth?.user?.jeux_preferes || [],
  types_salles_preferes: props.user?.types_salles_preferes || props.auth?.user?.types_salles_preferes || []
});

// Fonction de déconnexion
const logout = () => {
  if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
    router.post('/logout');
  }
};

// Fonction de sauvegarde du profil
const updateProfile = () => {
  router.put('/client/profile', form.value, {
    onSuccess: () => {
      // Notification de succès
      alert('Profil mis à jour avec succès !');
    },
    onError: (errors) => {
      console.error('Erreurs de validation:', errors);
      alert('Une erreur est survenue lors de la mise à jour du profil.');
    }
  });
};

// Fonction de changement de mot de passe
const changePassword = () => {
  // Ouvrir un modal pour changer le mot de passe
  const newPassword = prompt('Entrez votre nouveau mot de passe:');
  if (newPassword) {
    router.put('/client/password', { password: newPassword }, {
      onSuccess: () => {
        alert('Mot de passe changé avec succès !');
      },
      onError: () => {
        alert('Erreur lors du changement du mot de passe.');
      }
    });
  }
};

// Fonction de téléchargement des données
const downloadData = () => {
  router.get('/client/download-data', {}, {
    onSuccess: (response) => {
      // Créer un blob et télécharger le fichier
      const blob = new Blob([JSON.stringify(response.props.data, null, 2)], { type: 'application/json' });
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'mes-donnees-gameon.json';
      a.click();
      window.URL.revokeObjectURL(url);
    }
  });
};

// Fonction de suppression du compte
const deleteAccount = () => {
  const confirmation = prompt('Pour supprimer votre compte, tapez "SUPPRIMER MON COMPTE" en majuscules:');
  if (confirmation === 'SUPPRIMER MON COMPTE') {
    router.delete('/client/account', {
      onSuccess: () => {
        alert('Votre compte a été supprimé. Redirection...');
        router.push('/');
      },
      onError: () => {
        alert('Erreur lors de la suppression du compte.');
      }
    });
  } else if (confirmation) {
    alert('Texte de confirmation incorrect. La suppression n\'a pas été effectuée.');
  }
};
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
            <a class="text-black text-sm font-medium text-accent-cyan" href="/client/profile">Mon Profil</a>
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
          <Link href="/client/profile" class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 bg-gray-300 hover:opacity-80 transition-opacity cursor-pointer" title="Mon Profil">
          </Link>
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
                  <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-border-light rounded-lg bg-subtle-light text-text-light focus:outline-none focus:ring-2 focus:ring-primary"/>
                </div>
                <div>
                  <label class="block text-sm font-medium text-text-light/70 mb-2">Email</label>
                  <input v-model="form.email" type="email" class="w-full px-4 py-2 border border-border-light rounded-lg bg-subtle-light text-text-light focus:outline-none focus:ring-2 focus:ring-primary"/>
                </div>
                <div>
                  <label class="block text-sm font-medium text-text-light/70 mb-2">Téléphone</label>
                  <input v-model="form.telephone" type="tel" class="w-full px-4 py-2 border border-border-light rounded-lg bg-subtle-light text-text-light focus:outline-none focus:ring-2 focus:ring-primary"/>
                </div>
                <div>
                  <label class="block text-sm font-medium text-text-light/70 mb-2">Date de naissance</label>
                  <input v-model="form.date_naissance" type="date" class="w-full px-4 py-2 border border-border-light rounded-lg bg-subtle-light text-text-light focus:outline-none focus:ring-2 focus:ring-primary"/>
                </div>
              </div>
              <div class="flex justify-end mt-4">
                <button @click="updateProfile" class="px-6 py-2 bg-primary text-white rounded-full hover:bg-opacity-90 transition-colors">
                  Sauvegarder les modifications
                </button>
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
                    <button @click="form.notifications_email = !form.notifications_email" :class="`relative inline-flex h-6 w-11 items-center rounded-full ${form.notifications_email ? 'bg-primary' : 'bg-gray-300'}`">
                      <span :class="`inline-block h-4 w-4 transform rounded-full bg-white transition ${form.notifications_email ? 'translate-x-6' : 'translate-x-1'}`"></span>
                    </button>
                  </div>
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="font-medium">Newsletter</p>
                      <p class="text-sm text-text-light/70">Nouveautés et événements spéciaux</p>
                    </div>
                    <button @click="form.newsletter = !form.newsletter" :class="`relative inline-flex h-6 w-11 items-center rounded-full ${form.newsletter ? 'bg-primary' : 'bg-gray-300'}`">
                      <span :class="`inline-block h-4 w-4 transform rounded-full bg-white transition ${form.newsletter ? 'translate-x-6' : 'translate-x-1'}`"></span>
                    </button>
                  </div>
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="font-medium">Partage de profil</p>
                      <p class="text-sm text-text-light/70">Autoriser les autres joueurs à voir votre profil</p>
                    </div>
                    <button @click="form.partage_profil = !form.partage_profil" :class="`relative inline-flex h-6 w-11 items-center rounded-full ${form.partage_profil ? 'bg-primary' : 'bg-gray-300'}`">
                      <span :class="`inline-block h-4 w-4 transform rounded-full bg-white transition ${form.partage_profil ? 'translate-x-6' : 'translate-x-1'}`"></span>
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
                    <button @click="changePassword" class="px-4 py-2 text-sm font-bold bg-subtle-light rounded-full hover:bg-border-light">
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
                  <button @click="downloadData" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold rounded-full bg-primary text-white">
                    <i class="fas fa-download"></i>
                    <span>Télécharger mes données</span>
                  </button>
                  <button class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold rounded-full bg-subtle-light hover:bg-border-light">
                    <i class="fas fa-share"></i>
                    <span>Partager mon profil</span>
                  </button>
                  <button @click="logout" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold rounded-full border border-red-500 text-red-500 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Se déconnecter</span>
                  </button>
                </div>
              </div>

              <!-- Delete Account -->
              <div class="bg-content-light rounded-lg p-6 border border-border-light">
                <h3 class="text-xl font-bold mb-2 text-red-500">Danger Zone</h3>
                <p class="text-sm text-text-light/70 mb-4">La suppression de votre compte est définitive et irréversible.</p>
                <button @click="deleteAccount" class="w-full px-4 py-2 text-sm font-bold rounded-full border border-red-500 text-red-500 hover:bg-red-50">
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
