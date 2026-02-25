<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  products: Object,
  categories: Array
})

const searchQuery = ref('')
const selectedCategory = ref('all')
const showMobileMenu = ref(false)

const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value
}

const filteredProducts = computed(() => {
  // Récupérer les données depuis l'objet paginé Laravel
  let filtered = props.products?.data || []
  console.log('DEBUG - props.products complet:', props.products)
  console.log('DEBUG - props.products.data:', props.products?.data)
  console.log('DEBUG - nombre de produits reçus:', filtered?.length || 0)
  
  // Filtrer les produits null/undefined
  filtered = filtered.filter(product => product && product.id)
  console.log('DEBUG - produits après filtre null:', filtered.length)
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(product => 
      product.name && product.name.toLowerCase().includes(query) ||
      product.description && product.description.toLowerCase().includes(query)
    )
  }
  
  if (selectedCategory.value !== 'all') {
    filtered = filtered.filter(product => product.category === selectedCategory.value)
  }
  
  console.log('DEBUG - produits finaux:', filtered.length)
  return filtered
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XOF',
    minimumFractionDigits: 0
  }).format(price)
}
</script>

<template>
  <Head title='Produits - YOUPIHUB' />
  
  <div class='min-h-screen bg-gray-50'>
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100">
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
            <a class="text-primary hover:text-primary text-sm font-medium transition-colors" href="/products">Boutique</a>
            <a 
              :class="[
                'text-sm font-medium transition-colors',
                $page.url === '/about' ? 'text-primary hover:text-primary' : 'text-medium-grey hover:text-soft-black'
              ]" 
              href="/about"
            >
              À propos
            </a>
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
              <a href="/products" class="text-primary hover:text-primary text-sm font-medium transition-colors py-2 text-center">Boutique</a>
              <a 
                :class="[
                  'text-sm font-medium transition-colors py-2 text-center',
                  $page.url === '/about' ? 'text-primary hover:text-primary' : 'text-medium-grey hover:text-soft-black'
                ]" 
                href="/about"
              >
                À propos
              </a>
              <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="/login" class="flex-1 text-center text-medium-grey hover:text-soft-black text-sm font-medium transition-colors py-2 border border-gray-200 rounded-lg">Connexion</a>
                <a href="/register" class="flex-1 text-center bg-primary hover:bg-blue-600 text-white text-sm font-bold py-2 rounded-lg transition-all shadow-lg shadow-primary/20">Inscription</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section with Search -->
    <section class="relative min-h-[500px] sm:min-h-[600px] pt-16 sm:pt-20 overflow-hidden">
      <!-- Background Image with Overlay -->
      <div class="absolute inset-0">
        <img 
          src="https://picsum.photos/seed/gaming-products/1920/800.jpg" 
          alt="Gaming Products Background"
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
            Notre <span class="text-yellow-400">Boutique Gaming</span>
          </h1>
          <p class="text-white/80 text-lg md:text-xl font-normal leading-relaxed max-w-2xl mx-auto">
            Découvrez notre sélection de produits gaming de haute qualité
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

    <!-- Filtres -->
    <section class='bg-white shadow-sm sticky top-0 z-40 border-b'>
      <div class='max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4'>
        <div class='flex flex-col lg:flex-row gap-4 items-center'>
          <div class='flex-1 relative'>
            <i class='fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
            <input 
              v-model='searchQuery'
              type='text' 
              placeholder='Rechercher un produit...'
              class='w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500'
            >
          </div>
          
          <div class='flex gap-3 items-center'>
            <select v-model='selectedCategory' class='px-4 py-3 border border-gray-300 rounded-lg'>
              <option value='all'>Toutes catégories</option>
              <option v-for='category in categories' :key='category' :value='category'>
                {{ category }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <!-- Produits -->
    <section class='py-8'>
      <div class='max-w-7xl mx-auto px-4 sm:px-6 lg:px-8'>
        <div class='flex justify-between items-center mb-8'>
          <div>
            <h2 class='text-2xl font-bold text-gray-900'>
              {{ filteredProducts.length }} produit{{ filteredProducts.length > 1 ? 's' : '' }} trouvé{{ filteredProducts.length > 1 ? 's' : '' }}
            </h2>
          </div>
        </div>

        <div v-if='filteredProducts.length > 0' class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6'>
          <div v-for='product in filteredProducts' :key='product.id'
               class='bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group'>
            <div class='relative h-48 bg-gray-100 overflow-hidden'>
              <img :src='product.image || "https://picsum.photos/seed/product-" + product.id + "/400/300"' 
                   :alt='product.name' 
                   class='w-full h-full object-cover group-hover:scale-110 transition-transform'>
            </div>
            
            <div class='p-6'>
              <h3 class='text-lg font-bold text-gray-900 mb-2'>{{ product.name || 'Nom du produit' }}</h3>
              <p class='text-gray-600 text-sm mb-4'>{{ product.description || 'Description du produit' }}</p>
              
              <div class='flex items-center justify-between mb-4'>
                <div class='text-2xl font-bold text-blue-600'>{{ formatPrice(product.price || 0) }}</div>
                <div class='flex items-center gap-1'>
                  <i class='fas fa-star text-yellow-400 text-sm'></i>
                  <span class='text-sm text-gray-600'>{{ product.rating || '4.5' }}</span>
                </div>
              </div>
              
              <button class='w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 font-medium'>
                <i class='fas fa-shopping-cart mr-2'></i>Ajouter
              </button>
            </div>
          </div>
        </div>

        <div v-else class='text-center py-16'>
          <i class='fas fa-box-open text-6xl text-gray-300 mb-4'></i>
          <h3 class='text-xl font-semibold text-gray-900 mb-2'>Aucun produit trouvé</h3>
          <p class='text-gray-600 mb-6'>Essayez de modifier vos filtres</p>
        </div>
      </div>
    </section>
  </div>
</template>
