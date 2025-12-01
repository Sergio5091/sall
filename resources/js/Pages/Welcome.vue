<template>
  <div class="min-h-screen text-[#222222] font-body">
    <!-- TopNavBar -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center backdrop-blur-sm shadow-sm">
      <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-2 text-white">
            <i class="fas fa-gamepad text-3xl text-gray-400"></i>
            <h2 class="text-white text-2xl font-display font-bold">GameOn</h2>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <a href="/login" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-600 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-500 transition-colors">
            <span class="truncate">Connexion</span>
          </a>
          <a href="/register" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-700 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-600 transition-all">
            <span class="truncate">S'inscrire</span>
          </a>
        </div>
      </div>
    </header>
    <!-- HeroSection -->
    <section class="relative flex min-h-[80vh] sm:min-h-screen flex-col gap-6 sm:gap-8 items-center justify-center text-center py-16 sm:py-20 w-full overflow-x-hidden">
      <!-- Animation 3D Background -->
      <Background3D />
      <!-- Overlay gradient pour la lisibilité -->
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/20 to-black/60 z-5"></div>
      <div class="relative z-10 flex flex-col gap-3 sm:gap-4 items-center w-full px-4 max-w-4xl">
        <h1 class="text-white text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-display font-bold leading-tight tracking-tighter">
          Votre Prochaine Partie Commence Ici.
        </h1>
        <h2 class="text-white/80 text-base sm:text-lg md:text-xl font-normal leading-normal max-w-3xl px-2">
          Découvrez et réservez des milliers de salles de jeux et d'expériences près de chez vous.
        </h2>
      </div>
      <div class="relative z-10 w-full max-w-2xl px-4">
        <a href="/search/rooms" class="flex w-full items-center justify-center overflow-hidden rounded-full h-14 sm:h-16 shadow-lg bg-gradient-to-r from-gray-600 to-gray-500 text-white text-lg sm:text-xl font-bold leading-normal tracking-[0.015em] hover:from-gray-500 hover:to-gray-400 transition-all duration-300 transform hover:scale-105">
          <i class="fas fa-search mr-3"></i>
          <span>Trouver une salle</span>
        </a>
      </div>
    </section>

    <!-- Nouveautés Section -->
    <section class="pt-8 pb-16 bg-background-dark relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-32 h-32 bg-accent-magenta rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-48 h-48 bg-accent-cyan rounded-full blur-3xl animate-pulse delay-1000"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-white text-2xl sm:text-3xl font-display font-bold leading-tight tracking-tight px-4 pb-6 transform hover:scale-105 transition-transform duration-300">Nouveautés</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div v-for="(item, index) in newItems" :key="index" class="w-full group">
            <div class="flex flex-col h-full gap-4 rounded-lg bg-[#1c1c27] shadow-lg transform hover:-translate-y-2 hover:rotate-1 transition-all duration-500 hover:shadow-2xl hover:shadow-accent-cyan/20">
              <div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-t-lg transform group-hover:scale-110 transition-transform duration-700" :style="'background-image: url(' + item.image + ')'"></div>
              <div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4">
                <div>
                  <p class="text-white text-lg font-bold leading-normal transform group-hover:translate-x-1 transition-transform duration-300">{{ item.title }}</p>
                  <p class="text-white text-sm font-normal leading-normal">{{ item.subtitle }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Salles Populaires Section -->
    <section class="py-16 bg-background-dark relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-15">
        <div class="absolute top-1/4 right-20 w-40 h-40 bg-accent-yellow rounded-full blur-3xl animate-pulse delay-500"></div>
        <div class="absolute bottom-1/4 left-20 w-36 h-36 bg-accent-magenta rounded-full blur-3xl animate-pulse delay-1500"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-white text-3xl font-display font-bold leading-tight tracking-tight px-4 pb-6 transform hover:scale-105 transition-transform duration-300">Salles Populaires</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="(room, index) in popularRooms" :key="index" class="flex flex-col gap-4 rounded-lg shadow-lg overflow-hidden group transform hover:-translate-y-3 transition-all duration-500">
            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover transition-all duration-700 group-hover:scale-110 group-hover:brightness-110" :style="'background-image: url(' + room.image + ')'"></div>
            <div class="flex flex-col p-4 pt-0 gap-3">
              <h3 class="text-white text-xl font-bold transform group-hover:translate-x-2 transition-transform duration-300">{{ room.name }}</h3>
              <div class="flex items-center justify-between text-sm text-[#9f9db9]">
                <div class="flex items-center gap-1">
                  <i class="fas fa-star text-accent-yellow !text-xl transform group-hover:rotate-12 transition-transform duration-300"></i>
                  <span class="font-bold text-white">{{ room.rating }}</span> ({{ room.reviews }} avis)
                </div>
                <span class="font-semibold">~ {{ room.distance }}</span>
              </div>
              <p class="text-sm text-white">À partir de <span class="font-bold text-lg text-gray-400">{{ room.price }}/h</span></p>
              <button class="w-full mt-2 flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-11 px-5 transform hover:scale-105 transition-all duration-300" :class="room.featured ? 'bg-accent-magenta hover:bg-accent-magenta/90' : 'bg-[#2a2839] hover:bg-accent-magenta'" @click="viewRoom(room.id)">
                <span class="truncate">Voir la salle</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Comment ça marche Section -->
    <section class="py-16 relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/3 left-1/4 w-44 h-44 bg-gradient-to-r from-accent-cyan to-accent-magenta rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/3 right-1/4 w-52 h-52 bg-gradient-to-r from-accent-magenta to-accent-yellow rounded-full blur-3xl animate-pulse delay-700"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-white text-center text-3xl font-display font-bold leading-tight tracking-tight pb-12 transform hover:scale-105 transition-transform duration-300">Comment ça marche ?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
          <div v-for="(step, index) in howItWorks" :key="index" class="flex flex-col items-center gap-4 group transform hover:-translate-y-2 transition-all duration-500">
            <div class="flex items-center justify-center w-24 h-24 rounded-full border-2 mb-4 bg-[#1c1c27] transform group-hover:rotate-6 group-hover:scale-110 transition-all duration-500" :class="step.borderColor">
              <i class="fas" :class="step.iconClass" style="font-size: 3.125rem;"></i>
            </div>
            <h3 class="text-white text-xl font-bold font-display transform group-hover:scale-105 transition-transform duration-300">{{ step.title }}</h3>
            <p class="text-[#9f9db9] transform group-hover:translate-y-1 transition-transform duration-300">{{ step.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="w-full border-t border-t-[#e5e7eb] mt-20">
      <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
        <div class="col-span-2 lg:col-span-1">
          <div class="flex items-center gap-2 text-white">
            <i class="fas fa-gamepad text-3xl text-gray-400"></i>
            <h2 class="text-white text-2xl font-display font-bold">GameOn</h2>
          </div>
          <p class="text-[#6b7280] mt-4 text-sm">La plus grande communauté de joueurs et de salles de jeux.</p>
        </div>
        <div>
          <h4 class="font-display font-bold text-white mb-4">Navigation</h4>
          <ul class="space-y-3">
            <li v-for="(link, index) in footerLinks.navigation" :key="index">
              <a :href="link.href" class="text-[#9f9db9] hover:text-white transition-colors">{{ link.label }}</a>
            </li>
          </ul>
        </div>
        <div>
          <h4 class="font-display font-bold text-white mb-4">Aide</h4>
          <ul class="space-y-3">
            <li v-for="(link, index) in footerLinks.help" :key="index">
              <a :href="link.href" class="text-[#9f9db9] hover:text-white transition-colors">{{ link.label }}</a>
            </li>
          </ul>
        </div>
        <div>
          <h4 class="font-display font-bold text-white mb-4">Légal</h4>
          <ul class="space-y-3">
            <li v-for="(link, index) in footerLinks.legal" :key="index">
              <a :href="link.href" class="text-[#9f9db9] hover:text-white transition-colors">{{ link.label }}</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="max-w-7xl mx-auto px-6 py-6 border-t border-t-[#e5e7eb]">
        <p class="text-center text-sm text-[#6b7280]"> 2024 GameOn. Tous droits réservés.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Background3D from '@/Components/Background3D.vue';

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
    { label: 'Accueil', href: '#' },
    { label: 'Salles', href: '#' },
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
  --background-dark: #121118;
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

/* Animations personnalisées */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}

@keyframes glow {
  0%, 100% { 
    box-shadow: 0 0 20px rgba(255, 0, 255, 0.5),
                0 0 40px rgba(0, 255, 255, 0.3);
  }
  50% { 
    box-shadow: 0 0 30px rgba(255, 0, 255, 0.8),
                0 0 60px rgba(0, 255, 255, 0.5);
  }
}

/* Classes d'animation */
.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-glow {
  animation: glow 2s ease-in-out infinite alternate;
}

/* Effet de parallaxe subtil */
.parallax-slow {
  will-change: transform;
  transition: transform 0.3s ease-out;
}

/* Amélioration des transitions */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

.group:hover .group-hover\:rotate-12 {
  transform: rotate(12deg);
}

/* Scrollbar personnalisée */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #1c1c27;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(45deg, #FF00FF, #00FFFF);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(45deg, #FF00FF, #FDFD00);
}
</style>