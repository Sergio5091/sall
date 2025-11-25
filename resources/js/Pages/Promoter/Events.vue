<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    events: Array,
    filters: Object
});
</script>

<template>
  <Head title="Gestion des Événements" />
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display">
    <!-- SideNavBar -->
    <aside class="flex flex-col w-64 p-4 bg-white dark:bg-[#111318] border-r border-gray-200 dark:border-gray-800 shrink-0">
      <div class="flex flex-col gap-4">
        <div class="flex items-center gap-3">
          <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Arcade X Logo" style='background-image: url("https://picsum.photos/seed/arcade-logo/100/100.jpg");'></div>
          <div class="flex flex-col">
            <h1 class="text-gray-900 dark:text-white text-base font-medium leading-normal">Salle d'Arcade X</h1>
            <p class="text-gray-500 dark:text-[#9da6b9] text-sm font-normal leading-normal">Promoteur</p>
          </div>
        </div>
        <nav class="flex flex-col gap-2 mt-4">
          <Link href="/promoter/dashboard" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-[#282e39]">
            <span class="material-symbols-outlined">dashboard</span>
            <p class="text-sm font-medium leading-normal">Dashboard</p>
          </Link>
          <Link href="/promoter/events" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/10 dark:bg-[#282e39] text-primary dark:text-white">
            <span class="material-symbols-outlined fill">calendar_month</span>
            <p class="text-sm font-medium leading-normal">Événements</p>
          </Link>
          <Link href="#" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-[#282e39]">
            <span class="material-symbols-outlined">groups</span>
            <p class="text-sm font-medium leading-normal">Communauté</p>
          </Link>
          <Link href="#" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-[#282e39]">
            <span class="material-symbols-outlined">bar_chart</span>
            <p class="text-sm font-medium leading-normal">Analyses</p>
          </Link>
          <Link href="#" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-[#282e39]">
            <span class="material-symbols-outlined">settings</span>
            <p class="text-sm font-medium leading-normal">Paramètres</p>
          </Link>
        </nav>
      </div>
      <div class="mt-auto flex flex-col gap-4">
        <button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em]">
          <span class="truncate">Voir la page publique</span>
        </button>
        <div class="flex flex-col gap-1">
          <Link href="#" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-[#282e39]">
            <span class="material-symbols-outlined">help</span>
            <p class="text-sm font-medium leading-normal">Aide</p>
          </Link>
          <Link href="#" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-[#282e39]">
            <span class="material-symbols-outlined">logout</span>
            <p class="text-sm font-medium leading-normal">Déconnexion</p>
          </Link>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 lg:p-10">
      <div class="max-w-7xl mx-auto">
        <!-- PageHeading -->
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div class="flex flex-col gap-2">
            <h1 class="text-gray-900 dark:text-white text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em]">Gestion de vos Événements</h1>
            <p class="text-gray-500 dark:text-[#9da6b9] text-base font-normal leading-normal">Créez, modifiez et suivez tous les événements de votre salle.</p>
          </div>
          <Link href="/promoter/events/create" class="flex items-center justify-center gap-2 min-w-[84px] cursor-pointer overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em]">
            <span class="material-symbols-outlined">add_circle</span>
            <span class="truncate">Créer un nouvel événement</span>
          </Link>
        </div>

        <!-- Search and Filters -->
        <div class="mt-8 flex flex-col md:flex-row gap-4">
          <!-- SearchBar -->
          <div class="flex-1">
            <label class="flex flex-col h-12 w-full">
              <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                <div class="text-[#9da6b9] flex bg-white dark:bg-[#282e39] items-center justify-center pl-4 rounded-l-lg border border-gray-200 dark:border-gray-700 border-r-0">
                  <span class="material-symbols-outlined">search</span>
                </div>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 bg-white dark:bg-[#282e39] border border-gray-200 dark:border-gray-700 h-full placeholder:text-[#9da6b9] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal" placeholder="Rechercher un événement par nom..." value=""/>
              </div>
            </label>
          </div>
          <!-- Chips -->
          <div class="flex items-center gap-2 overflow-x-auto pb-2">
            <div class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-300 px-4 border border-primary/20 dark:border-primary/30">
              <p class="text-sm font-medium leading-normal">Tous</p>
            </div>
            <div class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white dark:bg-[#282e39] px-4 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
              <p class="text-gray-700 dark:text-white text-sm font-medium leading-normal">Publié</p>
            </div>
            <div class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white dark:bg-[#282e39] px-4 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
              <p class="text-gray-700 dark:text-white text-sm font-medium leading-normal">Brouillon</p>
            </div>
            <div class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white dark:bg-[#282e39] px-4 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
              <p class="text-gray-700 dark:text-white text-sm font-medium leading-normal">Terminé</p>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="mt-6 @container">
          <div class="flex overflow-hidden rounded-lg border border-gray-200 dark:border-[#3b4354] bg-white dark:bg-[#1c1f27]">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-[#1c1f27]">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[35%]">Nom de l'événement</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%]">Date</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%]">Statut</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%]">Inscrits</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[20%] text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-[#3b4354]">
                <tr class="hover:bg-gray-50 dark:hover:bg-[#282e39]/50">
                  <td class="h-[72px] px-4 py-2 text-gray-900 dark:text-white text-sm font-medium">Tournoi Super Smash Bros.</td>
                  <td class="h-[72px] px-4 py-2 text-gray-500 dark:text-[#9da6b9] text-sm">25 Oct. 2024</td>
                  <td class="h-[72px] px-4 py-2 text-sm">
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-green-100 dark:bg-green-500/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-300">
                      <span class="size-1.5 rounded-full bg-green-500"></span>Publié
                    </div>
                  </td>
                  <td class="h-[72px] px-4 py-2 text-sm">
                    <div class="flex items-center gap-3">
                      <p class="text-gray-900 dark:text-white text-sm font-medium">85 / 100</p>
                      <div class="w-20 overflow-hidden rounded-full bg-gray-200 dark:bg-[#3b4354]"><div class="h-1.5 rounded-full bg-primary" style="width: 85%;"></div></div>
                    </div>
                  </td>
                  <td class="h-[72px] px-4 py-2 text-right">
                    <div class="flex items-center justify-end gap-2">
                      <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-base">edit</span></button>
                      <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-base">visibility</span></button>
                      <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-base">content_copy</span></button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-[#282e39]/50">
                  <td class="h-[72px] px-4 py-2 text-gray-900 dark:text-white text-sm font-medium">Soirée Rétro Gaming</td>
                  <td class="h-[72px] px-4 py-2 text-gray-500 dark:text-[#9da6b9] text-sm">15 Nov. 2024</td>
                  <td class="h-[72px] px-4 py-2 text-sm">
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-green-100 dark:bg-green-500/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-300">
                      <span class="size-1.5 rounded-full bg-green-500"></span>Publié
                    </div>
                  </td>
                  <td class="h-[72px] px-4 py-2 text-sm">
                    <div class="flex items-center gap-3">
                      <p class="text-gray-900 dark:text-white text-sm font-medium">45 / 80</p>
                      <div class="w-20 overflow-hidden rounded-full bg-gray-200 dark:bg-[#3b4354]"><div class="h-1.5 rounded-full bg-yellow-500" style="width: 56.25%;"></div></div>
                    </div>
                  </td>
                  <td class="h-[72px] px-4 py-2 text-right">
                    <div class="flex items-center justify-end gap-2">
                      <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-base">edit</span></button>
                      <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-base">visibility</span></button>
                      <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-base">content_copy</span></button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-[#282e39]/50">
                  <td class="h-[72px] px-4 py-2 text-gray-900 dark:text-white text-sm font-medium">Atelier Création de Jeu</td>
                  <td class="h-[72px] px-4 py-2 text-gray-500 dark:text-[#9da6b9] text-sm">05 Déc. 2024</td>
                  <td class="h-[72px] px-4 py-2 text-sm">
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-yellow-100 dark:bg-yellow-500/20 px-2 py-1 text-xs font-medium text-yellow-800 dark:text-yellow-300">
                      <span class="size-1.5 rounded-full bg-yellow-500"></span>Brouillon
                    </div>
                  </td>
                  <td class="h-[72px] px-4 py-2 text-sm">
                    <div class="flex items-center gap-3">
                      <p class="text-gray-900 dark:text-white text-sm font-medium">0 / 20</p>
                      <div class="w-20 overflow-hidden rounded-full bg-gray-200 dark:bg-[#3b4354]"><div class="h-1.5 rounded-full bg-gray-400" style="width: 0%;"></div></div>
                    </div>
                  </td>
                  <td class="h-[72px] px-4 py-2 text-right">
                    <div class="flex items-center justify-end gap-2">
                      <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-base">edit</span></button>
                      <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-base">visibility</span></button>
                      <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-base">content_copy</span></button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-[#282e39]/50">
                  <td class="h-[72px] px-4 py-2 text-gray-900 dark:text-white text-sm font-medium">Compétition e-sport FIFA</td>
                  <td class="h-[72px] px-4 py-2 text-gray-500 dark:text-[#9da6b9] text-sm">30 Sept. 2024</td>
                  <td class="h-[72px] px-4 py-2 text-sm">
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 dark:bg-gray-700/50 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">
                      <span class="size-1.5 rounded-full bg-gray-400"></span>Terminé
                    </div>
                  </td>
                  <td class="h-[72px] px-4 py-2 text-sm">
                    <div class="flex items-center gap-3">
                      <p class="text-gray-900 dark:text-white text-sm font-medium">128 / 128</p>
                      <div class="w-20 overflow-hidden rounded-full bg-gray-200 dark:bg-[#3b4354]"><div class="h-1.5 rounded-full bg-green-500" style="width: 100%;"></div></div>
                    </div>
                  </td>
                  <td class="h-[72px] px-4 py-2 text-right">
                    <div class="flex items-center justify-end gap-2">
                      <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-base">edit</span></button>
                      <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-base">visibility</span></button>
                      <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-base">content_copy</span></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
