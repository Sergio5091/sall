<template>
  <div class="min-h-screen bg-background-dark text-[#EAEAEA] font-body overflow-x-hidden">
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
