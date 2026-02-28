<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

import PromoterLayout from '@/Layouts/PromoterLayout.vue';
defineOptions({ layout: PromoterLayout });
import ConfirmModal from '../../Components/ConfirmModal.vue';

const props = defineProps({
    notifications: Object,
    stats: Object,
    filters: Object
});


// États pour les modaux
const showDeleteModal = ref(false);
const showDeleteAllModal = ref(false);
const notificationToDelete = ref(null);

const getNotificationIcon = (type) => {
    switch (type) {
        case 'success':
            return 'fas fa-check-circle text-green-500';
        case 'warning':
            return 'fas fa-exclamation-triangle text-yellow-500';
        case 'error':
            return 'fas fa-times-circle text-red-500';
        default:
            return 'fas fa-info-circle text-blue-500';
    }
};

const getNotificationBgClass = (type, isRead) => {
    if (isRead) return 'bg-white dark:bg-gray-800';
    
    switch (type) {
        case 'success':
            return 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800';
        case 'warning':
            return 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800';
        case 'error':
            return 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800';
        default:
            return 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800';
    }
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 0) {
        return 'Aujourd\'hi ' + date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    } else if (diffDays === 1) {
        return 'Hier ' + date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    } else if (diffDays < 7) {
        return date.toLocaleDateString('fr-FR', { weekday: 'long', hour: '2-digit', minute: '2-digit' });
    } else {
        return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' });
    }
};

// Méthodes pour gérer les actions
const markAsRead = async (notification) => {
    try {
        await router.post(`/promoter/notifications/${notification.id}/read`, {}, {
            onSuccess: () => {
                // Mettre à jour l'état local
                notification.is_read = true;
                // Mettre à jour le compteur dans le sidebar
                window.location.reload();
            }
        });
    } catch (error) {
        console.error('Erreur lors du marquage comme lu:', error);
    }
};

const markAllAsRead = async () => {
    try {
        await router.post('/promoter/notifications/read-all', {}, {
            onSuccess: () => {
                // Recharger la page pour voir les changements
                window.location.reload();
            }
        });
    } catch (error) {
        // Erreur silencieuse
    }
};

const deleteNotification = async (notification) => {
    notificationToDelete.value = notification;
    showDeleteModal.value = true;
};

const confirmDeleteNotification = async () => {
    try {
        await router.delete(`/promoter/notifications/${notificationToDelete.value.id}`, {
            onSuccess: () => {
                // Recharger la page pour voir les changements
                window.location.reload();
            }
        });
    } catch (error) {
        // Erreur silencieuse
    }
    showDeleteModal.value = false;
    notificationToDelete.value = null;
};

const deleteAllNotifications = async () => {
    showDeleteAllModal.value = true;
};

const confirmDeleteAllNotifications = async () => {
    try {
        await router.delete('/promoter/notifications/delete-all', {
            onSuccess: () => {
                // Recharger la page pour voir les changements
                window.location.reload();
            }
        });
    } catch (error) {
        // Erreur silencieuse
    }
    showDeleteAllModal.value = false;
};

const viewSalle = (notification) => {
    if (notification.related_type === 'salle' && notification.related_id) {
        router.visit(`/promoter/venues`);
    }
};
</script>

<template>
  <Head title="Notifications" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex min-h-screen w-full bg-gray-50 font-display text-gray-800">
    <main class="flex-1 overflow-y-auto transition-all duration-300">
      <!-- Conteneur principal responsive -->
      <div class="w-full sm:max-w-full md:max-w-4xl lg:max-w-6xl xl:max-w-7xl 2xl:max-w-screen-2xl mx-auto px-3 sm:px-4 md:px-6 py-6 md:py-8">
        <!-- Messages flash -->
        <div v-if="$page.props.success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
          <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-2"></i>
            <p class="text-green-800">{{ $page.props.success }}</p>
          </div>
        </div>
        
        <div v-if="$page.props.error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
          <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
            <p class="text-red-800">{{ $page.props.error }}</p>
          </div>
        </div>

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Notifications</p>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Gérez toutes vos notifications et messages</p>
          </div>
          <div class="flex items-center gap-2">
            <button @click="markAllAsRead" class="flex items-center justify-center rounded-lg h-10 bg-white dark:bg-[#19202e] border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 gap-2 text-sm font-medium px-4 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
              <i class="fas fa-check-double"></i>
              <span class="truncate">Marquer tout comme lu</span>
            </button>
            <button @click="deleteAllNotifications" class="flex items-center justify-center rounded-lg h-10 bg-brand-red text-white gap-2 text-sm font-medium px-4 hover:bg-brand-red/90 transition-colors">
              <i class="fas fa-trash"></i>
              <span class="truncate">Supprimer tout</span>
            </button>
          </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mt-8">
          <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
              </div>
              <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                <i class="fas fa-bell text-gray-600 dark:text-gray-400"></i>
              </div>
            </div>
          </div>
          
          <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Non lues</p>
                <p class="text-2xl font-bold text-yellow-600">{{ stats.unread }}</p>
              </div>
              <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                <i class="fas fa-envelope text-yellow-600"></i>
              </div>
            </div>
          </div>
          
          <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Succès</p>
                <p class="text-2xl font-bold text-green-600">{{ stats.success }}</p>
              </div>
              <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600"></i>
              </div>
            </div>
          </div>
          
          <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Alertes</p>
                <p class="text-2xl font-bold text-yellow-600">{{ stats.warning }}</p>
              </div>
              <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
              </div>
            </div>
          </div>
          
          <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Erreurs</p>
                <p class="text-2xl font-bold text-red-600">{{ stats.error }}</p>
              </div>
              <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                <i class="fas fa-times-circle text-red-600"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Notifications List -->
        <div class="mt-8">
          <div v-if="notifications.data.length === 0" class="text-center py-12">
            <i class="fas fa-bell-slash text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucune notification</h3>
            <p class="text-gray-600 dark:text-gray-400">Vous n'avez aucune notification pour le moment.</p>
          </div>
          
          <div v-else class="space-y-3">
            <div 
              v-for="notification in notifications.data" 
              :key="notification.id"
              :class="[
                'p-4 rounded-lg border transition-all hover:shadow-md',
                getNotificationBgClass(notification.type, notification.is_read)
              ]"
            >
              <div class="flex gap-3 items-start">
                <div class="flex-shrink-0 mt-1">
                  <i :class="getNotificationIcon(notification.type)"></i>
                </div>
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <h4 class="font-semibold text-gray-900 dark:text-white">
                      {{ notification.title }}
                    </h4>
                    <div class="flex items-center gap-2">
                      <span v-if="!notification.is_read" class="w-2 h-2 bg-blue-500 rounded-full"></span>
                      <button @click="deleteNotification(notification)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <p class="text-gray-600 dark:text-gray-400 mt-1">
                    {{ notification.message }}
                  </p>
                  <div class="flex items-center gap-4 mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      {{ formatDate(notification.created_at) }}
                    </p>
                    <button @click="markAsRead(notification)" v-if="!notification.is_read" class="text-xs font-medium text-primary hover:underline">
                      Marquer comme lu
                    </button>
                    <button @click="viewSalle(notification)" v-if="notification.related_type === 'salle' && notification.related_id" class="text-xs font-medium text-primary hover:underline">
                      Voir la salle
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Pagination -->
          <div v-if="notifications.data.length > 0" class="mt-8">
            <div class="flex items-center justify-between">
              <p class="text-sm text-gray-600 dark:text-gray-400">
                Affichage de {{ notifications.from }} à {{ notifications.to }} sur {{ notifications.total }} notifications
              </p>
              <div class="flex gap-2">
                <!-- Pagination links will be added here if needed -->
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
    
    <!-- Modaux de confirmation -->
    <ConfirmModal
        :show="showDeleteModal"
        title="Supprimer la notification"
        message="Êtes-vous sûr de vouloir supprimer cette notification ? Cette action est irréversible."
        confirm-text="Supprimer"
        cancel-text="Annuler"
        type="danger"
        @confirm="confirmDeleteNotification"
        @close="showDeleteModal = false"
    />
    
    <ConfirmModal
        :show="showDeleteAllModal"
        title="Supprimer toutes les notifications"
        message="Êtes-vous sûr de vouloir supprimer toutes les notifications ? Cette action est irréversible."
        confirm-text="Supprimer tout"
        cancel-text="Annuler"
        type="danger"
        @confirm="confirmDeleteAllNotifications"
        @close="showDeleteAllModal = false"
    />
  </div>
</template>
