<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    auth: {
        type: Object,
        required: true
    }
});

const form = useForm({
    name: props.auth.user.name,
    email: props.auth.user.email,
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updateProfile = () => {
    form.put(route('admin.profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('current_password', 'password', 'password_confirmation');
        },
    });
};

const updatePassword = () => {
    form.put(route('admin.password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('current_password', 'password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Head title="Profil Administrateur" />
  
    <div class="flex flex-col gap-6">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
            <Link :href="route('welcome')" class="hover:text-primary transition-colors">Accueil</Link>
            <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
            <span class="text-slate-900 dark:text-white font-medium">Administration</span>
            <span class="fas fa-chevron-right icon-sm text-slate-300"></span>
            <span class="text-slate-900 dark:text-white font-medium">Profil</span>
        </nav>

        <!-- Page Heading -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Profil Administrateur</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Gérez vos informations personnelles et votre mot de passe.</p>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="p-4 md:p-6">
                <div class="flex items-center gap-6 mb-6">
                    <div class="size-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ auth.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ auth.user.name }}</h3>
                        <p class="text-slate-500 dark:text-slate-400">{{ auth.user.email }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-200 dark:border-green-800">
                                <span class="size-1.5 rounded-full bg-green-500"></span>
                                Administrateur
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Informations Personnelles</h4>
                        
                        <form @submit.prevent="updateProfile" class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Nom
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    v-model="form.name"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                                    :class="{ 'border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500': form.errors.name }"
                                    required
                                >
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    v-model="form.email"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                                    :class="{ 'border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500': form.errors.email }"
                                    required
                                >
                                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <div class="flex items-center justify-end pt-4 border-t border-slate-200 dark:border-slate-800">
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors"
                                    :disabled="form.processing"
                                >
                                    {{ form.processing ? 'Mise à jour...' : 'Mettre à jour' }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Changer le mot de passe</h4>
                        
                        <form @submit.prevent="updatePassword" class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Mot de passe actuel
                                </label>
                                <input
                                    type="password"
                                    id="current_password"
                                    v-model="form.current_password"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                                    :class="{ 'border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500': form.errors.current_password }"
                                >
                                <p v-if="form.errors.current_password" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.current_password }}
                                </p>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Nouveau mot de passe
                                </label>
                                <input
                                    type="password"
                                    id="password"
                                    v-model="form.password"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                                    :class="{ 'border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500': form.errors.password }"
                                >
                                <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.password }}
                                </p>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Confirmer le nouveau mot de passe
                                </label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                                >
                            </div>

                            <div class="flex items-center justify-end pt-4 border-t border-slate-200 dark:border-slate-800">
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors"
                                    :disabled="form.processing"
                                >
                                    {{ form.processing ? 'Mise à jour...' : 'Changer le mot de passe' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Date de création</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ new Date(auth.user.created_at).toLocaleDateString('fr-FR') }}</p>
                    </div>
                    <div class="size-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <i class="fas fa-calendar text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Dernière connexion</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ new Date(auth.user.updated_at).toLocaleDateString('fr-FR') }}</p>
                    </div>
                    <div class="size-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <i class="fas fa-clock text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Rôle</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">Admin</p>
                    </div>
                    <div class="size-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-purple-600 dark:text-purple-400"></i>
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
