<template>
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="min-h-screen text-gray-800 font-body bg-gray-50">
    <!-- Navigation Supérieure (Fixe) -->
    <header class="fixed top-0 left-0 right-0 z-50 backdrop-blur-xl bg-gray-900/80 border-b border-gray-800/50 shadow-2xl">
      <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <!-- Logo -->
          <div class="flex items-center gap-3">
            <div class="bg-gradient-to-br from-blue-500 to-purple-600 size-10 rounded-xl flex items-center justify-center shadow-lg">
              <i class="fas fa-gamepad text-white text-xl"></i>
            </div>
            <h1 class="text-white text-2xl font-bold tracking-tight">GameOn</h1>
          </div>
          
          <!-- Navigation Menu -->
          <nav class="hidden md:flex items-center gap-8">
            <a href="/" class="text-white px-4 py-2 text-sm font-medium border-b-2 border-blue-500 bg-blue-500/10 rounded-lg">Accueil</a>
            <a href="/search/rooms" class="text-gray-300 hover:text-white px-4 py-2 text-sm font-medium transition-all hover:bg-gray-800/50 rounded-lg">Salles</a>
            <a href="/events" class="text-gray-300 hover:text-white px-4 py-2 text-sm font-medium transition-all hover:bg-gray-800/50 rounded-lg">Événements</a>
          </nav>
          
          <!-- Auth Buttons -->
          <div class="flex items-center gap-3">
            <a href="/login" class="px-5 py-2.5 text-gray-300 hover:text-white text-sm font-medium transition-all hover:bg-gray-800/50 rounded-lg">
              Connexion
            </a>
            <a href="/register" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white text-sm font-medium rounded-lg hover:from-blue-500 hover:to-blue-400 transition-all shadow-lg hover:shadow-blue-500/25">
              S'inscrire
            </a>
          </div>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
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
        <h1 class="text-white text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold leading-tight tracking-tighter animate-fade-in-up drop-shadow-lg">
          Votre Prochaine Partie Commence Ici.
        </h1>
        <h2 class="text-white text-base sm:text-lg md:text-xl font-normal leading-normal max-w-3xl px-2 animate-fade-in-up delay-300 drop-shadow-md">
          Découvrez et réservez des milliers de centres de loisirs et d'événements près de chez vous.
        </h2>
      </div>
      <div class="relative z-10 w-full max-w-2xl px-4">
        <a href="/search/rooms" class="flex w-full items-center justify-center overflow-hidden rounded-full h-14 sm:h-16 shadow-lg bg-gradient-to-r from-blue-600 to-blue-500 text-white text-lg sm:text-xl font-bold leading-normal tracking-[0.015em] hover:from-blue-500 hover:to-blue-400 transition-all duration-300 animate-fade-in-up delay-500">
          <i class="fas fa-search mr-3"></i>
          <span>Trouver une salle</span>
        </a>
      </div>
    </section>

    <!-- Section Nouveautés -->
    <section class="pt-8 pb-16 bg-gray-50 relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-32 h-32 bg-blue-600 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-48 h-48 bg-purple-600 rounded-full blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-gray-800 text-2xl sm:text-3xl font-bold leading-tight tracking-tight px-4 pb-6 animate-fade-in-up">Nouveautés</h2>
        
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

    <!-- Section Salles Populaires -->
    <section class="py-16 bg-gray-50 relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-15">
        <div class="absolute top-1/4 right-20 w-40 h-40 bg-blue-600 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 left-20 w-36 h-36 bg-purple-600 rounded-full blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-gray-800 text-3xl font-bold leading-tight tracking-tight px-4 pb-6 animate-fade-in-up delay-300">Salles Populaires</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="(room, index) in popularRooms" :key="index" class="flex flex-col gap-4 rounded-lg shadow-lg overflow-hidden group bg-white border border-gray-200 animate-fade-in-up" :style="{ animationDelay: `${index * 150}ms` }">
            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover group-hover:brightness-110 transition-all duration-500" :style="'background-image: url(' + room.image + ')'"></div>
            <div class="flex flex-col p-4 pt-0 gap-3">
              <h3 class="text-gray-800 text-xl font-bold transform transition-transform duration-300 group-hover:translate-x-1">{{ room.name }}</h3>
              <div class="flex items-center justify-between text-sm text-gray-600">
                <div class="flex items-center gap-1 transform transition-transform duration-300 group-hover:scale-110">
                  <i class="fas fa-star text-yellow-500 !text-xl"></i>
                  <span>{{ room.rating }}</span>
                </div> 
                <span>({{ room.reviews }} avis)</span>
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

    <!-- Section Comment ça marche -->
    <section class="py-16 bg-gray-50 relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/3 left-1/4 w-44 h-44 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/3 right-1/4 w-52 h-52 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-gray-800 text-center text-3xl font-bold leading-tight tracking-tight pb-12 animate-fade-in-up delay-600">Comment ça marche ?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
          <div v-for="(step, index) in howItWorks" :key="index" class="flex flex-col items-center gap-4 group animate-fade-in-up" :style="{ animationDelay: `${index * 200}ms` }">
            <div class="flex items-center justify-center w-24 h-24 rounded-full border-2 mb-4 bg-gray-50 transform transition-all duration-500 group-hover:scale-110 group-hover:shadow-lg" :class="step.borderColor">
              <i class="fas transform transition-transform duration-300 group-hover:rotate-12" :class="step.iconClass" style="font-size: 3.125rem;"></i>
            </div>
            <h3 class="text-gray-800 text-xl font-bold transform transition-transform duration-300 group-hover:translate-y-1">{{ step.title }}</h3>
            <p class="text-gray-600 transform transition-all duration-300 group-hover:text-gray-700">{{ step.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Section Crédibilité -->
    <section class="py-16 bg-gradient-to-br from-gray-800 to-gray-900 relative overflow-hidden">
      <!-- Background gaming blur -->
      <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-64 h-64 bg-blue-600 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-purple-600 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full blur-3xl"></div>
      </div>
      
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-12">
          <h2 class="text-white text-3xl md:text-4xl font-bold leading-tight tracking-tight mb-4 animate-fade-in-up">
            GameOn accompagne les joueurs depuis 5 années
          </h2>
          <p class="text-gray-300 text-lg animate-fade-in-up delay-300">
            La confiance de milliers de gamers pour leurs expériences gaming
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center animate-fade-in-up delay-500">
            <div class="text-4xl md:text-5xl font-bold text-white mb-2">{{ formatNumber(usersCount) }}</div>
            <div class="text-gray-300 text-lg">Utilisateurs actifs</div>
          </div>
          <div class="text-center animate-fade-in-up delay-700">
            <div class="text-4xl md:text-5xl font-bold text-white mb-2">{{ formatNumber(roomsCount) }}</div>
            <div class="text-gray-300 text-lg">Salles disponibles</div>
          </div>
          <div class="text-center animate-fade-in-up delay-900">
            <div class="text-4xl md:text-5xl font-bold text-white mb-2">{{ formatNumber(bookingsCount) }}</div>
            <div class="text-gray-300 text-lg">Réservations effectuées</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div class="col-span-1 md:col-span-2">
            <div class="flex items-center gap-3 mb-4">
              <div class="bg-gradient-to-br from-blue-500 to-purple-600 size-10 rounded-xl flex items-center justify-center">
                <i class="fas fa-gamepad text-white text-xl"></i>
              </div>
              <h3 class="text-2xl font-bold">GameOn</h3>
            </div>
            <p class="text-gray-300 mb-4">Votre plateforme pour trouver et réserver les meilleurs centres de loisirs près de chez vous.</p>
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
      <div class="max-w-7xl mx-auto px-6 py-6 border-t border-gray-700">
        <p class="text-center text-sm text-gray-500">© 2024 GameOn. Tous droits réservés.</p>
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

// Statistics data
const usersCount = ref(15420);
const roomsCount = ref(892);
const bookingsCount = ref(28456);

// Format numbers with separators
const formatNumber = (num) => {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
};

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
        title: 'PlayStation 5 Arena',
        subtitle: 'Nouvelle génération de gaming',
        image: 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=500&h=300&fit=crop'
      },
      {
        title: 'VR Quest 3 Experience',
        subtitle: 'Immersion totale en réalité virtuelle',
        image: 'https://images.unsplash.com/photo-1592478411213-6153e4ebc07d?w=500&h=300&fit=crop'
      },
      {
        title: 'CyberHero 2088',
        subtitle: 'Le dernier jeu cyberpunk',
        image: 'https://images.unsplash.com/photo-1511512578047-dfb36704621f?w=500&h=300&fit=crop'
      },
      {
        title: 'PC Gamer Ultimate',
        subtitle: 'Puissance maximale garantie',
        image: 'https://images.unsplash.com/photo-1587829741301-dc798b83dec3?w=500&h=300&fit=crop'
      },
      {
        title: 'VR World Experience',
        subtitle: 'Réalité virtuelle immersive',
        image: 'https://images.unsplash.com/photo-1592478411213-6153e4ebc07d?w=500&h=300&fit=crop'
      },
      {
        title: 'Retro Arcade Classic',
        subtitle: 'Jeux rétro nostalgiques',
        image: 'https://images.unsplash.com/photo-1552728089-57bdde6beb07?w=500&h=300&fit=crop'
      }
    ];
});

// Popular rooms data
const popularRooms = ref([
  {
    id: 1,
    name: 'Gaming Zone Pro',
    rating: 4.8,
    reviews: 234,
    distance: '2.5 km',
    price: '25€',
    featured: true,
    image: 'https://images.unsplash.com/photo-1493770348161-369560ae357d?w=500&h=300&fit=crop'
  },
  {
    id: 2,
    name: 'E-Sports Arena',
    rating: 4.9,
    reviews: 189,
    distance: '1.8 km',
    price: '30€',
    featured: false,
    image: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=500&h=300&fit=crop'
  },
  {
    id: 3,
    name: 'VR Experience Center',
    rating: 4.7,
    reviews: 156,
    distance: '3.2 km',
    price: '35€',
    featured: true,
    image: 'https://images.unsplash.com/photo-1592478411213-6153e4ebc07d?w=500&h=300&fit=crop'
  },
  {
    id: 4,
    name: 'Retro Gaming Lounge',
    rating: 4.6,
    reviews: 98,
    distance: '4.1 km',
    price: '20€',
    featured: false,
    image: 'https://images.unsplash.com/photo-1552728089-57bdde6beb07?w=500&h=300&fit=crop'
  },
  {
    id: 5,
    name: 'PC Master Race',
    rating: 4.9,
    reviews: 312,
    distance: '2.8 km',
    price: '28€',
    featured: true,
    image: 'https://images.unsplash.com/photo-1587829741301-dc798b83dec3?w=500&h=300&fit=crop'
  },
  {
    id: 6,
    name: 'Mobile Gaming Hub',
    rating: 4.5,
    reviews: 67,
    distance: '5.3 km',
    price: '18€',
    featured: false,
    image: 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=500&h=300&fit=crop'
  }
]);

// How it works data
const howItWorks = ref([
  {
    title: 'Recherche',
    description: 'Trouvez la salle parfaite pour votre prochaine session de jeu',
    iconClass: 'fa-search text-blue-600',
    borderColor: 'border-blue-600'
  },
  {
    title: 'Réservez',
    description: 'Réservez facilement en quelques clics',
    iconClass: 'fa-gamepad text-purple-600',
    borderColor: 'border-purple-600'
  },
  {
    title: 'Jouez',
    description: 'Profitez de votre expérience gaming unique',
    iconClass: 'fa-play text-green-600',
    borderColor: 'border-green-600'
  }
]);

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

// Methods
function viewRoom(id) {
  // Implémentez la navigation vers la page de la salle
  console.log('View room:', id);
}

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
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

.font-body {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* Scrollbar personnalisée */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f0f0f0;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(45deg, #3B82F6, #8B5CF6);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(45deg, #2563EB, #7C3AED);
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
.delay-600 { animation-delay: 0.6s; }
.delay-700 { animation-delay: 0.7s; }
.delay-900 { animation-delay: 0.9s; }

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

/* Particle animations */
.particles {
  position: absolute;
  width: 100%;
  height: 100%;
  overflow: hidden;
}

.particle {
  position: absolute;
  background: linear-gradient(45deg, #3B82F6, #8B5CF6);
  border-radius: 50%;
  opacity: 0.7;
  box-shadow: 0 0 12px rgba(59, 130, 246, 0.6);
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
