<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';
import GoogleMap from '../../Components/GoogleMap.vue';

const props = defineProps({
    salle: Object,
    coordinates: Object
});

const handleLocationSelected = (location) => {
    console.log('Nouvelle position sélectionnée:', location);
    // Ici vous pouvez ajouter la logique pour sauvegarder les coordonnées
};

const confirmDeleteVenue = () => {
    if (confirm('Êtes-vous sûr de vouloir supprimer votre salle ? Cette action est irréversible.')) {
        // Logique de suppression à implémenter
      console.log('Suppression de la salle...');
    }
};
</script>

<template>
  <Head title="Ma Salle" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <!-- Sidebar Component -->
    <Sidebar current-route="promoter.venues" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto transition-all duration-300">
      <div class="p-8">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
          <div>
            <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Ma Salle</p>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Gérez les informations de votre salle</p>
          </div>
        </div>

        <!-- État : Pas de salle -->
        <div v-if="!salle" class="flex flex-col items-center justify-center py-20">
          <div class="text-center max-w-md mx-auto">
            <!-- Icône -->
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-store-slash text-4xl text-gray-400 dark:text-gray-500"></i>
            </div>
            
            <!-- Message -->
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              Vous n'avez pas encore de salle
            </h2>
            <p class="text-gray-600 dark:text-gray-400 mb-8">
              Créez votre première salle pour commencer à attirer des clients dans votre espace de gaming.
            </p>
            
            <!-- Bouton d'action -->
            <Link href="/promoter/venues/create" class="inline-flex items-center gap-3 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
              <i class="fas fa-plus-circle"></i>
              <span>Ajouter ma salle maintenant</span>
            </Link>
          </div>
        </div>

        <!-- État : Salle existante -->
        <div v-else>
          <!-- Boutons d'action en haut -->
          <div class="flex items-center gap-3 mb-8">
            <button class="flex items-center justify-center gap-2 min-w-[84px] cursor-pointer overflow-hidden rounded-lg h-10 px-4 bg-white dark:bg-[#19202e] border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
              <i class="fas fa-eye"></i>
              <span class="truncate">Voir la page publique</span>
            </button>
            <Link href="/promoter/venues/edit" class="flex items-center justify-center gap-2 min-w-[84px] cursor-pointer overflow-hidden rounded-lg h-10 px-4 bg-brand-red text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-brand-red/90 transition-colors">
              <i class="fas fa-edit"></i>
              <span class="truncate">Modifier</span>
            </Link>
            <button @click="confirmDeleteVenue" class="flex items-center justify-center gap-2 min-w-[84px] cursor-pointer overflow-hidden rounded-lg h-10 px-4 bg-red-600 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-red-700 transition-colors">
              <i class="fas fa-trash"></i>
              <span class="truncate">Supprimer</span>
            </button>
          </div>

        <!-- Venue Status Alert -->
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 mb-8">
          <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
            <div>
              <h3 class="font-semibold text-green-800 dark:text-green-200">Salle active</h3>
              <p class="text-green-700 dark:text-green-300 text-sm">Votre salle est visible par les utilisateurs et peut recevoir des réservations</p>
            </div>
          </div>
        </div>

        <!-- Venue Main Card -->
        <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden mb-8">
          <!-- Cover Image -->
          <div class="h-64 bg-cover bg-center relative" style="background-image: url('https://picsum.photos/seed/cyberzone/1200/400.jpg');">
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
            <div class="absolute bottom-6 left-6 right-6">
              <div class="flex items-end justify-between">
                <div class="flex items-end gap-4">
                  <div class="w-24 h-24 rounded-xl border-4 border-white dark:border-[#19202e] bg-cover bg-center shadow-lg" style="background-image: url('https://picsum.photos/seed/logo/200/200.jpg');"></div>
                  <div class="text-white">
                    <h1 class="text-3xl font-black mb-1">{{ salle.nom }}</h1>
                    <div class="flex items-center gap-4 text-sm">
                      <div class="flex items-center gap-1">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ salle.ville }}, {{ salle.pays }}</span>
                      </div>
                      <div class="flex items-center gap-1">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span>{{ salle.note }} ({{ salle.nombre_avis }} avis)</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="flex gap-2">
                  <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">Active</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="border-b border-gray-200 dark:border-gray-800 p-4">
            <div class="flex flex-wrap gap-3">
              <Link href="/promoter/venues/edit" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-500 transition-colors">
                <i class="fas fa-edit"></i>
                <span>Modifier les informations</span>
              </Link>
              <button class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-camera"></i>
                <span>Gérer les photos</span>
              </button>
              <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-cog"></i>
                <span>Paramètres avancés</span>
              </button>
              <button class="flex items-center gap-2 px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" onclick="confirmDeleteVenue()">
                <i class="fas fa-trash"></i>
                <span>Supprimer la salle</span>
              </button>
            </div>
          </div>

          <!-- Venue Info -->
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <i class="fas fa-users text-2xl text-primary mb-2"></i>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ salle.capacite }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Capacité</p>
              </div>
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <i class="fas fa-euro-sign text-2xl text-primary mb-2"></i>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ salle.prix_heure }}€</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Prix/heure</p>
              </div>
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <i class="fas fa-wifi text-2xl text-primary mb-2"></i>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ salle.wifi ? 'Oui' : 'Non' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">WiFi</p>
              </div>
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <i class="fas fa-car text-2xl text-primary mb-2"></i>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ salle.parking ? 'Oui' : 'Non' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Parking</p>
              </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">12</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Événements</p>
              </div>
            </div>

            <div class="mb-6">
              <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Description</h3>
              <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                {{ salle.description }}
              </p>
            </div>

            <div class="mb-6">
              <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Équipements</h3>
              <div class="flex flex-wrap gap-2">
                <span class="bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-300 text-sm font-medium px-3 py-1 rounded-full">Bornes d'arcade</span>
                <span class="bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-300 text-sm font-medium px-3 py-1 rounded-full">VR Headsets</span>
                <span class="bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-300 text-sm font-medium px-3 py-1 rounded-full">Flippers</span>
                <span class="bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-300 text-sm font-medium px-3 py-1 rounded-full">Consoles rétro</span>
                <span class="bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-300 text-sm font-medium px-3 py-1 rounded-full">PC Gaming</span>
                <span class="bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-300 text-sm font-medium px-3 py-1 rounded-full">WiFi Gratuit</span>
                <span class="bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-300 text-sm font-medium px-3 py-1 rounded-full">Parking</span>
                <span class="bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-300 text-sm font-medium px-3 py-1 rounded-full">Snack bar</span>
              </div>
            </div>

            <div>
              <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Horaires d'ouverture</h3>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Lun-Ven</span>
                  <span class="font-medium text-gray-900 dark:text-white">10:00 - 22:00</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Samedi</span>
                  <span class="font-medium text-gray-900 dark:text-white">10:00 - 23:00</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Dimanche</span>
                  <span class="font-medium text-gray-900 dark:text-white">12:00 - 20:00</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Gallery Section -->
        <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6 mb-8">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Galerie Photos</h3>
            <button class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
              <i class="fas fa-camera"></i>
              <span>Modifier les photos</span>
            </button>
          </div>
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="aspect-square rounded-lg overflow-hidden group cursor-pointer relative">
              <img src="https://picsum.photos/seed/gallery1/400/400.jpg" alt="Photo 1" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <i class="fas fa-search-plus text-white text-xl"></i>
              </div>
            </div>
            <div class="aspect-square rounded-lg overflow-hidden group cursor-pointer relative">
              <img src="https://picsum.photos/seed/gallery2/400/400.jpg" alt="Photo 2" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <i class="fas fa-search-plus text-white text-xl"></i>
              </div>
            </div>
            <div class="aspect-square rounded-lg overflow-hidden group cursor-pointer relative">
              <img src="https://picsum.photos/seed/gallery3/400/400.jpg" alt="Photo 3" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <i class="fas fa-search-plus text-white text-xl"></i>
              </div>
            </div>
            <div class="aspect-square rounded-lg overflow-hidden group cursor-pointer relative">
              <img src="https://picsum.photos/seed/gallery4/400/400.jpg" alt="Photo 4" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <i class="fas fa-search-plus text-white text-xl"></i>
              </div>
            </div>
            <div class="aspect-square rounded-lg overflow-hidden group cursor-pointer relative">
              <img src="https://picsum.photos/seed/gallery5/400/400.jpg" alt="Photo 5" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <i class="fas fa-search-plus text-white text-xl"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Google Maps Location -->
        <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6 mb-8">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Localisation</h3>
            <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
              <i class="fas fa-map-marker-alt"></i>
              <span>Modifier la position</span>
            </button>
          </div>
          
          <!-- Adresse actuelle -->
          <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="flex items-start gap-3">
              <i class="fas fa-map-marker-alt text-red-500 mt-1"></i>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Adresse de la salle</h4>
                <p class="text-gray-600 dark:text-gray-400 mb-1">
                  123 Rue du Gaming, Dakar, Sénégal
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  <i class="fas fa-phone mr-2"></i>+221 33 123 45 67
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  <i class="fas fa-envelope mr-2"></i>contact@cyberzone.sn
                </p>
              </div>
            </div>
          </div>

          <!-- Carte Google Maps -->
          <div class="mb-4">
            <GoogleMap 
              :initial-lat="coordinates?.lat || 14.6928"
              :initial-lng="coordinates?.lng || -17.4467"
              height="500px"
              :readonly="true"
              @location-selected="handleLocationSelected"
            />
          </div>

          <!-- Instructions -->
          <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start gap-3">
              <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5"></i>
              <div>
                <h4 class="font-semibold text-blue-800 dark:text-blue-200 mb-1">Point de repère</h4>
                <p class="text-blue-700 dark:text-blue-300 text-sm">
                  Près du marché Sandaga, à côté du centre commercial Sea Plaza
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-blue-600 dark:text-blue-400"></i>
              </div>
              <h3 class="font-semibold text-gray-900 dark:text-white">Statistiques</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Consultez les performances de votre salle</p>
            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
              Voir les stats
            </button>
          </div>

          <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-images text-purple-600 dark:text-purple-400"></i>
              </div>
              <h3 class="font-semibold text-gray-900 dark:text-white">Galerie</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Ajoutez des photos pour attirer plus de visiteurs</p>
            <button class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
              Gérer les photos
            </button>
          </div>

          <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-share-alt text-green-600 dark:text-green-400"></i>
              </div>
              <h3 class="font-semibold text-gray-900 dark:text-white">Partager</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Partagez votre salle sur les réseaux sociaux</p>
            <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
              Partager la salle
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  methods: {
    confirmDeleteVenue() {
      if (confirm('Êtes-vous sûr de vouloir supprimer votre salle ? Cette action est irréversible et supprimera également tous les événements associés.')) {
        // Ici vous pouvez ajouter la logique de suppression
        // Par exemple, faire une requête API ou rediriger vers la route de suppression
        alert('Fonctionnalité de suppression à implémenter');
      }
    }
  }
}
</script>
