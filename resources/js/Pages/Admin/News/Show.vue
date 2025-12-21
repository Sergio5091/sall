<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

defineProps({
    news: {
        type: Object,
        required: true
    }
});

const deleteNews = (newsItem) => {
    if (confirm(`Êtes-vous sûr de vouloir supprimer l'actualité "${newsItem.title}" ?`)) {
        router.delete(route('admin.news.destroy', newsItem));
    }
};
</script>

<template>
    <Head :title="news.title" />
  
    <div class="flex flex-col gap-6">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
            <Link :href="route('welcome')" class="hover:text-primary transition-colors">Accueil</Link>
            <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
            <span class="text-slate-900 dark:text-white font-medium">Administration</span>
            <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
            <Link :href="route('admin.news.index')" class="hover:text-primary transition-colors">Actualités</Link>
            <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
            <span class="text-slate-900 dark:text-white font-medium">Détails</span>
        </nav>

        <!-- Page Heading -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Détails de l'Actualité</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Consultez les informations complètes de l'actualité.</p>
            </div>
            <Link :href="route('admin.news.index')" class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-4 py-2.5 rounded-lg flex items-center gap-2 font-medium transition-colors">
                <i class="fas fa-arrow-left icon-sm"></i>
                <span>Retour aux actualités</span>
            </Link>
        </div>

        <!-- News Details -->
        <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="p-4 md:p-6">
                <div class="space-y-6">
                    <!-- Title and Status -->
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-4">{{ news.title }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 mb-6">
                            <span 
                                :class="[
                                    'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border',
                                    news.is_active 
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-200 dark:border-green-800'
                                        : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-200 dark:border-red-800'
                                ]"
                            >
                                <span 
                                    :class="[
                                        'size-1.5 rounded-full',
                                        news.is_active ? 'bg-green-500' : 'bg-red-500'
                                    ]"
                                ></span>
                                {{ news.is_active ? 'Publiée' : 'Non publiée' }}
                            </span>
                            <span class="text-sm text-slate-500 dark:text-slate-400">
                                <i class="fas fa-calendar mr-1"></i>
                                Créée le {{ new Date(news.created_at).toLocaleDateString('fr-FR') }}
                            </span>
                            <span v-if="news.updated_at !== news.created_at" class="text-sm text-slate-500 dark:text-slate-400">
                                <i class="fas fa-edit mr-1"></i>
                                Modifiée le {{ new Date(news.updated_at).toLocaleDateString('fr-FR') }}
                            </span>
                        </div>
                    </div>

                    <!-- Image -->
                    <div v-if="news.image" class="mb-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-3">Image</h3>
                        <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4 inline-block">
                            <img 
                                :src="'/storage/' + news.image" 
                                :alt="news.title"
                                class="max-w-md h-auto rounded-lg shadow-sm"
                            >
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-3">Description</h3>
                        <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ news.description }}</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-6 border-t border-slate-200 dark:border-slate-800">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                <Link
                                    :href="route('admin.news.edit', news.id)"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 font-medium transition-colors"
                                >
                                    <i class="fas fa-edit icon-sm"></i>
                                    <span>Modifier</span>
                                </Link>
                                <button
                                    @click="deleteNews(news)"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 font-medium transition-colors"
                                >
                                    <i class="fas fa-trash icon-sm"></i>
                                    <span>Supprimer</span>
                                </button>
                            </div>
                            <Link
                                :href="route('admin.news.index')"
                                class="px-4 py-2 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-600 dark:hover:bg-slate-700"
                            >
                                Retour à la liste
                            </Link>
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
</style>
