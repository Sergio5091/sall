<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

// État du formulaire
const eventForm = ref({
    titre: props.event.titre || '',
    description: props.event.description || '',
    type: props.event.type || 'offline',
    categorie: props.event.categorie || 'tournoi',
    date_debut: props.event.date_debut ? new Date(props.event.date_debut).toISOString().slice(0, 16) : '',
    date_fin: props.event.date_fin ? new Date(props.event.date_fin).toISOString().slice(0, 16) : '',
    lieu: props.event.lieu || '',
    adresse: props.event.adresse || '',
    ville: props.event.ville || 'Dakar',
    pays: props.event.pays || 'Sénégal',
    capacite_max: props.event.capacite_max || '100',
    prix_base: props.event.prix_base || '15.00',
    devise: props.event.devise || 'XOF',
    gratuit: props.event.gratuit || false,
    limite_inscription: props.event.limite_inscription || true,
    visibilite: props.event.visibilite || 'public',
    contact_email: props.event.contact_email || '',
    contact_telephone: props.event.contact_telephone || '',
    site_web: props.event.site_web || '',
    services: props.event.services || {},
    statut: props.event.statut || 'brouillon',
    image_banniere: null
});

// Mettre à jour l'événement
const updateEvent = () => {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    
    // Ajouter tous les champs
    Object.keys(eventForm.value).forEach(key => {
        if (key === 'gratuit' || key === 'limite_inscription') {
            formData.append(key, eventForm.value[key] ? '1' : '0');
        } else if (key === 'services') {
            formData.append(key, JSON.stringify(eventForm.value[key]));
        } else if (key !== 'image_banniere') {
            formData.append(key, eventForm.value[key]);
        }
    });
    
    // Ajouter l'image si présente
    if (eventForm.value.image_banniere) {
        formData.append('image_banniere', eventForm.value.image_banniere);
    }

    router.post(`/promoter/events/${props.event.id}`, formData, {
        onSuccess: () => {
            window.location.href = '/promoter/events';
        },
        onError: (errors) => {
            console.error('Erreurs:', errors);
        }
    });
};

// Gestion des fichiers
const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        eventForm.value.image_banniere = file;
    }
};
</script>

<template>
  <Head title="Modifier l'événement" />
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <Sidebar current-route="promoter.events" />
    
    <main class="flex-1 overflow-y-auto transition-all duration-300">
      <div class="p-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Modifier l'événement</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Mettez à jour les informations de votre événement</p>
          </div>
          <Link href="/promoter/events" class="flex items-center gap-2 px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Retour</span>
          </Link>
        </div>

        <!-- Formulaire -->
        <div class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-8">
          <form @submit.prevent="updateEvent" class="space-y-6">
            <!-- Informations de base -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nom de l'événement *</label>
                <input v-model="eventForm.titre" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="Tournoi FIFA 2025">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catégorie *</label>
                <select v-model="eventForm.categorie" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]">
                  <option value="tournoi">Tournoi de gaming</option>
                  <option value="soiree">Soirée gaming</option>
                  <option value="atelier">Atelier/Formation</option>
                  <option value="lancement">Lancement produit</option>
                  <option value="festival">Festival gaming</option>
                  <option value="conference">Conférence</option>
                  <option value="formation">Formation</option>
                  <option value="meetup">Meetup</option>
                  <option value="competition">Compétition</option>
                  <option value="autre">Autre</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type *</label>
                <select v-model="eventForm.type" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]">
                  <option value="online">En ligne</option>
                  <option value="offline">Sur place</option>
                  <option value="hybride">Hybride</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
              <textarea v-model="eventForm.description" required rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="Décrivez votre événement..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date de début *</label>
                <input v-model="eventForm.date_debut" type="datetime-local" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date de fin *</label>
                <input v-model="eventForm.date_fin" type="datetime-local" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]">
              </div>
            </div>

            <!-- Lieu -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lieu *</label>
                <input v-model="eventForm.lieu" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="Cyber Café Dakar">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adresse *</label>
                <input v-model="eventForm.adresse" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="Rue 123, Plateau">
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ville *</label>
                <input v-model="eventForm.ville" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="Dakar">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pays *</label>
                <input v-model="eventForm.pays" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="Sénégal">
              </div>
            </div>

            <!-- Tarifs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prix (FCFA) *</label>
                <input v-model="eventForm.prix_base" type="number" step="0.01" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="5000">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Capacité maximale *</label>
                <input v-model="eventForm.capacite_max" type="number" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="50">
              </div>
            </div>

            <div class="flex items-center space-x-4">
              <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" v-model="eventForm.gratuit" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="text-sm text-gray-700 dark:text-gray-300">Événement gratuit</span>
              </label>
            </div>

            <!-- Contact -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                <input v-model="eventForm.contact_email" type="email" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="contact@example.com">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Téléphone</label>
                <input v-model="eventForm.contact_telephone" type="tel" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="+221 33 123 45 67">
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site web</label>
              <input v-model="eventForm.site_web" type="url" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]" placeholder="https://example.com">
            </div>

            <!-- Image -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Image de bannière</label>
              <div v-if="props.event.image_banniere" class="mb-4">
                <img :src="`/storage/events/bannieres/${props.event.image_banniere}`" alt="Image actuelle" class="h-32 object-cover rounded">
                <p class="text-sm text-gray-500 mt-2">Image actuelle</p>
              </div>
              <input type="file" @change="handleImageUpload" accept="image/*" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#1a1f2e]">
            </div>

            <!-- Boutons -->
            <div class="flex justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
              <Link href="/promoter/events" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                Annuler
              </Link>
              <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Mettre à jour
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>
