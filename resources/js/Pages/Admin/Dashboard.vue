<script setup>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { computed } from 'vue';

defineOptions({ layout: AdminLayout });

const props = defineProps([
    'stats',
    'inscriptionsEvolution', 
    'sallesRepartition',
    'recentActivity'
]);

// Calculer la hauteur maximale pour le graphique
const maxCount = computed(() => {
    if (!props.inscriptionsEvolution || props.inscriptionsEvolution.length === 0) return 1;
    return Math.max(...props.inscriptionsEvolution.map(i => i.count), 1);
});

// Calculer la hauteur pour chaque barre
const getBarHeight = (count) => {
    return Math.max((count / maxCount.value) * 80, 5) + '%';
};

// Calculer les pourcentages pour la répartition des salles
const sallesPercentages = computed(() => {
    const total = props.sallesRepartition?.total || 0;
    if (total === 0) {
        return {
            actives: 0,
            en_attente: 0,
            desactivees: 0
        };
    }
    
    return {
        actives: Math.round((props.sallesRepartition?.actives || 0) / total * 100),
        en_attente: Math.round((props.sallesRepartition?.en_attente || 0) / total * 100),
        desactivees: Math.round((props.sallesRepartition?.desactivees || 0) / total * 100)
    };
});

// Générer le style pour le donut chart
const donutChartStyle = computed(() => {
    const total = props.sallesRepartition?.total || 0;
    if (total === 0) {
        return 'background: conic-gradient(#e5e7eb 0% 100%);';
    }
    
    const activesPercent = (props.sallesRepartition?.actives || 0) / total * 100;
    const enAttentePercent = ((props.sallesRepartition?.actives || 0) + (props.sallesRepartition?.en_attente || 0)) / total * 100;
    
    return `background: conic-gradient(
        #10b981 0% ${activesPercent}%, 
        #f97316 ${activesPercent}% ${enAttentePercent}%, 
        #ef4444 ${enAttentePercent}% 100%
    );`;
});

// Debug logging
console.log('Admin Dashboard component loaded');
console.log('Stats:', props.stats);
console.log('Auth user:', window.auth?.user);
</script>

<template>
  <Head title="Tableau de bord Admin" />
  
  <div class="max-w-7xl mx-auto space-y-8">
    <!-- Section A: KPI Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Card 1: Salles Totales -->
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:shadow-md transition-all">
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Salles Totales</p>
          <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ props.stats?.total_salles || 0 }}</h3>
        </div>
        <div class="w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
          <i class="fas fa-store"></i>
        </div>
      </div>
      
      <!-- Card 2: Salles Actives -->
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:shadow-md transition-all">
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Salles Actives</p>
          <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ props.stats?.salles_actives || 0 }}</h3>
        </div>
        <div class="w-12 h-12 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 flex items-center justify-center">
          <i class="fas fa-check-circle"></i>
        </div>
      </div>
      
      <!-- Card 3: En Attente -->
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:shadow-md transition-all relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-orange-500"></div>
        <div class="pl-2">
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">En Attente</p>
          <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ props.stats?.salles_en_attente || 0 }}</h3>
        </div>
        <div class="w-12 h-12 rounded-lg bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 flex items-center justify-center">
          <i class="fas fa-hourglass-half"></i>
        </div>
      </div>
      
      <!-- Card 4: Clients Total -->
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:shadow-md transition-all">
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Clients Total</p>
          <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ props.stats?.total_users || 0 }}</h3>
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
          
          <!-- Dynamic Bars based on real data -->
          <div 
            v-for="(item, index) in props.inscriptionsEvolution || []" 
            :key="index"
            class="w-full bg-primary/10 rounded-t-sm hover:bg-primary/20 transition-all relative group cursor-pointer" 
            :style="{ height: getBarHeight(item.count) }"
            :title="item.date"
          >
            <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded">{{ item.count }}</div>
          </div>
        </div>
        
        <div class="flex justify-between text-xs text-slate-400 mt-2 px-1">
          <span v-for="(item, index) in props.inscriptionsEvolution || []" :key="index">{{ item.date }}</span>
        </div>
      </div>
      
      <!-- Chart 2: Donut Chart Area -->
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-6">Répartition des Salles</h3>
        <div class="flex items-center justify-center flex-1">
          <div 
            class="relative w-48 h-48 rounded-full" 
            :style="donutChartStyle"
          >
            <div class="absolute inset-4 bg-white dark:bg-slate-850 rounded-full flex items-center justify-center">
              <div class="text-center">
                <span class="block text-3xl font-bold text-slate-800 dark:text-white">{{ props.sallesRepartition?.total || 0 }}</span>
                <span class="text-xs text-slate-500 uppercase tracking-wide">Total</span>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-6 flex justify-center gap-6">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-green-500"></span>
            <span class="text-sm text-slate-600 dark:text-slate-400">Actives ({{ sallesPercentages.actives }}%)</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-orange-500"></span>
            <span class="text-sm text-slate-600 dark:text-slate-400">Attente ({{ sallesPercentages.en_attente }}%)</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-500"></span>
            <span class="text-sm text-slate-600 dark:text-slate-400">Désactivées ({{ sallesPercentages.desactivees }}%)</span>
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
        <!-- Dynamic Activity Items -->
        <div 
          v-for="(activity, index) in props.recentActivity || []" 
          :key="index"
          class="flex items-start gap-4 p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-50 dark:border-slate-800/50"
          :class="{ 'border-b-0': index === (props.recentActivity?.length || 0) - 1 }"
        >
          <div 
            class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
            :class="{
              'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400': activity.color === 'green',
              'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400': activity.color === 'blue',
              'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400': activity.color === 'orange',
              'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400': activity.color === 'purple'
            }"
          >
            <i :class="activity.icon"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center justify-between mb-1">
              <p class="text-sm font-medium text-slate-800 dark:text-white">{{ activity.title }}</p>
              <span class="text-xs text-slate-400">{{ activity.time }}</span>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ activity.description }}</p>
          </div>
        </div>
        
        <!-- Empty state if no activity -->
        <div v-if="props.recentActivity?.length === 0" class="p-8 text-center">
          <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
            <i class="fas fa-inbox text-slate-400 text-xl"></i>
          </div>
          <p class="text-slate-500 dark:text-slate-400">Aucune activité récente</p>
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