<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, ref, onMounted, onUnmounted } from "vue";

const props = defineProps({
    currentRoute: String,
    mainAccount: Object,
    allAccounts: Array,
    activeAccount: Object,
    isMainAccount: Boolean,
});

const page = usePage();
const notifications = computed(() => page.props.notifications || []);
const user = computed(() => page.props.auth?.user);

const isSidebarOpen = ref(false);
const isMobile = ref(false);

const checkScreenSize = () => {
    isMobile.value = window.innerWidth < 1024;
    if (!isMobile.value) {
        isSidebarOpen.value = false;
    }
};

onMounted(() => {
    checkScreenSize();
    window.addEventListener("resize", checkScreenSize);
});

onUnmounted(() => {
    window.removeEventListener("resize", checkScreenSize);
});

const menuItems = [
    {
        name: "Tableau de bord",
        icon: "fas fa-tachometer-alt",
        route: "promoter.dashboard",
        href: "/promoter/dashboard"
    },
    {
        name: "Ma Salle",
        icon: "fas fa-store",
        route: "promoter.venues",
        href: "/promoter/venues"
    },
    {
        name: "Événements",
        icon: "fas fa-calendar",
        route: "promoter.events",
        href: "/promoter/events"
    },
    {
        name: "Notifications",
        icon: "fas fa-bell",
        route: "promoter.notifications",
        href: "/promoter/notifications",
        badge: computed(() => notifications.value.filter(n => !n.read).length)
    }
];

const handleNavigation = () => {
    if (isMobile.value) {
        isSidebarOpen.value = false;
    }
};

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
  <div class="flex h-screen">
    <div class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" v-if="isSidebarOpen" @click="toggleSidebar"></div>
    
    <aside class="fixed lg:relative w-64 h-full bg-white border-r border-gray-200 flex flex-col transition-transform duration-300 z-50"
           :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
      
      <div class="p-6 border-b border-gray-200">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
            <i class="fas fa-gamepad text-white"></i>
          </div>
          <h1 class="text-xl font-bold text-gray-900">GameOn</h1>
        </div>
      </div>
      
      <nav class="flex-1 p-4">
        <div class="space-y-2">
          <Link v-for="item in menuItems" :key="item.name" :href="item.href" @click="handleNavigation"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors w-full"
                :class="{ 'bg-blue-50 text-blue-600': currentRoute === item.route }">
            <i :class="item.icon"></i>
            <span>{{ item.name }}</span>
            <span v-if="item.badge && item.badge > 0" 
                  class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
              {{ item.badge }}
            </span>
          </Link>
        </div>
      </nav>
      
      <div class="p-4 border-t border-gray-200">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
            <i class="fas fa-user text-gray-600"></i>
          </div>
          <div class="flex-1">
            <p class="text-sm font-medium text-gray-900">{{ user?.name || 'Utilisateur' }}</p>
            <p class="text-xs text-gray-500">{{ user?.email || '' }}</p>
          </div>
        </div>
        
        <form @submit.prevent="$inertia.post('/logout')">
          <button type="submit" class="flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg w-full">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
          </button>
        </form>
      </div>
    </aside>
    
    <button class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-md" @click="toggleSidebar">
      <i class="fas fa-bars text-gray-700"></i>
    </button>
  </div>
</template>
