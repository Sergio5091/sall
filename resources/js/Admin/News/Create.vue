<script setup>
import { Head } from '@inertiajs/vue3';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const form = useForm({
    title: '',
    description: '',
    image: null,
    is_active: true
});

const submit = () => {
    form.post(route('admin.news.store'));
};

const handleFileChange = (event) => {
    form.image = event.target.files[0];
};
</script>

<template>
    <Head title="Créer une actualité" />
  
    <div class="flex flex-col gap-6">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
            <Link :href="route('welcome')" class="hover:text-primary transition-colors">Accueil</Link>
            <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
            <span class="text-slate-900 dark:text-white font-medium">Administration</span>
            <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
            <Link :href="route('admin.news.index')" class="hover:text-primary transition-colors">Actualités</Link>
            <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
            <span class="text-slate-900 dark:text-white font-medium">Créer</span>
        </nav>

        <!-- Page Heading -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Créer une Actualité</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Ajoutez une nouvelle actualité à la plateforme.</p>
            </div>
            <Link :href="route('admin.news.index')" class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-4 py-2.5 rounded-lg flex items-center gap-2 font-medium transition-colors">
                <i class="fas fa-arrow-left icon-sm"></i>
                <span>Retour aux actualités</span>
            </Link>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="p-4 md:p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Titre *
                        </label>
                        <input
                            type="text"
                            id="title"
                            v-model="form.title"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                            :class="{ 'border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500': form.errors.title }"
                            placeholder="Entrez le titre de l'actualité"
                            required
                        >
                        <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Description *
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                            :class="{ 'border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500': form.errors.description }"
                            placeholder="Entrez la description de l'actualité"
                            required
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Image
                        </label>
                        <div class="mt-1 flex items-center space-x-4">
                            <input
                                type="file"
                                id="image"
                                @change="handleFileChange"
                                accept="image/*"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                :class="{ 'border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500': form.errors.image }"
                            >
                        </div>
                        <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">
                            {{ form.errors.image }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            Formats acceptés: JPEG, PNG, JPG, GIF. Taille max: 2MB.
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="is_active"
                                v-model="form.is_active"
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            >
                            <label for="is_active" class="ml-2 block text-sm text-slate-900 dark:text-slate-300">
                                Publier cette actualité (rendre visible sur la page d'accueil)
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200 dark:border-slate-800">
                        <Link
                            :href="route('admin.news.index')"
                            class="px-4 py-2 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Annuler
                        </Link>
                        <button
                            type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Création...' : 'Créer l\'actualité' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.icon-sm {
    font-size: 20px;
}
</style>
