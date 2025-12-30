<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  show: Boolean,
  type: {
    type: String,
    default: 'info' // info, success, warning, error
  },
  title: String,
  message: String,
  confirmText: {
    type: String,
    default: 'OK'
  },
  cancelText: {
    type: String,
    default: 'Annuler'
  },
  showCancel: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close', 'confirm']);

// Close modal when show prop changes to false
watch(() => props.show, (newVal) => {
  if (!newVal) {
    emit('close');
  }
});

// Handle escape key
const handleEscape = (e) => {
  if (e.key === 'Escape' && props.show) {
    emit('close');
  }
};

// Add and remove event listener
watch(() => props.show, (newVal) => {
  if (newVal) {
    document.addEventListener('keydown', handleEscape);
    document.body.style.overflow = 'hidden';
  } else {
    document.removeEventListener('keydown', handleEscape);
    document.body.style.overflow = '';
  }
});

// Get icon and colors based on type
const getIcon = () => {
  const icons = {
    info: 'fas fa-info-circle',
    success: 'fas fa-check-circle',
    warning: 'fas fa-exclamation-triangle',
    error: 'fas fa-times-circle'
  };
  return icons[props.type] || icons.info;
};

const getColors = () => {
  const colors = {
    info: {
      bg: 'bg-blue-100',
      text: 'text-blue-600',
      border: 'border-blue-200',
      button: 'bg-blue-600 hover:bg-blue-700'
    },
    success: {
      bg: 'bg-green-100',
      text: 'text-green-600',
      border: 'border-green-200',
      button: 'bg-green-600 hover:bg-green-700'
    },
    warning: {
      bg: 'bg-yellow-100',
      text: 'text-yellow-600',
      border: 'border-yellow-200',
      button: 'bg-yellow-600 hover:bg-yellow-700'
    },
    error: {
      bg: 'bg-red-100',
      text: 'text-red-600',
      border: 'border-red-200',
      button: 'bg-red-600 hover:bg-red-700'
    }
  };
  return colors[props.type] || colors.info;
};
</script>

<template>
  <teleport to="body">
    <div 
      v-if="show" 
      class="fixed inset-0 z-50 flex items-center justify-center px-4"
      @click.self="$emit('close')"
    >
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
      
      <!-- Modal -->
      <div class="relative w-full max-w-md bg-white shadow-2xl rounded-lg border border-gray-200">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100">
          <div class="flex items-center gap-4">
            <!-- Icon -->
            <div :class="[getColors().bg, getColors().text]" class="p-3 rounded-full">
              <i :class="getIcon()" class="text-xl"></i>
            </div>
            
            <!-- Title -->
            <h3 class="text-lg font-bold text-gray-900 flex-1">
              {{ title }}
            </h3>
            
            <!-- Close button -->
            <button 
              @click="$emit('close')"
              class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
              <i class="fas fa-times text-gray-500"></i>
            </button>
          </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
          <p class="text-gray-700 leading-relaxed">
            {{ message }}
          </p>
        </div>
        
        <!-- Actions -->
        <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
          <button 
            v-if="showCancel"
            @click="$emit('close')"
            class="px-4 py-2.5 text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
          >
            {{ cancelText }}
          </button>
          <button 
            @click="$emit('confirm')"
            :class="[getColors().button, 'px-4 py-2.5 text-sm font-medium text-white rounded-lg transition-colors']"
          >
            {{ confirmText }}
          </button>
        </div>
      </div>
    </div>
  </teleport>
</template>

<style scoped>
/* Animation styles */
.fixed {
  animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Modal animation */
.relative {
  animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
