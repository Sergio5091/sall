<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    notifications: Array
});
</script>

<template>
  <Head title="Notifications" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <!-- Sidebar -->
    <aside class="flex w-64 flex-col bg-white dark:bg-[#19202e] border-r border-gray-200 dark:border-gray-800">
      <div class="flex h-full flex-col justify-between p-4">
        <div class="flex flex-col gap-4">
          <div class="flex items-center gap-3 p-2">
            <i class="fas fa-gamepad text-3xl text-brand-red"></i>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">GameOn</h1>
          </div>
          <div class="flex flex-col gap-1 mt-4">
            <Link href="/promoter/dashboard" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
              <i class="fas fa-tachometer-alt"></i>
              <p class="text-sm font-semibold">Dashboard</p>
            </Link>
            <Link href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
              <i class="fas fa-store"></i>
              <p class="text-sm font-medium">Salles</p>
            </Link>
            <Link href="/promoter/events" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
              <i class="fas fa-calendar-alt"></i>
              <p class="text-sm font-medium">Événements</p>
            </Link>
            <Link href="/promoter/notifications" class="flex items-center gap-3 rounded-lg bg-primary/10 px-3 py-2 text-primary dark:bg-primary/20">
              <i class="fas fa-comment"></i>
              <p class="text-sm font-medium">Messages</p>
              <span class="ml-auto text-xs font-bold bg-brand-red text-white rounded-full px-2 py-0.5">3</span>
            </Link>
            <Link href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
              <i class="fas fa-users"></i>
              <p class="text-sm font-medium">Admins</p>
            </Link>
          </div>
        </div>
        <div class="flex flex-col gap-4">
          <div class="flex flex-col gap-1 border-t border-gray-200 dark:border-gray-800 pt-4">
            <Link href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
              <i class="fas fa-cog"></i>
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
              <i class="fas fa-sign-out-alt"></i>
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
          <div>
            <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Notifications</p>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Gérez toutes vos notifications et messages</p>
          </div>
          <div class="flex items-center gap-2">
            <button class="flex items-center justify-center rounded-lg h-10 bg-white dark:bg-[#19202e] border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 gap-2 text-sm font-medium px-4 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
              <i class="fas fa-check-double"></i>
              <span class="truncate">Marquer tout comme lu</span>
            </button>
            <button class="flex items-center justify-center rounded-lg h-10 bg-brand-red text-white gap-2 text-sm font-medium px-4 hover:bg-brand-red/90 transition-colors">
              <i class="fas fa-trash"></i>
              <span class="truncate">Supprimer tout</span>
            </button>
          </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 mt-8 border-b border-gray-200 dark:border-gray-800">
          <button class="px-4 py-3 text-sm font-medium text-primary border-b-2 border-primary">
            Toutes
          </button>
          <button class="px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            Non lues
          </button>
          <button class="px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            Messages
          </button>
          <button class="px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            Réservations
          </button>
          <button class="px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            Système
          </button>
        </div>

        <!-- Notifications List -->
        <div class="mt-6 space-y-4">
          <!-- Today -->
          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Aujourd'hui</h3>
            <div class="space-y-3">
              <div class="flex gap-3 items-start p-4 rounded-lg bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 hover:shadow-md transition-shadow">
                <div class="mt-1 flex-shrink-0">
                  <div class="w-2 h-2 rounded-full bg-red-500"></div>
                </div>
                <div class="flex-1">
                  <p class="font-semibold text-sm text-gray-900 dark:text-white">Alerte Admin</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Votre salle "Pixel Palace" a été désactivée pour informations incomplètes. Veuillez mettre à jour votre profil.</p>
                  <div class="flex items-center gap-4 mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Il y a 15 minutes</p>
                    <button class="text-xs font-medium text-primary hover:underline">Voir les détails</button>
                    <button class="text-xs font-medium text-gray-600 dark:text-gray-400 hover:underline">Ignorer</button>
                  </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                  <i class="fas fa-times"></i>
                </button>
              </div>

              <div class="flex gap-3 items-start p-4 rounded-lg bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 hover:shadow-md transition-shadow">
                <div class="mt-1 flex-shrink-0">
                  <i class="fas fa-comment text-blue-500"></i>
                </div>
                <div class="flex-1">
                  <p class="font-semibold text-sm text-gray-900 dark:text-white">Nouveau message</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Alice Martin vous a envoyé un message concernant "CyberZone Arena".</p>
                  <div class="flex items-center gap-4 mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Il y a 2 heures</p>
                    <button class="text-xs font-medium text-primary hover:underline">Lire le message</button>
                    <button class="text-xs font-medium text-gray-600 dark:text-gray-400 hover:underline">Archiver</button>
                  </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Yesterday -->
          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Hier</h3>
            <div class="space-y-3">
              <div class="flex gap-3 items-start p-4 rounded-lg bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 hover:shadow-md transition-shadow opacity-75">
                <div class="mt-1 flex-shrink-0">
                  <i class="fas fa-ticket-alt text-green-500"></i>
                </div>
                <div class="flex-1">
                  <p class="font-semibold text-sm text-gray-900 dark:text-white">Réservation récente</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Nouvelle réservation pour "Tournoi Super Smash" par Bob Johnson.</p>
                  <div class="flex items-center gap-4 mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Hier</p>
                    <button class="text-xs font-medium text-primary hover:underline">Voir la réservation</button>
                    <button class="text-xs font-medium text-gray-600 dark:text-gray-400 hover:underline">Archiver</button>
                  </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                  <i class="fas fa-times"></i>
                </button>
              </div>

              <div class="flex gap-3 items-start p-4 rounded-lg bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 hover:shadow-md transition-shadow opacity-75">
                <div class="mt-1 flex-shrink-0">
                  <i class="fas fa-comment-dots text-yellow-500"></i>
                </div>
                <div class="flex-1">
                  <p class="font-semibold text-sm text-gray-900 dark:text-white">Nouveau commentaire</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Charlie Brown a commenté votre événement "Soirée Découverte VR".</p>
                  <div class="flex items-center gap-4 mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Il y a 3 jours</p>
                    <button class="text-xs font-medium text-primary hover:underline">Voir le commentaire</button>
                    <button class="text-xs font-medium text-gray-600 dark:text-gray-400 hover:underline">Archiver</button>
                  </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- This Week -->
          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Cette semaine</h3>
            <div class="space-y-3">
              <div class="flex gap-3 items-start p-4 rounded-lg bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 hover:shadow-md transition-shadow opacity-75">
                <div class="mt-1 flex-shrink-0">
                  <i class="fas fa-star text-purple-500"></i>
                </div>
                <div class="flex-1">
                  <p class="font-semibold text-sm text-gray-900 dark:text-white">Nouvel avis</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Sarah a laissé un avis 5 étoiles pour votre salle "Pixel Palace".</p>
                  <div class="flex items-center gap-4 mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Il y a 5 jours</p>
                    <button class="text-xs font-medium text-primary hover:underline">Voir l'avis</button>
                    <button class="text-xs font-medium text-gray-600 dark:text-gray-400 hover:underline">Répondre</button>
                  </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                  <i class="fas fa-times"></i>
                </button>
              </div>

              <div class="flex gap-3 items-start p-4 rounded-lg bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 hover:shadow-md transition-shadow opacity-75">
                <div class="mt-1 flex-shrink-0">
                  <i class="fas fa-chart-line text-indigo-500"></i>
                </div>
                <div class="flex-1">
                  <p class="font-semibold text-sm text-gray-900 dark:text-white">Rapport hebdomadaire</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Votre rapport de performance hebdomadaire est disponible.</p>
                  <div class="flex items-center gap-4 mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Il y a 6 jours</p>
                    <button class="text-xs font-medium text-primary hover:underline">Voir le rapport</button>
                    <button class="text-xs font-medium text-gray-600 dark:text-gray-400 hover:underline">Télécharger</button>
                  </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Load More -->
        <div class="mt-8 text-center">
          <button class="px-6 py-3 text-sm font-medium text-primary border border-primary rounded-lg hover:bg-primary/10 transition-colors">
            Charger plus de notifications
          </button>
        </div>
      </div>
    </main>
  </div>
</template>
