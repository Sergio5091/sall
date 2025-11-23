<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Inscription" />

        <div class="min-h-screen flex flex-col md:flex-row">
            <!-- Côté gauche - Formulaire -->
            <div class="w-full md:w-1/2 min-h-screen flex items-center justify-center p-8 bg-background-dark">
                <div class="w-full max-w-md">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-display font-bold text-white mb-2">Créer un compte</h1>
                        <p class="text-[#9f9db9]">Rejoignez notre communauté de joueurs passionnés</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="name" value="Nom complet" class="text-white mb-1" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="email" value="Email" class="text-white mb-1" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                required
                                autocomplete="username"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Mot de passe" class="text-white mb-1" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div>
                            <InputLabel for="password_confirmation" value="Confirmer le mot de passe" class="text-white mb-1" />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                        <!-- Sélection du rôle -->
                        <div class="mt-6">
                            <InputLabel value="Je m'inscris en tant que" class="text-white mb-3 block text-center" />
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input
                                        id="client"
                                        v-model="form.role"
                                        type="radio"
                                        value="client"
                                        class="hidden peer"
                                        required
                                    />
                                    <label
                                        for="client"
                                        class="flex flex-col items-center justify-center p-4 h-full border-2 border-gray-700 rounded-lg cursor-pointer transition-all duration-200 peer-checked:border-accent-cyan peer-checked:bg-accent-cyan/10 hover:bg-gray-800/50"
                                    >
                                        <div class="text-3xl mb-2">👤</div>
                                        <div class="font-medium text-white">Client</div>
                                        <p class="text-sm text-gray-400 text-center mt-1">Je veux réserver des salles</p>
                                    </label>
                                </div>
                                <div>
                                    <input
                                        id="promoteur"
                                        v-model="form.role"
                                        type="radio"
                                        value="promoteur"
                                        class="hidden peer"
                                    />
                                    <label
                                        for="promoteur"
                                        class="flex flex-col items-center justify-center p-4 h-full border-2 border-gray-700 rounded-lg cursor-pointer transition-all duration-200 peer-checked:border-accent-cyan peer-checked:bg-accent-cyan/10 hover:bg-gray-800/50"
                                    >
                                        <div class="text-3xl mb-2">🏢</div>
                                        <div class="font-medium text-white">Promoteur</div>
                                        <p class="text-sm text-gray-400 text-center mt-1">Je veux proposer des salles</p>
                                    </label>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors.role" />
                        </div>

                        <div class="pt-2">
                            <PrimaryButton
                                class="w-full justify-center bg-accent-magenta hover:bg-accent-magenta/90 focus:ring-2 focus:ring-accent-magenta/50 focus:ring-offset-2 focus:ring-offset-background-dark"
                                :class="{ 'opacity-70': form.processing }"
                                :disabled="form.processing"
                            >
                                <span v-if="!form.processing">Créer mon compte</span>
                                <span v-else class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Création en cours...
                                </span>
                            </PrimaryButton>
                        </div>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-[#9f9db9]">
                            Vous avez déjà un compte ?
                            <Link :href="route('login')" class="font-medium text-accent-cyan hover:text-accent-cyan/80">
                                Se connecter
                            </Link>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Côté droit - Illustration -->
            <div class="hidden md:flex md:w-1/2 min-h-screen bg-gradient-to-br from-accent-magenta/20 to-accent-cyan/20 items-center justify-center p-12">
                <div class="max-w-md text-center">
                    <div class="bg-[#1c1c27] p-8 rounded-2xl shadow-xl">
                        <div class="text-white text-4xl mb-4">🎮</div>
                        <h2 class="text-2xl font-display font-bold text-white mb-2">Rejoignez l'aventure</h2>
                        <p class="text-[#9f9db9]">Créez votre compte pour réserver des salles, participer à des tournois et profiter d'offres exclusives.</p>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
