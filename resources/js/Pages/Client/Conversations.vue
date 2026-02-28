<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
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
  router.visit(`/client/conversations/${conversationId}`);
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

  <div class="min-h-screen bg-gray-50 font-sans">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center gap-8">
            <Link href="/client/dashboard" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
              <i class="fas fa-gamepad text-xl text-blue-600"></i>
              <span class="text-lg font-bold text-gray-900">YOUPIHUB</span>
            </Link>
            <nav class="hidden md:flex items-center gap-6">
              <Link href="/client/dashboard" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">Dashboard</Link>
              <Link href="/client/salles" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">Salles</Link>
              <Link href="/client/evenements" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">Événements</Link>
              <Link href="/client/conversations" class="text-sm font-medium text-blue-600">Conversations</Link>
              <Link href="/client/reservations" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">Réservations</Link>
            </nav>
          </div>
          <div class="flex items-center gap-3">
            <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
              <i class="fas fa-bell text-gray-600"></i>
              <span class="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full"></span>
            </button>
            <Link href="/client/profile" class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold">
              {{ $page.props.auth?.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="pt-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Mes Conversations</h1>
          <p class="text-gray-600 mt-2">Discutez avec les propriétaires de salles</p>
        </div>

        <!-- Liste des conversations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="divide-y divide-gray-200">
            <div 
              v-for="conversation in props.conversations.data" 
              :key="conversation.id"
              @click="goToConversation(conversation.id)"
              class="p-6 hover:bg-gray-50 transition-colors cursor-pointer"
            >
              <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                  <!-- Promoteur et salle -->
                  <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                      <i class="fas fa-store text-purple-600"></i>
                    </div>
                    <div>
                      <h3 class="text-sm font-semibold text-gray-900">
                        {{ conversation.promoter_name }}
                      </h3>
                      <p class="text-xs text-gray-500">
                        {{ conversation.venue_name }}
                      </p>
                    </div>
                  </div>

                  <!-- Dernier message -->
                  <div v-if="conversation.last_message" class="mb-2">
                    <p class="text-sm text-gray-600">
                      <span class="font-medium">
                        {{ conversation.last_message.sender_id === $page.props.auth.user.id ? 'Vous:' : 'Propriétaire:' }}
                      </span>
                      {{ formatMessage(conversation.last_message.content) }}
                    </p>
                  </div>

                  <!-- Métadonnées -->
                  <div class="flex items-center gap-4 text-xs text-gray-500">
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
            <i class="fas fa-comments text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Aucune conversation</p>
            <p class="text-gray-400 text-sm mt-2">
              Les conversations apparaîtront ici une fois que vous aurez fait des réservations
            </p>
            <Link 
              href="/client/salles"
              class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors mt-4"
            >
              <i class="fas fa-search mr-2"></i>
              Explorer les salles
            </Link>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="props.conversations.data.length > 0" class="mt-6 flex justify-center">
          <div class="flex gap-2">
            <Link 
              v-if="props.conversations.prev_page_url"
              :href="props.conversations.prev_page_url"
              class="px-3 py-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <i class="fas fa-chevron-left"></i>
            </Link>
            
            <span class="px-3 py-2 bg-white border border-gray-200 rounded-lg">
              {{ props.conversations.current_page }} / {{ props.conversations.last_page }}
            </span>
            
            <Link 
              v-if="props.conversations.next_page_url"
              :href="props.conversations.next_page_url"
              class="px-3 py-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
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
    :show="showNotificationModal"
    :type="notificationType"
    :title="notificationTitle"
    :message="notificationMessage"
    @close="showNotificationModal = false"
  />
</template>
