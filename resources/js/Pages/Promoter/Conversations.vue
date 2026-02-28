<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

import PromoterLayout from '@/Layouts/PromoterLayout.vue';
defineOptions({ layout: PromoterLayout });
import NotificationModal from '../../Components/NotificationModal.vue';

const props = defineProps({
  conversations: Object
});

// États pour les modaux
const showNotificationModal = ref(false);
const notificationType = ref('success');
const notificationTitle = ref('');
const notificationMessage = ref('');

// Formater la date
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

// Formater le message
const formatMessage = (content) => {
  if (content.length > 50) {
    return content.substring(0, 50) + '...';
  }
  return content;
};

// Obtenir la classe du statut
const getStatusClass = (status) => {
  const classes = {
    'en_attente': 'bg-yellow-100 text-yellow-800',
    'confirme': 'bg-green-100 text-green-800',
    'annule': 'bg-red-100 text-red-800',
    'termine': 'bg-gray-100 text-gray-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

// Obtenir le libellé du statut
const getStatusLabel = (status) => {
  const labels = {
    'en_attente': 'En attente',
    'confirme': 'Confirmé',
    'annule': 'Annulé',
    'termine': 'Terminé'
  };
  return labels[status] || status;
};

// Aller à une conversation
const goToConversation = (conversationId) => {
  router.visit(`/promoter/conversations/${conversationId}`);
};

// Afficher une notification
const showNotification = (type, title, message) => {
  notificationType.value = type;
  notificationTitle.value = title;
  notificationMessage.value = message;
  showNotificationModal.value = true;
};
</script>

<template>
  <Head title="Conversations" />

  <div class="flex h-screen bg-gray-50">

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto lg:ml-64">
      <!-- Header -->
      <div class="sticky top-0 z-10 bg-white/80 dark:bg-[#19202e]/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 px-8 py-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div class="flex min-w-72 flex-col gap-1">
            <p class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Conversations</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm font-normal leading-normal">Discutez avec vos clients</p>
          </div>
        </div>
      </div>

      <div class="p-8">
        <!-- Liste des conversations -->
        <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
          <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <div 
              v-for="conversation in props.conversations.data" 
              :key="conversation.id"
              @click="goToConversation(conversation.id)"
              class="p-6 hover:bg-gray-50 dark:hover:bg-[#2d3748] transition-colors cursor-pointer"
            >
              <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                  <!-- Client et salle -->
                  <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                      <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ conversation.client_name }}
                      </h3>
                      <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ conversation.venue_name }}
                      </p>
                    </div>
                  </div>

                  <!-- Dernier message -->
                  <div v-if="conversation.last_message" class="mb-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                      <span class="font-medium">
                        {{ conversation.last_message.sender_id === $page.props.auth.user.id ? 'Vous:' : 'Client:' }}
                      </span>
                      {{ formatMessage(conversation.last_message.content) }}
                    </p>
                  </div>

                  <!-- Métadonnées -->
                  <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                    <span>{{ formatDate(conversation.last_message_at || conversation.created_at) }}</span>
                    <span :class="'px-2 py-1 rounded-full text-xs font-medium ' + getStatusClass(conversation.reservation_status)">
                      {{ getStatusLabel(conversation.reservation_status) }}
                    </span>
                  </div>
                </div>

                <!-- Badge de messages non lus -->
                <div class="flex flex-col items-end gap-2">
                  <div v-if="conversation.unread_count > 0" class="bg-red-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center">
                    {{ conversation.unread_count }}
                  </div>
                  <i class="fas fa-chevron-right text-gray-400"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Message si aucune conversation -->
          <div v-if="props.conversations.data.length === 0" class="text-center py-12">
            <i class="fas fa-comments text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <p class="text-gray-500 dark:text-gray-400 text-lg">Aucune conversation</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">
              Les conversations apparaîtront ici une fois que les clients commenceront à réserver vos salles
            </p>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="props.conversations.data.length > 0" class="mt-6 flex justify-center">
          <div class="flex gap-2">
            <Link 
              v-if="props.conversations.prev_page_url"
              :href="props.conversations.prev_page_url"
              class="px-3 py-2 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
            >
              <i class="fas fa-chevron-left"></i>
            </Link>
            
            <span class="px-3 py-2 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-lg">
              {{ props.conversations.current_page }} / {{ props.conversations.last_page }}
            </span>
            
            <Link 
              v-if="props.conversations.next_page_url"
              :href="props.conversations.next_page_url"
              class="px-3 py-2 bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
            >
              <i class="fas fa-chevron-right"></i>
            </Link>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Notification Modal -->
  <NotificationModal 
    v-if="showNotificationModal"
    :type="notificationType"
    :title="notificationTitle"
    :message="notificationMessage"
    @close="showNotificationModal = false"
  />
</template>
