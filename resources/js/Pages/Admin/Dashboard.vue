<script setup>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });
</script>

<template>
  <Head title="Tableau de bord Admin" />
  
  <div class="max-w-7xl mx-auto space-y-8">
    <!-- Section A: KPI Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Card 1 -->
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:shadow-md transition-all">
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Salles Totales</p>
          <h3 class="text-2xl font-bold text-slate-800 dark:text-white">1,240</h3>
        </div>
        <div class="w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
          <i class="fas fa-store"></i>
        </div>
      </div>
      
      <!-- Card 2 -->
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:shadow-md transition-all">
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Salles Actives</p>
          <h3 class="text-2xl font-bold text-slate-800 dark:text-white">980</h3>
        </div>
        <div class="w-12 h-12 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 flex items-center justify-center">
          <i class="fas fa-check-circle"></i>
        </div>
      </div>
      
      <!-- Card 3 -->
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:shadow-md transition-all relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-orange-500"></div>
        <div class="pl-2">
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">En Attente</p>
          <h3 class="text-2xl font-bold text-slate-800 dark:text-white">45</h3>
        </div>
        <div class="w-12 h-12 rounded-lg bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 flex items-center justify-center">
          <i class="fas fa-hourglass-half"></i>
        </div>
      </div>
      
      <!-- Card 4 -->
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:shadow-md transition-all">
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Clients Total</p>
          <h3 class="text-2xl font-bold text-slate-800 dark:text-white">15.2k</h3>
        </div>
        <div class="w-12 h-12 rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center">
          <i class="fas fa-users"></i>
        </div>
      </div>
    </section>

    <!-- Section B: Charts -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Chart 1: Line Chart Area -->
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-bold text-slate-800 dark:text-white">Évolution des Inscriptions</h3>
          <select class="text-xs border-slate-200 dark:border-slate-700 rounded-lg bg-transparent text-slate-500 dark:text-slate-400 focus:ring-0">
            <option>7 derniers jours</option>
            <option>30 derniers jours</option>
          </select>
        </div>
        
        <!-- Mock Chart Representation -->
        <div class="h-64 w-full flex items-end justify-between gap-2 px-2 pb-2 relative">
          <!-- Background Grid Lines -->
          <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
            <div class="border-t border-slate-100 dark:border-slate-800 w-full h-px"></div>
            <div class="border-t border-slate-100 dark:border-slate-800 w-full h-px"></div>
            <div class="border-t border-slate-100 dark:border-slate-800 w-full h-px"></div>
            <div class="border-t border-slate-100 dark:border-slate-800 w-full h-px"></div>
          </div>
          
          <!-- Mock Bars/Line -->
          <div class="w-full bg-primary/10 rounded-t-sm h-[40%] hover:bg-primary/20 transition-all relative group cursor-pointer" title="Lun">
            <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded">40</div>
          </div>
          <div class="w-full bg-primary/20 rounded-t-sm h-[55%] hover:bg-primary/30 transition-all relative group cursor-pointer" title="Mar">
            <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded">55</div>
          </div>
          <div class="w-full bg-primary/30 rounded-t-sm h-[45%] hover:bg-primary/40 transition-all relative group cursor-pointer" title="Mer">
            <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded">45</div>
          </div>
          <div class="w-full bg-primary/40 rounded-t-sm h-[70%] hover:bg-primary/50 transition-all relative group cursor-pointer" title="Jeu">
            <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded">70</div>
          </div>
          <div class="w-full bg-primary/60 rounded-t-sm h-[60%] hover:bg-primary/70 transition-all relative group cursor-pointer" title="Ven">
            <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded">60</div>
          </div>
          <div class="w-full bg-primary/80 rounded-t-sm h-[85%] hover:bg-primary/90 transition-all relative group cursor-pointer" title="Sam">
            <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded">85</div>
          </div>
          <div class="w-full bg-primary rounded-t-sm h-[95%] hover:bg-blue-600 transition-all relative group cursor-pointer" title="Dim">
            <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded">95</div>
          </div>
        </div>
        
        <div class="flex justify-between text-xs text-slate-400 mt-2 px-1">
          <span>Lun</span><span>Mar</span><span>Mer</span><span>Jeu</span><span>Ven</span><span>Sam</span><span>Dim</span>
        </div>
      </div>
      
      <!-- Chart 2: Donut Chart Area -->
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-6">Répartition des Salles</h3>
        <div class="flex items-center justify-center flex-1">
          <div class="relative w-48 h-48 rounded-full" style="background: conic-gradient(#10b981 0% 70%, #f97316 70% 85%, #ef4444 85% 100%);">
            <div class="absolute inset-4 bg-white dark:bg-slate-850 rounded-full flex items-center justify-center">
              <div class="text-center">
                <span class="block text-3xl font-bold text-slate-800 dark:text-white">1,240</span>
                <span class="text-xs text-slate-500 uppercase tracking-wide">Total</span>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-6 flex justify-center gap-6">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-green-500"></span>
            <span class="text-sm text-slate-600 dark:text-slate-400">Actives (70%)</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-orange-500"></span>
            <span class="text-sm text-slate-600 dark:text-slate-400">Attente (15%)</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-500"></span>
            <span class="text-sm text-slate-600 dark:text-slate-400">Désactivées (15%)</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Section C: Recent Activity -->
    <section class="bg-white dark:bg-slate-850 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">
      <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white">Activité Récente</h3>
        <button class="text-primary text-sm font-medium hover:underline">Voir tout</button>
      </div>
      <div class="p-0">
        <!-- Activity Item 1 -->
        <div class="flex items-start gap-4 p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-50 dark:border-slate-800/50">
          <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0 text-green-600 dark:text-green-400">
            <i class="fas fa-check"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center justify-between mb-1">
              <p class="text-sm font-medium text-slate-800 dark:text-white">Salle activée : <span class="font-bold">Laser Game Paris 12</span></p>
              <span class="text-xs text-slate-400">Il y a 2 min</span>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">La salle a soumis tous les documents requis et a été validée par le système.</p>
          </div>
        </div>
        
        <!-- Activity Item 2 -->
        <div class="flex items-start gap-4 p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-50 dark:border-slate-800/50">
          <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0 text-blue-600 dark:text-blue-400">
            <i class="fas fa-user-plus"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center justify-between mb-1">
              <p class="text-sm font-medium text-slate-800 dark:text-white">Nouveau promoteur : <span class="font-bold">Marc Dubois</span></p>
              <span class="text-xs text-slate-400">Il y a 15 min</span>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Compte créé via le formulaire d'inscription rapide.</p>
          </div>
        </div>
        
        <!-- Activity Item 3 -->
        <div class="flex items-start gap-4 p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-50 dark:border-slate-800/50">
          <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center shrink-0 text-orange-600 dark:text-orange-400">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center justify-between mb-1">
              <p class="text-sm font-medium text-slate-800 dark:text-white">Signalement : <span class="font-bold">VR Zone Lyon</span></p>
              <span class="text-xs text-slate-400">Il y a 1 h</span>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Un utilisateur a signalé des informations incorrectes sur les horaires.</p>
          </div>
        </div>
        
        <!-- Activity Item 4 -->
        <div class="flex items-start gap-4 p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
          <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center shrink-0 text-purple-600 dark:text-purple-400">
            <i class="fas fa-newspaper"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center justify-between mb-1">
              <p class="text-sm font-medium text-slate-800 dark:text-white">Publication modérée</p>
              <span class="text-xs text-slate-400">Il y a 2 h</span>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">L'annonce "Tournoi FIFA 24" a été approuvée par l'équipe.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <div class="text-center text-xs text-slate-400 pb-4">
      © 2023 GameNet Admin Panel. v1.2.0
    </div>
  </div>
</template>

<style>
.icon-sm {
  font-size: 20px;
}

/* Custom scrollbar for sidebar */
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 20px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #334155;
}
</style>