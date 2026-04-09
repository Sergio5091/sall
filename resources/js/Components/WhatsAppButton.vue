<template>
  <a
    :href="whatsappUrl"
    target="_blank"
    rel="noreferrer"
    class="fixed z-50 bottom-5 right-5 w-14 h-14 rounded-full bg-[#25D366] shadow-2xl flex items-center justify-center text-white text-2xl transition-transform duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white"
  >
    <i class="fab fa-whatsapp"></i>
    <span class="sr-only">Contact WhatsApp</span>
  </a>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  phone: {
    type: String,
    default: import.meta.env.VITE_WHATSAPP_NUMBER || '+229123456789',
  },
  message: {
    type: String,
    default:
      import.meta.env.VITE_WHATSAPP_MESSAGE ||
      "Bonjour, je rencontre un problème d'utilisation du site YOUPIHUB.",
  },
});

const whatsappUrl = computed(() => {
  const phoneNum = props.phone.replace(/\D/g, '');
  const encodedMessage = encodeURIComponent(props.message);
  return `https://wa.me/${phoneNum}?text=${encodedMessage}`;
});
</script>
