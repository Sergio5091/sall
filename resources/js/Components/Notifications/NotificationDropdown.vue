<script setup>
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    notifications: Array,
    unreadCount: Number
})

const emit = defineEmits(['markAsRead', 'markAllAsRead'])

const showDropdown = ref(false)



const markAsRead = (notificationId) => {
    emit('markAsRead', notificationId)
}

const markAllAsRead = () => {
    emit('markAllAsRead')
}

const getNotificationIcon = (type) => {
    switch (type) {
        case 'success':
            return 'fas fa-check-circle text-green-500'
        case 'warning':
            return 'fas fa-exclamation-triangle text-yellow-500'
        case 'error':
            return 'fas fa-times-circle text-red-500'
        default:
            return 'fas fa-info-circle text-blue-500'
    }
}

const getNotificationBgClass = (type) => {
    switch (type) {
        case 'success':
            return 'bg-green-50 border-green-200'
        case 'warning':
            return 'bg-yellow-50 border-yellow-200'
        case 'error':
            return 'bg-red-50 border-red-200'
        default:
            return 'bg-blue-50 border-blue-200'
    }
}
</script>

<template>
    <div class="relative">
        <!-- Bouton de notification -->
        <button 
            @click="showDropdown = !showDropdown"
            class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded-lg"
        >
            <i class="fas fa-bell text-lg"></i>
            <span 
                v-if="unreadCount > 0"
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
            >
                {{ unreadCount > 99 ? '99+' : unreadCount }}
            </span>
        </button>

        <!-- Dropdown des notifications -->
        <div 
            v-if="showDropdown"
            class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-96 overflow-hidden"
        >
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Notifications</h3>
                <button 
                    v-if="unreadCount > 0"
                    @click="markAllAsRead"
                    class="text-sm text-primary hover:text-primary-dark"
                >
                    Tout marquer comme lu
                </button>
            </div>

            <!-- Liste des notifications -->
            <div class="max-h-80 overflow-y-auto">
                <div v-if="notifications.length === 0" class="p-8 text-center text-gray-500">
                    <i class="fas fa-bell-slash text-2xl mb-2"></i>
                    <p>Aucune notification</p>
                </div>

                <div v-else class="divide-y divide-gray-100">
                    <div 
                        v-for="notification in notifications" 
                        :key="notification.id"
                        :class="[
                            'p-4 hover:bg-gray-50 transition-colors cursor-pointer',
                            !notification.is_read ? getNotificationBgClass(notification.type) : ''
                        ]"
                        @click="markAsRead(notification.id)"
                    >
                        <div class="flex items-start gap-3">
                            <i :class="getNotificationIcon(notification.type)"></i>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-gray-900 truncate">
                                        {{ notification.title }}
                                    </h4>
                                    <span class="text-xs text-gray-500 ml-2 whitespace-nowrap">
                                        {{ new Date(notification.created_at).toLocaleDateString('fr-FR') }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ notification.message }}
                                </p>
                                <div v-if="!notification.is_read" class="mt-2">
                                    <span class="inline-flex items-center text-xs text-primary">
                                        <span class="w-1.5 h-1.5 bg-primary rounded-full mr-1"></span>
                                        Non lu
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div v-if="notifications.length > 0" class="p-3 border-t border-gray-200">
                <Link 
                    href="/promoter/notifications"
                    class="block w-full text-center text-sm text-primary hover:text-primary-dark font-medium"
                    @click="showDropdown = false"
                >
                    Voir toutes les notifications
                </Link>
            </div>
        </div>

        <!-- Overlay pour fermer le dropdown -->
        <div 
            v-if="showDropdown"
            class="fixed inset-0 z-40"
            @click="showDropdown = false"
        ></div>
    </div>
</template>
