<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const props = defineProps({
    venue: Object
});
</script>

<template>
  <Head title="Ajouter une Salle" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="flex h-screen bg-gray-50">
    <Sidebar current-route="promoter.venues" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto lg:ml-64">
      <div class="sticky top-0 z-10 bg-white/80 dark:bg-[#19202e]/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 px-8 py-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div class="flex min-w-72 flex-col gap-1">
            <p class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Ajouter une nouvelle salle</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm font-normal leading-normal">Configurez les informations de votre nouvelle salle</p>
          </div>
          <div class="flex items-center gap-3">
            <Link href="/promoter/venues" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-white dark:bg-[#19202e] border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
              <span class="truncate">Annuler</span>
            </Link>
            <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-blue-500 transition-colors">
              <span class="truncate">Créer la salle</span>
            </button>
          </div>
        </div>
      </div>
      
      <div class="p-8">
        <form class="max-w-4xl mx-auto space-y-8">
          <!-- Basic Information -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">Informations de base</h2>
            
            <div class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nom de la salle *</label>
                  <input type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Ex: CyberZone Arena">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type de salle *</label>
                  <select class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                    <option>Sélectionnez un type</option>
                    <option>Salle d'arcade</option>
                    <option>Centre VR</option>
                    <option>Salle e-sport</option>
                    <option>Salle rétro gaming</option>
                    <option>Mixte</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Décrivez votre salle, les équipements, l'ambiance..."></textarea>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adresse *</label>
                  <input type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="123 Rue du Gaming">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Code postal *</label>
                  <input type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="75001">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ville *</label>
                  <input type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Paris">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Téléphone</label>
                  <input type="tel" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="01 23 45 67 89">
                </div>
              </div>
            </div>
          </section>

          <!-- Capacity & Equipment -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">Capacité et Équipements</h2>
            
            <div class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Capacité maximale *</label>
                  <input type="number" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="250">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Surface (m²)</label>
                  <input type="number" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="500">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre de bornes</label>
                  <input type="number" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="50">
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Équipements disponibles</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="rounded text-primary focus:ring-primary/50">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Bornes d'arcade</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="rounded text-primary focus:ring-primary/50">
                    <span class="text-sm text-gray-700 dark:text-gray-300">VR Headsets</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="rounded text-primary focus:ring-primary/50">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Flippers</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="rounded text-primary focus:ring-primary/50">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Consoles rétro</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="rounded text-primary focus:ring-primary/50">
                    <span class="text-sm text-gray-700 dark:text-gray-300">PC Gaming</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="rounded text-primary focus:ring-primary/50">
                    <span class="text-sm text-gray-700 dark:text-gray-300">WiFi</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="rounded text-primary focus:ring-primary/50">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Parking</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="rounded text-primary focus:ring-primary/50">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Snack bar</span>
                  </label>
                </div>
              </div>
            </div>
          </section>

          <!-- Opening Hours -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">Horaires d'ouverture</h2>
            
            <div class="space-y-4">
              <div class="grid grid-cols-7 gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                <div>Lundi</div>
                <div>Mardi</div>
                <div>Mercredi</div>
                <div>Jeudi</div>
                <div>Vendredi</div>
                <div>Samedi</div>
                <div>Dimanche</div>
              </div>
              <div class="grid grid-cols-7 gap-2">
                <input type="text" class="px-2 py-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-[#19202e] text-gray-900 dark:text-white text-sm" placeholder="10:00-22:00">
                <input type="text" class="px-2 py-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-[#19202e] text-gray-900 dark:text-white text-sm" placeholder="10:00-22:00">
                <input type="text" class="px-2 py-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-[#19202e] text-gray-900 dark:text-white text-sm" placeholder="10:00-22:00">
                <input type="text" class="px-2 py-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-[#19202e] text-gray-900 dark:text-white text-sm" placeholder="10:00-22:00">
                <input type="text" class="px-2 py-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-[#19202e] text-gray-900 dark:text-white text-sm" placeholder="10:00-23:00">
                <input type="text" class="px-2 py-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-[#19202e] text-gray-900 dark:text-white text-sm" placeholder="10:00-23:00">
                <input type="text" class="px-2 py-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-[#19202e] text-gray-900 dark:text-white text-sm" placeholder="12:00-20:00">
              </div>
            </div>
          </section>

          <!-- Images -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">Images</h2>
            
            <div class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Photo de couverture</label>
                <div class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 dark:border-gray-700 border-dashed rounded-lg bg-gray-50 dark:bg-gray-800 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                  <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Cliquez pour upload ou glissez-déposez</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG jusqu'à 10MB</p>
                  </div>
                  <input type="file" class="hidden" accept="image/*">
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Galerie d'images</label>
                <div class="grid grid-cols-4 gap-4">
                  <div class="aspect-square border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                    <i class="fas fa-plus text-gray-400"></i>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- SEO Settings -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">Paramètres SEO</h2>
            
            <div class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">URL personnalisée</label>
                <div class="flex">
                  <span class="inline-flex items-center px-3 text-sm text-gray-900 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 border border-r-0 border-gray-300 dark:border-gray-700 rounded-l-lg">
                    youpihub.com/
                  </span>
                  <input type="text" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-r-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="cyberzone-arena">
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meta titre</label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="CyberZone Arena - Salle d'arcade à Paris">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maximum 70 caractères</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meta description</label>
                <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Découvrez CyberZone Arena, la meilleure salle d'arcade de Paris avec 50 bornes modernes et espace VR..."></textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maximum 160 caractères</p>
              </div>
            </div>
          </section>

          <!-- Actions -->
          <div class="flex justify-end gap-4">
            <Link href="/promoter/venues" class="px-6 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
              Annuler
            </Link>
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-500 transition-colors">
              Créer la salle
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>
