<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import NotificationModal from '../../Components/NotificationModal.vue';
import Navigation from '../../Components/Navigation.vue';

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
    const response = await fetch(`/client/conversations/${props.conversation.id}/messages`, {
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
    const response = await fetch(`/client/conversations/${props.conversation.id}/messages/new?last_message_id=${lastMessageId.value}`, {
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

  <div class="min-h-screen bg-gray-50 font-sans">
    <Navigation :user="$page.props.auth?.user" current-page="conversations" />

    <!-- Messages -->
    <main class="pt-20 h-screen flex flex-col">
      <div class="flex-1 overflow-y-auto p-4 md:p-8">
        <div ref="messagesContainer" class="max-w-4xl mx-auto space-y-4">
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
      </div>

      <!-- Input -->
      <div class="border-t border-gray-200 bg-white p-4">
        <div class="max-w-4xl mx-auto">
          <div class="flex gap-4">
            <input
              v-model="newMessage"
              @keypress="handleKeyPress"
              type="text"
              placeholder="Tapez votre message..."
              class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
    :show="showNotificationModal"
    :type="notificationType"
    :title="notificationTitle"
    :message="notificationMessage"
    @close="showNotificationModal = false"
  />
</template>
