<script setup>
import { Link, usePage, router } from "@inertiajs/vue3";
import { computed, ref, onMounted, onUnmounted, watch } from "vue";

const props = defineProps({
    currentRoute: {
        type: String,
        default: null,
    },
    mainAccount: Object,
    allAccounts: Array,
    activeAccount: Object,
    isMainAccount: Boolean,
    isMobileOpen: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close']);

// determine current route using Ziggy if not provided
const computedCurrent = computed(() => {
    if (props.currentRoute) return props.currentRoute;
    if (typeof window !== 'undefined' && window.route) {
        try {
            return window.route().current();
        } catch {
            return '';
        }
    }
    return '';
});


const page = usePage();
const notifications = computed(() => page.props.notifications?.data || []);
const unreadCount = ref(0);
const user = computed(() => page.props.auth?.user);

// Récupérer le nombre de notifications non lues
const fetchUnreadCount = async () => {
    try {
        // Vérifier si l'utilisateur est un promoteur avant de faire la requête
        if (!user.value || user.value?.role !== 'promoter') {
            unreadCount.value = 0;
            return;
        }
        
        // Éviter les appels multiples si déjà en cours
        if (window.fetchInProgress) {
            return;
        }
        
        window.fetchInProgress = true;
        const response = await fetch('/promoter/api/unread-count');
        const data = await response.json();
        unreadCount.value = data.count;
        window.fetchInProgress = false;
    } catch (error) {
        window.fetchInProgress = false;
        // Fallback: utiliser les props si disponibles
        unreadCount.value = page.props.unreadCount || 0;
    }
};

// Utiliser la prop isMobileOpen pour l'état mobile
const isMobile = ref(false);
let intervalId = null;

const checkScreenSize = () => {
    isMobile.value = window.innerWidth < 1024;
};

onMounted(() => {
  try {
    fetchUnreadCount();
    // Rafraîchir toutes les 30 secondes
    intervalId = setInterval(fetchUnreadCount, 30000);
    
    checkScreenSize();
    window.addEventListener("resize", checkScreenSize);
    
  } catch (error) {
    console.error('Erreur lors du montage du composant:', error)
  }
});

onUnmounted(() => {
  // Nettoyer l'intervalle pour éviter les fuites mémoire
  if (intervalId) {
    clearInterval(intervalId);
    intervalId = null;
  }
  window.removeEventListener("resize", checkScreenSize);
});

const menuItems = computed(() => [
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
        name: "Réservations",
        icon: "fas fa-calendar-check",
        route: "promoter.reservations",
        href: "/promoter/reservations"
    },
    {
        name: "Conversations",
        icon: "fas fa-comments",
        route: "promoter.conversations",
        href: "/promoter/conversations"
    },
    {
        name: "Notifications",
        icon: "fas fa-bell",
        route: "promoter.notifications",
        href: "/promoter/notifications",
        badge: unreadCount.value
    },
    {
        name: "Mon Profil",
        icon: "fas fa-user",
        route: "promoter.profile",
        href: "/promoter/profile"
    }
]);

const handleNavigation = () => {
    if (isMobile.value) {
        emit('close');
    }
};
</script>

<template>
  <div class="flex h-screen">
    <aside class="fixed top-0 left-0 w-64 h-screen bg-white border-r border-gray-200 flex flex-col transition-transform duration-300 z-40 lg:translate-x-0"
           :class="props.isMobileOpen ? 'translate-x-0' : '-translate-x-full'">
      
      <div class="p-6 flex items-center gap-3">
        <div class="bg-primary size-10 rounded-full flex items-center justify-center text-white">
          <i class="fas fa-gamepad"></i>
        </div>
        <h1 class="text-slate-900 dark:text-white text-lg font-bold tracking-tight">YOUPIHUB</h1>
      </div>
      
      <nav class="flex-1 overflow-y-auto custom-scrollbar px-4 py-2 flex flex-col gap-2">
          <Link v-for="item in menuItems" :key="item.name" :href="item.href" @click="handleNavigation"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group w-full text-left"
                :class="{ 'bg-primary/10 text-primary dark:text-blue-400': computedCurrent === item.route }">
            <i :class="item.icon" class="group-hover:text-primary transition-colors"></i>
            <span class="text-sm font-medium">{{ item.name }}</span>
            <span v-if="item.badge && item.badge > 0" 
                  class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
              {{ item.badge }}
            </span>
          </Link>
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
  </div>
</template>
