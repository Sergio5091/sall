<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    currentRoute: String
});

const page = usePage();
const notifications = computed(() => page.props.notifications || []);

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

      <!-- Bottom Section -->
      <div class="flex flex-col gap-4">
        <!-- Settings -->
        <div class="flex flex-col gap-1 border-t border-gray-200 dark:border-gray-800 pt-4">
          <Link href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5 transition-all duration-300 hover:translate-x-1">
            <i class="fas fa-cog transition-transform duration-300 hover:rotate-90"></i>
            <p class="text-sm font-medium">Paramètres</p>
          </Link>
        </div>
        
        <!-- Profile -->
        <div class="flex items-center gap-3 border-t border-gray-200 dark:border-gray-800 pt-4">
          <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 transition-transform duration-300 hover:scale-110" 
               data-alt="Profile picture of Jean Dupont" 
               style='background-image: url("https://picsum.photos/seed/promoter/100/100.jpg");'></div>
          <div class="flex flex-col">
            <h1 class="text-gray-900 dark:text-white text-sm font-medium leading-normal">Jean Dupont</h1>
            <p class="text-gray-500 dark:text-gray-400 text-xs font-normal leading-normal">Promoteur</p>
          </div>
          <button class="ml-auto text-gray-500 dark:text-gray-400 transition-all duration-300 hover:text-red-500 hover:rotate-12">
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </div>
      </div>
    </div>
  </aside>
</template>
