<template>
  <div class="page-transition-container">
    <!-- Loading Overlay -->
    <Transition name="page-loading" appear>
      <div 
        v-if="isLoading" 
        class="fixed inset-0 z-[9999] bg-white flex items-center justify-center"
      >
        <div class="loading-content">
          <!-- Loading Spinner -->
          <div class="relative">
            <div class="w-16 h-16 border-4 border-gray-200 border-t-blue-600 rounded-full animate-spin"></div>
            <div class="absolute inset-0 w-16 h-16 border-4 border-transparent border-b-purple-600 rounded-full animate-spin animation-delay-150"></div>
          </div>
          
          <!-- Loading Text -->
          <div class="mt-4 text-center">
            <p class="text-gray-600 font-medium animate-pulse">Chargement...</p>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Page Content with Transition -->
    <Transition name="page-content" appear>
      <div v-if="!isLoading" class="page-content">
        <slot />
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const isLoading = ref(false)
let loadingTimeout = null

// Show loading with minimum duration
const showLoading = () => {
  isLoading.value = true
  // Minimum loading time for better UX
  loadingTimeout = setTimeout(() => {
    isLoading.value = false
  }, 300)
}

// Hide loading
const hideLoading = () => {
  if (loadingTimeout) {
    clearTimeout(loadingTimeout)
    loadingTimeout = null
  }
  isLoading.value = false
}

// Handle navigation start
router.on('start', () => {
  showLoading()
})

// Handle navigation finish
router.on('finish', () => {
  // Small delay to ensure loading is visible
  setTimeout(() => {
    hideLoading()
  }, 100)
})

// Handle navigation errors
router.on('error', () => {
  hideLoading()
})

// Clean up on unmount
onUnmounted(() => {
  if (loadingTimeout) {
    clearTimeout(loadingTimeout)
  }
})
</script>

<style scoped>
/* Page Loading Animation */
.page-loading-enter-active,
.page-loading-leave-active {
  transition: all 0.3s ease;
}

.page-loading-enter-from {
  opacity: 0;
}

.page-loading-leave-to {
  opacity: 0;
}

/* Page Content Animation */
.page-content-enter-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.page-content-leave-active {
  transition: all 0.3s ease;
}

.page-content-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.page-content-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

/* Loading spinner animation delay */
.animation-delay-150 {
  animation-delay: 150ms;
}

/* Loading content styling */
.loading-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

/* Page transition container */
.page-transition-container {
  position: relative;
  min-height: 100vh;
}

.page-content {
  width: 100%;
  height: 100%;
}
</style>
