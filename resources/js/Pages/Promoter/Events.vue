<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const props = defineProps({
    events: {
        type: Object,
        default: () => ({ data: [] })
    },
    stats: {
        type: Object,
        default: () => ({
            total: 0,
            publies: 0,
            brouillons: 0,
            avenir: 0,
            en_cours: 0,
            passes: 0
        })
    }
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getStatutColor = (statut) => {
    const colors = {
        'brouillon': 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
        'publie': 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-300',
        'annule': 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-300',
        'termine': 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-300'
    };
    return colors[statut] || 'bg-gray-100 text-gray-800';
};

const getCategorieIcon = (categorie) => {
    const icons = {
        'tournoi': 'fas fa-trophy',
        'soiree': 'fas fa-glass-cheers',
        'atelier': 'fas fa-chalkboard-teacher',
        'lancement': 'fas fa-rocket',
        'festival': 'fas fa-music',
        'conference': 'fas fa-microphone',
        'formation': 'fas fa-graduation-cap',
        'meetup': 'fas fa-users',
        'competition': 'fas fa-medal',
        'autre': 'fas fa-calendar'
    };
    return icons[categorie] || 'fas fa-calendar';
};

const getTypeIcon = (type) => {
    const icons = {
        'online': 'fas fa-laptop',
        'offline': 'fas fa-map-marker-alt',
        'hybride': 'fas fa-globe'
    };
    return icons[type] || 'fas fa-calendar';
};

const estPasse = (dateFin) => {
    return dateFin ? new Date(dateFin) < new Date() : false;
};

const estEnCours = (dateDebut, dateFin) => {
    if (!dateDebut || !dateFin) return false;
    const now = new Date();
    return new Date(dateDebut) <= now && new Date(dateFin) >= now;
};

// Helper pour accéder aux propriétés en toute sécurité
const getEventProp = (event, prop, defaultValue = '') => {
    return event && event[prop] !== undefined ? event[prop] : defaultValue;
};
</script>

<template>
  <Head title="Événements" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <!-- Sidebar Component -->
    <Sidebar current-route="promoter.events" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto transition-all duration-300">
      <div class="p-8">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
          <div>
            <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Événements</p>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Gérez vos événements et suivez leurs performances</p>
          </div>
          <Link href="/promoter/events/create" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus-circle"></i>
            <span>Créer un événement</span>
          </Link>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar text-blue-600 dark:text-blue-400 text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Total événements</p>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-eye text-green-600 dark:text-green-400 text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.publies }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Publiés</p>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-orange-600 dark:text-orange-400 text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.avenir }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">À venir</p>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-play-circle text-purple-600 dark:text-purple-400 text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.en_cours }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">En cours</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Liste des événements -->
        <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
          <div class="p-6 border-b border-gray-200 dark:border-gray-800">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Vos événements</h2>
          </div>

          <!-- État vide -->
          <div v-if="!events.data || events.data.length === 0" class="p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-calendar-times text-gray-400 dark:text-gray-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Aucun événement</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Commencez par créer votre premier événement</p>
            <Link href="/promoter/events/create" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
              <i class="fas fa-plus-circle"></i>
              Créer un événement
            </Link>
          </div>

          <!-- Liste des événements -->
          <div v-else class="divide-y divide-gray-200 dark:divide-gray-800">
            <div v-for="event in events.data" :key="event.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
              <!-- Bannière de l'événement -->
              <div v-if="event.image_banniere_url" class="h-48 bg-cover bg-center relative" :style="`background-image: url('${event.image_banniere_url}')`">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                <div class="absolute bottom-4 left-6 right-6">
                  <div class="flex items-center gap-3">
                    <h3 class="text-2xl font-bold text-white truncate">{{ getEventProp(event, 'titre', 'Sans titre') }}</h3>
                    <span :class="getStatutColor(event.statut)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                      {{ getEventProp(event, 'statut_texte', event.statut || 'Inconnu') }}
                    </span>
                  </div>
                </div>
              </div>
              
              <div class="p-6" :class="{ 'pt-4': event.image_banniere_url }">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                  <!-- Informations principales -->
                  <div class="flex-1">
                    <div class="flex items-start gap-4">
                      <!-- Image (affichée seulement si pas de bannière) -->
                      <div v-if="!event.image_banniere_url" class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-800">
                        <img v-if="event.image_affiche_url" :src="event.image_affiche_url" :alt="event.titre" class="w-full h-full object-cover">
                        <div v-else class="w-full h-full flex items-center justify-center">
                          <i :class="getCategorieIcon(event.categorie)" class="text-gray-400 dark:text-gray-500 text-2xl"></i>
                        </div>
                      </div>

                      <!-- Détails -->
                      <div class="flex-1 min-w-0">
                        <!-- Titre et statut (affichés seulement si pas de bannière) -->
                        <div v-if="!event.image_banniere_url" class="flex items-center gap-3 mb-2">
                          <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">{{ getEventProp(event, 'titre', 'Sans titre') }}</h3>
                          <span :class="getStatutColor(event.statut)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                            {{ getEventProp(event, 'statut_texte', event.statut || 'Inconnu') }}
                          </span>
                        </div>

                      <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <div class="flex items-center gap-1">
                          <i :class="getCategorieIcon(event.categorie)" class="text-xs"></i>
                          <span>{{ getEventProp(event, 'categorie_texte', event.categorie || 'Non défini') }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                          <i :class="getTypeIcon(event.type)" class="text-xs"></i>
                          <span>{{ getEventProp(event, 'type_texte', event.type || 'Non défini') }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                          <i class="fas fa-map-marker-alt text-xs"></i>
                          <span>{{ getEventProp(event, 'salle', {}).nom || 'Salle non définie' }}</span>
                        </div>
                      </div>

                      <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center gap-1" v-if="event.date_debut">
                          <i class="fas fa-calendar text-xs"></i>
                          <span>{{ formatDate(event.date_debut) }}</span>
                        </div>
                        <div v-if="!event.gratuit && event.prix_formatte" class="flex items-center gap-1">
                          <i class="fas fa-tag text-xs"></i>
                          <span>{{ event.prix_formatte }}</span>
                        </div>
                        <div v-else class="flex items-center gap-1">
                          <i class="fas fa-gift text-xs"></i>
                          <span>Gratuit</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                  <Link :href="`/promoter/events/${event.id}`" class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Voir">
                    <i class="fas fa-eye"></i>
                  </Link>
                  <Link :href="`/promoter/events/${event.id}/edit`" class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Modifier">
                    <i class="fas fa-edit"></i>
                  </Link>
                  <button 
                    v-if="event.statut === 'publie'"
                    @click="confirmCancel(event)"
                    class="p-2 text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" 
                    title="Annuler"
                  >
                    <i class="fas fa-times-circle"></i>
                  </button>
                  <button 
                    v-else-if="event.statut === 'brouillon'"
                    @click="publish(event)"
                    class="p-2 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors" 
                    title="Publier"
                  >
                    <i class="fas fa-check-circle"></i>
                  </button>
                </div>
              </div>

              <!-- Indicateurs visuels -->
              <div class="mt-4 flex flex-wrap gap-2">
                <span v-if="estEnCours(event.date_debut, event.date_fin)" class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 rounded text-xs">
                  <i class="fas fa-play-circle text-xs"></i>
                  En cours
                </span>
                <span v-else-if="estPasse(event.date_fin)" class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300 rounded text-xs">
                  <i class="fas fa-check-circle text-xs"></i>
                  Terminé
                </span>
                <span v-else class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded text-xs">
                  <i class="fas fa-clock text-xs"></i>
                  À venir
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
          <div v-if="events.data && events.data.length > 0" class="p-6 border-t border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-600 dark:text-gray-400">
                Affichage de {{ events.from || 0 }} à {{ events.to || 0 }} sur {{ events.total || 0 }} événements
              </div>
              <div class="flex gap-2">
                <Link 
                  v-if="events.prev_page_url" 
                  :href="events.prev_page_url" 
                  class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-700 rounded hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                >
                  Précédent
                </Link>
                <Link 
                  v-if="events.next_page_url" 
                  :href="events.next_page_url" 
                  class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-700 rounded hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                >
                  Suivant
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
