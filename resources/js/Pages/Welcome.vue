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
            <a href="/" class="text-blue-600 hover:text-blue-700 px-3 py-2 text-sm font-medium border-b-2 border-blue-600">Accueil</a>
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
      <!-- Gaming Background Animation -->
      <div class="absolute inset-0 z-0">
        <!-- Base gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-gray-800 via-gray-700 to-gray-900"></div>
        
        <!-- Animated gaming elements -->
        <div class="absolute inset-0">
          <!-- Floating gamepad icon -->
          <div class="absolute top-20 left-10 text-6xl text-blue-400 opacity-40 animate-float">
            <i class="fas fa-gamepad"></i>
          </div>
          
          <!-- Floating controller -->
          <div class="absolute top-40 right-20 text-5xl text-purple-400 opacity-40 animate-float-delay">
            <i class="fas fa-gamepad"></i>
          </div>
          
          <!-- Floating trophy -->
          <div class="absolute bottom-30 left-20 text-4xl text-gray-400 opacity-40 animate-float-delay-2">
            <i class="fas fa-trophy"></i>
          </div>
          
          <!-- Floating star -->
          <div class="absolute top-60 left-1/3 text-3xl text-cyan-400 opacity-40 animate-float">
            <i class="fas fa-star"></i>
          </div>
          
          <!-- Floating fire -->
          <div class="absolute bottom-40 right-1/3 text-5xl text-orange-400 opacity-40 animate-float-delay">
            <i class="fas fa-fire"></i>
          </div>
          
          <!-- Floating crown -->
          <div class="absolute top-80 right-10 text-4xl text-cyan-300 opacity-50 animate-float-delay-2">
            <i class="fas fa-crown"></i>
          </div>
          
          <!-- Floating rocket -->
          <div class="absolute bottom-60 left-1/4 text-3xl text-green-400 opacity-40 animate-float">
            <i class="fas fa-rocket"></i>
          </div>
          
          <!-- Floating gem -->
          <div class="absolute top-1/3 right-1/4 text-4xl text-indigo-400 opacity-40 animate-float-delay">
            <i class="fas fa-gem"></i>
          </div>
        </div>
        
        <!-- Animated particles -->
        <div class="absolute inset-0">
          <div class="particles">
            <div class="particle particle-1"></div>
            <div class="particle particle-2"></div>
            <div class="particle particle-3"></div>
            <div class="particle particle-4"></div>
            <div class="particle particle-5"></div>
            <div class="particle particle-6"></div>
            <div class="particle particle-7"></div>
            <div class="particle particle-8"></div>
          </div>
        </div>
        
        <!-- Gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/30"></div>
      </div>
      
      <!-- Content -->
      <div class="relative z-10 flex flex-col gap-3 sm:gap-4 items-center w-full px-4 max-w-4xl">
        <h1 class="text-white text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-display font-bold leading-tight tracking-tighter animate-fade-in-up drop-shadow-lg">
          Votre Prochaine Partie Commence Ici.
        </h1>
        <h2 class="text-white text-base sm:text-lg md:text-xl font-normal leading-normal max-w-3xl px-2 animate-fade-in-up delay-300 drop-shadow-md">
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
    <section class="pt-8 pb-16 bg-gray-50 relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-32 h-32 bg-gray-600 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-48 h-48 bg-gray-500 rounded-full blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-gray-800 text-2xl sm:text-3xl font-display font-bold leading-tight tracking-tight px-4 pb-6 animate-fade-in-up">Nouveautés</h2>
        
        <!-- Carousel Container -->
        <div class="relative">
          <!-- Carousel Track -->
          <div class="overflow-hidden rounded-xl">
            <div 
              class="flex transition-all duration-700 ease-out"
              :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
            >
              <div 
                v-for="(slide, slideIndex) in carouselSlides" 
                :key="slideIndex"
                class="w-full flex-shrink-0"
              >
                <div class="flex gap-6 p-2">
                  <div 
                    v-for="(item, itemIndex) in slide" 
                    :key="itemIndex"
                    class="w-full group animate-fade-in-up" :style="{ animationDelay: `${itemIndex * 100}ms` }"
                  >
                    <div class="flex flex-col gap-4 rounded-xl shadow-lg overflow-hidden group bg-white border border-gray-200 transform transition-all duration-500 hover:scale-105 hover:shadow-2xl">
                      <div class="relative overflow-hidden">
                        <div class="w-full bg-center bg-no-repeat aspect-video bg-cover transition-transform duration-700 group-hover:scale-110" :style="'background-image: url(' + item.image + ')'"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                      </div>
                      <div class="flex flex-col p-4 pt-0 gap-3">
                        <div>
                          <h3 class="text-gray-800 text-xl font-bold transform transition-transform duration-300 group-hover:translate-x-1">{{ item.title }}</h3>
                          <p class="text-gray-600 text-sm font-normal transform transition-transform duration-300 group-hover:translate-x-1">{{ item.subtitle }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Navigation Buttons -->
          <button 
            @click="prevSlide"
            class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-md rounded-full p-3 shadow-xl hover:bg-white hover:shadow-2xl transition-all duration-300 z-10 transform hover:scale-110"
          >
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          
          <button 
            @click="nextSlide"
            class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-md rounded-full p-3 shadow-xl hover:bg-white hover:shadow-2xl transition-all duration-300 z-10 transform hover:scale-110"
          >
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </button>

          <!-- Progress Indicator -->
          <div class="flex justify-center gap-2 mt-6">
            <button 
              v-for="(slide, index) in carouselSlides" 
              :key="index"
              @click="goToSlide(index)"
              :class="[
                'h-2 rounded-full transition-all duration-500 ease-out',
                currentSlide === index 
                  ? 'bg-gradient-to-r from-blue-500 to-blue-600 w-8 shadow-lg' 
                  : 'bg-gray-300 hover:bg-gray-400 w-2'
              ]"
            />
          </div>
        </div>
      </div>
    </section>
    <!-- Salles Populaires Section -->
    <section class="py-16 bg-gray-50 relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-15">
        <div class="absolute top-1/4 right-20 w-40 h-40 bg-gray-600 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 left-20 w-36 h-36 bg-gray-500 rounded-full blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-gray-800 text-3xl font-display font-bold leading-tight tracking-tight px-4 pb-6 animate-fade-in-up delay-300">Salles Populaires</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="(room, index) in popularRooms" :key="index" class="flex flex-col gap-4 rounded-lg shadow-lg overflow-hidden group bg-white border border-gray-200 animate-fade-in-up" :style="{ animationDelay: `${index * 150}ms` }">
            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover group-hover:brightness-110 transition-all duration-500" :style="'background-image: url(' + room.image + ')'"></div>
            <div class="flex flex-col p-4 pt-0 gap-3">
              <h3 class="text-gray-800 text-xl font-bold transform transition-transform duration-300 group-hover:translate-x-1">{{ room.name }}</h3>
              <div class="flex items-center justify-between text-sm text-gray-600">
                <div class="flex items-center gap-1 transform transition-transform duration-300 group-hover:scale-110">
                  <i class="fas fa-star text-gray-500 !text-xl"></i>
                  <span>{{ room.rating }}</span>
                </div> ({{ room.reviews }} avis)
                <span class="font-semibold transform transition-transform duration-300 group-hover:translate-x-1">~ {{ room.distance }}</span>
              </div>
              <p class="text-sm text-gray-600 transform transition-transform duration-300 group-hover:translate-x-1">À partir de <span class="font-bold text-lg text-blue-600">{{ room.price }}/h</span></p>
              <button class="w-full mt-2 flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-11 px-5 transform transition-all duration-300 hover:scale-105" :class="room.featured ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-200 hover:bg-blue-600 hover:text-white text-gray-800'" @click="viewRoom(room.id)">
                <span class="truncate">Voir la salle</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Comment ça marche Section -->
    <section class="py-16 bg-gray-50 relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/3 left-1/4 w-44 h-44 bg-gradient-to-r from-gray-600 to-gray-500 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/3 right-1/4 w-52 h-52 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-gray-800 text-center text-3xl font-display font-bold leading-tight tracking-tight pb-12 animate-fade-in-up delay-600">Comment ça marche ?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
          <div v-for="(step, index) in howItWorks" :key="index" class="flex flex-col items-center gap-4 group animate-fade-in-up" :style="{ animationDelay: `${index * 200}ms` }">
            <div class="flex items-center justify-center w-24 h-24 rounded-full border-2 mb-4 bg-gray-50 transform transition-all duration-500 group-hover:scale-110 group-hover:shadow-lg" :class="step.borderColor">
              <i class="fas transform transition-transform duration-300 group-hover:rotate-12" :class="step.iconClass" style="font-size: 3.125rem;"></i>
            </div>
            <h3 class="text-gray-800 text-xl font-bold font-display transform transition-transform duration-300 group-hover:translate-y-1">{{ step.title }}</h3>
            <p class="text-gray-600 transform transition-all duration-300 group-hover:text-gray-700">{{ step.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div class="col-span-1 md:col-span-2">
            <div class="flex items-center gap-2 mb-4">
              <i class="fas fa-gamepad text-3xl text-blue-400"></i>
              <h3 class="text-2xl font-bold">GameOn</h3>
            </div>
            <p class="text-gray-300 mb-4">Votre plateforme pour trouver et réserver les meilleures salles de jeux près de chez vous.</p>
            <div class="flex gap-4">
              <a href="#" class="text-gray-400 hover:text-white transition-colors">
                <i class="fab fa-facebook text-xl"></i>
              </a>
              <a href="#" class="text-gray-400 hover:text-white transition-colors">
                <i class="fab fa-twitter text-xl"></i>
              </a>
              <a href="#" class="text-gray-400 hover:text-white transition-colors">
                <i class="fab fa-instagram text-xl"></i>
              </a>
              <a href="#" class="text-gray-400 hover:text-white transition-colors">
                <i class="fab fa-linkedin text-xl"></i>
              </a>
            </div>
          </div>
          
          <div>
            <h4 class="text-lg font-semibold mb-4">Navigation</h4>
            <ul class="space-y-2">
              <li><a href="/" class="text-gray-300 hover:text-white transition-colors">Accueil</a></li>
              <li><a href="/search/rooms" class="text-gray-300 hover:text-white transition-colors">Salles</a></li>
              <li><a href="/events" class="text-gray-300 hover:text-white transition-colors">Événements</a></li>
              <li><a href="/about" class="text-gray-300 hover:text-white transition-colors">À propos</a></li>
            </ul>
          </div>
          
          <div>
            <h4 class="text-lg font-semibold mb-4">Aide</h4>
            <ul class="space-y-2">
              <li><a href="/help" class="text-gray-300 hover:text-white transition-colors">Centre d'aide</a></li>
              <li><a href="/contact" class="text-gray-300 hover:text-white transition-colors">Contact</a></li>
              <li><a href="/faq" class="text-gray-300 hover:text-white transition-colors">FAQ</a></li>
              <li><a href="/support" class="text-gray-300 hover:text-white transition-colors">Support</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="max-w-7xl mx-auto px-6 py-6 border-t border-t-gray-700">
        <p class="text-center text-sm text-gray-500"> 2024 GameOn. Tous droits réservés.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

// Data
const searchQuery = ref('');
const location = ref('');

// Props from backend
const props = defineProps({
    news: {
        type: Array,
        default: () => []
    }
});

// Use news from database or fallback to hardcoded data
const newItems = computed(() => {
    if (props.news && props.news.length > 0) {
        return props.news.map(item => ({
            title: item.title,
            subtitle: item.description.substring(0, 50) + (item.description.length > 50 ? '...' : ''),
            image: item.image ? '/storage/' + item.image : 'https://via.placeholder.com/400x300/6366f1/ffffff?text=News'
        }));
    }
    
    // Fallback hardcoded data
    return [
      {
        title: 'PlayStation 5',
        subtitle: 'Nouvelle Génération',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuD69TFuViF7AheUFy17TAyvRJAUQDSjQvthTej3ob3xgQmrxiCd4iSWk-15WKVVXVg7iR7MRVXLP69kARZpApmxUiFWTjQupx5knfihjq16_6LiKHOGALdCgtZqEbqj2Lx05w_E9SPXIfRnzOvrlMaC70jZ0GoQHNJ-FSg8ND7bjfjfHmdw_MqQ3tRBQr7BrN_nw6e1VHb8V5ourB1d5hDkm0qFuKQ6IGF7YjTjMHDGjCnqQ2mjuzhCipJ53hS8D4cuaPDgHOyyhOgE'
      },
      {
        title: 'VR Quest 3',
        subtitle: 'Immersion Totale',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCYHh5IqU9m7sGzI6nN-2TOY3cjqw6LvCPwENSrv1VEvphNaLlp8oKX_ChHWzDiAi9cQHqZzYOB5KUtfhaLcjJW_UOgoH9tS0xJuEpB9hPjC-ug8sBblwZq9yB1nXRMBDpKZy9x-ckdOciw7G1dgP5bJQflmdbedf6-LEwhU_rUgZPWNclac2ejM5-wf7h7ZqLDxaZ26KdjbR7S9QmN2aHgH3b6Wrcxc1LAK-t53YktCKAWh_nWLzRxSqabo14Awculpec_FS2SU2pt'
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
      },
      {
        title: 'VR World Experience',
        subtitle: 'Réalité Virtuelle',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDZk-K3kdE3ajUoJvzYE-P5V9EppOKL8LPxzcGNhBBEFNBXC7F5jYXrYrwDrW0P4VKmutq7EmadaTVN_b9AVhEasTSc4KYfjGVTFn0s903IJxUxwFXFS_K1QsZ4gFcXhPRD0FaretJixko9EAwJGx96RTLdxrfXwfeugzGsGp-jYct8KgevHhFT-0FSU6WxM0SZ5Phpkqu5Q6RHdudPx25ttQcImu_6BD-CPUjTWe7VC8fHQDsTcVJp58dXTQnjEpHJh_Ba6CrOPUsD'
      },
      {
        title: 'Retro Arcade Classic',
        subtitle: 'Jeux Rétro',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCYHh5IqU9m7sGzI6nN-2TOY3cjqw6LvCPwENSrv1VEvphNaLlp8oKX_ChHWzDiAi9cQHqZzYOB5KUtfhaLcjJW_UOgoH9tS0xJuEpB9hPjC-ug8sBblwZq9yB1nXRMBDpKZy9x-ckdOciw7G1dgP5bJQflmdbedf6-LEwhU_rUgZPWNclac2ejM5-wf7h7ZqLDxaZ26KdjbR7S9QmN2aHgH3b6Wrcxc1LAK-t53YktCKAWh_nWLzRxSqabo14Awculpec_FS2SU2pt'
      },
      {
        title: 'Mobile Gaming Zone',
        subtitle: 'Gaming Mobile',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDimTmosVRtitqU9V2oDIOQw50iGnF5QW3_KIKBaqu9WSJ6grhZy_x9Ae_lduBc_pSW7n6n0fdslfekYXLqoiES9JL0QHnKTOhiuS0amxXj43wWxYzVYUBCmQu2VSKPJVPWu7LoNMIQI1myV_R0FkUiHFHXNPfCG-wSmSlJgDp7jtXCr5fTgDvAynUYwM1PHaumBxDPSfTTJ40KvLX01F4PWrPOpTz6IGey33XXIKdUJHkgz2V0gqb2kltAbruyvqecqYp37qlscfYo'
      },
      {
        title: 'E-Sports Arena',
        subtitle: 'Compétitions',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBoU7Oags87biuaaffc3aUGrDFW5kFUjxEbccg4ySsGzsmxpxUZoRY2m7gAWMY7xIqU5nQjefm2KQjibIT2Hn_fQ3SdFNqkMNgzCgtF5a09GtEpv59x7uIkqZv4UpDF2HYh4zSbA0MCObwDjUz2idQ4vU2ENeaBSQ2iPep1DeeAcN_oAIIdNDnfg7akYdQerYMQlTC9XfCixtt-VjVhodttIg_eETqbYCDuTTEmffxXDM6qfWkOTz8KXI4KVTjJnAwgnv_KLV0d0okk'
      },
      {
        title: 'Streaming Studio',
        subtitle: 'Live Streaming',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAFHU-O26YCVGb8nn72fw_t3zCT0ekH-nFV7acoJIzDsxjIR-Lc_K7kG27Cdlw2y9G6cjOtbMeCCTpb6fpvIprI0mmn5ex2yb-BXmD0L6KlPW5rU6p-lXc__1mF2es4ZEWq3q4ApJbjlDkC2TSC7mq-_NE0vXwbHX6WGf4RibZubgcspYf4t8fKul0l8KUZrUNKhCv41euF-GtVhgfA3ESe3VG4R69TUjr3MK0L6Mo_FiYuXRADPqlhMa6yL4JRSgTFG6wPWKvxjCOM'
      }
    ];
});

// Carousel logic
const currentSlide = ref(0);
const itemsPerSlide = ref(3);

// Calculate slides based on screen size
const carouselSlides = computed(() => {
  const items = newItems.value;
  const slides = [];
  const itemsCount = itemsPerSlide.value;
  
  for (let i = 0; i < items.length; i += itemsCount) {
    slides.push(items.slice(i, i + itemsCount));
  }
  
  return slides;
});

// Auto-play
let autoplayInterval = null;

const startAutoplay = () => {
  autoplayInterval = setInterval(() => {
    nextSlide();
  }, 4000);
};

const stopAutoplay = () => {
  if (autoplayInterval) {
    clearInterval(autoplayInterval);
    autoplayInterval = null;
  }
};

// Navigation methods
const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % carouselSlides.value.length;
};

const prevSlide = () => {
  currentSlide.value = currentSlide.value === 0 ? carouselSlides.value.length - 1 : currentSlide.value - 1;
};

const goToSlide = (index) => {
  currentSlide.value = index;
};

// Update items per slide based on screen size
const updateItemsPerSlide = () => {
  if (window.innerWidth >= 1024) { // lg
    itemsPerSlide.value = 3;
  } else if (window.innerWidth >= 640) { // sm
    itemsPerSlide.value = 2;
  } else {
    itemsPerSlide.value = 1;
  }
};

// Lifecycle
onMounted(() => {
  updateItemsPerSlide();
  window.addEventListener('resize', updateItemsPerSlide);
  startAutoplay();
});

onUnmounted(() => {
  window.removeEventListener('resize', updateItemsPerSlide);
  stopAutoplay();
});
const howItWorks = ref([
  {
    title: 'Recherche',
    description: 'Trouvez la salle parfaite pour votre prochaine session de jeu',
    iconClass: 'fa-search text-gray-600',
    borderColor: 'border-gray-600'
  },
  {
    title: 'Réservez',
    description: 'Réservez facilement en quelques clics',
    iconClass: 'fa-gamepad text-gray-500',
    borderColor: 'border-gray-500'
  },
  {
    title: 'Jouez',
    description: 'Profitez de votre expérience gaming unique',
    iconClass: 'fa-play text-gray-400',
    borderColor: 'border-gray-400'
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
  }
}

function searchLocation() {
  if (location.value.trim()) {
    // Implémentez la logique de recherche par localisation ici
  }
}

function viewRoom(id) {
  // Implémentez la navigation vers la page de la salle
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
  --accent-cyan: #6B7280;
  --accent-magenta: #4B5563;
  --accent-yellow: #9CA3AF;
  --accent-magenta-rgb: 75, 85, 99;
  --accent-cyan-rgb: 107, 114, 128;
  --accent-yellow-rgb: 156, 163, 175;
}

.font-display {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
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
.delay-600 { animation-delay: 0.6s; }

/* Gaming background animations */
@keyframes float {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-20px) rotate(5deg);
  }
}

@keyframes float-delay {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-15px) rotate(-3deg);
  }
}

@keyframes float-delay-2 {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-25px) rotate(8deg);
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-float-delay {
  animation: float-delay 8s ease-in-out infinite;
}

.animate-float-delay-2 {
  animation: float-delay-2 7s ease-in-out infinite;
}

/* Text stroke for better visibility - WebKit only */
.text-stroke-2xl {
  -webkit-text-stroke: 3px rgba(0, 0, 0, 0.8);
  /* Fallback for other browsers */
  text-shadow: 
    3px 3px 0 rgba(0, 0, 0, 0.9),
    -3px 3px 0 rgba(0, 0, 0, 0.9),
    3px -3px 0 rgba(0, 0, 0, 0.9),
    -3px -3px 0 rgba(0, 0, 0, 0.9),
    0 0 12px rgba(0, 0, 0, 1),
    0 0 20px rgba(0, 0, 0, 0.8);
}

.text-stroke-lg {
  -webkit-text-stroke: 2px rgba(0, 0, 0, 0.7);
  /* Fallback for other browsers */
  text-shadow: 
    2px 2px 0 rgba(0, 0, 0, 0.8),
    -2px 2px 0 rgba(0, 0, 0, 0.8),
    2px -2px 0 rgba(0, 0, 0, 0.8),
    -2px -2px 0 rgba(0, 0, 0, 0.8),
    0 0 8px rgba(0, 0, 0, 0.9),
    0 0 15px rgba(0, 0, 0, 0.7);
}

/* Particle animations */
.particles {
  position: absolute;
  width: 100%;
  height: 100%;
  overflow: hidden;
}

.particle {
  position: absolute;
  background: linear-gradient(45deg, #f59e0b, #ef4444);
  border-radius: 50%;
  opacity: 0.7;
  box-shadow: 0 0 12px rgba(245, 158, 11, 0.6);
}

.particle-1 {
  width: 4px;
  height: 4px;
  top: 20%;
  left: 10%;
  animation: particle-float 15s linear infinite;
}

.particle-2 {
  width: 3px;
  height: 3px;
  top: 40%;
  right: 15%;
  animation: particle-float 12s linear infinite reverse;
}

.particle-3 {
  width: 5px;
  height: 5px;
  bottom: 30%;
  left: 20%;
  animation: particle-float 18s linear infinite;
}

.particle-4 {
  width: 2px;
  height: 2px;
  top: 60%;
  right: 25%;
  animation: particle-float 14s linear infinite reverse;
}

.particle-5 {
  width: 6px;
  height: 6px;
  bottom: 20%;
  right: 10%;
  animation: particle-float 16s linear infinite;
}

.particle-6 {
  width: 3px;
  height: 3px;
  top: 30%;
  left: 40%;
  animation: particle-float 13s linear infinite reverse;
}

.particle-7 {
  width: 4px;
  height: 4px;
  bottom: 40%;
  left: 30%;
  animation: particle-float 17s linear infinite;
}

.particle-8 {
  width: 5px;
  height: 5px;
  top: 70%;
  right: 35%;
  animation: particle-float 15s linear infinite reverse;
}

@keyframes particle-float {
  0% {
    transform: translate(0, 0) scale(1);
    opacity: 0;
  }
  10% {
    opacity: 0.3;
  }
  90% {
    opacity: 0.3;
  }
  100% {
    transform: translate(100px, -100px) scale(0.5);
    opacity: 0;
  }
}
</style>