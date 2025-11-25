<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    salle: Object,
    events: Array,
    notifications: Array
});
</script>

<template>
  <Head title="Dashboard Promoteur" />
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <!-- Sidebar -->
    <aside class="flex w-64 flex-col bg-white dark:bg-[#19202e] border-r border-gray-200 dark:border-gray-800">
      <div class="flex h-full flex-col justify-between p-4">
        <div class="flex flex-col gap-4">
          <div class="flex items-center gap-3 p-2">
            <span class="material-symbols-outlined text-brand-red text-3xl">stadia_controller</span>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">GameOn</h1>
          </div>
          <div class="flex flex-col gap-1 mt-4">
            <Link href="/promoter/dashboard" class="flex items-center gap-3 rounded-lg bg-primary/10 px-3 py-2 text-primary dark:bg-primary/20">
              <span class="material-symbols-outlined">dashboard</span>
              <p class="text-sm font-semibold">Dashboard</p>
            </Link>
            <Link href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
              <span class="material-symbols-outlined">storefront</span>
              <p class="text-sm font-medium">Salles</p>
            </Link>
            <Link href="/promoter/events" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
              <span class="material-symbols-outlined">calendar_month</span>
              <p class="text-sm font-medium">Événements</p>
            </Link>
            <Link href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
              <span class="material-symbols-outlined">chat_bubble</span>
              <p class="text-sm font-medium">Messages</p>
              <span class="ml-auto text-xs font-bold bg-brand-red text-white rounded-full px-2 py-0.5">3</span>
            </Link>
            <Link href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
              <span class="material-symbols-outlined">group</span>
              <p class="text-sm font-medium">Admins</p>
            </Link>
          </div>
        </div>
        <div class="flex flex-col gap-4">
          <div class="flex flex-col gap-1 border-t border-gray-200 dark:border-gray-800 pt-4">
            <Link href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
              <span class="material-symbols-outlined">settings</span>
              <p class="text-sm font-medium">Paramètres</p>
            </Link>
          </div>
          <div class="flex items-center gap-3 border-t border-gray-200 dark:border-gray-800 pt-4">
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Profile picture of Jean Dupont" style='background-image: url("https://picsum.photos/seed/promoter/100/100.jpg");'></div>
            <div class="flex flex-col">
              <h1 class="text-gray-900 dark:text-white text-sm font-medium leading-normal">Jean Dupont</h1>
              <p class="text-gray-500 dark:text-gray-400 text-xs font-normal leading-normal">Promoteur</p>
            </div>
            <button class="ml-auto text-gray-500 dark:text-gray-400">
              <span class="material-symbols-outlined">logout</span>
            </button>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
      <div class="p-8">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Dashboard Promoteur</p>
          <div class="flex items-center gap-2">
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">calendar_today</span>
              <select class="pl-10 pr-4 py-2 text-sm font-medium bg-white dark:bg-background-dark border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 focus:ring-brand-blue focus:border-brand-blue">
                <option>30 derniers jours</option>
                <option>7 derniers jours</option>
                <option>Ce mois-ci</option>
                <option>Cette année</option>
              </select>
            </div>
            <button class="p-2.5 text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">
              <span class="material-symbols-outlined text-base">file_upload</span>
            </button>
            <Link href="/promoter/events/create" class="flex items-center justify-center rounded-lg h-10 bg-brand-red text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] px-4 hover:bg-brand-red/90 transition-colors">
              <span class="material-symbols-outlined text-base font-bold">add</span>
              <span class="truncate">Créer un événement</span>
            </Link>
          </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mt-8">
          <div class="flex flex-col gap-2 rounded-xl p-4 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Visiteurs (page salle)</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">1,230</p>
          </div>
          <div class="flex flex-col gap-2 rounded-xl p-4 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Réservations (mois)</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">85</p>
          </div>
          <div class="flex flex-col gap-2 rounded-xl p-4 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Événements publiés</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">12</p>
          </div>
          <div class="flex flex-col gap-2 rounded-xl p-4 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Messages reçus</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">28</p>
          </div>
          <div class="flex flex-col gap-2 rounded-xl p-4 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Recommandations</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">42</p>
          </div>
          <div class="flex flex-col gap-2 rounded-xl p-4 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Taux de remplissage</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold">78%</p>
          </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
          <div class="lg:col-span-2 flex flex-col gap-6">
            <!-- Venue Info -->
            <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
              <div class="flex justify-between items-center mb-4">
                <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">Gestion des Informations de la Salle</h2>
                <button class="flex items-center justify-center rounded-lg h-9 bg-primary text-white gap-2 text-sm font-bold leading-normal px-3 hover:bg-primary/90 transition-colors">
                  <span class="material-symbols-outlined text-base">edit</span>
                  <span class="truncate">Modifier</span>
                </button>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1 flex flex-col items-center text-center gap-4">
                  <div class="relative w-40 h-40">
                    <img alt="Venue Banner" class="w-full h-full object-cover rounded-xl" src="https://picsum.photos/seed/venue/400/400.jpg">
                    <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-20 h-20 rounded-full border-4 border-white dark:border-[#19202e] bg-cover bg-center" data-alt="Venue Logo" style="background-image: url('https://picsum.photos/seed/logo/200/200.jpg');"></div>
                  </div>
                  <div class="mt-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">CyberZone Arena</h3>
                    <div class="mt-1 flex justify-center items-center gap-2">
                      <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Active</span>
                    </div>
                  </div>
                </div>
                <div class="md:col-span-2 space-y-4">
                  <div>
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Description</h4>
                    <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">La première salle d'arcade et de VR au coeur de la ville. Venez découvrir nos jeux exclusifs et participer à nos tournois hebdomadaires.</p>
                  </div>
                  <div>
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Adresse</h4>
                    <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">123 Rue du Jeu, 75001 Paris, France</p>
                  </div>
                  <div>
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Horaires</h4>
                    <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">Mar - Dim : 14h00 - 00h00</p>
                  </div>
                  <div>
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Tarifs</h4>
                    <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">À partir de 15€ / heure</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Events Management -->
            <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl">
              <div class="p-6 flex justify-between items-center">
                <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">Gestion des événements</h2>
                <div class="flex items-center gap-2">
                  <button class="p-2 text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 text-sm">À venir</button>
                  <button class="p-2 text-gray-500 dark:text-gray-400 border border-transparent rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 text-sm">En cours</button>
                  <button class="p-2 text-gray-500 dark:text-gray-400 border border-transparent rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 text-sm">Passés</button>
                </div>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                  <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-background-dark border-b border-t border-gray-200 dark:border-gray-800">
                    <tr>
                      <th class="px-6 py-3" scope="col">Événement</th>
                      <th class="px-6 py-3" scope="col">Dates</th>
                      <th class="px-6 py-3" scope="col">Participants</th>
                      <th class="px-6 py-3" scope="col">Statut</th>
                      <th class="px-6 py-3 text-right" scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                      <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                        <div class="flex items-center gap-3">
                          <div class="w-10 h-10 rounded-lg bg-cover bg-center" data-alt="Tournament thumbnail" style="background-image: url('https://picsum.photos/seed/tournament/100/100.jpg');"></div>
                          <span>Tournoi Super Smash</span>
                        </div>
                      </td>
                      <td class="px-6 py-4">25 Déc 2023</td>
                      <td class="px-6 py-4">28 / 32</td>
                      <td class="px-6 py-4"><span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Actif</span></td>
                      <td class="px-6 py-4 text-right space-x-2">
                        <button class="font-medium text-primary hover:underline">Modifier</button>
                        <button class="font-medium text-brand-red hover:underline">Supprimer</button>
                      </td>
                    </tr>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                      <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                        <div class="flex items-center gap-3">
                          <div class="w-10 h-10 rounded-lg bg-cover bg-center" data-alt="VR Discovery thumbnail" style="background-image: url('https://picsum.photos/seed/vr/100/100.jpg');"></div>
                          <span>Soirée Découverte VR</span>
                        </div>
                      </td>
                      <td class="px-6 py-4">15 Jan 2024</td>
                      <td class="px-6 py-4">12 / 20</td>
                      <td class="px-6 py-4"><span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Inactif</span></td>
                      <td class="px-6 py-4 text-right space-x-2">
                        <button class="font-medium text-primary hover:underline">Modifier</button>
                        <button class="font-medium text-brand-red hover:underline">Supprimer</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Notifications -->
          <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6 flex flex-col">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-4">Notifications</h2>
            <div class="flex flex-col gap-4 flex-1">
              <div class="flex gap-3 items-start p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5">
                <div class="mt-1 flex-shrink-0"><span class="material-symbols-outlined text-red-500">error</span></div>
                <div class="flex-1">
                  <p class="font-semibold text-sm text-gray-900 dark:text-white">Alerte Admin</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Votre salle "Pixel Palace" a été désactivée pour informations incomplètes. Veuillez mettre à jour votre profil.</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Il y a 15 minutes</p>
                </div>
              </div>
              <div class="flex gap-3 items-start p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5">
                <div class="mt-1 flex-shrink-0"><span class="material-symbols-outlined text-blue-500">chat_bubble</span></div>
                <div class="flex-1">
                  <p class="font-semibold text-sm text-gray-900 dark:text-white">Nouveau message</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Alice Martin vous a envoyé un message concernant "CyberZone Arena".</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Il y a 2 heures</p>
                </div>
              </div>
              <div class="flex gap-3 items-start p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5">
                <div class="mt-1 flex-shrink-0"><span class="material-symbols-outlined text-green-500">confirmation_ticket</span></div>
                <div class="flex-1">
                  <p class="font-semibold text-sm text-gray-900 dark:text-white">Réservation récente</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Nouvelle réservation pour "Tournoi Super Smash" par Bob Johnson.</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hier</p>
                </div>
              </div>
              <div class="flex gap-3 items-start p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5">
                <div class="mt-1 flex-shrink-0"><span class="material-symbols-outlined text-yellow-500">comment</span></div>
                <div class="flex-1">
                  <p class="font-semibold text-sm text-gray-900 dark:text-white">Nouveau commentaire</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Charlie Brown a commenté votre événement "Soirée Découverte VR".</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Il y a 3 jours</p>
                </div>
              </div>
            </div>
            <div class="mt-auto pt-4">
              <button class="w-full text-center font-medium text-brand-blue text-sm hover:underline">Voir toutes les notifications</button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
