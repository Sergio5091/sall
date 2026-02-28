<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

import PromoterLayout from '@/Layouts/PromoterLayout.vue';
defineOptions({ layout: PromoterLayout });
import NotificationModal from '../../Components/NotificationModal.vue';

const props = defineProps({
  conversation: Object,
  messages: Array
});

// États
const showNotificationModal = ref(false);
const notificationType = ref('success');
const notificationTitle = ref('');
const notificationMessage = ref('');
const newMessage = ref('');
const messagesContainer = ref(null);
const isLoading = ref(false);
const lastMessageId = ref(0);

// Polling pour les nouveaux messages
let pollingInterval = null;

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

// Envoyer un message
const sendMessage = async () => {
  if (!newMessage.value.trim() || isLoading.value) return;

  isLoading.value = true;
  const messageContent = newMessage.value.trim();

  try {
    const response = await fetch(`/promoter/conversations/${props.conversation.id}/messages`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        content: messageContent,
        _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      })
    });

    if (response.ok) {
      const data = await response.json();
      props.messages.push(data.message);
      newMessage.value = '';
      lastMessageId.value = data.message.id;
      
      // Scroller vers le bas
      await nextTick();
      scrollToBottom();
    } else {
      throw new Error('Erreur lors de l\'envoi du message');
    }
  } catch (error) {
    console.error('Erreur:', error);
    showNotification('error', 'Erreur', 'Impossible d\'envoyer le message');
  } finally {
    isLoading.value = false;
  }
};

// Vérifier les nouveaux messages
const checkNewMessages = async () => {
  try {
    const response = await fetch(`/promoter/conversations/${props.conversation.id}/messages/new?last_message_id=${lastMessageId.value}`, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    if (response.ok) {
      const data = await response.json();
      if (data.messages && data.messages.length > 0) {
        props.messages.push(...data.messages);
        lastMessageId.value = Math.max(lastMessageId.value, ...data.messages.map(m => m.id));
        
        // Scroller vers le bas
        await nextTick();
        scrollToBottom();
      }
    }
  } catch (error) {
    console.error('Erreur lors de la vérification des messages:', error);
  }
};

// Scroller vers le bas
const scrollToBottom = () => {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
  }
};

// Afficher une notification
const showNotification = (type, title, message) => {
  notificationType.value = type;
  notificationTitle.value = title;
  notificationMessage.value = message;
  showNotificationModal.value = true;
};

// Gérer les touches du clavier
const handleKeyPress = (event) => {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault();
    sendMessage();
  }
};

// Démarrer le polling
onMounted(() => {
  // Initialiser le dernier ID de message
  if (props.messages.length > 0) {
    lastMessageId.value = Math.max(...props.messages.map(m => m.id));
  }
  
  // Scroller vers le bas
  nextTick(() => {
    scrollToBottom();
  });
  
  // Démarrer le polling toutes les 5 secondes
  pollingInterval = setInterval(checkNewMessages, 5000);
});

onUnmounted(() => {
  if (pollingInterval) {
    clearInterval(pollingInterval);
  }
});
</script>

<template>
  <Head title="Conversation" />

  <div class="flex h-screen bg-gray-50">

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto lg:ml-64">
      <!-- Header -->
      <div class="sticky top-0 z-10 bg-white/80 dark:bg-[#19202e]/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 px-8 py-4">
        <div class="flex items-center gap-4">
          <Link href="/promoter/conversations" class="text-gray-600 hover:text-gray-900 transition-colors">
            <i class="fas fa-arrow-left"></i>
          </Link>
          <div class="flex-1">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
              {{ props.conversation.client.name }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              {{ props.conversation.venue.name }} • 
              <span :class="'px-2 py-1 rounded-full text-xs font-medium ' + getStatusClass(props.conversation.reservation_status)">
                {{ getStatusLabel(props.conversation.reservation_status) }}
              </span>
            </p>
          </div>
        </div>
      </div>

      <!-- Messages -->
      <div class="flex-1 flex flex-col">
        <div ref="messagesContainer" class="flex-1 overflow-y-auto p-8 space-y-4">
          <div 
            v-for="message in props.messages" 
            :key="message.id"
            :class="[
              'flex',
              message.is_from_me ? 'justify-end' : 'justify-start'
            ]"
          >
            <div 
              :class="[
                'max-w-xs lg:max-w-md px-4 py-2 rounded-lg',
                message.is_from_me 
                  ? 'bg-blue-600 text-white' 
                  : 'bg-gray-200 text-gray-900'
              ]"
            >
              <p class="text-sm">{{ message.content }}</p>
              <p 
                :class="[
                  'text-xs mt-1',
                  message.is_from_me ? 'text-blue-100' : 'text-gray-500'
                ]"
              >
                {{ formatDate(message.created_at) }}
              </p>
            </div>
          </div>
        </div>

        <!-- Input -->
        <div class="border-t border-gray-200 dark:border-gray-800 p-4">
          <div class="flex gap-4">
            <input
              v-model="newMessage"
              @keypress="handleKeyPress"
              type="text"
              placeholder="Tapez votre message..."
              class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#19202e] text-gray-900 dark:text-white"
              :disabled="isLoading"
            />
            <button
              @click="sendMessage"
              :disabled="!newMessage.trim() || isLoading"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i v-if="isLoading" class="fas fa-spinner fa-spin"></i>
              <i v-else class="fas fa-paper-plane"></i>
            </button>
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
