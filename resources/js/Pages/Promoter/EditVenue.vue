<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const props = defineProps({
    venue: Object
});
</script>

<template>
  <Head title="Edit Arcade Page" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="flex h-screen bg-gray-50">
    <Sidebar current-route="promoter.events" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto lg:ml-64">
      <div class="sticky top-0 z-10 bg-background-dark/80 backdrop-blur-md border-b border-white/10 px-8 py-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div class="flex min-w-72 flex-col gap-1">
            <p class="text-white text-xl font-bold leading-tight tracking-tight">Modifier votre salle : Arcade Invaders</p>
            <p class="text-gray-400 text-sm font-normal leading-normal">Les modifications sont sauvegardées automatiquement.</p>
          </div>
          <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 text-gray-400 text-sm">
              <i class="fas fa-check-circle text-base"></i>
              <span>Sauvegardé</span>
            </div>
            <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-white/10 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-white/20 transition-colors">
              <span class="truncate">Prévisualiser</span>
            </button>
            <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-blue-500 transition-colors">
              <span class="truncate">Publier les changements</span>
            </button>
          </div>
        </div>
      </div>
      
      <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 p-8">
        <!-- Form Column -->
        <div class="flex flex-col gap-8">
          <!-- General Information Section -->
          <section>
            <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] pb-4">Informations Générales</h2>
            <div class="space-y-6">
              <div class="flex flex-col sm:flex-row gap-6">
                <label class="flex flex-col flex-1">
                  <p class="text-white text-sm font-medium leading-normal pb-2">Nom de la salle</p>
                  <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-white/20 bg-white/5 h-12 placeholder:text-gray-400 px-4 text-base font-normal leading-normal" value="Arcade Invaders"/>
                </label>
                <label class="flex flex-col flex-1">
                  <p class="text-white text-sm font-medium leading-normal pb-2">Adresse</p>
                  <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-white/20 bg-white/5 h-12 placeholder:text-gray-400 px-4 text-base font-normal leading-normal" value="123 Rue du Pixel, 75001 Paris"/>
                </label>
              </div>
              <label class="flex flex-col">
                <p class="text-white text-sm font-medium leading-normal pb-2">Description</p>
                <textarea class="form-textarea flex w-full min-w-0 flex-1 resize-y overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-white/20 bg-white/5 min-h-32 placeholder:text-gray-400 p-4 text-base font-normal leading-normal">Plongez dans un univers rétro-futuriste chez Arcade Invaders. Des classiques des années 80 aux dernières expériences VR, il y en a pour tous les goûts !</textarea>
              </label>
              <div>
                <p class="text-white text-sm font-medium leading-normal pb-2">Photo de couverture</p>
                <div class="flex flex-col items-center gap-6 rounded-lg border-2 border-dashed border-white/20 px-6 py-10 bg-white/5">
                  <div class="flex max-w-[480px] flex-col items-center gap-2">
                    <p class="text-white text-base font-bold leading-tight tracking-[-0.015em] text-center">Glissez-déposez votre photo de couverture</p>
                    <p class="text-gray-400 text-sm font-normal leading-normal text-center">Pour de meilleurs résultats, utilisez une image de 1600x400px.</p>
                  </div>
                  <button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-white/10 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-white/20 transition-colors">
                    <span class="truncate">Télécharger une image</span>
                  </button>
                </div>
              </div>
            </div>
          </section>

          <!-- SEO & Visibility Section -->
          <section>
            <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] pb-4 pt-5">SEO & Visibilité</h2>
            <div class="space-y-6">
              <label class="flex flex-col">
                <div class="flex justify-between items-center pb-2">
                  <p class="text-white text-sm font-medium leading-normal">Slug de la page (URL)</p>
                  <p class="text-gray-400 text-xs font-mono">/arcade-invaders</p>
                </div>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-white/20 bg-white/5 h-12 placeholder:text-gray-400 px-4 text-base font-normal leading-normal" value="arcade-invaders"/>
              </label>
              <label class="flex flex-col">
                <div class="flex justify-between items-center pb-2">
                  <p class="text-white text-sm font-medium leading-normal">Méta-Titre</p>
                  <p class="text-gray-400 text-xs">65 / 70</p>
                </div>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-white/20 bg-white/5 h-12 placeholder:text-gray-400 px-4 text-base font-normal leading-normal" value="Arcade Invaders - Salle de jeux et VR à Paris | Réservez maintenant"/>
              </label>
              <label class="flex flex-col">
                <div class="flex justify-between items-center pb-2">
                  <p class="text-white text-sm font-medium leading-normal">Méta-Description</p>
                  <p class="text-gray-400 text-xs">145 / 160</p>
                </div>
                <textarea class="form-textarea flex w-full min-w-0 flex-1 resize-y overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-white/20 bg-white/5 min-h-24 placeholder:text-gray-400 p-4 text-base font-normal leading-normal">Découvrez Arcade Invaders, la meilleure salle d'arcade de Paris. Jeux rétro, flippers, bornes d'arcade et réalité virtuelle pour toute la famille.</textarea>
              </label>
            </div>
          </section>
        </div>

        <!-- Preview Column -->
        <div class="relative">
          <div class="sticky top-28">
            <h3 class="text-white text-lg font-bold leading-tight mb-4">Aperçu en direct (WYSIWYG)</h3>
            <div class="w-full h-[70vh] max-h-[800px] rounded-xl overflow-hidden shadow-2xl shadow-black/30 border border-white/10">
              <div class="w-full h-full bg-background-dark overflow-y-auto">
                <!-- Simulated Browser Chrome -->
                <div class="flex items-center gap-2 p-3 bg-gray-900/50 border-b border-white/10">
                  <div class="size-3 rounded-full bg-red-500"></div>
                  <div class="size-3 rounded-full bg-yellow-500"></div>
                  <div class="size-3 rounded-full bg-green-500"></div>
                  <div class="ml-4 flex-1 text-sm bg-white/5 text-gray-400 rounded-md px-3 py-1">https://playzone.com/arcade-invaders</div>
                </div>
                
                <!-- Preview Content -->
                <div class="h-48 bg-cover bg-center" data-alt="Cover image of an arcade with neon lights" style="background-image: url('https://picsum.photos/seed/arcade-cover/800/400.jpg');"></div>
                <div class="p-6">
                  <h1 class="text-white text-3xl font-black tracking-tighter mb-2">Arcade Invaders</h1>
                  <div class="flex items-center gap-2 text-primary text-sm mb-4">
                    <i class="fas fa-map-marker-alt text-base"></i>
                    <span>123 Rue du Pixel, 75001 Paris</span>
                  </div>
                  <p class="text-gray-300 leading-relaxed">
                    Plongez dans un univers rétro-futuriste chez Arcade Invaders. Des classiques des années 80 aux dernières expériences VR, il y en a pour tous les goûts !
                  </p>
                  <div class="mt-6 border-t border-white/10 pt-6">
                    <h4 class="text-white text-lg font-bold mb-3">Équipements</h4>
                    <div class="flex flex-wrap gap-2">
                      <span class="bg-primary/20 text-primary text-xs font-bold px-3 py-1 rounded-full">VR Headsets</span>
                      <span class="bg-primary/20 text-primary text-xs font-bold px-3 py-1 rounded-full">Pinball</span>
                      <span class="bg-primary/20 text-primary text-xs font-bold px-3 py-1 rounded-full">Retro Consoles</span>
                      <span class="bg-primary/20 text-primary text-xs font-bold px-3 py-1 rounded-full">WiFi Gratuit</span>
                      <span class="bg-primary/20 text-primary text-xs font-bold px-3 py-1 rounded-full">Parking</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
