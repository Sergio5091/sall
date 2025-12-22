<script setup>
import { Head } from '@inertiajs/vue3';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

defineProps({
    news: {
        type: Array,
        required: true
    }
});

const searchQuery = ref('');
const statusFilter = ref('');

const handleSearch = debounce(() => {
    updateFilters();
}, 300);

const handleStatusFilter = debounce(() => {
    updateFilters();
}, 300);

const updateFilters = () => {
    const params = new URLSearchParams(window.location.search);
  
    if (searchQuery.value) {
        params.set('search', searchQuery.value);
    } else {
        params.delete('search');
    }
  
    if (statusFilter.value) {
        params.set('status', statusFilter.value);
    } else {
        params.delete('status');
    }
  
    router.get(`${window.location.pathname}?${params.toString()}`, {}, { preserveState: true });
};

const clearFilters = () => {
    searchQuery.value = '';
    statusFilter.value = '';
    updateFilters();
};

const toggleNewsStatus = (newsItem) => {
    const newStatus = newsItem.is_active ? 0 : 1;
    router.patch(`/admin/news/${newsItem.id}`, 
        { is_active: newStatus }, 
        { preserveScroll: true }
    );
};

const deleteNews = (newsItem) => {
    if (confirm(`Êtes-vous sûr de vouloir supprimer l'actualité "${newsItem.title}" ?`)) {
        router.delete(`/admin/news/${newsItem.id}`);
    }
};
</script>

<template>
    <Head title="Actualités" />
  
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col gap-6">
                        <!-- Breadcrumbs -->
                        <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                            <Link :href="route('welcome')" class="hover:text-primary transition-colors">Accueil</Link>
                            <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
                            <span class="text-slate-900 dark:text-white font-medium">Administration</span>
                            <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
                            <span class="text-slate-900 dark:text-white font-medium">Actualités</span>
                        </nav>

                        <!-- Page Heading -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Gestion des Actualités</h2>
                                <p class="text-slate-500 dark:text-slate-400 mt-1">Gérez et supervisez toutes les actualités de la plateforme.</p>
                            </div>
                            <Link :href="route('admin.news.create')" class="bg-primary hover:bg-blue-600 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 font-medium shadow-lg shadow-primary/20 transition-all active:scale-95">
                                <i class="fas fa-plus icon-sm"></i>
                                <span>Ajouter une actualité</span>
                            </Link>
                        </div>

                        <!-- Stats Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                            <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Actualités</p>
                                        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ news.length }}</p>
                                    </div>
                                    <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                                        <i class="fas fa-newspaper text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Actualités Actives</p>
                                        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ news.filter(n => n.is_active).length }}</p>
                                    </div>
                                    <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                                        <i class="fas fa-eye text-green-600 dark:text-green-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Actualités Inactives</p>
                                        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ news.filter(n => !n.is_active).length }}</p>
                                    </div>
                                    <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-lg">
                                        <i class="fas fa-eye-slash text-red-600 dark:text-red-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Section -->
                        <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 md:p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Filtres</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Recherche</label>
                                    <input
                                        v-model="searchQuery"
                                        @input="handleSearch"
                                        type="text"
                                        placeholder="Titre ou description..."
                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                                    />
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Statut</label>
                                    <select
                                        v-model="statusFilter"
                                        @change="handleStatusFilter"
                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                                    >
                                        <option value="">Tous</option>
                                        <option value="active">Actives</option>
                                        <option value="inactive">Inactives</option>
                                    </select>
                                </div>
                                
                                <div class="flex items-end">
                                    <button
                                        @click="clearFilters"
                                        class="w-full px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors"
                                    >
                                        Effacer les filtres
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- News List -->
                        <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                            <div class="px-4 md:px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Liste des Actualités</h3>
                            </div>
                            
                            <div class="divide-y divide-slate-200 dark:divide-slate-800">
                                <template v-for="newsItem in news" :key="newsItem.id">
                                    <div class="px-4 md:px-6 py-4">
                                        <!-- Mobile Card Layout -->
                                        <div class="flex flex-col gap-4 sm:hidden">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-slate-200 dark:bg-slate-700 rounded-lg size-10 md:size-12 flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-newspaper text-slate-600 dark:text-slate-400 text-sm md:text-base"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ newsItem.title }}</h4>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">{{ newsItem.description }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span 
                                                    :class="[
                                                        'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border',
                                                        newsItem.is_active 
                                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-200 dark:border-green-800'
                                                            : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-200 dark:border-red-800'
                                                    ]"
                                                >
                                                    <span 
                                                        :class="[
                                                            'size-1.5 rounded-full',
                                                            newsItem.is_active ? 'bg-green-500' : 'bg-red-500'
                                                        ]"
                                                    ></span>
                                                    {{ newsItem.is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                <span class="text-xs text-slate-400">Créée le {{ new Date(newsItem.created_at).toLocaleDateString('fr-FR') }}</span>
                                            </div>
                                            
                                            <div class="flex items-center justify-between">
                                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                                    Actions:
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <Link 
                                                        :href="route('admin.news.show', newsItem.id)"
                                                        class="text-slate-400 hover:text-primary dark:hover:text-primary transition-colors p-2" 
                                                        title="Voir les détails"
                                                    >
                                                        <i class="fas fa-eye icon-sm"></i>
                                                    </Link>
                                                    <Link 
                                                        :href="route('admin.news.edit', newsItem.id)"
                                                        class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-500 transition-colors p-2" 
                                                        title="Modifier"
                                                    >
                                                        <i class="fas fa-edit icon-sm"></i>
                                                    </Link>
                                                    <button 
                                                        @click="toggleNewsStatus(newsItem)"
                                                        :class="[
                                                            'transition-colors p-2',
                                                            newsItem.is_active 
                                                                ? 'text-amber-500 hover:text-amber-600 dark:hover:text-amber-400' 
                                                                : 'text-green-500 hover:text-green-600 dark:hover:text-green-400'
                                                        ]" 
                                                        :title="newsItem.is_active ? 'Désactiver' : 'Activer'"
                                                    >
                                                        <i :class="newsItem.is_active ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                                    </button>
                                                    <button 
                                                        @click="deleteNews(newsItem)"
                                                        class="text-slate-400 hover:text-red-600 dark:hover:text-red-500 transition-colors p-2" 
                                                        title="Supprimer"
                                                    >
                                                        <i class="fas fa-trash icon-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Desktop Layout -->
                                        <div class="hidden sm:flex sm:items-center sm:justify-between gap-4">
                                            <div class="flex items-center gap-4 flex-1">
                                                <div class="bg-slate-200 dark:bg-slate-700 rounded-lg size-12 flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-newspaper text-slate-600 dark:text-slate-400"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ newsItem.title }}</h4>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-1">{{ newsItem.description }}</p>
                                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                                        <span 
                                                            :class="[
                                                                'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border',
                                                                newsItem.is_active 
                                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-200 dark:border-green-800'
                                                                    : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-200 dark:border-red-800'
                                                            ]"
                                                        >
                                                            <span 
                                                                :class="[
                                                                    'size-1.5 rounded-full',
                                                                    newsItem.is_active ? 'bg-green-500' : 'bg-red-500'
                                                                ]"
                                                            ></span>
                                                            {{ newsItem.is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                        <span class="text-xs text-slate-400">Créée le {{ new Date(newsItem.created_at).toLocaleDateString('fr-FR') }}</span>
                                                        <span v-if="newsItem.image" class="text-xs text-slate-400">
                                                            <i class="fas fa-image mr-1"></i>Avec image
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <Link 
                                                    :href="route('admin.news.show', newsItem.id)"
                                                    class="text-slate-400 hover:text-primary dark:hover:text-primary transition-colors p-2" 
                                                    title="Voir les détails"
                                                >
                                                    <i class="fas fa-eye icon-sm"></i>
                                                </Link>
                                                <Link 
                                                    :href="route('admin.news.edit', newsItem.id)"
                                                    class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-500 transition-colors p-2" 
                                                    title="Modifier"
                                                >
                                                    <i class="fas fa-edit icon-sm"></i>
                                                </Link>
                                                <button 
                                                    @click="toggleNewsStatus(newsItem)"
                                                    :class="[
                                                        'transition-colors p-2',
                                                        newsItem.is_active 
                                                            ? 'text-amber-500 hover:text-amber-600 dark:hover:text-amber-400' 
                                                            : 'text-green-500 hover:text-green-600 dark:hover:text-green-400'
                                                    ]" 
                                                    :title="newsItem.is_active ? 'Désactiver' : 'Activer'"
                                                >
                                                    <i :class="newsItem.is_active ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                                </button>
                                                <button 
                                                    @click="deleteNews(newsItem)"
                                                    class="text-slate-400 hover:text-red-600 dark:hover:text-red-500 transition-colors p-2" 
                                                    title="Supprimer"
                                                >
                                                    <i class="fas fa-trash icon-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

    <style scoped>
    .icon-sm {
        font-size: 20px;
    }

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
