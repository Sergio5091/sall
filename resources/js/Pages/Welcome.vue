<template>
  <div class="bg-white text-soft-black font-display antialiased selection:bg-primary/20 selection:text-primary">
    <!-- Navigation -->
    <nav class="absolute top-0 left-0 w-full z-50 transition-all duration-300 bg-gradient-to-b from-black/40 to-transparent backdrop-blur-xl hover:bg-black/30">
      <div class="max-w-[1320px] mx-auto px-6 h-20 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center gap-3 hover:opacity-90 transition-opacity">
          <div class="size-9 flex items-center justify-center bg-gradient-to-br from-primary to-blue-500 rounded-xl text-white shadow-lg shadow-primary/40">
            <i class="fas fa-gamepad text-[20px]"></i>
          </div>
          <h1 class="text-white text-2xl font-extrabold tracking-tighter bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent">YOUPIHUB</h1>
        </div>
        
        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-12">
          <a class="text-white/90 hover:text-white text-sm font-semibold transition-colors relative group" href="/">
            Accueil
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary to-transparent group-hover:w-full transition-all duration-300"></span>
          </a>
          <a class="text-white/70 hover:text-white text-sm font-semibold transition-colors relative group" href="/search/rooms">
            Salles
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary to-transparent group-hover:w-full transition-all duration-300"></span>
          </a>
          <a class="text-white/70 hover:text-white text-sm font-semibold transition-colors relative group" href="/events">
            Événements
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary to-transparent group-hover:w-full transition-all duration-300"></span>
          </a>
          <a 
            :class="[
              'text-sm font-semibold transition-colors relative group',
              $page.url === '/products' ? 'text-white/90' : 'text-white/70 hover:text-white'
            ]" 
            href="/products"
          >
            Boutique
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary to-transparent group-hover:w-full transition-all duration-300"></span>
          </a>
          <a 
            :class="[
              'text-sm font-semibold transition-colors relative group',
              $page.url === '/about' ? 'text-white/90' : 'text-white/70 hover:text-white'
            ]" 
            href="/about"
          >
            À propos
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary to-transparent group-hover:w-full transition-all duration-300"></span>
          </a>
        </div>
        
        <!-- Desktop Right Actions -->
        <div class="hidden md:flex items-center gap-3">
          <Link 
            href="/login" 
            @click="handleAuthClick($event, 'login')"
            class="text-white hover:text-white/80 text-sm font-semibold px-6 py-2.5 transition-all border border-white/20 rounded-full hover:border-white/40 backdrop-blur-sm"
          >
            Connexion
          </Link>
          <Link 
            href="/register" 
            @click="handleAuthClick($event, 'register')"
            class="flex items-center justify-center rounded-full bg-gradient-to-r from-primary to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-bold px-7 py-2.5 transition-all shadow-lg shadow-primary/40 hover:shadow-xl hover:shadow-primary/50 transform hover:scale-105"
          >
            S'inscrire
          </Link>
        </div>

        <!-- Mobile Menu Button -->
        <button 
          @click="isMobileMenuOpen = !isMobileMenuOpen"
          class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-colors"
        >
          <i class="fas fa-bars text-2xl text-white" v-if="!isMobileMenuOpen"></i>
          <i class="fas fa-times text-2xl text-white" v-else></i>
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
            <Link href="/" class="text-white/90 hover:text-white text-sm font-medium transition-colors py-2 text-center">Accueil</Link>
            <Link href="/search/rooms" class="text-white/70 hover:text-white text-sm font-medium transition-colors py-2 text-center">Salles</Link>
            <Link href="/events" class="text-white/70 hover:text-white text-sm font-medium transition-colors py-2 text-center">Événements</Link>
            <Link 
              :class="[
                'text-sm font-medium transition-colors py-2 text-center',
                $page.url === '/products' ? 'text-white/90' : 'text-white/70 hover:text-white'
              ]" 
              href="/products"
            >
              Boutique
            </Link>
            <Link 
              :class="[
                'text-sm font-medium transition-colors py-2 text-center',
                $page.url === '/about' ? 'text-white/90' : 'text-white/70 hover:text-white'
              ]" 
              href="/about"
            >
              À propos
            </Link>
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
   <!-- Hero Section - Carousel -->
<header class="relative w-full overflow-hidden bg-black" style="height: clamp(600px, 90vh, 900px);">

  <!-- Slides wrapper -->
  <div
    class="flex h-full transition-all duration-1200 ease-[cubic-bezier(0.4,0,0.2,1)]"
    :style="{ transform: `translateX(-${heroSlide * 100}%)` }"
  >
    <div
      v-for="(slide, i) in heroSlides"
      :key="i"
      class="relative min-w-full h-full flex-shrink-0"
    >
      <!-- Background image avec zoom au slide actif -->
      <div
        class="absolute inset-0 bg-cover bg-center transition-all duration-[10000ms] ease-linear"
        :style="{ 
          backgroundImage: `url(${slide.image})`,
          transform: heroSlide === i ? 'scale(1.08) translateY(-5px)' : 'scale(1.02) translateY(0px)'
        }"
      ></div>
      <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-black/40 to-black/70"></div>
      <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/20 to-transparent"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/30"></div>

      <!-- Decorative elements -->
      <div class="absolute top-20 left-20 w-32 h-32 bg-primary/20 rounded-full blur-3xl animate-float"></div>
      <div class="absolute bottom-32 right-32 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 1s;"></div>
      <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-purple-500/15 rounded-full blur-2xl animate-float" style="animation-delay: 2s;"></div>
      <!-- Contenu -->
      <div class="relative z-20 flex h-full items-center px-6 md:px-20 lg:px-24 xl:px-32">
        <div class="max-w-[720px] xl:max-w-[800px]">
          <!-- Badge -->
          <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-white/30 bg-white/12 text-white uppercase tracking-[1.5px] text-xs font-bold mb-6 backdrop-blur-md hover:bg-white/18 transition-all animate-slide-in-left" :class="{ 'opacity-0': !heroLoaded }">
            <span class="size-2.5 rounded-full animate-pulse" :style="{ background: slide.dotColor }"></span>
            {{ slide.tag }}
          </span>

          <!-- Titre -->
          <h1 class="text-white text-[clamp(40px,6vw,72px)] font-black leading-[1.02] tracking-tighter mb-8 animate-slide-in-up" :class="{ 'opacity-0': !heroLoaded }" style="animation-delay: 0.1s">
            <span v-html="slide.title"></span>
          </h1>

          <!-- Sous-titre -->
          <p class="text-white/85 text-[clamp(16px,1.5vw,20px)] leading-[1.8] mb-12 max-w-[600px] font-medium animate-slide-in-up" :class="{ 'opacity-0': !heroLoaded }" style="animation-delay: 0.2s">
            {{ slide.subtitle }}
          </p>

          <!-- CTA -->
          <div class="flex items-center gap-5 flex-wrap animate-slide-in-up" :class="{ 'opacity-0': !heroLoaded }" style="animation-delay: 0.3s">
            <button
              @click="() => slide.primaryAction()"
              class="inline-flex items-center justify-center gap-3 bg-white text-[#111] px-10 py-5 rounded-full text-base font-bold hover:shadow-2xl transition-all transform hover:scale-105 hover:bg-white/95 shadow-2xl hover:shadow-white/20 animate-pulse-glow relative overflow-hidden"
            >
              <div class="absolute inset-0 animate-shimmer"></div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="relative z-10">
                <path d="M11 4a7 7 0 1 0 4.95 11.95l4.55 4.55a1 1 0 0 0 1.42-1.42l-4.55-4.55A7 7 0 0 0 11 4Zm0 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10Z" fill="#111"/>
              </svg>
              <span class="relative z-10">{{ slide.primaryLabel }}</span>
            </button>
            <button
              @click="() => slide.secondaryAction()"
              class="flex items-center gap-3 text-white/90 text-base font-bold hover:text-white transition-all group px-8 py-5 rounded-full hover:bg-white/12 backdrop-blur-sm border border-white/20 hover:border-white/30 animate-slide-in-right"
            >
              {{ slide.secondaryLabel }}
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="group-hover:translate-x-1 transition-transform">
                <path d="M5 12h14M13 6l6 6-6 6" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Compteur slide -->
  <div class="absolute top-12 right-32 text-white/70 text-sm font-bold tracking-[3px] z-30 hidden lg:block animate-float">
    {{ String(heroSlide + 1).padStart(2, '0') }} / {{ String(heroSlides.length).padStart(2, '0') }}
  </div>

  <!-- Flèches nav -->
  <button
    @click="prevHeroSlide"
    class="absolute left-12 top-1/2 -translate-y-1/2 z-30 size-14 rounded-full bg-white/20 border border-white/30 text-white flex items-center justify-center hover:bg-white/30 transition-all backdrop-blur-md hover:scale-110 hover:shadow-lg"
  >
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </button>
  <button
    @click="nextHeroSlide"
    class="absolute right-12 top-1/2 -translate-y-1/2 z-30 size-14 rounded-full bg-white/20 border border-white/30 text-white flex items-center justify-center hover:bg-white/30 transition-all backdrop-blur-md hover:scale-110 hover:shadow-lg"
  >
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </button>

  <!-- Dots + progress -->
  <div class="absolute bottom-12 left-1/2 -translate-x-1/2 z-30 flex items-center gap-3">
    <button
      v-for="(_, i) in heroSlides"
      :key="i"
      @click="goToHeroSlide(i)"
      class="h-3 rounded-full transition-all duration-300 hover:scale-125"
      :class="heroSlide === i ? 'w-8 bg-white shadow-lg shadow-white/30 animate-pulse-glow' : 'w-3 bg-white/50 hover:bg-white/70'"
    ></button>
  </div>

  <!-- Barre de progression -->
  <div class="absolute bottom-0 left-0 w-full h-[4px] bg-white/30 z-30">
    <div 
      class="h-full bg-gradient-to-r from-primary to-blue-400 transition-none"
      :style="{ width: heroProgress + '%', transition: heroProgressTransition }"
    ></div>
  </div>
</header>

    <!-- Main Content -->
    <main class="flex flex-col w-full bg-white pb-20">
      <!-- Carousel / News Section -->
      <section class="w-full max-w-[1320px] mx-auto px-6 py-24">
        <div class="flex items-end justify-between mb-14">
          <div>
            <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary font-bold text-xs uppercase tracking-widest rounded-full mb-3">Featured</span>
            <h2 class="text-soft-black text-4xl md:text-5xl font-black tracking-tighter mb-3">À la une</h2>
            <p class="text-medium-grey text-lg font-medium">Les dernières ouvertures et événements majeurs.</p>
          </div>
        </div>
        
        <!-- Cards Container with Auto-scroll -->
        <div class="relative overflow-hidden">
          <!-- Slide Indicators -->
          <div class="flex justify-center gap-3 mb-8">
            <button
              v-for="(_, index) in Math.min(featuredItemsData.length, 6)"
              :key="index"
              @click="goToSlide(index)"
              :class="[
                'transition-all duration-300 rounded-full',
                currentSlide === index ? 'w-8 h-2.5 bg-primary shadow-lg shadow-primary/40' : 'w-2.5 h-2.5 bg-gray-300 hover:bg-gray-400'
              ]"
            />
          </div>
          
                  <div 
            ref="carouselContainer"
            class="flex gap-7 overflow-x-auto no-scrollbar snap-x snap-mandatory touch-pan-x md:overflow-hidden"
            :class="isAutoScrolling ? 'animate-scroll-left' : 'transition-transform duration-300 ease-in-out'"
            :style="carouselStyle"
          >
            <!-- Duplicate items for infinite scroll -->
            <div 
              v-for="(item, index) in [...featuredItemsData, ...featuredItemsData]" 
              :key="`${index}-duplicate`"
              class="group relative flex flex-col gap-4 cursor-pointer min-w-[280px] max-w-[280px] sm:min-w-[320px] sm:max-w-[320px] md:min-w-[400px] md:max-w-[400px] flex-shrink-0 snap-start transition-transform duration-300 hover:scale-105"
              @click="viewItem(item)"
            >
              <div class="relative w-full aspect-[4/3] rounded-3xl overflow-hidden shadow-lg group-hover:shadow-2xl transition-shadow">
                <div class="absolute top-5 left-5 z-10">
                  <span 
                    :class="[
                      'px-4 py-2 text-xs font-black rounded-full uppercase tracking-widest inline-block shadow-lg',
                      item.type === 'Nouveau' ? 'bg-white text-soft-black' :
                      item.type === 'Tournoi' ? 'bg-gradient-to-r from-primary to-blue-500 text-white' :
                      'bg-white/95 text-soft-black'
                    ]"
                  >
                    {{ item.type }}
                  </span>
                </div>
                <img 
                  class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                  :src="item.image || 'https://picsum.photos/seed/featured-' + item.id + '/400/300.jpg'"
                  :alt="item.title"
                />
              </div>
              <div class="flex flex-col gap-3">
                <h3 class="text-lg font-bold text-soft-black leading-tight line-clamp-2">{{ item.title }}</h3>
                <p class="text-medium-grey text-sm leading-relaxed line-clamp-2">{{ item.description }}</p>
              </div>
            </div>
          </div>
        </div>
        </section>

    <!-- Popular Rooms Section -->
      <section class="w-full bg-gradient-to-b from-[#f8f9fc] to-white py-24">
        <div class="max-w-[1320px] mx-auto px-6">
          <!-- Header with Tabs -->
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-16">
            <div>
              <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary font-bold text-xs uppercase tracking-widest rounded-full mb-3">Popular</span>
              <h2 class="text-soft-black text-4xl md:text-5xl font-black tracking-tighter">Salles Populaires</h2>
            </div>
            <div class="flex bg-white p-1 rounded-full shadow-lg w-fit border border-gray-100">
              <button 
                v-for="tab in roomTabs"
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                  'px-7 py-2.5 rounded-full text-sm font-bold transition-all duration-300',
                  activeTab === tab.id ? 'bg-gradient-to-r from-primary to-blue-600 text-white shadow-lg shadow-primary/30' : 'text-medium-grey hover:text-soft-black'
                ]"
              >
                {{ tab.label }}
              </button>
            </div>
          </div>
          
          <!-- Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <article 
              v-for="(room, index) in filteredRooms" 
              :key="index"
              class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-lg hover:shadow-2xl transition-all duration-500 group hover:border-primary/20 hover:scale-105"
            >
              <div class="relative aspect-[3/2] overflow-hidden bg-gray-200">
                <img 
                  class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                  :src="room.image || 'https://picsum.photos/seed/room-' + room.id + '/400/300.jpg'"
                  :alt="room.name"
                />
                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur rounded-full px-3 py-2 flex items-center gap-1.5 shadow-lg font-bold">
                  <i class="fas fa-star text-yellow-400 text-[16px]"></i>
                  <span class="text-xs font-black text-soft-black">{{ room.rating }}</span>
                </div>
              </div>
              <div class="p-6 flex flex-col gap-4">
                <div>
                  <h3 class="text-lg font-bold text-soft-black leading-tight line-clamp-2">{{ room.name }}</h3>
                  <div class="flex items-center gap-2 text-medium-grey mt-2">
                    <i class="fas fa-map-marker-alt text-[14px]"></i>
                    <span class="text-xs font-semibold">{{ room.location }}</span>
                  </div>
                </div>
                <div class="w-full h-[1px] bg-gradient-to-r from-gray-100 via-gray-200 to-gray-100"></div>
                <div class="flex items-center justify-between">
                  <div>
                    <span class="text-primary font-black text-lg">{{ room.price }}€</span>
                    <span class="text-medium-grey font-medium text-xs ml-1">/ heure</span>
                  </div>
                  <button 
                    @click="reserveRoom(room)"
                    class="text-soft-black hover:text-white hover:bg-primary text-xs font-black px-4 py-2 rounded-full transition-all flex items-center gap-1.5 border border-gray-200 hover:border-primary"
                  >
                    <i class="fas fa-calendar"></i>
                    Réserver
                  </button>
                </div>
              </div>
            </article>
          </div>
          
          <div class="mt-16 text-center">
            <a href="/search/rooms" class="inline-flex items-center justify-center gap-2 px-10 py-4 rounded-full border-2 border-primary text-primary font-bold hover:bg-primary hover:text-white transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
              Voir toutes les salles
              <i class="fas fa-arrow-right text-[18px]"></i>
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
            YOUPIHUB accompagne les joueurs depuis 5 années
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
    <section class="w-full bg-white py-24 relative overflow-hidden">
      <!-- Background decoration -->
      <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-400 rounded-full blur-3xl"></div>
      </div>
      
      <div class="max-w-[1320px] mx-auto px-6 relative z-10">
        <!-- Header -->
        <div class="text-center mb-20">
          <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary font-bold text-xs uppercase tracking-widest rounded-full mb-4">Process</span>
          <h2 class="text-soft-black text-4xl md:text-5xl font-black tracking-tighter mb-6">Comment ça marche ?</h2>
          <p class="text-medium-grey text-lg max-w-2xl mx-auto font-medium leading-relaxed">
            Réservez votre salle de gaming en quelques clics et profitez d'une expérience unique
          </p>
        </div>

        <!-- Steps -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-16">
          <!-- Step 1 -->
          <div class="text-center group relative">
            <div class="size-24 bg-gradient-to-br from-primary/15 to-primary/5 rounded-full flex items-center justify-center mx-auto mb-8 group-hover:from-primary/25 group-hover:to-primary/10 transition-all transform group-hover:scale-110 shadow-lg">
              <i class="fas fa-search text-[48px] text-primary"></i>
            </div>
            <div class="flex items-center justify-center gap-3 mb-5">
              <div class="size-10 bg-gradient-to-br from-primary to-blue-600 text-white rounded-full flex items-center justify-center font-black text-lg shadow-lg shadow-primary/40">1</div>
              <h3 class="text-2xl font-black text-soft-black">Recherchez</h3>
            </div>
            <p class="text-medium-grey leading-relaxed text-base font-medium">
              Trouvez la salle parfaite parmi notre sélection de salles de gaming équipées
            </p>
          </div>

          <!-- Arrow decoration -->
          <div class="hidden md:flex items-center justify-center -mx-8">
            <div class="transform group-hover:translate-x-2 transition-transform">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" class="text-gray-200">
                <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </div>

          <!-- Step 2 -->
          <div class="text-center group">
            <div class="size-24 bg-gradient-to-br from-primary/15 to-primary/5 rounded-full flex items-center justify-center mx-auto mb-8 group-hover:from-primary/25 group-hover:to-primary/10 transition-all transform group-hover:scale-110 shadow-lg">
              <i class="fas fa-calendar-alt text-[48px] text-primary"></i>
            </div>
            <div class="flex items-center justify-center gap-3 mb-5">
              <div class="size-10 bg-gradient-to-br from-primary to-blue-600 text-white rounded-full flex items-center justify-center font-black text-lg shadow-lg shadow-primary/40">2</div>
              <h3 class="text-2xl font-black text-soft-black">Réservez</h3>
            </div>
            <p class="text-medium-grey leading-relaxed text-base font-medium">
              Choisissez vos dates et réservez instantanément en ligne
            </p>
          </div>

          <!-- Arrow decoration -->
          <div class="hidden md:flex items-center justify-center -mx-8">
            <div class="transform group-hover:translate-x-2 transition-transform">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" class="text-gray-200">
                <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </div>

          <!-- Step 3 -->
          <div class="text-center group">
            <div class="size-24 bg-gradient-to-br from-primary/15 to-primary/5 rounded-full flex items-center justify-center mx-auto mb-8 group-hover:from-primary/25 group-hover:to-primary/10 transition-all transform group-hover:scale-110 shadow-lg">
              <i class="fas fa-gamepad text-[48px] text-primary"></i>
            </div>
            <div class="flex items-center justify-center gap-3 mb-5">
              <div class="size-10 bg-gradient-to-br from-primary to-blue-600 text-white rounded-full flex items-center justify-center font-black text-lg shadow-lg shadow-primary/40">3</div>
              <h3 class="text-2xl font-black text-soft-black">Jouez</h3>
            </div>
            <p class="text-medium-grey leading-relaxed text-base font-medium">
              Profitez de votre session gaming dans un environnement professionnel
            </p>
          </div>
        </div>

        <!-- CTA -->
        <div class="text-center mt-20">
          <button 
            @click="goToSearchRooms"
            class="bg-gradient-to-r from-primary to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-12 py-5 rounded-full text-lg font-bold transition-all shadow-xl shadow-primary/40 hover:shadow-2xl hover:shadow-primary/50 transform hover:scale-105"
          >
            Commencer maintenant
            <i class="fas fa-arrow-right ml-2"></i>
          </button>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <MainFooter />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import MainFooter from '@/Components/MainFooter.vue';
import heroImg1 from './Public/image-hero/sale-de-jeu.jpg';
import heroImg2 from './Public/image-hero/bolling.jpg';
import heroImg3 from './Public/image-hero/hero-tech.jpg';
import heroImg4 from './Public/image-hero/park-attraction.jpg';

// Nouvelles images ajoutées
import eventImg from '../../../public/IMAGES/evenements.jpg';
import image11 from '../../../public/IMAGES/image11.jpg';
import saleJeuxImg from '../../../public/IMAGES/sale de jeux.jpg';

const heroSlides = [
  {
    image: heroImg1,
    tag: 'Gaming & E-sport',
    dotColor: '#4ade80',
    title: 'Votre prochaine partie<br>commence <span style="color:#93c5fd">ici</span>',
    subtitle: 'Réservez les meilleures salles de gaming haute performance et participez à des événements exclusifs près de chez vous.',
    primaryLabel: 'Trouver une salle',
    secondaryLabel: 'Voir les événements',
    primaryAction: () => window.location.href = '/search/rooms',
    secondaryAction: () => window.location.href = '/events',
  },
  {
    image: eventImg,
    tag: 'Événements',
    dotColor: '#f59e0b',
    title: 'Rejoignez nos <span style="color:#fcd34d">événements</span><br>exclusifs',
    subtitle: 'Tournois, LAN parties, soirées gaming... Vivez des expériences uniques avec la communauté YOUPIHUB.',
    primaryLabel: 'Voir les événements',
    secondaryLabel: 'S\'inscrire',
    primaryAction: () => window.location.href = '/events',
    secondaryAction: () => window.location.href = '/register',
  },
  {
    image: image11,
    tag: 'Communauté',
    dotColor: '#f87171',
    title: 'Une communauté <span style="color:#f87171">passionnée</span><br>vous attend',
    subtitle: 'Rejoignez des milliers de gamers passionnés et créez des souvenirs inoubliables ensemble.',
    primaryLabel: 'Rejoindre',
    secondaryLabel: 'En savoir plus',
    primaryAction: () => window.location.href = '/register',
    secondaryAction: () => window.location.href = '/about',
  },
  {
    image: saleJeuxImg,
    tag: 'Salle de Jeux',
    dotColor: '#a855f7',
    title: 'Votre salle de jeux <span style="color:#c084fc">idéale</span><br>à portée de main',
    subtitle: 'Équipements haut de gamme, ambiance parfaite, et service premium pour vos sessions gaming.',
    primaryLabel: 'Réserver maintenant',
    secondaryLabel: 'Voir les salles',
    primaryAction: () => window.location.href = '/search/rooms',
    secondaryAction: () => window.location.href = '/search/rooms',
  },
];

const heroSlide = ref(0);
const heroProgress = ref(0);
const heroProgressTransition = ref('none');
let heroTimer = null;
let heroProgressTimer = null;

const goToHeroSlide = (n) => {
  heroSlide.value = (n + heroSlides.length) % heroSlides.length;
  startHeroProgress();
};
const nextHeroSlide = () => { clearInterval(heroTimer); goToHeroSlide(heroSlide.value + 1); startHeroAuto(); };
const prevHeroSlide = () => { clearInterval(heroTimer); goToHeroSlide(heroSlide.value - 1); startHeroAuto(); };

const startHeroProgress = () => {
  clearInterval(heroProgressTimer);
  heroProgress.value = 0;
  heroProgressTransition.value = 'none';
  setTimeout(() => {
    heroProgressTransition.value = 'width 5000ms linear';
    heroProgress.value = 100;
  }, 50);
};

const startHeroAuto = () => {
  clearInterval(heroTimer);
  heroTimer = setInterval(() => goToHeroSlide(heroSlide.value + 1), 5000);
  startHeroProgress();
};

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    featuredItems: Array,
    popularRooms: Array,
    stats: Object,
    news: Array
});

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

// Utiliser les données réelles du contrôleur pour les stats
const usersCount = ref(props.stats?.usersCount || 0);
const roomsCount = ref(props.stats?.roomsCount || 0);
const bookingsCount = ref(props.stats?.bookingsCount || 0);

// Utiliser les données réelles du contrôleur
const featuredItemsData = ref(props.featuredItems || []);

// Utiliser les données réelles du contrôleur
const popularRoomsData = ref(props.popularRooms || []);

// Computed property for filtered rooms
const filteredRooms = ref(popularRoomsData.value);

// Carousel functionality
const currentSlide = ref(0);
const carouselContainer = ref(null);
const isAutoScrolling = ref(true);
const isMobileView = ref(false);
const searchQuery = ref('');
const selectedLocation = ref('Paris');
const heroLoaded = ref(false);

const carouselStyle = computed(() => {
  if (isAutoScrolling.value || isMobileView.value) {
    return {};
  }
  return { transform: `translateX(-${currentSlide.value * 424}px)` };
});

const updateMobileView = () => {
  isMobileView.value = window.innerWidth < 768;
  if (isMobileView.value) {
    isAutoScrolling.value = false;
  }
};

const prevSlide = () => {
  isAutoScrolling.value = false;
  if (currentSlide.value > 0) {
    currentSlide.value--;
  }
};

const nextSlide = () => {
  isAutoScrolling.value = false;
  const maxSlide = Math.max(0, featuredItemsData.value.length - 1);
  if (currentSlide.value < maxSlide) {
    currentSlide.value++;
  }
};

const goToSlide = (index) => {
  isAutoScrolling.value = false;
  currentSlide.value = index;
};

onMounted(() => {
  updateMobileView();
  window.addEventListener('resize', updateMobileView);

  // Trigger hero entrance animation
  setTimeout(() => {
    heroLoaded.value = true;
  }, 100);
});

onUnmounted(() => {
  window.removeEventListener('resize', updateMobileView);
});

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
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

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

/* Fade in up animation */
@keyframes fadeInUp {
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
  animation: fadeInUp 0.8s ease-out forwards;
  opacity: 0;
}

.animate-fade-in-up.delay-300 {
  animation-delay: 0.3s;
}

.animate-fade-in-up.delay-600 {
  animation-delay: 0.6s;
}

.delay-100 {
  animation-delay: 0.1s !important;
}

.delay-300 {
  animation-delay: 0.3s !important;
}

.delay-500 {
  animation-delay: 0.5s !important;
}

.delay-700 {
  animation-delay: 0.7s !important;
}

.delay-900 {
  animation-delay: 0.9s !important;
}

/* Enhanced animations */
@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(60px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-40px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(40px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-10px);
  }
}

@keyframes pulse-glow {
  0%, 100% {
    box-shadow: 0 0 20px rgba(19, 91, 188, 0.3);
  }
  50% {
    box-shadow: 0 0 40px rgba(19, 91, 188, 0.6);
  }
}

@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

.animate-slide-in-up {
  animation: slideInUp 0.8s ease-out forwards;
}

.animate-slide-in-left {
  animation: slideInLeft 0.8s ease-out forwards;
}

.animate-slide-in-right {
  animation: slideInRight 0.8s ease-out forwards;
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

.animate-pulse-glow {
  animation: pulse-glow 2s ease-in-out infinite;
}

.animate-shimmer {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  background-size: 200% 100%;
  animation: shimmer 2s infinite;
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

/* Smooth transitions */
* {
  @apply transition-colors;
}

/* Hero gradient text */
.gradient-text {
  background: linear-gradient(135deg, #135bec 0%, #3b82f6 100%);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* Card hover effect */
.card-hover {
  @apply transition-all duration-300 hover:scale-105 hover:shadow-2xl;
}

/* Button hover glow */
.btn-glow {
  @apply shadow-lg shadow-primary/40 hover:shadow-xl hover:shadow-primary/60;
}
</style>
