<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const stats = ref([
  { number: '500+', label: 'centres référencés' },
  { number: '50K+', label: 'utilisateurs' },
  { number: '1000+', label: 'événements diffusés' }
]);

const features = ref([
  {
    icon: 'fas fa-building',
    title: 'Centres de loisirs',
    description: 'Publication et promotion de tous les centres de loisirs.'
  },
  {
    icon: 'fas fa-calendar-alt',
    title: 'Événements',
    description: 'Diffusion d\'événements culturels, sportifs, gaming et bien plus.'
  },
  {
    icon: 'fas fa-eye',
    title: 'Visibilité',
    description: 'Publicité digitale pour attirer plus de clients et de participants.'
  }
]);

const showMobileMenu = ref(false);

const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value;
};
</script>

<template>
  <Head title="À propos - YOUPIHUB" />

  <div class="min-h-screen bg-white font-display">
    <!-- Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100">
      <div class="max-w-[1320px] mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <!-- Logo -->
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold">GB</span>
            </div>
            <h1 class="text-soft-black text-xl font-bold tracking-tight">YOUPIHUB</h1>
          </div>
          
          <!-- Desktop Menu -->
          <div class="hidden md:flex items-center gap-10">
            <a class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors" href="/">Accueil</a>
            <a class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors" href="/search/rooms">Salles</a>
            <a 
              :class="[
                'text-sm font-medium transition-colors',
                $page.url === '/events' ? 'text-primary hover:text-primary' : 'text-medium-grey hover:text-soft-black'
              ]" 
              href="/events"
            >
              Événements
            </a>
            <a 
              :class="[
                'text-sm font-medium transition-colors',
                $page.url === '/products' ? 'text-primary hover:text-primary' : 'text-medium-grey hover:text-soft-black'
              ]" 
              href="/products"
            >
              Boutique
            </a>
            <a class="text-primary hover:text-primary text-sm font-medium transition-colors" href="/about">À propos</a>
          </div>
          
          <!-- Desktop Auth Buttons -->
          <div class="hidden md:flex items-center gap-3">
            <a href="/login" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-soft-black text-sm font-medium rounded-lg transition-colors">Connexion</a>
            <a href="/register" class="flex items-center justify-center rounded-full bg-primary hover:bg-blue-600 text-white text-sm font-bold px-6 py-2.5 transition-all shadow-lg shadow-primary/20">
              Inscription
            </a>
          </div>

          <!-- Mobile Menu Button -->
          <button 
            @click="toggleMobileMenu"
            class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <i class="fas fa-bars text-2xl text-soft-black" v-if="!showMobileMenu"></i>
            <i class="fas fa-times text-2xl text-soft-black" v-else></i>
          </button>
        </div>

        <!-- Mobile Menu -->
        <div 
          :class="[
            'md:hidden transition-all duration-300 overflow-hidden',
            showMobileMenu ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'
          ]"
        >
          <div class="py-4 border-t border-gray-100">
            <div class="flex flex-col gap-4">
              <a href="/" class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors py-2 text-center">Accueil</a>
              <a href="/search/rooms" class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors py-2 text-center">Salles</a>
              <a 
                :class="[
                  'text-sm font-medium transition-colors py-2 text-center',
                  $page.url === '/events' ? 'text-primary hover:text-primary' : 'text-medium-grey hover:text-soft-black'
                ]" 
                href="/events"
              >
                Événements
              </a>
              <a 
                :class="[
                  'text-sm font-medium transition-colors py-2 text-center',
                  $page.url === '/products' ? 'text-primary hover:text-primary' : 'text-medium-grey hover:text-soft-black'
                ]" 
                href="/products"
              >
                Boutique
              </a>
              <a href="/about" class="text-primary hover:text-primary text-sm font-medium transition-colors py-2 text-center">À propos</a>
              <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="/login" class="flex-1 text-center text-medium-grey hover:text-soft-black text-sm font-medium transition-colors py-2 border border-gray-200 rounded-lg">Connexion</a>
                <a href="/register" class="flex-1 text-center bg-primary hover:bg-blue-600 text-white text-sm font-bold py-2 rounded-lg transition-all shadow-lg shadow-primary/20">Inscription</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="relative min-h-[500px] sm:min-h-[600px] pt-16 sm:pt-20 overflow-hidden">
      <!-- Background Image with Overlay -->
      <div class="absolute inset-0">
        <img 
          src="https://picsum.photos/seed/about-us/1920/800.jpg" 
          alt="About Us Background"
          class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-br from-primary/60 via-purple-600/50 to-indigo-700/60"></div>
      </div>
      
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 right-20 w-40 h-40 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-1/3 w-36 h-36 bg-white rounded-full blur-3xl"></div>
      </div>
      
      <div class="relative z-10 max-w-[1320px] mx-auto px-4 sm:px-6 py-12 sm:py-16">
        <div class="text-center mb-8 sm:mb-12">
          <h1 class="text-white text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1] mb-4 sm:mb-6">
            À propos de <span class="text-yellow-400">YOUPIHUB</span>
          </h1>
          <p class="text-white/80 text-lg md:text-xl font-normal leading-relaxed max-w-2xl mx-auto">
            La plateforme N°1 de promotion des centres de loisirs et événements en Afrique
          </p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a href="/search/rooms" class="px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-lg font-medium hover:bg-white/30 transition-colors border border-white/30">
            Explorer les centres
          </a>
          <a href="/events" class="px-6 py-3 bg-white hover:bg-gray-100 text-soft-black rounded-lg font-medium transition-colors">
            Voir les événements
          </a>
        </div>
      </div>
    </section>

    <!-- Qui sommes-nous -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Qui sommes-nous</h2>
        
        <div class="prose prose-lg max-w-none">
          <p class="text-gray-600 leading-relaxed">
            YOUPIHUB est une plateforme digitale dédiée à la visibilité des centres de loisirs
            et à la diffusion d'événements de tous types en Afrique.
          </p>
          <p class="text-gray-600 leading-relaxed mt-4">
            Notre objectif est simple : connecter le public, les promoteurs et les espaces de loisirs
            sur une seule plateforme fiable et accessible.
          </p>
        </div>
      </div>
    </section>

    <!-- Notre mission -->
    <section class="py-20 bg-blue-50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Notre mission</h2>
        
        <p class="text-lg text-gray-600 leading-relaxed">
          Rendre les centres de loisirs et les événements africains visibles, accessibles
          et facilement découvrables par tous.
        </p>
      </div>
    </section>

    <!-- Ce que fait YOUPIHUB -->
    <section class="py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Ce que fait YOUPIHUB</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
          <div v-for="(feature, index) in features" :key="index" class="text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i :class="feature.icon + ' text-blue-600 text-xl'"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ feature.title }}</h3>
            <p class="text-gray-600">{{ feature.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Pourquoi YOUPIHUB -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Pourquoi YOUPIHUB</h2>
        
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <i class="fas fa-check text-green-500"></i>
            <span class="text-gray-700">Plateforme spécialisée Afrique</span>
          </div>
          <div class="flex items-center gap-3">
            <i class="fas fa-check text-green-500"></i>
            <span class="text-gray-700">Tous types de loisirs et événements</span>
          </div>
          <div class="flex items-center gap-3">
            <i class="fas fa-check text-green-500"></i>
            <span class="text-gray-700">Accessible à tous</span>
          </div>
          <div class="flex items-center gap-3">
            <i class="fas fa-check text-green-500"></i>
            <span class="text-gray-700">Interface simple et rapide</span>
          </div>
          <div class="flex items-center gap-3">
            <i class="fas fa-check text-green-500"></i>
            <span class="text-gray-700">Pensée pour le contexte local</span>
          </div>
        </div>
      </div>
    </section>

    <!-- L'équipe -->
    <section class="py-20 bg-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">L'équipe</h2>
        
        <div class="flex items-center gap-8">
          <img src="https://picsum.photos/seed/sergio/200/200" 
               alt="Sergio AHOUANGONOU" 
               class="w-24 h-24 rounded-full object-cover">
          <div>
            <h3 class="text-xl font-semibold text-gray-900 mb-1">Fondateur</h3>
            <p class="text-lg font-medium text-gray-800 mb-2">Sergio AHOUANGONOU</p>
            <p class="text-gray-600">Fondateur de YOUPIHUB</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Chiffres clés -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8 text-center">
          <div v-for="(stat, index) in stats" :key="index">
            <div class="text-4xl font-bold text-blue-600 mb-2">{{ stat.number }}</div>
            <div class="text-gray-600">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA final -->
    <section class="py-20 bg-blue-900 text-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-6">
          Rejoignez la plateforme qui centralise les loisirs et événements en Afrique.
        </h2>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link href="/register" class="px-6 py-3 bg-yellow-400 text-gray-900 rounded-lg font-medium hover:bg-yellow-300 transition-colors">
            S'inscrire gratuitement
          </Link>
          <Link href="/contact" class="px-6 py-3 bg-white text-blue-900 rounded-lg font-medium hover:bg-gray-100 transition-colors">
            Publier un centre ou événement
          </Link>
        </div>
      </div>
    </section>
  </div>
</template>
