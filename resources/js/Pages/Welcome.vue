<template>
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="min-h-screen text-gray-800 font-body">
    <!-- TopNavBar -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center backdrop-blur-sm shadow-sm bg-white/90">
      <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-2 text-gray-800">
            <i class="fas fa-gamepad text-3xl text-gray-400"></i>
            <h2 class="text-gray-800 text-2xl font-display font-bold">GameOn</h2>
          </div>
          <nav class="hidden md:flex space-x-8">
            <a href="/" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Accueil</a>
            <a href="/search/rooms" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Salles</a>
            <a href="/events" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Événements</a>
          </nav>
        </div>
        <div class="flex items-center gap-3">
          <a href="/login" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-200 text-gray-800 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-300 transition-colors">
            <span class="truncate">Connexion</span>
          </a>
          <a href="/register" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-800 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-700 transition-all">
            <span class="truncate">S'inscrire</span>
          </a>
        </div>
      </div>
    </header>
    <!-- HeroSection -->
    <section class="relative flex min-h-[80vh] sm:min-h-screen flex-col gap-6 sm:gap-8 items-center justify-center text-center py-16 sm:py-20 w-full overflow-x-hidden">
      <!-- Simple Background -->
      <div class="absolute inset-0 z-0 bg-gradient-to-br from-gray-50 to-gray-100">
      </div>
      
      <!-- Light Overlay -->
      <div class="absolute inset-0 bg-white/40 backdrop-blur-sm z-5"></div>
      <div class="relative z-10 flex flex-col gap-3 sm:gap-4 items-center w-full px-4 max-w-4xl">
        <h1 class="text-gray-800 text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-display font-bold leading-tight tracking-tighter animate-fade-in-up">
          Votre Prochaine Partie Commence Ici.
        </h1>
        <h2 class="text-gray-600 text-base sm:text-lg md:text-xl font-normal leading-normal max-w-3xl px-2 animate-fade-in-up delay-300">
          Découvrez et réservez des milliers de salles de jeux et d'expériences près de chez vous.
        </h2>
      </div>
      <div class="relative z-10 w-full max-w-2xl px-4">
        <a href="/search/rooms" class="flex w-full items-center justify-center overflow-hidden rounded-full h-14 sm:h-16 shadow-lg bg-gradient-to-r from-blue-600 to-blue-500 text-white text-lg sm:text-xl font-bold leading-normal tracking-[0.015em] hover:from-blue-500 hover:to-blue-400 transition-all duration-300 animate-fade-in-up delay-500">
          <i class="fas fa-search mr-3"></i>
          <span>Trouver une salle</span>
        </a>
      </div>
    </section>

    <!-- Nouveautés Section -->
    <section class="pt-8 pb-16 bg-white relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-32 h-32 bg-accent-magenta rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-48 h-48 bg-accent-cyan rounded-full blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-gray-800 text-2xl sm:text-3xl font-display font-bold leading-tight tracking-tight px-4 pb-6">Nouveautés</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div v-for="(item, index) in newItems" :key="index" class="w-full group">
            <div class="flex flex-col h-full gap-4 rounded-lg bg-gray-50 shadow-lg hover:shadow-2xl hover:shadow-blue-200/20 border border-gray-200">
              <div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-t-lg" :style="'background-image: url(' + item.image + ')'"></div>
              <div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4">
                <div>
                  <p class="text-gray-800 text-lg font-bold leading-normal">{{ item.title }}</p>
                  <p class="text-gray-600 text-sm font-normal leading-normal">{{ item.subtitle }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Salles Populaires Section -->
    <section class="py-16 bg-gray-50 relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-15">
        <div class="absolute top-1/4 right-20 w-40 h-40 bg-accent-yellow rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 left-20 w-36 h-36 bg-accent-magenta rounded-full blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-gray-800 text-3xl font-display font-bold leading-tight tracking-tight px-4 pb-6">Salles Populaires</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="(room, index) in popularRooms" :key="index" class="flex flex-col gap-4 rounded-lg shadow-lg overflow-hidden group bg-white border border-gray-200">
            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover group-hover:brightness-110" :style="'background-image: url(' + room.image + ')'""></div>
            <div class="flex flex-col p-4 pt-0 gap-3">
              <h3 class="text-gray-800 text-xl font-bold">{{ room.name }}</h3>
              <div class="flex items-center justify-between text-sm text-gray-600">
                <div class="flex items-center gap-1">
                  <i class="fas fa-star text-accent-yellow !text-xl"></i>
                  <span class="font-bold text-gray-800">{{ room.rating }}</span> ({{ room.reviews }} avis)
                </div>
                <span class="font-semibold">~ {{ room.distance }}</span>
              </div>
              <p class="text-sm text-gray-600">À partir de <span class="font-bold text-lg text-blue-600">{{ room.price }}/h</span></p>
              <button class="w-full mt-2 flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-11 px-5" :class="room.featured ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-200 hover:bg-blue-600 hover:text-white text-gray-800'" @click="viewRoom(room.id)">
                <span class="truncate">Voir la salle</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Comment ça marche Section -->
    <section class="py-16 bg-white relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/3 left-1/4 w-44 h-44 bg-gradient-to-r from-accent-cyan to-accent-magenta rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/3 right-1/4 w-52 h-52 bg-gradient-to-r from-accent-magenta to-accent-yellow rounded-full blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-gray-800 text-center text-3xl font-display font-bold leading-tight tracking-tight pb-12">Comment ça marche ?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
          <div v-for="(step, index) in howItWorks" :key="index" class="flex flex-col items-center gap-4 group">
            <div class="flex items-center justify-center w-24 h-24 rounded-full border-2 mb-4 bg-gray-50" :class="step.borderColor">
              <i class="fas" :class="step.iconClass" style="font-size: 3.125rem;"></i>
            </div>
            <h3 class="text-gray-800 text-xl font-bold font-display">{{ step.title }}</h3>
            <p class="text-gray-600">{{ step.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="w-full border-t border-t-gray-700 mt-20 bg-gray-900">
      <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
        <div class="col-span-2 lg:col-span-1">
          <div class="flex items-center gap-2 text-white">
            <i class="fas fa-gamepad text-3xl text-blue-400"></i>
            <h2 class="text-white text-2xl font-display font-bold">GameOn</h2>
          </div>
          <p class="text-gray-300 mt-4 text-sm">La plus grande communauté de joueurs et de salles de jeux.</p>
        </div>
        <div>
          <h4 class="font-display font-bold text-white mb-4">Navigation</h4>
          <ul class="space-y-3">
            <li v-for="(link, index) in footerLinks.navigation" :key="index">
              <a :href="link.href" class="text-gray-300 hover:text-white transition-colors">{{ link.label }}</a>
            </li>
          </ul>
        </div>
        <div>
          <h4 class="font-display font-bold text-white mb-4">Aide</h4>
          <ul class="space-y-3">
            <li v-for="(link, index) in footerLinks.help" :key="index">
              <a :href="link.href" class="text-gray-300 hover:text-white transition-colors">{{ link.label }}</a>
            </li>
          </ul>
        </div>
        <div>
          <h4 class="font-display font-bold text-white mb-4">Légal</h4>
          <ul class="space-y-3">
            <li v-for="(link, index) in footerLinks.legal" :key="index">
              <a :href="link.href" class="text-gray-300 hover:text-white transition-colors">{{ link.label }}</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="max-w-7xl mx-auto px-6 py-6 border-t border-t-gray-700">
        <p class="text-center text-sm text-gray-500"> 2024 GameOn. Tous droits réservés.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue';

// Data
const searchQuery = ref('');
const location = ref('');

const newItems = ref([
  {
    title: 'PlayStation 5',
    subtitle: 'Nouvelle Génération',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuD69TFuViF7AheUFy17TAyvRJAUQDSjQvthTej3ob3xgQmrxiCd4iSWk-15WKVVXVg7iR7MRVXLP69kARZpApmxUiFWTjQupx5knfihjq16_6LiKHOGALdCgtZqEbqj2Lx05w_E9SPXIfRnzOvrlMaC70jZ0GoQHNJ-FSg8ND7bjfjfHmdw_MqQ3tRBQr7BrN_nw6e1VHb8V5ourB1d5hDkm0qFuKQ6IGF7YjTjMHDGjCnqQ2mjuzhCipJ53hS8D4cuaPDgHOyyhOgE'
  },
  {
    title: 'VR Quest 3',
    subtitle: 'Immersion Totale',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDZk-K3kdE3ajUoJvzYE-P5V9EppOKL8LPxzcGNhBBEFNBXC7F5jYXrYrwDrW0P4VKmutq7EmadaTVN_b9AVhEasTSc4KYfjGVTFn0s903IJxUxwFXFS_K1QsZ4gFcXhPRD0FaretJixko9EAwJGx96RTLdxrfXwfeugzGsGp-jYct8KgevHhFT-0FSU6WxM0SZ5Phpkqu5Q6RHdudPx25ttQcImu_6BD-CPUjTWe7VC8fHQDsTcVJp58dXTQnjEpHJh_Ba6CrOPUsD'
  },
  {
    title: 'Manette Pro-X',
    subtitle: 'Précision Extrême',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCYHh5IqU9m7sGzI6nN-2TOY3cjqw6LvCPwENSrv1VEvphNaLlp8oKX_ChHWzDiAi9cQHqZzYOB5KUtfhaLcjJW_UOgoH9tS0xJuEpB9hPjC-ug8sBblwZq9yB1nXRMBDpKZy9x-ckdOciw7G1dgP5bJQflmdbedf6-LEwhU_rUgZPWNclac2ejM5-wf7h7ZqLDxaZ26KdjbR7S9QmN2aHgH3b6Wrcxc1LAK-t53YktCKAWh_nWLzRxSqobo14Awculpec_FS2SU2pt'
  },
  {
    title: 'CyberHero 2088',
    subtitle: 'Dernière Sortie',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBoU7Oags87biuaaffc3aUGrDFW5kFUjxEbccg4ySsGzsmxpxUZoRY2m7gAWMY7xIqU5nQjefm2KQjibIT2Hn_fQ3SdFNqkMNgzCgtF5a09GtEpv59x7uIkqZv4UpDF2HYh4zSbA0MCObwDjUz2idQ4vU2ENeaBSQ2iPep1DeeAcN_oAIIdNDnfg7akYdQerYMQlTC9XfCixtt-VjVhodttIg_eETqbYCDuTTEmffxXDM6qfWkOTz8KXI4KVTjJnAwgnv_KLV0d0okk'
  },
  {
    title: 'PC Gamer Ultimate',
    subtitle: 'Puissance Max',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAFHU-O26YCVGb8nn72fw_t3zCT0ekH-nFV7acoJIzDsxjIR-Lc_K7kG27Cdlw2y9G6cjOtbMeCCTpb6fpvIprI0mmn5ex2yb-BXmD0L6KlPW5rU6p-lXc__1mF2es4ZEWq3q4ApJbjlDkC2TSC7mq-_NE0vXwbHX6WGf4RibZubgcspYf4t8fKul0l8KUZrUNKhCv41euF-GtVhgfA3ESe3VG4R69TUjr3MK0L6Mo_FiYuXRADPqlhMa6yL4JRSgTFG6wPWKvxjCOM'
  }
]);

const popularRooms = ref([
  {
    id: 1,
    name: 'Cyber Arena',
    rating: 4.8,
    reviews: 120,
    distance: '2 km',
    price: '12€',
    featured: true,
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDZk-K3kdE3ajUoJvzYE-P5V9EppOKL8LPxzcGNhBBEFNBXC7F5jYXrYrwDrW0P4VKmutq7EmadaTVN_b9AVhEasTSc4KYfjGVTFn0s903IJxUxwFXFS_K1QsZ4gFcXhPRD0FaretJixko9EAwJGx96RTLdxrfXwfeugzGsGp-jYct8KgevHhFT-0FSU6WxM0SZ5Phpkqu5Q6RHdudPx25ttQcImu_6BD-CPUjTWe7VC8fHQDsTcVJp58dXTQnjEpHJh_Ba6CrOPUsD'
  },
  {
    id: 2,
    name: 'VR World Experience',
    rating: 4.9,
    reviews: 98,
    distance: '5 km',
    price: '25€',
    featured: false,
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCYHh5IqU9m7sGzI6nN-2TOY3cjqw6LvCPwENSrv1VEvphNaLlp8oKX_ChHWzDiAi9cQHqZzYOB5KUtfhaLcjJW_UOgoH9tS0xJuEpB9hPjC-ug8sBblwZq9yB1nXRMBDpKZy9x-ckdOciw7G1dgP5bJQflmdbedf6-LEwhU_rUgZPWNclac2ejM5-wf7h7ZqLDxaZ26KdjbR7S9QmN2aHgH3b6Wrcxc1LAK-t53YktCKAWh_nWLzRxSqabo14Awculpec_FS2SU2pt'
  },
  {
    id: 3,
    name: 'Retro Game Hub',
    rating: 4.7,
    reviews: 250,
    distance: '1.5 km',
    price: '8€',
    featured: false,
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDimTmosVRtitqU9V2oDIOQw50iGnF5QW3_KIKBaqu9WSJ6grhZy_x9Ae_lduBc_pSW7n6n0fdslfekYXLqoiES9JL0QHnKTOhiuS0amxXj43wWxYzVYUBCmQu2VSKPJVPWu7LoNMIQI1myV_R0FkUiHFHXNPfCG-wSmSlJgDp7jtXCr5fTgDvAynUYwM1PHaumBxDPSfTTJ40KvLX01F4PWrPOpTz6IGey33XXIKdUJHkgz2V0gqb2kltAbruyvqecqYp37qlscfYo'
  }
]);

const howItWorks = ref([
  {
    step: 1,
    title: 'Trouvez',
    description: 'Utilisez notre recherche intelligente pour découvrir les meilleures salles de jeux près de chez vous.',
    iconClass: 'fa-search text-accent-magenta',
    borderColor: 'border-accent-magenta'
  },
  {
    step: 2,
    title: 'Réservez',
    description: 'Sécurisez votre place en quelques clics, choisissez votre créneau et votre équipement.',
    iconClass: 'fa-calendar-plus text-gray-400',
    borderColor: 'border-accent-cyan'
  },
  {
    step: 3,
    title: 'Jouez',
    description: 'Présentez-vous à la salle et plongez dans l\'action. Profitez de l\'expérience !',
    iconClass: 'fa-gamepad text-accent-yellow',
    borderColor: 'border-accent-yellow'
  }
]);

const footerLinks = ref({
  navigation: [
    { label: 'Accueil', href: '/' },
    { label: 'Salles', href: '/salles' },
    { label: 'Événements', href: '#' },
    { label: 'Blog', href: '#' }
  ],
  help: [
    { label: 'FAQ', href: '#' },
    { label: 'Contactez-nous', href: '#' },
    { label: 'Support', href: '#' }
  ],
  legal: [
    { label: 'Conditions d\'utilisation', href: '#' },
    { label: 'Politique de confidentialité', href: '#' }
  ]
});

// Methods
function searchRooms() {
  if (searchQuery.value.trim()) {
    // Implémentez la logique de recherche ici
    console.log('Recherche pour:', searchQuery.value);
  }
}

function searchLocation() {
  if (location.value.trim()) {
    // Implémentez la logique de recherche par localisation ici
    console.log('Recherche de salles près de:', location.value);
  }
}

function viewRoom(id) {
  // Implémentez la navigation vers la page de la salle
  console.log('Voir la salle avec ID:', id);
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Inter:wght@400;500;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..200');

.material-symbols-outlined {
  font-variation-settings:
    'FILL' 0,
    'wght' 400,
    'GRAD' 0,
    'opsz' 24;
}

:root {
  --primary: #3b2bee;
  --background-light: #f6f6f8;
  --background-dark: #ffffff;
  --accent-magenta: #FF00FF;
  --accent-cyan: #00FFFF;
  --accent-yellow: #FDFD00;
  --accent-magenta-rgb: 255, 0, 255;
  --accent-cyan-rgb: 0, 255, 255;
  --accent-yellow-rgb: 253, 253, 0;
}

.dark {
  color-scheme: dark;
}

.font-display {
  font-family: 'Poppins', sans-serif;
}

.font-body {
  font-family: 'Inter', sans-serif;
}

html, body {
  background-color: var(--background-dark);
  margin: 0;
  padding: 0;
  min-height: 100%;
}

/* Custom styles can be added here if needed */


/* Scrollbar personnalisée */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f0f0f0;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(45deg, #FF00FF, #00FFFF);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(45deg, #FF00FF, #FDFD00);
}

/* Text Animations */
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s ease-out forwards;
  opacity: 0;
}

.delay-300 { animation-delay: 0.3s; }
.delay-500 { animation-delay: 0.5s; }

</style>