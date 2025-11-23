<template>
  <div class="min-h-screen bg-background-dark text-[#EAEAEA] font-body">
    <!-- TopNavBar -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center bg-background-dark/80 backdrop-blur-sm shadow-sm">
      <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-3 text-white">
            <svg class="w-7 h-7 text-accent-cyan" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
              <path clip-rule="evenodd" d="M24 18.4228L42 11.475V34.3663C42 34.7796 41.7457 35.1504 41.3601 35.2992L24 42V18.4228Z" fill="currentColor" fill-rule="evenodd"></path>
              <path clip-rule="evenodd" d="M24 8.18819L33.4123 11.574L24 15.2071L14.5877 11.574L24 8.18819ZM9 15.8487L21 20.4805V37.6263L9 32.9945V15.8487ZM27 37.6263V20.4805L39 15.8487V32.9945L27 37.6263ZM25.354 2.29885C24.4788 1.98402 23.5212 1.98402 22.646 2.29885L4.98454 8.65208C3.7939 9.08038 3 10.2097 3 11.475V34.3663C3 36.0196 4.01719 37.5026 5.55962 38.098L22.9197 44.7987C23.6149 45.0671 24.3851 45.0671 25.0803 44.7987L42.4404 38.098C43.9828 37.5026 45 36.0196 45 34.3663V11.475C45 10.2097 44.2061 9.08038 43.0155 8.65208L25.354 2.29885Z" fill="currentColor" fill-rule="evenodd"></path>
            </svg>
            <h2 class="text-white text-2xl font-display font-bold">GameOn</h2>
          </div>
        </div>
        <div class="hidden md:flex flex-1 justify-center">
          <label class="flex flex-col w-full max-w-sm h-11">
            <div class="flex w-full flex-1 items-stretch rounded-full h-full">
              <div class="text-[#9f9db9] flex bg-[#2a2839] items-center justify-center pl-4 rounded-l-full">
                <span class="material-symbols-outlined">search</span>
              </div>
              <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden text-white focus:outline-0 focus:ring-0 border-none bg-[#2a2839] h-full placeholder:text-[#9f9db9] px-4 rounded-r-full text-base font-normal leading-normal" placeholder="Rechercher une salle, un jeu..." v-model="searchQuery" @keyup.enter="searchRooms"/>
            </div>
          </label>
        </div>
        <div class="flex items-center gap-3">
          <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-[#2a2839] text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#3c3a50] transition-colors">
            <span class="truncate">Connexion</span>
          </button>
          <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-accent-magenta text-white text-sm font-bold leading-normal tracking-[0.015em] hover:brightness-110 transition-all">
            <span class="truncate">S'inscrire</span>
          </button>
        </div>
      </div>
    </header>
    <!-- HeroSection -->
    <section class="relative flex min-h-[80vh] sm:min-h-screen flex-col gap-6 sm:gap-8 items-center justify-center text-center py-16 sm:py-20 -mx-4 sm:-mx-6">
      <div class="absolute inset-0 bg-cover bg-center bg-no-repeat z-0" style='background-image: linear-gradient(rgba(18, 17, 24, 0.8) 0%, rgba(18, 17, 24, 1) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuAvMkKqOU-KKBFEfq-XSqP8ERc3OcUD8c_cPSMVMQ51u7hagAf8c_kCEfAd86ta1yjbIE-5sLkK3wUnDLhWLOTh7SEnmUTk7eYbIG2ctck4ibodkjHwNZR2qQjYZjnEEW9bMiU8oTRROJKW5ErST-A-PlzGsUI-_7EzmG6n9rQEd02EfnEiJBNocXkEh4Q0lpRwJpxFjFSADix08DdbLqgv3LAEtZgVoQTgq8Fg9pcuOHKYf1A191jXTu07_i2G61JZjs70Bep8nXg5");'></div>
      <div class="relative z-10 flex flex-col gap-3 sm:gap-4 items-center w-full px-4 max-w-4xl">
        <h1 class="text-white text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-display font-bold leading-tight tracking-tighter">
          Votre Prochaine Partie Commence Ici.
        </h1>
        <h2 class="text-white/80 text-base sm:text-lg md:text-xl font-normal leading-normal max-w-3xl px-2">
          Découvrez et réservez des milliers de salles de jeux et d'expériences près de chez vous.
        </h2>
      </div>
      <div class="relative z-10 w-full max-w-2xl px-4">
        <label class="flex flex-col h-14 sm:h-16 w-full">
          <div class="flex w-full flex-1 items-stretch rounded-full h-full shadow-lg">
            <div class="text-[#9f9db9] flex border border-accent-cyan/50 bg-[#1c1c27] items-center justify-center pl-4 sm:pl-5 rounded-l-full border-r-0">
              <span class="material-symbols-outlined text-xl sm:text-2xl">location_on</span>
            </div>
            <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden text-white focus:outline-0 focus:ring-2 focus:ring-accent-cyan/80 border border-accent-cyan/50 bg-[#1c1c27] h-full placeholder:text-[#9f9db9] px-3 sm:px-4 rounded-none border-l-0 border-r-0 text-sm sm:text-base font-normal leading-normal" 
                   placeholder="Entrez une ville, une adresse..." 
                   v-model="location"/>
            <div class="flex items-center justify-center rounded-r-full border-l-0 border border-accent-cyan/50 bg-[#1c1c27] pr-1 sm:pr-2">
              <button class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-full h-11 sm:h-12 px-4 sm:px-6 bg-accent-cyan text-black text-sm sm:text-base font-bold leading-normal tracking-[0.015em] hover:brightness-110 transition-all" 
                      @click="searchLocation">
                <span class="truncate">Trouver une salle</span>
              </button>
            </div>
          </div>
        </label>
      </div>
    </section>

    <!-- Nouveautés Section -->
    <section class="pt-8 pb-16 bg-background-dark">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-white text-2xl sm:text-3xl font-display font-bold leading-tight tracking-tight px-4 pb-6">Nouveautés</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div v-for="(item, index) in newItems" :key="index" class="w-full">
            <div class="flex flex-col h-full gap-4 rounded-lg bg-[#1c1c27] shadow-lg transform hover:-translate-y-1 transition-transform duration-300">
              <div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-t-lg" :style="'background-image: url(' + item.image + ')'"></div>
              <div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4">
                <div>
                  <p class="text-white text-lg font-bold leading-normal">{{ item.title }}</p>
                  <p class="text-[#9f9db9] text-sm font-normal leading-normal">{{ item.subtitle }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Salles Populaires Section -->
    <section class="py-16 bg-background-dark">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-white text-3xl font-display font-bold leading-tight tracking-tight px-4 pb-6">Salles Populaires</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="(room, index) in popularRooms" :key="index" class="flex flex-col gap-4 rounded-lg bg-[#1c1c27] shadow-lg overflow-hidden group">
            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover transition-transform duration-300 group-hover:scale-105" :style="'background-image: url(' + room.image + ')'"></div>
            <div class="flex flex-col p-4 pt-0 gap-3">
              <h3 class="text-white text-xl font-bold">{{ room.name }}</h3>
              <div class="flex items-center justify-between text-sm text-[#9f9db9]">
                <div class="flex items-center gap-1">
                  <span class="material-symbols-outlined text-accent-yellow !text-xl" style="font-variation-settings: 'FILL' 1">star</span>
                  <span class="font-bold text-white">{{ room.rating }}</span> ({{ room.reviews }} avis)
                </div>
                <span class="font-semibold">~ {{ room.distance }}</span>
              </div>
              <p class="text-sm text-white">À partir de <span class="font-bold text-lg text-accent-cyan">{{ room.price }}/h</span></p>
              <button class="w-full mt-2 flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-11 px-5" :class="room.featured ? 'bg-accent-magenta' : 'bg-[#2a2839] hover:bg-accent-magenta'" @click="viewRoom(room.id)">
                <span class="truncate">Voir la salle</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Comment ça marche Section -->
    <section class="py-16 bg-background-dark">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-white text-center text-3xl font-display font-bold leading-tight tracking-tight pb-12">Comment ça marche ?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
          <div v-for="(step, index) in howItWorks" :key="index" class="flex flex-col items-center gap-4">
            <div class="flex items-center justify-center w-24 h-24 rounded-full bg-[#1c1c27] border-2 mb-4" :class="step.borderColor">
              <span class="material-symbols-outlined !text-5xl" :class="step.iconColor">{{ step.icon }}</span>
            </div>
            <h3 class="text-white text-xl font-bold font-display">{{ step.step }}. {{ step.title }}</h3>
            <p class="text-[#9f9db9]">{{ step.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="w-full border-t border-t-[#2a2839] mt-20 bg-background-dark">
      <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
        <div class="col-span-2 lg:col-span-1">
          <div class="flex items-center gap-3 text-white">
            <svg class="w-7 h-7 text-accent-cyan" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
              <path clip-rule="evenodd" d="M24 18.4228L42 11.475V34.3663C42 34.7796 41.7457 35.1504 41.3601 35.2992L24 42V18.4228Z" fill="currentColor" fill-rule="evenodd"></path>
              <path clip-rule="evenodd" d="M24 8.18819L33.4123 11.574L24 15.2071L14.5877 11.574L24 8.18819ZM9 15.8487L21 20.4805V37.6263L9 32.9945V15.8487ZM27 37.6263V20.4805L39 15.8487V32.9945L27 37.6263ZM25.354 2.29885C24.4788 1.98402 23.5212 1.98402 22.646 2.29885L4.98454 8.65208C3.7939 9.08038 3 10.2097 3 11.475V34.3663C3 36.0196 4.01719 37.5026 5.55962 38.098L22.9197 44.7987C23.6149 45.0671 24.3851 45.0671 25.0803 44.7987L42.4404 38.098C43.9828 37.5026 45 36.0196 45 34.3663V11.475C45 10.2097 44.2061 9.08038 43.0155 8.65208L25.354 2.29885Z" fill="currentColor" fill-rule="evenodd"></path>
            </svg>
            <h2 class="text-white text-2xl font-display font-bold">GameOn</h2>
          </div>
          <p class="text-[#9f9db9] mt-4 text-sm">La plus grande communauté de joueurs et de salles de jeux.</p>
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
      <div class="max-w-7xl mx-auto px-6 py-6 border-t border-t-[#2a2839]">
        <p class="text-center text-sm text-[#9f9db9]">© 2024 GameOn. Tous droits réservés.</p>
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
    icon: 'search',
    borderColor: 'border-accent-magenta',
    iconColor: 'text-accent-magenta'
  },
  {
    step: 2,
    title: 'Réservez',
    description: 'Sécurisez votre place en quelques clics, choisissez votre créneau et votre équipement.',
    icon: 'calendar_add_on',
    borderColor: 'border-accent-cyan',
    iconColor: 'text-accent-cyan'
  },
  {
    step: 3,
    title: 'Jouez',
    description: 'Présentez-vous à la salle et plongez dans l\'action. Profitez de l\'expérience !',
    icon: 'stadia_controller',
    borderColor: 'border-accent-yellow',
    iconColor: 'text-accent-yellow'
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
</style>