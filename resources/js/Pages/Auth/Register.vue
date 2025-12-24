<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import LightNavbar from '@/Components/LightNavbar.vue';
import MainFooter from '@/Components/MainFooter.vue';

const props = defineProps({
    availableRoles: Object,
    ref: String,
    referrer: Object,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '', // Vide pour forcer la sélection
    ref: props.ref || '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-white text-soft-black font-display antialiased selection:bg-primary/20 selection:text-primary">
        <!-- Navigation -->
        <LightNavbar />
        
        <!-- Add Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <div class="relative flex min-h-screen w-full flex-col lg:flex-row pt-20">
            <!-- Côté gauche - Formulaire -->
            <div class="flex flex-1 items-center justify-center p-4 lg:p-8">
                <div class="w-full max-w-md space-y-8 animate-fade-in-up">
                    <header>
                        <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-soft-black">Créer un compte</h1>
                        <p class="mt-2 text-medium-grey">Rejoignez notre communauté de joueurs passionnés</p>
                    </header>

                    <div v-if="form.ref" class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                        Vous vous inscrivez via le lien de parrainage de <span class="font-semibold">{{ props.referrer?.name || 'un utilisateur' }}</span>.
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Nom complet -->
                        <div>
                            <label class="text-sm font-medium text-soft-black" for="name">
                                Nom complet<span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input
                                    id="name"
                                    type="text"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg bg-white border border-gray-300 text-soft-black placeholder:text-gray-500 focus:border-blue-500 focus:ring-blue-500 h-12 px-4 text-base transition-colors"
                                    placeholder="John Doe"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />
                                <InputError class="mt-1" :message="form.errors.name" />
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="text-sm font-medium text-soft-black" for="email">
                                Email<span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input
                                    id="email"
                                    type="email"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg bg-white border border-gray-300 text-soft-black placeholder:text-gray-500 focus:border-blue-500 focus:ring-blue-500 h-12 px-4 text-base transition-colors"
                                    placeholder="vous@exemple.com"
                                    v-model="form.email"
                                    required
                                    autocomplete="username"
                                />
                                <InputError class="mt-1" :message="form.errors.email" />
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div>
                            <label class="text-sm font-medium text-soft-black" for="password">
                                Mot de passe<span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input
                                    id="password"
                                    type="password"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg bg-white border border-gray-300 text-soft-black placeholder:text-gray-500 focus:border-blue-500 focus:ring-blue-500 h-12 px-4 text-base transition-colors"
                                    placeholder="••••••••"
                                    v-model="form.password"
                                    required
                                    autocomplete="new-password"
                                />
                                <InputError class="mt-1" :message="form.errors.password" />
                            </div>
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div>
                            <label class="text-sm font-medium text-soft-black" for="password_confirmation">
                                Confirmer le mot de passe<span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg bg-white border border-gray-300 text-soft-black placeholder:text-gray-500 focus:border-blue-500 focus:ring-blue-500 h-12 px-4 text-base transition-colors"
                                    placeholder="••••••••"
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="new-password"
                                />
                                <InputError class="mt-1" :message="form.errors.password_confirmation" />
                            </div>
                        </div>

                        <!-- Sélection du rôle -->
                        <div class="space-y-4 pt-2">
                            <h3 class="text-center text-soft-black">Je m'inscris en tant que</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="group relative cursor-pointer">
                                    <input 
                                        id="client" 
                                        v-model="form.role"
                                        name="role" 
                                        type="radio" 
                                        value="client" 
                                        class="sr-only" 
                                        required
                                    />
                                    <label 
                                        for="client"
                                        :class="[
                                            'flex flex-col items-center justify-center p-4 border-2 rounded-lg h-full transition-all',
                                            form.role === 'client' 
                                                ? 'border-blue-500 bg-blue-50' 
                                                : 'border-gray-300 group-hover:border-blue-500 group-hover:bg-blue-50'
                                        ]"
                                    >
                                        <i class="fas fa-user text-4xl text-blue-500"></i>
                                        <span class="mt-2 font-semibold text-soft-black">Client</span>
                                        <span class="text-sm text-medium-grey text-center">Je veux réserver des salles</span>
                                    </label>
                                </div>
                                <div class="group relative cursor-pointer">
                                    <input 
                                        id="promoteur" 
                                        v-model="form.role"
                                        name="role" 
                                        type="radio" 
                                        value="promoter" 
                                        class="sr-only"
                                    />
                                    <label 
                                        for="promoteur"
                                        :class="[
                                            'flex flex-col items-center justify-center p-4 border-2 rounded-lg h-full transition-all',
                                            form.role === 'promoter' 
                                                ? 'border-blue-500 bg-blue-50' 
                                                : 'border-gray-300 group-hover:border-blue-500 group-hover:bg-blue-50'
                                        ]"
                                    >
                                        <i class="fas fa-store text-4xl text-blue-500"></i>
                                        <span class="mt-2 font-semibold text-soft-black">Promoteur</span>
                                        <span class="text-sm text-medium-grey text-center">Je veux proposer des salles</span>
                                    </label>
                                </div>
                            </div>
                            <InputError class="mt-1" :message="form.errors.role" />
                        </div>

                        <!-- Bouton de soumission -->
                        <div>
                            <button 
                                type="submit" 
                                class="flex w-full min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-12 px-6 bg-gradient-to-r from-blue-600 to-blue-500 text-white text-base font-bold leading-normal tracking-[0.015em] hover:from-blue-500 hover:to-blue-400 transition-all duration-300 disabled:opacity-50 disabled:cursor-wait disabled:transform-none"
                                :disabled="form.processing"
                                :class="{ 'opacity-70': form.processing }"
                            >
                                <span v-if="!form.processing">Créer mon compte</span>
                                <span v-else class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Création en cours...
                                </span>
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p class="text-sm text-medium-grey">
                            Déjà inscrit ?
                            <Link :href="route('login')" class="font-medium text-blue-600 hover:underline transition-colors">
                                Se connecter
                            </Link>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Côté droit - Illustration -->
            <div class="relative hidden lg:flex flex-1 items-center justify-center bg-gradient-to-br from-blue-100 to-gray-100">
                <div class="absolute inset-0 bg-cover bg-center opacity-20" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAfbf3N5fG_NjsjDLj2jYFOZI5GNmFUBz_GvuMpIESqw322JnueC1sAIWWxsXpcQZS6F0JFQI-HR7uMDhMm66bUrs0kNIOgOoWaK_YRW-3vsSlCVnZqgk8ypfIR4mYJsjiyCooww0c2ZWYwo8mBOcTyABCp8Zt8JpVTDI4ru4KayQjbfWGXUPHHAaFxsTSkyMclyJUC2IOmB89QgYlQ-YUENWzuwrUT_6t5z5lRe5LiSH1-axqWAvVZiU0IHWJSjrvZdmvs0AJ-qs8g')"></div>
                <div class="relative z-10 max-w-md text-center p-8 space-y-4 animate-slide-in-left delay-200">
                    <h2 class="text-4xl font-bold text-gray-800">Rejoignez l'aventure</h2>
                    <p class="text-gray-600">
                        Entrez dans un monde de compétition et de fun. Connectez-vous avec d'autres joueurs, découvrez de nouveaux lieux et vivez votre passion pour le jeu.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <MainFooter />
    </div>
</template>

<style scoped>
/* Page Animations */
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slide-in-left {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s ease-out forwards;
  opacity: 0;
}

.animate-fade-in {
  animation: fade-in 0.6s ease-out forwards;
  opacity: 0;
}

.animate-slide-in-left {
  animation: slide-in-left 0.8s ease-out forwards;
  opacity: 0;
}

.delay-200 { animation-delay: 0.2s; }
.delay-400 { animation-delay: 0.4s; }
.delay-600 { animation-delay: 0.6s; }
</style>
