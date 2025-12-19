<script setup>
import { ref, watch, onMounted } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: 'Notification',
    },
    message: {
        type: String,
        default: '',
    },
    type: {
        type: String,
        default: 'success', // success, error, warning, info
    },
    duration: {
        type: Number,
        default: 3000, // Auto-close after 3 seconds
    },
    showCloseButton: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);

let timeoutId = null;

const close = () => {
    emit('close');
};

const startAutoClose = () => {
    if (props.duration > 0) {
        timeoutId = setTimeout(() => {
            close();
        }, props.duration);
    }
};

const clearAutoClose = () => {
    if (timeoutId) {
        clearTimeout(timeoutId);
        timeoutId = null;
    }
};

// Auto-close when shown
watch(() => props.show, (show) => {
    if (show) {
        startAutoClose();
    } else {
        clearAutoClose();
    }
});

// Clean up on unmount
onMounted(() => {
    return () => {
        clearAutoClose();
    };
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform translate-y-2 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-2 opacity-0"
        >
            <div v-if="show" class="fixed top-4 right-4 z-[9999] max-w-sm w-full">
                <div class="bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden">
                    <!-- Header with gradient -->
                    <div class="p-4 pb-0">
                        <div class="flex items-start">
                            <!-- Icon -->
                            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                                 :class="{
                                     'bg-green-100': type === 'success',
                                     'bg-red-100': type === 'error',
                                     'bg-yellow-100': type === 'warning',
                                     'bg-blue-100': type === 'info'
                                 }">
                                <i class="text-lg"
                                   :class="{
                                       'fas fa-check text-green-600': type === 'success',
                                       'fas fa-times text-red-600': type === 'error',
                                       'fas fa-exclamation text-yellow-600': type === 'warning',
                                       'fas fa-info text-blue-600': type === 'info'
                                   }"></i>
                            </div>
                            
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-semibold text-gray-900">
                                    {{ title }}
                                </h3>
                            </div>
                            
                            <!-- Close button -->
                            <button
                                v-if="showCloseButton"
                                @click="close"
                                class="flex-shrink-0 ml-2 text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Body -->
                    <div class="p-4 pt-2">
                        <p class="text-sm text-gray-600">
                            {{ message }}
                        </p>
                    </div>
                    
                    <!-- Progress bar (optional) -->
                    <div v-if="duration > 0" class="h-1 bg-gray-200">
                        <div 
                            class="h-full bg-gradient-to-r transition-all ease-linear"
                            :class="{
                                'from-green-400 to-green-600': type === 'success',
                                'from-red-400 to-red-600': type === 'error',
                                'from-yellow-400 to-yellow-600': type === 'warning',
                                'from-blue-400 to-blue-600': type === 'info'
                            }"
                            :style="{ 
                                width: '100%',
                                animation: `shrink ${duration}ms linear forwards`
                            }"
                        ></div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
@keyframes shrink {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}
</style>
