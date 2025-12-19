<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: 'Confirmation',
    },
    message: {
        type: String,
        default: 'Êtes-vous sûr de vouloir continuer ?',
    },
    confirmText: {
        type: String,
        default: 'Confirmer',
    },
    cancelText: {
        type: String,
        default: 'Annuler',
    },
    type: {
        type: String,
        default: 'danger', // danger, warning, info, success
    },
});

const emit = defineEmits(['confirm', 'cancel', 'close']);

const confirm = () => {
    emit('confirm');
    emit('close');
};

const cancel = () => {
    emit('cancel');
    emit('close');
};

// Close modal on escape key
const handleKeydown = (event) => {
    if (event.key === 'Escape' && props.show) {
        cancel();
    }
};

// Add and remove event listener
watch(() => props.show, (show) => {
    if (show) {
        document.addEventListener('keydown', handleKeydown);
    } else {
        document.removeEventListener('keydown', handleKeydown);
    }
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="flex min-h-screen items-center justify-center p-4">
                    <!-- Overlay -->
                    <div 
                        class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                        @click="cancel"
                    ></div>
                    
                    <!-- Modal -->
                    <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all">
                        <!-- Header -->
                        <div class="p-6 pb-0">
                            <div class="flex items-center">
                                <!-- Icon -->
                                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center"
                                     :class="{
                                         'bg-red-100': type === 'danger',
                                         'bg-yellow-100': type === 'warning',
                                         'bg-blue-100': type === 'info',
                                         'bg-green-100': type === 'success'
                                     }">
                                    <i class="text-xl"
                                       :class="{
                                           'fas fa-exclamation-triangle text-red-600': type === 'danger',
                                           'fas fa-exclamation text-yellow-600': type === 'warning',
                                           'fas fa-info-circle text-blue-600': type === 'info',
                                           'fas fa-check-circle text-green-600': type === 'success'
                                       }"></i>
                                </div>
                                
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ title }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Body -->
                        <div class="p-6">
                            <p class="text-gray-600">
                                {{ message }}
                            </p>
                        </div>
                        
                        <!-- Footer -->
                        <div class="p-6 pt-0 flex gap-3 justify-end">
                            <button
                                @click="cancel"
                                class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors font-medium"
                            >
                                {{ cancelText }}
                            </button>
                            <button
                                @click="confirm"
                                class="px-4 py-2 text-white rounded-lg transition-colors font-medium"
                                :class="{
                                    'bg-red-600 hover:bg-red-700': type === 'danger',
                                    'bg-yellow-600 hover:bg-yellow-700': type === 'warning',
                                    'bg-blue-600 hover:bg-blue-700': type === 'info',
                                    'bg-green-600 hover:bg-green-700': type === 'success'
                                }"
                            >
                                {{ confirmText }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
