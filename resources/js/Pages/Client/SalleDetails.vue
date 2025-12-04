<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    salle: Object,
    sallesSimilaires: Array,
    aReserve: Boolean
});

const form = ref({
    date_heure: '',
    duree: 1,
    nombre_personnes: 1,
    message: ''
});

// Formater le prix
const formatPrice = (prix) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(prix);
};

// Formater la capacité
const formatCapacity = (capacite) => {
    return new Intl.NumberFormat('fr-FR').format(capacite);
};

// Calculer le prix total
const prixTotal = ref(0);

watch(() => form.value.duree, (newDuree) => {
    prixTotal.value = props.salle.prix_heure * newDuree;
});

// Soumettre la réservation
const reserver = () => {
    const formData = new FormData();
    formData.append('date_heure', form.value.date_heure);
    formData.append('duree', form.value.duree);
    formData.append('nombre_personnes', form.value.nombre_personnes);
    formData.append('message', form.value.message);

    router.post(`/client/salles/${props.salle.id}/reserver`, formData, {
        onSuccess: () => {
            // Rediriger vers la page de détails avec un message de succès
            router.reload();
        },
        onError: (errors) => {
            console.error('Erreurs de réservation:', errors);
        }
    });
};
</script>

<template>
  <Head :title="`${salle.nom} - GameOn`" />
  
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Client -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <Link href="/client/dashboard" class="flex items-center gap-2">
              <i class="fas fa-gamepad text-2xl text-red-600"></i>
              <span class="text-xl font-bold text-gray-900 dark:text-white">GameOn</span>
            </Link>
          </div>
          
          <nav class="hidden md:flex space-x-8">
            <Link href="/client/dashboard" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 px-3 py-2 text-sm font-medium">
              Tableau de bord
            </Link>
            <Link href="/client/salles" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 px-3 py-2 text-sm font-medium">
              Salles
            </Link>
            <Link href="/client/profile" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 px-3 py-2 text-sm font-medium">
              Profil
            </Link>
          </nav>

          <div class="flex items-center space-x-4">
            <Link href="/logout" method="post" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 px-3 py-2 text-sm font-medium">
              Déconnexion
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Breadcrumb -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex">
          <Link href="/client/salles" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
            Salles
          </Link>
          <span class="mx-2 text-gray-500">/</span>
          <span class="text-gray-900 dark:text-white">{{ salle.nom }}</span>
        </nav>
      </div>
    </div>

    <!-- Contenu principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2">
          <!-- Image principale -->
          <div class="h-96 bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center mb-8">
            <i class="fas fa-gamepad text-8xl text-white/50"></i>
          </div>

          <!-- Informations de la salle -->
          <div class="bg-white dark:bg-gray-800 rounded-xl p-6 mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ salle.nom }}</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="flex items-center text-gray-600 dark:text-gray-400">
                <i class="fas fa-map-marker-alt mr-3 text-red-600"></i>
                <div>
                  <div class="font-medium">{{ salle.ville }}, {{ salle.pays }}</div>
                  <div class="text-sm">{{ salle.adresse }}</div>
                </div>
              </div>
              
              <div class="flex items-center text-gray-600 dark:text-gray-400">
                <i class="fas fa-user mr-3 text-red-600"></i>
                <div>
                  <div class="font-medium">Propriétaire</div>
                  <div class="text-sm">{{ salle.promoter?.name || 'Non spécifié' }}</div>
                </div>
              </div>
            </div>

            <div class="prose max-w-none text-gray-600 dark:text-gray-400 mb-8">
              <p>{{ salle.description || 'Salle de gaming moderne équipée du meilleur matériel pour vos sessions de gaming.' }}</p>
            </div>

            <!-- Équipements et caractéristiques -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <i class="fas fa-users text-2xl text-red-600 mb-2"></i>
                <div class="font-medium text-gray-900 dark:text-white">{{ formatCapacity(salle.capacite_max) }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Places</div>
              </div>
              
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <i class="fas fa-wifi text-2xl text-red-600 mb-2"></i>
                <div class="font-medium text-gray-900 dark:text-white">WiFi</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Inclus</div>
              </div>
              
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <i class="fas fa-parking text-2xl text-red-600 mb-2"></i>
                <div class="font-medium text-gray-900 dark:text-white">Parking</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Disponible</div>
              </div>
              
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <i class="fas fa-shield-alt text-2xl text-red-600 mb-2"></i>
                <div class="font-medium text-gray-900 dark:text-white">Sécurisé</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">24/7</div>
              </div>
            </div>

            <!-- Événements à venir -->
            <div v-if="salle.evenements && salle.evenements.length > 0">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Événements à venir</h3>
              <div class="space-y-4">
                <div 
                  v-for="event in salle.evenements" 
                  :key="event.id"
                  class="border border-gray-200 dark:border-gray-700 rounded-lg p-4"
                >
                  <div class="flex justify-between items-start">
                    <div>
                      <h4 class="font-medium text-gray-900 dark:text-white">{{ event.titre }}</h4>
                      <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ event.description }}</p>
                      <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        {{ new Date(event.date_debut).toLocaleDateString('fr-FR') }}
                      </div>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                      {{ event.statut }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Salles similaires -->
          <div v-if="sallesSimilaires && sallesSimilaires.length > 0">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Salles similaires</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div 
                v-for="salleSimilaire in sallesSimilaires" 
                :key="salleSimilaire.id"
                class="bg-white dark:bg-gray-800 rounded-lg p-4 hover:shadow-lg transition-shadow"
              >
                <div class="h-32 bg-gradient-to-br from-red-500 to-red-700 rounded-lg mb-4 flex items-center justify-center">
                  <i class="fas fa-gamepad text-3xl text-white/50"></i>
                </div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-2">{{ salleSimilaire.nom }}</h4>
                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ salleSimilaire.ville }}</div>
                <div class="flex justify-between items-center">
                  <span class="text-red-600 font-bold">{{ formatPrice(salleSimilaire.prix_heure) }}/h</span>
                  <Link 
                    :href="`/client/salles/${salleSimilaire.id}`"
                    class="text-red-600 hover:text-red-700 text-sm font-medium"
                  >
                    Voir
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
          <!-- Carte de réservation -->
          <div class="bg-white dark:bg-gray-800 rounded-xl p-6 sticky top-6">
            <div class="mb-6">
              <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ formatPrice(salle.prix_heure) }}</div>
              <div class="text-gray-500 dark:text-gray-400">par heure</div>
            </div>

            <div v-if="aReserve" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
              <div class="flex items-center text-green-800 dark:text-green-400">
                <i class="fas fa-check-circle mr-2"></i>
                <span>Vous avez déjà réservé cette salle</span>
              </div>
            </div>

            <form v-else @submit.prevent="reserver" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Date et heure
                </label>
                <input 
                  v-model="form.date_heure" 
                  type="datetime-local" 
                  required
                  :min="new Date().toISOString().slice(0, 16)"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                >
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Durée (heures)
                </label>
                <select 
                  v-model="form.duree" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                >
                  <option :value="1">1 heure</option>
                  <option :value="2">2 heures</option>
                  <option :value="3">3 heures</option>
                  <option :value="4">4 heures</option>
                  <option :value="5">5 heures</option>
                  <option :value="6">6 heures</option>
                  <option :value="7">7 heures</option>
                  <option :value="8">8 heures</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Nombre de personnes
                </label>
                <input 
                  v-model="form.nombre_personnes" 
                  type="number" 
                  min="1" 
                  :max="salle.capacite_max"
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                >
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Message (optionnel)
                </label>
                <textarea 
                  v-model="form.message" 
                  rows="3"
                  placeholder="Informations supplémentaires..."
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                ></textarea>
              </div>

              <!-- Prix total -->
              <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex justify-between items-center mb-4">
                  <span class="text-lg font-medium text-gray-900 dark:text-white">Total</span>
                  <span class="text-2xl font-bold text-red-600 dark:text-red-400">{{ formatPrice(prixTotal) }}</span>
                </div>
              </div>

              <button 
                type="submit"
                class="w-full bg-red-600 text-white py-3 rounded-lg font-medium hover:bg-red-700 transition-colors"
              >
                Réserver maintenant
              </button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
