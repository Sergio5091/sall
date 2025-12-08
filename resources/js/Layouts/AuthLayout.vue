<template>
  <div class="min-h-screen bg-gray-50 text-gray-800 font-body overflow-x-hidden">
    <Navbar />
    <main class="pt-20 min-h-[calc(100vh-5rem)] flex items-center justify-center">
      <div class="w-full">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue';
</script>
