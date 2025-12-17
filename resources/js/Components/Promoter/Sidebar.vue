<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    currentRoute: String
});

const page = usePage();
const notifications = computed(() => page.props.notifications || []);
const user = computed(() => page.props.auth?.user);

const menuItems = [
    {
        name: 'Dashboard',
        icon: 'fas fa-tachometer-alt',
        route: 'promoter.dashboard',
        href: '/promoter/dashboard'
    },
    {
        name: 'Ma Salle',
        icon: 'fas fa-store',
        route: 'promoter.venues',
        href: '/promoter/venues'
    },
    {
        name: 'Événements',
        icon: 'fas fa-calendar-alt',
        route: 'promoter.events',
        href: '/promoter/events'
    },
    {
        name: 'Notifications',
        icon: 'fas fa-bell',
        route: 'promoter.notifications',
        href: '/promoter/notifications',
        badge: computed(() => notifications.value.filter(n => !n.read).length)
    }
];

const isAnimating = ref(false);

const handleNavigation = () => {
    isAnimating.value = true;
    setTimeout(() => {
        isAnimating.value = false;
    }, 300);
};
</script>

<template>
  <aside class="flex w-64 flex-col bg-white dark:bg-[#19202e] border-r border-gray-200 dark:border-gray-800 transition-all duration-300">
    <div class="flex h-full flex-col justify-between p-4">
      <div class="flex flex-col gap-4">
        <!-- Logo -->
        <div class="flex items-center gap-3 p-2">
          <i class="fas fa-gamepad text-3xl text-brand-red transition-transform duration-300 hover:rotate-12"></i>
          <h1 class="text-xl font-bold text-gray-900 dark:text-white">GameOn</h1>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex flex-col gap-1 mt-4">
          <Link 
            v-for="item in menuItems" 
            :key="item.name"
            :href="item.href"
            @click="handleNavigation"
            :class="[
              'flex items-center gap-3 rounded-lg px-3 py-2 transition-all duration-300 transform',
              currentRoute === item.route 
                ? 'bg-primary/10 text-primary dark:bg-primary/20 scale-105 shadow-sm' 
                : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5 hover:translate-x-1'
            ]"
          >
            <i 
              :class="[
                item.icon,
                'transition-all duration-300',
                currentRoute === item.route ? 'animate-pulse' : ''
              ]"
            ></i>
            <p :class="currentRoute === item.route ? 'text-sm font-semibold' : 'text-sm font-medium'">
              {{ item.name }}
            </p>
            <span 
              v-if="item.badge && item.badge > 0" 
              :class="[
                'ml-auto text-xs font-bold rounded-full px-2 py-0.5 transition-all duration-300',
                currentRoute === item.route 
                  ? 'bg-primary text-white' 
                  : 'bg-brand-red text-white hover:scale-110'
              ]"
            >
              {{ item.badge }}
            </span>
          </Link>
        </nav>
      </div>

      <!-- User Profile & Logout -->
      <div class="flex flex-col gap-3 border-t border-gray-200 dark:border-gray-700 pt-4">
        <!-- User Info -->
        <div class="flex items-center gap-3 px-3 py-2">
          <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary dark:bg-primary/20">
            <i class="fas fa-user"></i>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
              {{ user?.name || 'Utilisateur' }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
              {{ user?.email || '' }}
            </p>
          </div>
        </div>
        
        <!-- Logout Button -->
        <Link 
          href="/logout"
          method="post"
          class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-300 hover:translate-x-1"
        >
          <i class="fas fa-sign-out-alt"></i>
          <span>Déconnexion</span>
        </Link>
      </div>
    </div>
  </aside>
</template>
