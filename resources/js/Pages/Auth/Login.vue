<template>
    <div class="min-h-screen flex flex-col md:flex-row bg-gray-50 dark:bg-gray-900">
        <!-- Côté gauche - Illustration -->
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-purple-900 to-blue-800 items-center justify-center p-10 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1550745165-9bc0b252726f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80')] bg-cover bg-center opacity-10"></div>
            <div class="w-full max-w-md text-center relative z-10">
                <div class="bg-black/40 backdrop-blur-sm p-8 rounded-2xl shadow-xl">
                    <div class="text-5xl mb-6">🎮</div>
                    <h1 class="text-3xl font-bold text-white leading-tight mb-4">Votre univers de jeu connecté</h1>
                    <p class="text-gray-200">Rejoignez une communauté de passionnés et découvrez les meilleures salles de jeux près de chez vous.</p>
                </div>
            </div>
        </div>

        <!-- Côté droit - Formulaire -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white dark:bg-gray-800">
            <div class="w-full max-w-md space-y-6">
                <!-- En-tête -->
                <div class="text-center md:text-left">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">Heureux de vous revoir !</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Connectez-vous pour rejoindre votre univers de jeu.</p>
                </div>

                <div v-if="status" class="mb-6 p-4 bg-green-500/10 text-green-500 rounded-lg text-sm">
                    {{ status }}
                </div>

                <!-- Boutons de connexion sociale -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <button type="button" class="flex w-full items-center justify-center gap-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_17_80)"><path d="M47.532 24.5528C47.532 22.9214 47.3997 21.2811 47.1175 19.6761H24.253v9.0909H37.3117C36.666 32.0433 34.3245 35.8237 30.6034 38.2206V44.22H39.2217C44.4913 39.3731 47.532 32.5458 47.532 24.5528Z" fill="#4285F4"></path><path d="M24.253 48.0001C30.9409 48.0001 36.5617 45.8392 40.2828 42.2207L32.6034 37.2206C30.3619 38.7103 27.5398 39.5668 24.253 39.5668C18.2522 39.5668 13.064 35.5337 11.2084 30.13H3.43262V36.2781C7.14486 43.3444 15.137 48.0001 24.253 48.0001Z" fill="#34A853"></path><path d="M11.2084 29.13C10.7493 27.709 10.4916 26.1952 10.4916 24.64C10.4916 23.0848 10.7493 21.571 11.2084 20.15H3.43262C1.84438 23.239 1.84438 26.8321 3.43262 29.92H11.2084V29.13Z" fill="#FBBC04"></path><path d="M24.253 9.43321C27.8821 9.43321 31.1118 10.6893 33.7259 13.204L40.4854 6.51614C36.5528 2.94913 30.9409 0.880005 24.253 0.880005C15.137 0.880005 7.14486 5.53572 3.43262 12.602L11.2084 18.15C13.064 13.4663 18.2522 9.43321 24.253 9.43321Z" fill="#EA4335"></path></g><defs><clipPath id="clip0_17_80"><rect fill="white" height="48" width="48"></rect></clipPath></defs>
                        </svg>
                        Continuer avec Google
                    </button>
                    <button type="button" class="flex w-full items-center justify-center gap-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="h-5 w-5 text-gray-800 dark:text-white" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M14.227 21.522C13.54 21.82 13.1 22.518 13.137 23.292C13.175 24.065 13.78 24.631 14.512 24.631C15.244 24.631 16.53 24.25 17.752 23.477C18.974 22.704 20.375 21.503 21.416 19.883C21.892 19.146 22.25 18.337 22.486 17.458C21.05 16.507 19.9 14.86 19.9 12.87C19.9 10.704 21.286 8.995 23.102 8.121C22.915 7.206 22.564 6.328 22.062 5.523C20.407 2.949 17.844 1.258 15.014 1.157C12.086 1.041 9.438 2.905 8.169 5.51C6.444 6.757 5.234 8.931 5.234 11.439C5.234 14.587 7.022 16.942 9.387 18.15C9.423 18.166 9.46 18.181 9.496 18.196C8.52 21.164 9.172 24.615 9.273 24.819C9.362 25.023 9.187 25.211 8.96 25.195C6.017 24.974 3.785 23.239 2.296 20.916C1.936 20.363 1.218 20.198 0.665 20.558C0.111 20.918 0.019 21.636 0.307 22.189C2.123 25.174 4.918 27.11 8.239 27.38C11.56 27.65 14.474 25.952 15.936 23.199C15.53 22.613 14.914 21.222 14.227 21.522Z"></path></svg>
                        Continuer avec Apple
                    </button>
                </div>

                <!-- Séparateur -->
                <div class="relative flex items-center py-2">
                    <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    <span class="flex-shrink mx-4 text-sm text-gray-500 dark:text-gray-400">ou connectez-vous avec</span>
                    <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                </div>

                <!-- Formulaire de connexion -->
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Email -->
                    <div>
                        <InputLabel for="email" value="Adresse e-mail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" />
                        <div class="relative">
                            <span class="material-symbols-outlined pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">mail</span>
                            <TextInput
                                id="email"
                                type="email"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-700/50 py-3 pl-10 pr-3 transition-colors duration-200"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="email@example.com"
                            />
                        </div>
                        <InputError class="mt-1 text-sm text-red-600 dark:text-red-400" :message="form.errors.email" />
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <InputLabel for="password" value="Mot de passe" class="block text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <Link :href="route('password.request')" class="text-sm font-medium text-purple-600 hover:text-purple-500 dark:text-purple-400 dark:hover:text-purple-300 transition-colors duration-200">Mot de passe oublié ?</Link>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">lock</span>
                            <TextInput
                                id="password"
                                type="password"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-700/50 py-3 pl-10 pr-10 transition-colors duration-200"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                            />
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-purple-500 dark:text-gray-400 dark:hover:text-purple-300 transition-colors duration-200">
                                <span class="material-symbols-outlined text-lg">visibility</span>
                            </button>
                        </div>
                        <InputError class="mt-1 text-sm text-red-600 dark:text-red-400" :message="form.errors.password" />
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="flex items-center">
                        <Checkbox 
                            id="remember"
                            name="remember" 
                            v-model:checked="form.remember"
                            class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
                        />
                        <InputLabel for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Se souvenir de moi
                        </InputLabel>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="pt-2">
                        <PrimaryButton 
                            class="w-full justify-center bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white py-3 px-4 rounded-lg font-medium shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200" 
                            :class="{ 'opacity-70 cursor-not-allowed': form.processing }" 
                            :disabled="form.processing"
                        >
                            <span v-if="!form.processing" class="flex items-center justify-center">
                                <span class="mr-2">Se connecter</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span v-else class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Connexion en cours...
                            </span>
                        </PrimaryButton>
                    </div>
                </form>

                <!-- Lien vers l'inscription -->
                <div class="text-center pt-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Pas encore de compte ?
                        <Link :href="route('register')" class="font-medium text-purple-600 hover:text-purple-500 dark:text-purple-400 dark:hover:text-purple-300 transition-colors duration-200 ml-1">S'inscrire</Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>