<template>
  <div class="bg-white text-soft-black font-display antialiased selection:bg-primary/20 selection:text-primary">
    <!-- Navigation -->
    <nav class="absolute top-0 left-0 w-full z-50 transition-all duration-300 bg-black/20 backdrop-blur-sm">
      <div class="max-w-[1320px] mx-auto px-6 h-20 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center gap-2">
          <div class="size-8 flex items-center justify-center bg-white rounded-lg text-primary">
            <span class="material-symbols-outlined text-[24px]">stadia_controller</span>
          </div>
          <h1 class="text-white text-xl font-bold tracking-tight">GameOn</h1>
        </div>
        
        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-10">
          <a class="text-white/90 hover:text-white text-sm font-medium transition-colors" href="/">Accueil</a>
          <a class="text-white/70 hover:text-white text-sm font-medium transition-colors" href="/search/rooms">Salles</a>
          <a class="text-white/70 hover:text-white text-sm font-medium transition-colors" href="/events">Événements</a>
        </div>
        
        <!-- Desktop Right Actions -->
        <div class="hidden md:flex items-center gap-4">
          <Link 
            href="/login" 
            @click="handleAuthClick($event, 'login')"
            class="text-white hover:text-white/80 text-sm font-semibold px-4 py-2 transition-colors"
          >
            Connexion
          </Link>
          <Link 
            href="/register" 
            @click="handleAuthClick($event, 'register')"
            class="flex items-center justify-center rounded-full bg-primary hover:bg-blue-600 text-white text-sm font-bold px-6 py-2.5 transition-all shadow-lg shadow-primary/20"
          >
            Inscription
          </Link>
        </div>

        <!-- Mobile Menu Button -->
        <button 
          @click="isMobileMenuOpen = !isMobileMenuOpen"
          class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-colors"
        >
          <span class="material-symbols-outlined text-2xl text-white">
            {{ isMobileMenuOpen ? 'close' : 'menu' }}
          </span>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div 
        :class="[
          'md:hidden transition-all duration-300 overflow-hidden',
          isMobileMenuOpen ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'
        ]"
      >
        <div class="py-4 border-t border-white/20">
          <div class="flex flex-col gap-4">
            <Link href="/" class="text-white/90 hover:text-white text-sm font-medium transition-colors py-2">Accueil</Link>
            <Link href="/search/rooms" class="text-white/70 hover:text-white text-sm font-medium transition-colors py-2">Salles</Link>
            <Link href="/events" class="text-white/70 hover:text-white text-sm font-medium transition-colors py-2">Événements</Link>
            <div class="flex gap-3 pt-4 border-t border-white/20">
              <Link 
                href="/login" 
                @click="handleAuthClick($event, 'login')"
                class="flex-1 text-center text-white hover:text-white/80 text-sm font-semibold py-2 border border-white/20 rounded-lg"
              >
                Connexion
              </Link>
              <Link 
                href="/register" 
                @click="handleAuthClick($event, 'register')"
                class="flex-1 text-center bg-primary hover:bg-blue-600 text-white text-sm font-bold py-2 rounded-lg transition-all shadow-lg shadow-primary/20"
              >
                Inscription
              </Link>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative w-full min-h-[85vh] flex flex-col items-center justify-center overflow-hidden">
      <!-- Background Image with Overlay -->
      <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/30 to-black/60 z-10"></div>
        <img 
          alt="Modern esports arena with neon lighting and high end gaming setups" 
          class="w-full h-full object-cover object-center" 
          src="https://lh3.googleusercontent.com/aida-public/AB6AXuDw-c047UTYC03aExwcs0RXeVsLIrlbZvCvnl7oGUhpO-iC8gvAcdmWLm32i0PUZZOzMPkFS81HLgahHcfc5pq9nc_NYyD44VzzFyNhu-gb_-YR5yDOhqWu__txe9Cv3iVX653wY8YCyfZ_kjWTN6gB0Wvaj_hyeoreJ710OnninuWad7J7t7a6PNjgKxVbL2fpNSSHNdyCuL_LaO61Gv3VmhCzCVayUqieVwu08p0JZfZmjGuESQWn8ujwNSKixSfZ7pxlukLevOY"
        />
      </div>
      
      <!-- Content -->
      <div class="relative z-10 w-full max-w-[1320px] px-6 flex flex-col items-center text-center gap-8 mt-16">
        <div class="flex flex-col gap-4 max-w-4xl">
          <h1 class="text-white text-4xl sm:text-5xl md:text-7xl font-black tracking-tight leading-[1.1]">
            Votre prochaine partie <br/>commence ici
          </h1>
          <p class="text-white/80 text-lg md:text-xl font-normal leading-relaxed max-w-2xl mx-auto">
            Réservez les meilleures salles de gaming haute performance et participez à des événements exclusifs près de chez vous.
          </p>
        </div>
        
        <!-- Search Button -->
        <div class="w-full max-w-[640px] mt-4">
          <button 
            @click="goToSearchRooms"
            class="w-full bg-white hover:bg-gray-50 text-soft-black p-4 rounded-full shadow-2xl flex items-center justify-center gap-3 transition-all hover:shadow-3xl group"
          >
            <span class="material-symbols-outlined text-[24px] text-primary">search</span>
            <span class="text-base font-medium">Trouver une salle de loisirs</span>
            <span class="material-symbols-outlined text-[20px] text-primary group-hover:translate-x-1 transition-transform">arrow_forward</span>
          </button>
        </div>
      </div>
      
      <!-- Scroll indicator -->
      <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce text-white/50">
        <span class="material-symbols-outlined text-[32px]">keyboard_arrow_down</span>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex flex-col w-full bg-white pb-20">
      <!-- Carousel / News Section -->
      <section class="w-full max-w-[1320px] mx-auto px-6 py-20">
        <div class="flex items-end justify-between mb-10">
          <div>
            <h2 class="text-soft-black text-3xl font-bold tracking-tight mb-2">À la une</h2>
            <p class="text-medium-grey text-base">Les dernières ouvertures et événements majeurs.</p>
          </div>
          <div class="flex gap-2">
            <button 
              @click="prevSlide"
              class="size-10 rounded-full border border-gray-200 flex items-center justify-center text-soft-black hover:border-primary hover:text-primary transition-colors"
            >
              <span class="material-symbols-outlined">arrow_back</span>
            </button>
            <button 
              @click="nextSlide"
              class="size-10 rounded-full border border-gray-200 flex items-center justify-center text-soft-black hover:border-primary hover:text-primary transition-colors"
            >
              <span class="material-symbols-outlined">arrow_forward</span>
            </button>
          </div>
        </div>
        
        <!-- Cards Container with Auto-scroll -->
        <div class="relative overflow-hidden">
          <div class="flex gap-6 animate-scroll-left">
            <!-- Duplicate items for infinite scroll -->
            <div 
              v-for="(item, index) in [...featuredItemsData, ...featuredItemsData]" 
              :key="`${index}-duplicate`"
              class="group relative flex flex-col gap-4 cursor-pointer min-w-[320px] md:min-w-[400px]"
              @click="viewItem(item)"
            >
              <div class="relative w-full aspect-[4/3] rounded-2xl overflow-hidden shadow-sm">
                <div class="absolute top-4 left-4 z-10">
                  <span 
                    :class="[
                      'px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider',
                      item.type === 'Nouveau' ? 'bg-white/90 backdrop-blur-sm text-soft-black' :
                      item.type === 'Tournoi' ? 'bg-primary/90 backdrop-blur-sm text-white' :
                      'bg-white/90 backdrop-blur-sm text-soft-black'
                    ]"
                  >
                    {{ item.type }}
                  </span>
                </div>
                <img 
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                  :src="item.image"
                  :alt="item.title"
                />
              </div>
              <div class="flex flex-col gap-2">
                <h3 class="text-lg font-bold text-soft-black leading-tight">{{ item.title }}</h3>
                <p class="text-medium-grey text-sm leading-relaxed">{{ item.description }}</p>
              </div>
            </div>
          </div>
        </div>
        </section>

    <!-- Popular Rooms Section -->
      <section class="w-full bg-[#f8f9fc] py-20">
        <div class="max-w-[1320px] mx-auto px-6">
          <!-- Header with Tabs -->
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <h2 class="text-soft-black text-3xl font-bold tracking-tight">Salles Populaires</h2>
            <div class="flex bg-white p-1 rounded-full shadow-sm w-fit">
              <button 
                v-for="tab in roomTabs"
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                  'px-6 py-2 rounded-full text-sm font-medium transition-all',
                  activeTab === tab.id ? 'bg-primary text-white shadow-sm' : 'text-medium-grey hover:bg-gray-50'
                ]"
              >
                {{ tab.label }}
              </button>
            </div>
          </div>
          
          <!-- Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <article 
              v-for="(room, index) in filteredRooms" 
              :key="index"
              class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_12px_24px_rgba(0,0,0,0.08)] transition-all duration-300 group"
            >
              <div class="relative aspect-[3/2] overflow-hidden">
                <img 
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                  :src="room.image"
                  :alt="room.name"
                />
                <div class="absolute top-3 right-3 bg-white/90 backdropver-blur rounded-full px-2 py-1 flex items-center gap-1 shadow-sm">
                  <span class="material-symbols-outlined text-yellow-500 text-[16px] fill-current">star</span>
                  <span class="text-xs font-bold text-soft-black">{{ room.rating }}</span>
                </div>
              </div>
              <div class="p-5 flex flex-col gap-3">
                <div>
                  <h3 class="text-lg font-bold text-soft-black leading-tight">{{ room.name }}</h3>
                  <div class="flex items-center gap-1 text-medium-grey mt-1">
                    <span class="material-symbols-outlined text-[16px]">location_on</span>
                    <span class="text-xs font-medium">{{ room.location }}</span>
                  </div>
                </div>
                <div class="w-full h-[1px] bg-gray-100"></div>
                <div class="flex items-center justify-between">
                  <span class="text-primary font-bold">{{ room.price }}€ <span class="text-medium-grey font-normal text-xs">/ heure</span></span>
                  <button 
                    @click="reserveRoom(room)"
                    class="text-soft-black hover:text-primary text-sm font-semibold flex items-center gap-1 transition-colors"
                  >
                    Réserver
                  </button>
                </div>
              </div>
            </article>
          </div>
          
          <div class="mt-12 text-center">
            <a href="/search/rooms" class="inline-flex items-center justify-center gap-2 px-8 py-3 rounded-full border border-gray-200 text-soft-black font-semibold hover:border-primary hover:text-primary transition-all">
              Voir toutes les salles
              <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </a>
          </div>
        </div>
      </section>
    </main>

    <!-- Section Comment ça marche -->
    <section class="py-16 bg-gray-50 relative overflow-hidden">
      <!-- Éléments décoratifs 3D -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/3 left-1/4 w-44 h-44 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/3 right-1/4 w-52 h-52 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full blur-3xl"></div>
      </div>
      <!-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
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
      </div> -->
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

    <!-- How It Works Section -->
    <section class="w-full bg-white py-20">
      <div class="max-w-[1320px] mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-16">
          <h2 class="text-soft-black text-3xl md:text-4xl font-bold tracking-tight mb-4">Comment ça marche ?</h2>
          <p class="text-medium-grey text-lg max-w-2xl mx-auto">
            Réservez votre salle de gaming en quelques clics et profitez d'une expérience unique
          </p>
        </div>

        <!-- Steps -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
          <!-- Step 1 -->
          <div class="text-center group">
            <div class="size-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/20 transition-colors">
              <span class="material-symbols-outlined text-[40px] text-primary">search</span>
            </div>
            <div class="flex items-center justify-center gap-2 mb-4">
              <div class="size-8 bg-primary text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
              <h3 class="text-xl font-bold text-soft-black">Recherchez</h3>
            </div>
            <p class="text-medium-grey leading-relaxed">
              Trouvez la salle parfaite parmi notre sélection de salles de gaming équipées
            </p>
          </div>

          <!-- Step 2 -->
          <div class="text-center group">
            <div class="size-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/20 transition-colors">
              <span class="material-symbols-outlined text-[40px] text-primary">calendar_month</span>
            </div>
            <div class="flex items-center justify-center gap-2 mb-4">
              <div class="size-8 bg-primary text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
              <h3 class="text-xl font-bold text-soft-black">Réservez</h3>
            </div>
            <p class="text-medium-grey leading-relaxed">
              Choisissez vos dates et réservez instantanément en ligne
            </p>
          </div>

          <!-- Step 3 -->
          <div class="text-center group">
            <div class="size-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/20 transition-colors">
              <span class="material-symbols-outlined text-[40px] text-primary">sports_esports</span>
            </div>
            <div class="flex items-center justify-center gap-2 mb-4">
              <div class="size-8 bg-primary text-white rounded-full flex items-center justify-center font-bold text-sm">3</div>
              <h3 class="text-xl font-bold text-soft-black">Jouez</h3>
            </div>
            <p class="text-medium-grey leading-relaxed">
              Profitez de votre session gaming dans un environnement professionnel
            </p>
          </div>
        </div>

        <!-- CTA -->
        <div class="text-center mt-16">
          <button 
            @click="goToSearchRooms"
            class="bg-primary hover:bg-blue-600 text-white px-8 py-4 rounded-full text-lg font-medium transition-all shadow-lg shadow-primary/20 hover:shadow-xl"
          >
            Commencer maintenant
          </button>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <MainFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import MainFooter from '@/Components/MainFooter.vue';

const page = usePage();
const isMobileMenuOpen = ref(false);

// Vérifier si l'utilisateur est connecté
const isLoggedIn = ref(!!page.props.auth?.user);

// Fonction pour gérer le clic sur connexion/inscription
const handleAuthClick = (event, type) => {
  if (isLoggedIn.value) {
    event.preventDefault();
    
    const user = page.props.auth.user;
    let redirectRoute = '/client/dashboard'; // défaut
    
    if (user.role === 'admin') {
      redirectRoute = '/admin/dashboard';
    } else if (user.role === 'promoter') {
      redirectRoute = '/promoter/dashboard';
    }
    
    window.location.href = redirectRoute;
  }
};

// Format numbers with separators
const formatNumber = (num) => {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
};

// État d'authentification (à implémenter avec votre système d'auth)
const authUser = ref(null);

// Mock data for stats
const usersCount = ref(15420);
const roomsCount = ref(89);
const bookingsCount = ref(3247);

// Mock data for featured items
const featuredItemsData = ref([
  {
    id: 1,
    type: 'Nouveau',
    title: 'Arena Gaming Paris',
    description: 'Nouvelle salle de gaming dernière génération',
    image: 'https://picsum.photos/seed/arena1/400/300.jpg'
  },
  {
    id: 2,
    type: 'Tournoi',
    title: 'CS:GO Championship',
    description: 'Tournoi national avec prix de 10 000€',
    image: 'https://picsum.photos/seed/tournament1/400/300.jpg'
  },
  {
    id: 3,
    type: 'Événement',
    title: 'LAN Party Weekend',
    description: '48h de gaming non-stop',
    image: 'https://picsum.photos/seed/lanparty1/400/300.jpg'
  }
]);

// Mock data for popular rooms
const popularRoomsData = ref([
  {
    id: 1,
    name: 'Elite Gaming Center',
    location: 'Paris',
    price: '25€/h',
    rating: 4.8,
    image: 'https://picsum.photos/seed/room1/400/300.jpg'
  },
  {
    id: 2,
    name: 'Pro Arena Lyon',
    location: 'Lyon',
    price: '20€/h',
    rating: 4.6,
    image: 'https://picsum.photos/seed/room2/400/300.jpg'
  },
  {
    id: 3,
    name: 'Battle Station Marseille',
    location: 'Marseille',
    price: '22€/h',
    rating: 4.7,
    image: 'https://picsum.photos/seed/room3/400/300.jpg'
  },
  {
    id: 4,
    name: 'Gaming Hub Bordeaux',
    location: 'Bordeaux',
    price: '18€/h',
    rating: 4.5,
    image: 'https://picsum.photos/seed/room4/400/300.jpg'
  }
]);

// Computed property for filtered rooms
const filteredRooms = ref(popularRoomsData.value);

// Carousel functionality
const currentSlide = ref(0);
const searchQuery = ref('');
const selectedLocation = ref('Paris');

const prevSlide = () => {
  currentSlide.value = currentSlide.value === 0 ? featuredItemsData.value.length - 1 : currentSlide.value - 1;
};

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % featuredItemsData.value.length;
};

const viewItem = (item) => {
  console.log('View item:', item);
  // Navigate to item details
};

const reserveRoom = (room) => {
  console.log('Reserve room:', room);
  // Vérifier si l'utilisateur est connecté
  if (!authUser.value) {
    // Rediriger vers la page de connexion
    window.location.href = '/login';
    return;
  }
  // Naviguer vers la page de réservation
  window.location.href = `/salles/${room.id}/reserver`;
};

const handleSearch = () => {
  console.log('Search:', searchQuery.value, 'Location:', selectedLocation.value);
  // Implement search functionality
};

const goToSearchRooms = () => {
  // Rediriger vers la page de recherche des salles
  window.location.href = '/search/rooms';
};
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap');

.font-display {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* Custom scrollbar hiding for clean UI */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Line clamp utility */
.line-clamp-2 {
  display: -webkit-box;
  display: -moz-box;
  display: box;
  -webkit-line-clamp: 2;
  -moz-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  -moz-box-orient: vertical;
  box-orient: vertical;
  overflow: hidden;
}

/* Auto-scroll animation */
@keyframes scroll-left {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}

.animate-scroll-left {
  animation: scroll-left 30s linear infinite;
}

/* Pause animation on hover */
.animate-scroll-left:hover {
  animation-play-state: paused;
}

/* Tailwind custom colors */
:root {
  --primary: #135bec;
  --background-light: #f6f6f8;
  --background-dark: #101622;
  --soft-black: #111827;
  --medium-grey: #6B7280;
}

.text-primary {
  color: var(--primary);
}

.bg-primary {
  background-color: var(--primary);
}

.text-soft-black {
  color: var(--soft-black);
}

.text-medium-grey {
  color: var(--medium-grey);
}

.bg-soft-black {
  background-color: var(--soft-black);
}

/* Animation classes */
.animate-bounce {
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 100% {
    transform: translateY(-25%);
    animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
  }
  50% {
    transform: translateY(0);
    animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
  }
}

/* Material Symbols */
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

.material-symbols-outlined.fill-current {
  font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
