<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Add Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- TopNavBar -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center backdrop-blur-sm shadow-sm bg-white/90">
      <div class="flex items-center justify-between w-full max-w-7xl px-6 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-2 text-gray-800">
            <i class="fas fa-gamepad text-3xl text-gray-400"></i>
            <h2 class="text-gray-800 text-2xl font-display font-bold">GameOn</h2>
          </div>
          <nav class="hidden md:flex space-x-8">
            <a href="/" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Accueil</a>
            <a href="/search/rooms" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Salles</a>
            <a href="/events" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Événements</a>
          </nav>
        </div>
        <div class="flex items-center gap-3">
          <a href="/login" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-200 text-gray-800 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-300 transition-colors">
            <span class="truncate">Connexion</span>
          </a>
          <a href="/register" class="flex min-w-[84px] items-center justify-center overflow-hidden rounded-full h-11 px-5 bg-gray-800 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-700 transition-all">
            <span class="truncate">S'inscrire</span>
          </a>
        </div>
      </div>
    </header>
    <div class="flex flex-col md:flex-row min-h-screen pt-16">
      <!-- Visual Panel (Left) -->
      <div class="hidden md:flex md:w-1/2 lg:w-2/5 bg-gradient-to-br from-blue-500 to-blue-600 items-center justify-center p-10">
        <div class="w-full max-w-md text-center relative z-10 animate-slide-in-left">
          <img 
            alt="Personnes jouant à des jeux vidéo dans une salle de jeu éclairée au néon" 
            class="rounded-lg shadow-2xl mb-8 w-full h-auto object-cover" 
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDvH2WTqOH4xdpVYNnE-AEFRRddj-EuRD0tnArJLb7oiAWvIp-Qtmwd-tR44tBYuIL9XkGjTu6hrz1fbv0sRpr-kX6GHHvDlqmEfeZBWWIDPjIxx5XICSthpyGGo6HnkD-KQJEARGM2QWfZFHPAL0LB_PlSt4ECuOTAZyeJGu2nfHkeKY5of2ezUggCSp6YWPhzFl6HDAxuJtpjpCG-DeCuElEFOfHRxYJ87yGW0NGnQggcGusH7isgz2z6G84Me6uB4dNF5ZG6uEKf"
          />
          <h1 class="text-3xl font-bold text-white leading-tight">Votre univers de jeu connecté</h1>
          <p class="mt-4 text-lg text-gray-300">Rejoignez une communauté de passionnés et découvrez les meilleures salles de jeux près de chez vous.</p>
        </div>
      </div>
      
      <!-- Form Panel (Right) -->
      <div class="w-full md:w-1/2 lg:w-3/5 flex items-center justify-center p-6 sm:p-12 bg-white">
        <div class="w-full max-w-md space-y-6 animate-fade-in-up delay-200">
          <!-- Header -->
          <div class="text-center md:text-left">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 tracking-tight">Heureux de vous revoir !</h2>
            <p class="mt-2 text-gray-600">Connectez-vous pour rejoindre votre univers de jeu.</p>
          </div>

          <!-- Social Login Buttons -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <button type="button" class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
              </svg>
              <span>Google</span>
            </button>
            <button type="button" class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" />
                <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
              </svg>
              <span>Apple</span>
            </button>
          </div>

          <!-- Divider -->
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-white text-gray-600">ou connectez-vous avec</span>
            </div>
          </div>

          <!-- Status Message -->
          <div v-if="status" class="mb-4 p-3 bg-green-500/10 text-green-500 text-sm rounded-lg">
            {{ status }}
          </div>

          <!-- Login Form -->
          <form @submit.prevent="submit" class="space-y-4">
            <!-- Email Field -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse e-mail</label>
              <div class="relative">
                <i class="mt-[17px] fas fa-envelope pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"></i>
                <TextInput
                  id="email"
                  type="email"
                  class="block w-full rounded-lg border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 py-3 pl-10 pr-3"
                  v-model="form.email"
                  required
                  autofocus
                  autocomplete="email"
                  placeholder="email@example.com"
                />
              </div>
              <InputError class="mt-1 text-sm text-red-500" :message="form.errors.email" />
            </div>

            <!-- Password Field -->
            <div>
              <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                <Link :href="route('password.request')" class="text-sm font-medium text-blue-600 hover:text-blue-500">Mot de passe oublié ?</Link>
              </div>
              <div class="relative">
                <i class="mt-[17px] fas fa-lock pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"></i>
                <TextInput
                  id="password"
                  :type="showPassword ? 'text' : 'password'"
                  class="block w-full rounded-lg border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 py-3 pl-10 pr-10"
                  v-model="form.password"
                  required
                  autocomplete="current-password"
                  placeholder="••••••••"
                />
                <button 
                  type="button" 
                  class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-500"
                  @click="showPassword = !showPassword"
                >
                  <i class="fas text-lg" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
              </div>
              <InputError class="mt-1 text-sm text-red-500" :message="form.errors.password" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
              <Checkbox 
                id="remember"
                name="remember" 
                v-model:checked="form.remember"
                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500/50"
              />
              <label for="remember" class="ml-2 block text-sm text-gray-700">
                Se souvenir de moi
              </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
              <button 
                type="submit"
                class="group relative flex w-full justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-70 disabled:cursor-not-allowed"
                :disabled="form.processing"
              >
                <span v-if="!form.processing" class="flex items-center">
                  <span>Se connecter</span>
                </span>
                <span v-else class="flex items-center">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Connexion...
                </span>
              </button>
            </div>
          </form>

          <!-- Sign up Link -->
          <p class="text-center text-sm text-gray-600">
            Pas encore de compte ?
            <Link :href="route('register')" class="font-medium text-blue-600 hover:text-blue-500 ml-1">
              S'inscrire
            </Link>
          </p>
        </div>
      </div>
    </div>
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

<script setup>
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainNavbar from '@/Components/MainNavbar.vue';

defineProps({
  status: String,
});

const showPassword = ref(false);

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