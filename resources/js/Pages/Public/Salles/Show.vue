<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    salle: Object
});

const activeTab = ref('overview');

const formatPrice = (price) => {
    if (!price) return 'Prix sur demande';
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0,
        currencyDisplay: 'symbol'
    }).format(price) + ' FCFA';
};

const formatPhone = (phone) => {
    if (!phone) return '';
    return phone.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
};

const isOpen = (day) => {
    const horaires = props.salle.horaires;
    if (!horaires || !horaires[day]) return false;
    return horaires[day].ouvert;
};

const getHours = (day) => {
    const horaires = props.salle.horaires;
    if (!horaires || !horaires[day]) return 'Fermé';
    if (!horaires[day].ouvert) return 'Fermé';
    return `${horaires[day].ouverture} - ${horaires[day].fermeture}`;
};

const hasService = (service) => {
    return props.salle.services && props.salle.services[service];
};

const mainImage = computed(() => {
    return props.salle.image_url ? `/storage/${props.salle.image_url}` : 'https://picsum.photos/seed/salle-' + props.salle.id + '/800/600.jpg';
});

const galleryImages = computed(() => {
    if (!props.salle.images) return [];
    // Si c'est une chaîne JSON, la parser
    if (typeof props.salle.images === 'string') {
        try {
            const parsed = JSON.parse(props.salle.images);
            return Array.isArray(parsed) ? parsed.map(img => `/storage/${img}`) : [];
        } catch (e) {
            return [];
        }
    }
    // Si c'est déjà un tableau
    if (Array.isArray(props.salle.images)) {
        return props.salle.images.map(img => `/storage/${img}`);
    }
    return [];
});

const getPromoterInitial = () => {
    if (!props.salle.promoter?.name) return 'P';
    return props.salle.promoter.name.charAt(0).toUpperCase();
};

const getPromoterDisplayName = () => {
    if (!props.salle.promoter?.name) return 'Promoteur';
    
    const name = props.salle.promoter.name;
    // Si c'est une valeur par défaut, afficher une description plus informative
    if (name.toLowerCase() === 'promoteur') {
        return 'Promoteur de la salle';
    }
    
    return name;
};

const getPromoterRole = () => {
    if (!props.salle.promoter?.name) return 'Promoteur';
    
    const name = props.salle.promoter.name;
    // Si c'est une valeur par défaut, afficher un rôle plus générique
    if (name.toLowerCase() === 'promoteur') {
        return 'Responsable';
    }
    
    return 'Propriétaire';
};
</script>

<template>
  <Head :title="salle.nom" />

  <!-- Navigation -->
  <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100">
    <div class="max-w-[1320px] mx-auto px-6 py-4">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold">GB</span>
          </div>
          <h1 class="text-soft-black text-xl font-bold tracking-tight">YOUPIHUB</h1>
        </div>
        
        <!-- Menu -->
        <div class="hidden md:flex items-center gap-10">
          <a class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors" href="/">Accueil</a>
          <a class="text-primary hover:text-primary text-sm font-medium transition-colors" href="/search/rooms">Salles</a>
          <a class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors" href="/events">Événements</a>
        </div>
        
        <!-- Auth Buttons -->
        <div class="flex items-center gap-3">
          <a href="/login" class="text-medium-grey hover:text-soft-black text-sm font-medium transition-colors">Connexion</a>
          <a href="/register" class="flex items-center justify-center rounded-full bg-primary hover:bg-blue-600 text-white text-sm font-bold px-6 py-2.5 transition-all shadow-lg shadow-primary/20">
            Inscription
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section with Main Image -->
  <section class="relative h-96 sm:h-[500px] overflow-hidden pt-16">
    <img 
      :src="mainImage" 
      :alt="salle.nom"
      class="w-full h-full object-cover"
    />
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
    
    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
      <div class="max-w-[1320px] mx-auto">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">{{ salle.nom }}</h1>
        <div class="flex flex-wrap items-center gap-4 text-white/90">
          <div class="flex items-center gap-2">
            <i class="fas fa-map-marker-alt"></i>
            <span>{{ salle.adresse }}, {{ salle.ville }}</span>
          </div>
          <div class="flex items-center gap-2">
            <i class="fas fa-users"></i>
            <span>{{ salle.capacite_max }} personnes max</span>
          </div>
          <div class="flex items-center gap-2">
            <i class="fas fa-star text-yellow-400"></i>
            <span>{{ salle.note_moyenne || '4.5' }} ({{ salle.nombre_avis || '0' }} avis)</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Content -->
  <div class="max-w-[1320px] mx-auto px-4 sm:px-6 py-8">
    <!-- Price and Actions -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
          <div class="text-3xl font-bold text-soft-black mb-2">
            {{ formatPrice(salle.prix_heure) }}
            <span class="text-lg font-normal text-gray-600">/heure</span>
          </div>
          <div v-if="salle.prix_journee" class="text-lg text-gray-600">
            Ou {{ formatPrice(salle.prix_journee) }}/journée
          </div>
        </div>
        
        <div class="flex gap-4">
          <button class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-blue-600 transition-colors font-semibold">
            Réserver maintenant
          </button>
          <button class="px-6 py-3 border border-gray-300 text-soft-black rounded-xl hover:border-primary hover:text-primary transition-colors font-semibold">
            Contacter
          </button>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
      <!-- Tab Headers -->
      <div class="flex border-b border-gray-200">
        <button
          v-for="tab in [
            { id: 'overview', label: 'Aperçu', icon: 'fas fa-info-circle' },
            { id: 'equipment', label: 'Équipements', icon: 'fas fa-gamepad' },
            { id: 'gallery', label: 'Galerie', icon: 'fas fa-images' },
            { id: 'schedule', label: 'Horaires', icon: 'fas fa-clock' },
            { id: 'contact', label: 'Contact', icon: 'fas fa-phone' }
          ]"
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            'flex-1 flex items-center justify-center gap-2 px-4 py-4 text-sm font-medium transition-colors',
            activeTab === tab.id 
              ? 'text-primary border-b-2 border-primary bg-blue-50' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          <i :class="tab.icon"></i>
          {{ tab.label }}
        </button>
      </div>

      <!-- Tab Content -->
      <div class="p-6">
        <!-- Overview Tab -->
        <div v-if="activeTab === 'overview'" class="space-y-6">
          <div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Description</h3>
            <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ salle.description }}</p>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">Informations générales</h3>
              <dl class="space-y-3">
                <div class="flex justify-between">
                  <dt class="text-gray-600">Type</dt>
                  <dd class="font-medium">{{ salle.type || 'Non spécifié' }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-gray-600">Catégorie</dt>
                  <dd class="font-medium">{{ salle.categorie || 'Non spécifié' }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-gray-600">Capacité</dt>
                  <dd class="font-medium">{{ salle.capacite_max }} personnes</dd>
                </div>
                <div v-if="salle.surface_area" class="flex justify-between">
                  <dt class="text-gray-600">Surface</dt>
                  <dd class="font-medium">{{ salle.surface_area }} m²</dd>
                </div>
              </dl>
            </div>
            
            <div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">Adresse</h3>
              <div class="space-y-2 text-gray-700">
                <p>{{ salle.adresse }}</p>
                <p>{{ salle.code_postal }} {{ salle.ville }}</p>
                <p>{{ salle.pays }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Equipment Tab -->
        <div v-if="activeTab === 'equipment'" class="space-y-6">
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Équipements disponibles</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-if="hasService('pc_gaming')" class="flex items-center gap-3 p-4 bg-green-50 rounded-lg">
              <i class="fas fa-desktop text-green-600 text-xl"></i>
              <div>
                <div class="font-medium">PC Gaming</div>
                <div class="text-sm text-gray-600">{{ salle.pc_gaming }} postes</div>
              </div>
            </div>
            
            <div v-if="hasService('vr')" class="flex items-center gap-3 p-4 bg-green-50 rounded-lg">
              <i class="fas fa-vr-cardboard text-green-600 text-xl"></i>
              <div>
                <div class="font-medium">Réalité Virtuelle</div>
                <div class="text-sm text-gray-600">{{ salle.casques_vr }} casques</div>
              </div>
            </div>
            
            <div v-if="hasService('playstation_4')" class="flex items-center gap-3 p-4 bg-green-50 rounded-lg">
              <i class="fab fa-playstation text-green-600 text-xl"></i>
              <div>
                <div class="font-medium">PlayStation 4</div>
                <div class="text-sm text-gray-600">Disponible</div>
              </div>
            </div>
            
            <div v-if="hasService('playstation_5')" class="flex items-center gap-3 p-4 bg-green-50 rounded-lg">
              <i class="fab fa-playstation text-green-600 text-xl"></i>
              <div>
                <div class="font-medium">PlayStation 5</div>
                <div class="text-sm text-gray-600">Disponible</div>
              </div>
            </div>
            
            <div v-if="hasService('billard')" class="flex items-center gap-3 p-4 bg-green-50 rounded-lg">
              <i class="fas fa-circle-notch text-green-600 text-xl"></i>
              <div>
                <div class="font-medium">Billard</div>
                <div class="text-sm text-gray-600">{{ salle.tables_billard }} tables</div>
              </div>
            </div>
            
            <div v-if="hasService('baby_foot')" class="flex items-center gap-3 p-4 bg-green-50 rounded-lg">
              <i class="fas fa-futbol text-green-600 text-xl"></i>
              <div>
                <div class="font-medium">Baby-foot</div>
                <div class="text-sm text-gray-600">Disponible</div>
              </div>
            </div>
          </div>
          
          <!-- Services -->
          <h3 class="text-xl font-semibold text-gray-900 mb-4 mt-8">Services</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-if="salle.wifi_gratuit" class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
              <i class="fas fa-wifi text-blue-600 text-xl"></i>
              <span class="font-medium">Wi-Fi gratuit</span>
            </div>
            
            <div v-if="salle.parking" class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
              <i class="fas fa-parking text-blue-600 text-xl"></i>
              <span class="font-medium">Parking</span>
            </div>
            
            <div v-if="salle.climatisation" class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
              <i class="fas fa-snowflake text-blue-600 text-xl"></i>
              <span class="font-medium">Climatisation</span>
            </div>
            
            <div v-if="salle.snack_bar" class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
              <i class="fas fa-coffee text-blue-600 text-xl"></i>
              <span class="font-medium">Snack bar</span>
            </div>
            
            <div v-if="salle.restaurant" class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
              <i class="fas fa-utensils text-blue-600 text-xl"></i>
              <span class="font-medium">Restaurant</span>
            </div>
            
            <div v-if="salle.terrasse" class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
              <i class="fas fa-umbrella-beach text-blue-600 text-xl"></i>
              <span class="font-medium">Terrasse</span>
            </div>
          </div>
        </div>

        <!-- Gallery Tab -->
        <div v-if="activeTab === 'gallery'" class="space-y-6">
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Galerie photos</h3>
          
          <div v-if="galleryImages.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="(image, index) in galleryImages" :key="index" class="relative aspect-video overflow-hidden rounded-lg">
              <img 
                :src="image" 
                :alt="`${salle.nom} - Photo ${index + 1}`"
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300 cursor-pointer"
              />
            </div>
          </div>
          
          <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
            <i class="fas fa-images text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Aucune photo dans la galerie pour le moment</p>
          </div>
        </div>

        <!-- Schedule Tab -->
        <div v-if="activeTab === 'schedule'" class="space-y-6">
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Horaires d'ouverture</h3>
          
          <div class="space-y-3">
            <div 
              v-for="day in ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']" 
              :key="day"
              class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
            >
              <div class="flex items-center gap-3">
                <i 
                  :class="isOpen(day) ? 'fas fa-check-circle text-green-600' : 'fas fa-times-circle text-red-600'"
                ></i>
                <span class="font-medium capitalize">{{ day }}</span>
              </div>
              <span class="text-gray-700">{{ getHours(day) }}</span>
            </div>
          </div>
        </div>

        <!-- Contact Tab -->
        <div v-if="activeTab === 'contact'" class="space-y-6">
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Informations de contact</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h4 class="font-medium text-gray-900 mb-3">Contact direct</h4>
              <div class="space-y-3">
                <div v-if="salle.telephone" class="flex items-center gap-3">
                  <i class="fas fa-phone text-primary"></i>
                  <a :href="'tel:' + salle.telephone" class="text-blue-600 hover:text-blue-800">
                    {{ formatPhone(salle.telephone) }}
                  </a>
                </div>
                
                <div v-if="salle.whatsapp" class="flex items-center gap-3">
                  <i class="fab fa-whatsapp text-green-600"></i>
                  <a :href="'https://wa.me/' + salle.whatsapp.replace(/\D/g, '')" class="text-green-600 hover:text-green-800">
                    {{ formatPhone(salle.whatsapp) }}
                  </a>
                </div>
                
                <div v-if="salle.email" class="flex items-center gap-3">
                  <i class="fas fa-envelope text-primary"></i>
                  <a :href="'mailto:' + salle.email" class="text-blue-600 hover:text-blue-800">
                    {{ salle.email }}
                  </a>
                </div>
                
                <div v-if="salle.site_web" class="flex items-center gap-3">
                  <i class="fas fa-globe text-primary"></i>
                  <a :href="salle.site_web" target="_blank" class="text-blue-600 hover:text-blue-800">
                    Site web
                  </a>
                </div>
              </div>
            </div>
            
            <div>
              <h4 class="font-medium text-gray-900 mb-3">Adresse</h4>
              <div class="space-y-2 text-gray-700">
                <p>{{ salle.adresse }}</p>
                <p>{{ salle.code_postal }} {{ salle.ville }}</p>
                <p>{{ salle.pays }}</p>
              </div>
            </div>
          </div>
          
          <!-- Promoter Info -->
          <div v-if="salle.promoter" class="border-t pt-6">
            <h4 class="font-medium text-gray-900 mb-3">Géré par</h4>
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
              <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                {{ getPromoterInitial() }}
              </div>
              <div>
                <div class="font-medium">{{ getPromoterDisplayName() }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
