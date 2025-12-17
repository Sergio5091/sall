<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '../../Components/Promoter/Sidebar.vue';
import GoogleMap from '../../Components/GoogleMap.vue';

const props = defineProps({
    defaultCoordinates: Object,
    paysAfricains: Object,
    typesSalle: Object,
    categoriesSalle: Object
});

const selectedLocation = ref({
    lat: props.defaultCoordinates?.lat || 14.6928,
    lng: props.defaultCoordinates?.lng || -17.4467
});

const formData = ref({
    // Informations de base
    nom: '',
    description: '',
    type: 'arcade',
    categorie: 'espace_jeux',
    
    // Adresse
    adresse: '',
    code_postal: '',
    ville: 'Dakar',
    pays: 'Sénégal',
    region: '',
    departement: '',
    quartier: '',
    
    // Contact
    telephone: '',
    whatsapp: '',
    email: '',
    site_web: '',
    reseaux_sociaux: {
        facebook: '',
        instagram: '',
        twitter: '',
        tiktok: ''
    },
    
    // Capacité
    capacite: 50,
    surface_area: null,
    
    // Équipements
    machines_arcade: 0,
    casques_vr: 0,
    flippers: 0,
    consoles_retro: 0,
    pc_gaming: 0,
    tables_bowling: 0,
    tables_billard: 0,
    
    // Services
    wifi_gratuit: false,
    parking: false,
    climatisation: false,
    accessibilite_pmr: false,
    surveillance_24h: false,
    snack_bar: false,
    restaurant: false,
    bar: false,
    terrasse: false,
    espace_fumeur: false,
    vestiaires: false,
    
    // Horaires
    horaires_ouverture: {
        lundi: '10:00-22:00',
        mardi: '10:00-22:00',
        mercredi: '10:00-22:00',
        jeudi: '10:00-22:00',
        vendredi: '10:00-23:00',
        samedi: '10:00-23:00',
        dimanche: '12:00-20:00'
    },
    
    // Médias
    image_couverture: '',
    images_galerie: [],
    video_presentation: '',
    
    // SEO
    meta_titre: '',
    meta_description: '',
    mots_cles: [],
    
    // Point de repère
    point_repere: ''
});

// Données par défaut si non fournies par le contrôleur
const defaultData = {
    defaultCoordinates: {
        lat: 14.6928,
        lng: -17.4467
    },
    paysAfricains: {
        'SN': 'Sénégal',
        'CI': 'Côte d\'Ivoire',
        'ML': 'Mali',
        'BF': 'Burkina Faso',
        'NE': 'Niger',
        'TG': 'Togo',
        'BJ': 'Bénin',
        'GN': 'Guinée',
        'GW': 'Guinée-Bissau',
        'SL': 'Sierra Leone',
        'LR': 'Libéria',
        'GH': 'Ghana',
        'NG': 'Nigeria',
        'CM': 'Cameroun',
        'TD': 'Tchad',
        'CF': 'Centrafrique',
        'CG': 'Congo-Brazzaville',
        'CD': 'Congo-Kinshasa',
        'GA': 'Gabon',
        'GQ': 'Guinée Équatoriale',
        'AO': 'Angola',
        'ZM': 'Zambie',
        'MW': 'Malawi',
        'MZ': 'Mozambique',
        'ZW': 'Zimbabwe',
        'BW': 'Botswana',
        'ZA': 'Afrique du Sud',
        'NA': 'Namibie',
        'SZ': 'Eswatini',
        'LS': 'Lesotho',
        'MG': 'Madagascar',
        'MU': 'Maurice',
        'SC': 'Seychelles',
        'KM': 'Comores',
        'ET': 'Éthiopie',
        'ER': 'Érythrée',
        'DJ': 'Djibouti',
        'SO': 'Somalie',
        'KE': 'Kenya',
        'UG': 'Ouganda',
        'TZ': 'Tanzanie',
        'RW': 'Rwanda',
        'BI': 'Burundi',
        'EG': 'Égypte',
        'LY': 'Libye',
        'TN': 'Tunisie',
        'DZ': 'Algérie',
        'MA': 'Maroc',
        'SD': 'Soudan',
        'SS': 'Soudan du Sud',
    },
    typesSalle: {
        'arcade': 'Salle d\'arcade',
        'vr': 'Centre VR',
        'retro': 'Retro gaming',
        'esports': 'E-sport',
        'mixed': 'Mixte',
        'bowling': 'Bowling',
        'billard': 'Billard',
        'laser': 'Laser game',
        'escape': 'Escape game',
        'karaoke': 'Karaoke',
    },
    categoriesSalle: {
        'bar': 'Bar',
        'restaurant': 'Restaurant',
        'club': 'Club',
        'centre_commercial': 'Centre commercial',
        'hotel': 'Hôtel',
        'complexe_sportif': 'Complexe sportif',
        'espace_jeux': 'Espace de jeux',
        'loisir': 'Centre de loisirs',
        'autre': 'Autre',
    }
};

// Fusionner les props avec les données par défaut
const mergedProps = {
    defaultCoordinates: props.defaultCoordinates || defaultData.defaultCoordinates,
    paysAfricains: props.paysAfricains || defaultData.paysAfricains,
    typesSalle: props.typesSalle || defaultData.typesSalle,
    categoriesSalle: props.categoriesSalle || defaultData.categoriesSalle
};

const handleLocationSelected = (location) => {
    selectedLocation.value = location;
    formData.value.latitude = location.lat;
    formData.value.longitude = location.lng;
};

const submitForm = () => {
    // Ajouter les coordonnées au formulaire
    formData.value.latitude = selectedLocation.value.lat;
    formData.value.longitude = selectedLocation.value.lng;
    
    // Soumettre le formulaire
    // Ici vous pouvez ajouter la logique de soumission
    console.log('Formulaire soumis:', formData.value);
};
</script>

<template>
  <Head title="Créer ma Salle" />
  
  <!-- Add Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <div class="relative flex min-h-screen w-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <!-- Sidebar Component -->
    <Sidebar current-route="promoter.venues" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto transition-all duration-300">
      <div class="sticky top-0 z-10 bg-white/80 dark:bg-[#19202e]/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 px-8 py-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div class="flex min-w-72 flex-col gap-1">
            <p class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Créer ma Salle</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm font-normal leading-normal">Configurez les informations de votre salle</p>
          </div>
          <div class="flex items-center gap-3">
            <Link href="/promoter/venues" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-white dark:bg-[#19202e] border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
              <span class="truncate">Annuler</span>
            </Link>
            <button @click="submitForm" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-blue-500 transition-colors">
              <span class="truncate">Créer la salle</span>
            </button>
          </div>
        </div>
      </div>
      
      <div class="p-8">
        <form class="max-w-4xl mx-auto space-y-8">
          <!-- Localisation d'abord -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">📍 Localisation</h2>
            
            <!-- Carte Google Maps -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Positionnez votre salle sur la carte
              </label>
              <GoogleMap 
                :initial-lat="selectedLocation.lat"
                :initial-lng="selectedLocation.lng"
                height="400px"
                :readonly="false"
                @location-selected="handleLocationSelected"
              />
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                Cliquez sur la carte ou faites glisser le marqueur pour positionner votre salle
              </p>
            </div>

            <!-- Adresse -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adresse *</label>
                <input v-model="formData.adresse" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="123 Rue du Gaming">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Code postal *</label>
                <input v-model="formData.code_postal" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="12345">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ville *</label>
                <input v-model="formData.ville" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Dakar">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pays *</label>
                <select v-model="formData.pays" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                  <option v-for="(nom, code) in mergedProps.paysAfricains" :key="code" :value="nom">{{ nom }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Région</label>
                <input v-model="formData.region" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Dakar">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quartier</label>
                <input v-model="formData.quartier" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Plateau">
              </div>
            </div>

            <!-- Point de repère -->
            <div class="mt-6">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Point de repère</label>
              <input v-model="formData.point_repere" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Près du marché Sandaga, à côté du centre commercial Sea Plaza">
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Aidez vos clients à trouver facilement votre salle
              </p>
            </div>
          </section>

          <!-- Informations de base -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">📋 Informations de base</h2>
            
            <div class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nom de la salle *</label>
                  <input v-model="formData.nom" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="CyberZone Arena">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type de salle *</label>
                  <select v-model="formData.type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                    <option v-for="(nom, code) in mergedProps.typesSalle" :key="code" :value="code">{{ nom }}</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                <textarea v-model="formData.description" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Décrivez votre salle, les équipements, l'ambiance..."></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catégorie *</label>
                <select v-model="formData.categorie" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                  <option v-for="(nom, code) in mergedProps.categoriesSalle" :key="code" :value="code">{{ nom }}</option>
                </select>
              </div>
            </div>
          </section>

          <!-- Contact -->
          <section class="bg-white dark:bg-[#19202e] border border-gray-200 dark:border-gray-800 rounded-xl p-6">
            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">📞 Contact</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Téléphone</label>
                <input v-model="formData.telephone" type="tel" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="+221 33 123 45 67">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">WhatsApp</label>
                <input v-model="formData.whatsapp" type="tel" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="+221 77 123 45 67">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input v-model="formData.email" type="email" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="contact@cyberzone.sn">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site web</label>
                <input v-model="formData.site_web" type="url" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#19202e] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="https://cyberzone.sn">
              </div>
            </div>
          </section>
        </form>
      </div>
    </main>
  </div>
</template>
