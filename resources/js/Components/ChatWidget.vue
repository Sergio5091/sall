<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';

const props = defineProps({
  conversationId: String,
  messages: Array,
  currentUser: Object
});

const emit = defineEmits(['message-sent']);

// États
const newMessage = ref('');
const messagesContainer = ref(null);
const isLoading = ref(false);
const lastMessageId = ref(0);

// Polling pour les nouveaux messages
let pollingInterval = null;

// Formater la date
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleTimeString('fr-FR', {
    hour: '2-digit',
    minute: '2-digit'
  });
};

// Envoyer un message
const sendMessage = async () => {
  if (!newMessage.value.trim() || isLoading.value) return;

  isLoading.value = true;
  const messageContent = newMessage.value.trim();

  try {
    const response = await fetch(`/client/conversations/${props.conversationId}/messages`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        content: messageContent
      })
    });

    if (response.ok) {
      const data = await response.json();
      emit('message-sent', data.message);
      newMessage.value = '';
      lastMessageId.value = data.message.id;
      
      // Scroller vers le bas
      await nextTick();
      scrollToBottom();
    }
  } catch (error) {
    console.error('Erreur:', error);
  } finally {
    isLoading.value = false;
  }
};

// Vérifier les nouveaux messages
const checkNewMessages = async () => {
  try {
    const response = await fetch(`/client/conversations/${props.conversationId}/messages/new?last_message_id=${lastMessageId.value}`, {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });

    if (response.ok) {
      const data = await response.json();
      if (data.messages && data.messages.length > 0) {
        data.messages.forEach(message => {
          emit('message-received', message);
        });
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
  <div class="bg-white rounded-lg shadow-lg border border-gray-200 w-full max-w-md">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-4 rounded-t-lg">
      <h3 class="font-semibold">Discussion avec le propriétaire</h3>
      <p class="text-xs opacity-90">Réponses en temps réel</p>
    </div>

    <!-- Messages -->
    <div ref="messagesContainer" class="h-64 overflow-y-auto p-4 space-y-3 bg-gray-50">
      <div 
        v-for="message in messages" 
        :key="message.id"
        :class="[
          'flex',
          message.is_from_me ? 'justify-end' : 'justify-start'
        ]"
      >
        <div 
          :class="[
            'max-w-xs px-3 py-2 rounded-lg text-sm',
            message.is_from_me 
              ? 'bg-blue-600 text-white' 
              : 'bg-white border border-gray-200 text-gray-900'
          ]"
        >
          <p>{{ message.content }}</p>
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
    <div class="p-4 border-t border-gray-200">
      <div class="flex gap-2">
        <input
          v-model="newMessage"
          @keypress="handleKeyPress"
          type="text"
          placeholder="Posez votre question..."
          class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          :disabled="isLoading"
        />
        <button
          @click="sendMessage"
          :disabled="!newMessage.trim() || isLoading"
          class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-sm"
        >
          <i v-if="isLoading" class="fas fa-spinner fa-spin"></i>
          <i v-else class="fas fa-paper-plane"></i>
        </button>
      </div>
    </div>
  </div>
</template>
