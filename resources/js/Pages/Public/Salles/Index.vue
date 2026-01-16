<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    salles: Object,
    filters: Object
});

// États pour les filtres
const search = ref(props.filters.search || '');
const type = ref(props.filters.type || '');
const minPrice = ref(props.filters.min_price || '');
const maxPrice = ref(props.filters.max_price || '');

// Types de salles disponibles
const types = [
    { value: 'salle_reunion', label: 'Salle de réunion' },
    { value: 'salle_fete', label: 'Salle de fête' },
    { value: 'salle_sport', label: 'Salle de sport' },
    { value: 'salle_conference', label: 'Salle de conférence' },
    { value: 'salle_mariage', label: 'Salle de mariage' },
    { value: 'autre', label: 'Autre' }
];

// Mettre à jour l'URL avec les filtres
const updateFilters = () => {
    const params = new URLSearchParams();
    
    if (search.value) params.append('search', search.value);
    if (type.value) params.append('type', type.value);
    if (minPrice.value) params.append('min_price', minPrice.value);
    if (maxPrice.value) params.append('max_price', maxPrice.value);
    
    const url = params.toString() ? `/salles?${params.toString()}` : '/salles';
    window.location.href = url;
};

// Réinitialiser les filtres
const resetFilters = () => {
    search.value = '';
    type.value = '';
    minPrice.value = '';
    maxPrice.value = '';
    updateFilters();
};

// Formater le prix
const formatPrice = (price) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(price);
};

// Obtenir l'icône du type de salle
const getTypeIcon = (salleType) => {
    const icons = {
        'salle_reunion': 'fas fa-users',
        'salle_fete': 'fas fa-glass-cheers',
        'salle_sport': 'fas fa-running',
        'salle_conference': 'fas fa-chalkboard-teacher',
        'salle_mariage': 'fas fa-heart',
        'autre': 'fas fa-door-open'
    };
    return icons[salleType] || 'fas fa-door-open';
};
</script>

<template>
    <Head title="Salles" />
    
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="sticky top-0 z-40 bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-8">
                        <a href="/" class="flex items-center space-x-2">
                            <i class="fas fa-gamepad text-2xl text-blue-600"></i>
                            <span class="text-xl font-bold text-gray-900">YOUPIHUB</span>
                        </a>
                        <nav class="hidden md:flex space-x-6">
                            <Link href="/client/dashboard" class="text-gray-600 hover:text-gray-900 transition-colors">Tableau de bord</Link>
                            <Link href="/salles" class="text-blue-600 font-medium">Salles</Link>
                            <Link href="/client/reservations" class="text-gray-600 hover:text-gray-900 transition-colors">Mes Réservations</Link>
                            <Link href="/client/profile" class="text-gray-600 hover:text-gray-900 transition-colors">Mon Profil</Link>
                        </nav>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="relative p-2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-blue-500 rounded-full"></span>
                        </button>
                        <Link href="/client/profile" class="w-10 h-10 bg-gray-300 rounded-full"></Link>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Page Header -->
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-gray-900">Découvrez nos salles</h1>
                <p class="text-gray-600 mt-2">Trouvez la salle parfaite pour votre événement</p>
            </div>

            <!-- Filtres -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input 
                                v-model="search"
                                @keyup.enter="updateFilters"
                                type="text"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Nom, description, adresse..."
                            >
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type de salle</label>
                        <select 
                            v-model="type"
                            @change="updateFilters"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Tous les types</option>
                            <option v-for="t in types" :key="t.value" :value="t.value">
                                {{ t.label }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix minimum</label>
                        <input 
                            v-model="minPrice"
                            @keyup.enter="updateFilters"
                            type="number"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="0 XOF"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix maximum</label>
                        <input 
                            v-model="maxPrice"
                            @keyup.enter="updateFilters"
                            type="number"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="10000 XOF"
                        >
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium text-gray-900">{{ salles.total }}</span> 
                        <span class="text-gray-500">salle(s) trouvée(s)</span>
                    </div>
                    <button 
                        @click="resetFilters"
                        class="px-4 py-2 text-gray-600 hover:text-gray-900"
                    >
                        <i class="fas fa-redo mr-2"></i>
                        Réinitialiser
                    </button>
                </div>
            </div>

            <!-- Grille des salles -->
            <div v-if="salles.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="salle in salles.data" 
                    :key="salle.id"
                    class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow"
                >
                    <!-- Image -->
                    <div class="h-48 bg-gray-200 relative">
                        <img 
                            v-if="salle.images && salle.images.length > 0"
                            :src="salle.images[0].url"
                            :alt="salle.nom"
                            class="w-full h-full object-cover"
                        >
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                        <div class="absolute top-2 right-2">
                            <span class="bg-white px-2 py-1 rounded-full text-xs font-medium text-gray-700">
                                {{ formatPrice(salle.prix_heure) }}/h
                            </span>
                        </div>
                    </div>
                    
                    <!-- Contenu -->
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-lg mb-1">{{ salle.nom }}</h3>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i :class="`${getTypeIcon(salle.type)} mr-2`"></i>
                                    {{ types.find(t => t.value === salle.type)?.label || 'Autre' }}
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ salle.description }}</p>
                        
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ salle.adresse }}
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-users mr-2"></i>
                                {{ salle.capacite }} pers.
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="text-lg font-bold text-gray-900">
                                {{ formatPrice(salle.prix_heure) }}
                                <span class="text-sm font-normal text-gray-500">/heure</span>
                            </div>
                            <Link 
                                :href="`/salles/${salle.id}`"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                <i class="fas fa-eye mr-2"></i>
                                Voir
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message si aucune salle -->
            <div v-else class="text-center py-16">
                <div class="mb-6">
                    <i class="fas fa-search text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">Aucune salle trouvée</h3>
                <p class="text-gray-600 max-w-md mx-auto mb-6">
                    Essayez de modifier vos critères de recherche ou découvrez toutes nos salles disponibles.
                </p>
                <button 
                    @click="resetFilters"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    <i class="fas fa-redo mr-2"></i>
                    Réinitialiser les filtres
                </button>
            </div>

            <!-- Pagination -->
            <div v-if="salles.data.length > 0" class="mt-8">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Affichage de {{ salles.from }} à {{ salles.to }} sur {{ salles.total }} résultats
                    </div>
                    <div class="flex space-x-2">
                        <Link 
                            v-if="salles.prev_page_url"
                            :href="salles.prev_page_url"
                            class="px-3 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                        >
                            <i class="fas fa-chevron-left"></i>
                        </Link>
                        <Link 
                            v-for="link in salles.links.slice(1, -1)"
                            :key="link.label"
                            :href="link.url"
                            :class="`px-3 py-2 rounded-md ${link.active ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50'}`"
                        >
                            {{ link.label }}
                        </Link>
                        <Link 
                            v-if="salles.next_page_url"
                            :href="salles.next_page_url"
                            class="px-3 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                        >
                            <i class="fas fa-chevron-right"></i>
                        </Link>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
/* Smooth transitions */
* {
    transition-property: background-color, border-color, color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
