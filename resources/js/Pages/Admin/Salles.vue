<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { debounce } from 'lodash'
import ConfirmModal from '../../Components/ConfirmModal.vue'
import NotificationModal from '../../Components/NotificationModal.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  salles: Object,
  filters: Object,
  stats: Object
})

const searchQuery = ref(props.filters?.search || '')
const selectedStatus = ref(props.filters?.status || '')

// États pour les modaux
const showConfirmModal = ref(false)
const confirmTitle = ref('')
const confirmMessage = ref('')
const confirmAction = ref(null)
const confirmData = ref(null)

const showNotificationModal = ref(false)
const notificationType = ref('success')
const notificationTitle = ref('')
const notificationMessage = ref('')
const showCreateModal = ref(false)
const showDetailModal = ref(false)
const selectedSalle = ref(null)

// Fonction pour formater les noms des jours
const formatDayName = (day) => {
  const days = {
    'lundi': 'Lundi',
    'mardi': 'Mardi', 
    'mercredi': 'Mercredi',
    'jeudi': 'Jeudi',
    'vendredi': 'Vendredi',
    'samedi': 'Samedi',
    'dimanche': 'Dimanche',
    'monday': 'Lundi',
    'tuesday': 'Mardi',
    'wednesday': 'Mercredi',
    'thursday': 'Jeudi',
    'friday': 'Vendredi',
    'saturday': 'Samedi',
    'sunday': 'Dimanche'
  }
  return days[day] || day
}

// Fonction pour formater les services
const formatServices = (services) => {
  if (!services) return []
  if (typeof services === 'object') {
    return Object.keys(services).filter(key => services[key] === true)
  }
  return Array.isArray(services) ? services : []
}

const handleSearch = debounce(() => {
  updateFilters()
}, 300)

const handleFilter = () => {
  updateFilters()
}

const updateFilters = () => {
  const params = new URLSearchParams(window.location.search)
  
  if (searchQuery.value) {
    params.set('search', searchQuery.value)
  } else {
    params.delete('search')
  }
  
  if (statusFilter.value) {
    params.set('status', statusFilter.value)
  } else {
    params.delete('status')
  }
  
  router.get(`${window.location.pathname}?${params.toString()}`, {}, { preserveState: true })
}

const getStatusClass = (salle) => {
  if (!salle.valide) {
    return 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800'
  }
  
  const classes = {
    actif: 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800',
    maintenance: 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300 border border-orange-200 dark:border-orange-800'
  }
  return classes[salle.statut] || classes.actif
}

const getStatusDotClass = (salle) => {
  if (!salle.valide) {
    return 'bg-yellow-500 animate-pulse'
  }
  
  const classes = {
    actif: 'bg-green-500',
    maintenance: 'bg-orange-500'
  }
  return classes[salle.statut] || 'bg-green-500'
}

const getStatusText = (salle) => {
  if (!salle.valide) {
    return 'En attente de validation'
  }
  
  const texts = {
    actif: 'Active',
    maintenance: 'Maintenance'
  }
  return texts[salle.statut] || 'Active'
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}

const viewSalle = (salle) => {
  selectedSalle.value = salle
  showDetailModal.value = true
  
  // Initialiser la carte après un court délai pour que le modal soit visible
  setTimeout(() => {
    initMap();
  }, 200);
}

// Fonctions Google Maps pour l'aperçu
const getGoogleMapsUrl = (salle) => {
  if (!salle.adresse && !salle.ville && !salle.pays) {
    return null;
  }
  
  const address = [
    salle.adresse,
    salle.ville,
    salle.pays
  ].filter(Boolean).join(', ');
  
  return `https://maps.google.com/maps?q=${encodeURIComponent(address)}&output=embed`;
};

const initMap = () => {
  console.log('🗺️ initMap appelée');
  console.log('🗺️ selectedSalle:', selectedSalle.value);
  console.log('🗺️ latitude:', selectedSalle.value?.latitude);
  console.log('🗺️ longitude:', selectedSalle.value?.longitude);
  
  if (!selectedSalle.value?.latitude || !selectedSalle.value?.longitude) {
    console.log('❌ Pas de coordonnées GPS');
    return;
  }
  
  // Attendre un peu que le modal soit visible
  setTimeout(() => {
    const mapElement = document.getElementById('admin-google-map');
    console.log('🗺️ mapElement trouvé:', mapElement);
    console.log('🗺️ window.google:', window.google);
    
    if (mapElement && window.google && window.google.maps) {
      console.log('✅ Initialisation de la carte');
      const map = new window.google.maps.Map(mapElement, {
        center: { 
          lat: parseFloat(selectedSalle.value.latitude), 
          lng: parseFloat(selectedSalle.value.longitude) 
        },
        zoom: 15,
        styles: [
          {
            featureType: "poi",
            elementType: "labels",
            stylers: [{ visibility: "off" }]
          }
        ]
      });
      
      new window.google.maps.Marker({
        position: { 
          lat: parseFloat(selectedSalle.value.latitude), 
          lng: parseFloat(selectedSalle.value.longitude) 
        },
        map: map,
        title: selectedSalle.value.nom
      });
    } else {
      console.log('❌ Google Maps pas chargé ou élément non trouvé');
    }
  }, 100);
  
  // Charger Google Maps API si nécessaire
  if (!window.google || !window.google.maps) {
    console.log('🔥 Chargement de Google Maps API');
    const script = document.createElement('script');
    const key = import.meta.env.VITE_GOOGLE_MAPS_KEY || '';
    script.src = `https://maps.googleapis.com/maps/api/js?key=${key}&callback=initMap`;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
  } else {
    console.log('✅ Google Maps déjà chargé');
  }
};

// Watcher pour initialiser la carte quand le modal s'ouvre
watch(showDetailModal, (newValue) => {
  if (newValue && selectedSalle.value) {
    initMap();
  }
})

const approveSalle = (salle) => {
  console.log('Bouton valider cliqué pour la salle:', salle);
  console.log('ID de la salle:', salle.id);
  console.log('Statut valide:', salle.valide);
  
  confirmTitle.value = 'Valider la salle';
  confirmMessage.value = `Êtes-vous sûr de vouloir valider la salle "${salle.nom}" ?`;
  confirmAction.value = 'approve';
  confirmData.value = salle;
  showConfirmModal.value = true;
  
  console.log('🔥 Modal devrait s\'afficher:', showConfirmModal.value);
  console.log('🔥 Titre:', confirmTitle.value);
  console.log('🔥 Message:', confirmMessage.value);
};

const toggleSalleStatus = (salle, newStatus) => {
  const action = newStatus === 'actif' ? 'réactiver' : 'mettre en maintenance'
  confirmTitle.value = 'Changer le statut de la salle';
  confirmMessage.value = `Êtes-vous sûr de vouloir ${action} la salle "${salle.nom}" ?`;
  confirmAction.value = 'toggle-status';
  confirmData.value = { salle, newStatus };
  showConfirmModal.value = true;
};

const deleteSalle = (salle) => {
  confirmTitle.value = 'Supprimer la salle';
  confirmMessage.value = `Êtes-vous sûr de vouloir supprimer définitivement la salle "${salle.nom}" ? Cette action est irréversible.`;
  confirmAction.value = 'delete';
  confirmData.value = salle;
  showConfirmModal.value = true;
};

// Confirmer l'action
const confirmActionHandler = () => {
  console.log('🚀 confirmActionHandler appelé');
  console.log('🚀 Action:', confirmAction.value);
  console.log('🚀 Données:', confirmData.value);
  console.log('🚀 Modal visible:', showConfirmModal.value);
  
  if (!confirmAction.value || !confirmData.value) {
    console.log('❌ Action ou données manquantes');
    return;
  }
  
  if (confirmAction.value === 'approve') {
    const salle = confirmData.value;
    console.log('📤 Début approbation salle ID:', salle.id);
    console.log('📤 Route générée:', route('admin.salles.approve', {id: salle.id}));
    console.log('📤 Token CSRF:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')?.substring(0, 20) + '...');
    
    console.log('📤 Envoi requête PATCH...');
    
    router.patch(route('admin.salles.approve', {id: salle.id}), {}, {
      onSuccess: (page) => {
        console.log('✅ Succès AJAX reçu:', page);
        console.log('✅ Props reçues:', page.props);
        console.log('✅ Salle dans les props:', page.props.salle);
        
        notificationType.value = 'success';
        notificationTitle.value = 'Succès';
        notificationMessage.value = `La salle "${salle.nom}" a été validée avec succès.`;
        showNotificationModal.value = true;
        showConfirmModal.value = false;
        
        // Forcer le rechargement
        window.location.reload();
      },
      onError: (errors) => {
        notificationType.value = 'error';
        notificationTitle.value = 'Erreur';
        notificationMessage.value = 'Une erreur est survenue lors de la validation de la salle.';
        showNotificationModal.value = true;
        showConfirmModal.value = false;
      },
      onStart: () => {
        console.log('⏳ Requête démarrée');
      },
      onFinish: () => {
        console.log('🏁 Requête terminée');
      }
    });
  } else if (confirmAction.value === 'toggle-status') {
    const { salle, newStatus } = confirmData.value;
    router.patch(route('admin.salles.toggle-status', {id: salle.id}), { status: newStatus }, {
      onSuccess: () => {
        notificationType.value = 'success';
        notificationTitle.value = 'Succès';
        notificationMessage.value = 'Statut de la salle modifié avec succès !';
        showNotificationModal.value = true;
      },
      onError: (errors) => {
        notificationType.value = 'error';
        notificationTitle.value = 'Erreur';
        notificationMessage.value = 'Une erreur est survenue lors de la modification du statut.';
        showNotificationModal.value = true;
      }
    });
  } else if (confirmAction.value === 'delete') {
    const salle = confirmData.value;
    router.delete(route('admin.salles.destroy', {id: salle.id}), {
      onSuccess: () => {
        notificationType.value = 'success';
        notificationTitle.value = 'Succès';
        notificationMessage.value = 'Salle supprimée avec succès !';
        showNotificationModal.value = true;
      },
      onError: (errors) => {
        notificationType.value = 'error';
        notificationTitle.value = 'Erreur';
        notificationMessage.value = 'Une erreur est survenue lors de la suppression.';
        showNotificationModal.value = true;
      }
    });
  }
  
  // Réinitialiser
  showConfirmModal.value = false;
  confirmAction.value = null;
  confirmData.value = null;
}
</script>

<template>
  <div class="max-w-6xl mx-auto flex flex-col gap-6">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
      <Link :href="route('admin.dashboard')" class="hover:text-primary transition-colors">Accueil</Link>
      <i class="fas fa-chevron-right icon-sm text-slate-300"></i>
      <span class="text-slate-900 dark:text-white font-medium">Salles</span>
    </nav>

    <!-- Page Heading -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Gestion des salles</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Gérez, modérez et surveillez l'activité des centres de loisirs.</p>
      </div>
      <button
        @click="showCreateModal = true"
        class="bg-primary hover:bg-blue-600 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 font-medium shadow-lg shadow-primary/20 transition-all active:scale-95"
      >
        <i class="fas fa-plus icon-sm"></i>
        <span>Ajouter une salle</span>
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total salles</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats?.total || 0 }}</p>
          </div>
          <div class="bg-primary/10 p-3 rounded-lg">
            <i class="fas fa-store text-primary"></i>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Actives</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ stats?.active || 0 }}</p>
          </div>
          <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
            <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">En attente</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ stats?.pending || 0 }}</p>
          </div>
          <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-lg">
            <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-slate-850 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Désactivées</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ stats?.disabled || 0 }}</p>
          </div>
          <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-lg">
            <i class="fas fa-ban text-red-600 dark:text-red-400"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters & Controls -->
    <div class="bg-white dark:bg-slate-850 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
      <!-- Search Field -->
      <div class="relative w-full md:max-w-md">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i class="fas fa-search text-slate-400 icon-sm"></i>
        </div>
        <input
          v-model="searchQuery"
          @input="handleSearch"
          class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg leading-5 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-shadow"
          placeholder="Rechercher par nom, ID ou propriétaire..."
          type="text"
        />
      </div>
      
      <!-- Filter Actions -->
      <div class="flex items-center gap-3 w-full md:w-auto">
        <div class="relative w-full md:w-48">
          <select
            v-model="statusFilter"
            @change="handleFilter"
            class="block w-full pl-3 pr-10 py-2.5 text-base border-slate-300 dark:border-slate-700 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none cursor-pointer"
          >
            <option value="">Tous les statuts</option>
            <option value="actif">Active</option>
            <option value="pending">En attente de validation</option>
            <option value="maintenance">Maintenance</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
            <i class="fas fa-chevron-down icon-sm"></i>
          </div>
        </div>
        
        <button class="p-2.5 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-colors" title="Filtres avancés">
          <i class="fas fa-filter icon-sm"></i>
        </button>
      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
          <thead class="bg-slate-50 dark:bg-slate-900/50">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider" scope="col">ID</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider" scope="col">Salle</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell" scope="col">Propriétaire</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell" scope="col">Date Création</th>
              <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider" scope="col">Capacité</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider" scope="col">Statut</th>
              <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider" scope="col">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-slate-850 divide-y divide-slate-200 dark:divide-slate-800">
            <tr v-for="salle in salles.data" :key="salle.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400 font-mono">#SL-{{ salle.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 flex-shrink-0 bg-slate-100 dark:bg-slate-700 rounded-lg bg-cover bg-center" :style="salle.image_url ? `background-image: url('${salle.image_url}')` : ''">
                    <span v-if="!salle.image_url" class="fas fa-store text-slate-400 m-auto"></span>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-slate-900 dark:text-white group-hover:text-primary transition-colors cursor-pointer">{{ salle.nom }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ salle.type || 'Centre de loisirs' }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white hidden sm:table-cell">
                <div class="flex items-center gap-2">
                  <div class="size-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                    <i class="fas fa-users text-xs text-slate-600 dark:text-slate-400"></i>
                  </div>
                  {{ salle.promoteur?.name || 'N/A' }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400 hidden lg:table-cell">{{ formatDate(salle.created_at) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white text-center">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-300">
                  {{ salle.capacite_max || 'N/A' }} pers.
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusClass(salle)">
                  <span class="size-1.5 rounded-full" :class="getStatusDotClass(salle)"></span>
                  {{ getStatusText(salle) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <button @click="viewSalle(salle)" class="text-slate-400 hover:text-primary dark:hover:text-primary transition-colors p-1" title="Voir">
                    <i class="fas fa-eye icon-sm"></i>
                  </button>
                  
                  <button v-if="!salle.valide" @click="approveSalle(salle)" class="text-slate-400 hover:text-green-600 dark:hover:text-green-500 transition-colors p-1" title="Valider">
                    <i class="fas fa-check-circle icon-sm"></i>
                  </button>
                  
                  <button v-else-if="salle.statut === 'actif'" @click="toggleSalleStatus(salle, 'maintenance')" class="text-slate-400 hover:text-amber-600 dark:hover:text-amber-500 transition-colors p-1" title="Mettre en maintenance">
                    <i class="fas fa-pause-circle icon-sm"></i>
                  </button>
                  
                  <button v-else-if="salle.statut === 'maintenance'" @click="toggleSalleStatus(salle, 'actif')" class="text-slate-400 hover:text-green-600 dark:hover:text-green-500 transition-colors p-1" title="Réactiver">
                    <i class="fas fa-play-circle icon-sm"></i>
                  </button>
                  
                  <button @click="deleteSalle(salle)" class="text-slate-400 hover:text-red-600 dark:hover:text-red-500 transition-colors p-1" title="Supprimer">
                    <i class="fas fa-trash icon-sm"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <div class="text-sm text-slate-500 dark:text-slate-400">
          Affichage de <span class="font-medium text-slate-900 dark:text-white">{{ salles.from || 0 }}</span> à <span class="font-medium text-slate-900 dark:text-white">{{ salles.to || 0 }}</span> sur <span class="font-medium text-slate-900 dark:text-white">{{ salles.total || 0 }}</span> résultats
        </div>
        <div class="flex gap-2">
          <Link
            v-if="salles.prev_page_url"
            :href="salles.prev_page_url"
            class="px-3 py-1 rounded-md border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-medium"
          >
            Précédent
          </Link>
          
          <template v-for="link in salles.links" :key="link.label">
            <Link
              v-if="link.url && !link.label.includes('Previous') && !link.label.includes('Next')"
              :href="link.url"
              class="px-3 py-1 rounded-md text-sm font-medium"
              :class="link.active ? 'bg-primary text-white hover:bg-blue-600 shadow-sm' : 'border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'"
              v-html="link.label"
            />
          </template>
          
          <Link
            v-if="salles.next_page_url"
            :href="salles.next_page_url"
            class="px-3 py-1 rounded-md border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-medium"
          >
            Suivant
          </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal de détails de la salle -->
    <div v-if="showDetailModal && selectedSalle" class="fixed inset-0 bg-black/60 dark:bg-black/80 flex items-center justify-center p-4 z-50" @click.self="showDetailModal = false">
      <div class="bg-white dark:bg-slate-850 rounded-2xl max-w-6xl w-full max-h-[95vh] overflow-hidden flex flex-col shadow-2xl">
        <!-- Header avec image de fond -->
        <div class="relative h-80 bg-gradient-to-br from-blue-600 to-purple-700 overflow-hidden">
          <img v-if="selectedSalle.image_url" 
               :src="selectedSalle.image_url.startsWith('http') ? selectedSalle.image_url : '/storage/' + selectedSalle.image_url" 
               :alt="selectedSalle.nom" 
               class="w-full h-full object-cover"
               @error="$event.target.style.display='none'">
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
          <div class="absolute inset-0 flex items-end p-10">
            <div class="text-white">
              <h3 class="text-5xl font-bold mb-4">{{ selectedSalle.nom }}</h3>
              <div class="flex items-center gap-4">
                <span :class="getStatusClass(selectedSalle)" class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm">
                  <span class="size-2 rounded-full inline-block mr-2" :class="getStatusDotClass(selectedSalle)"></span>
                  {{ getStatusText(selectedSalle) }}
                </span>
                <span class="text-white/80 text-lg">{{ selectedSalle.type || 'Centre de loisirs' }}</span>
              </div>
            </div>
          </div>
          <button @click="showDetailModal = false" class="absolute top-4 right-4 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/30 transition-colors">
            <i class="fas fa-times text-lg"></i>
          </button>
        </div>

        <!-- Contenu du modal -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 bg-white dark:bg-slate-850">
          <!-- Image principale -->
          <div v-if="selectedSalle.image_url" class="mb-8">
            <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
              <i class="fas fa-image text-primary"></i>
              Image principale
            </h4>
            <div class="relative h-96 bg-slate-100 dark:bg-slate-700 rounded-xl overflow-hidden group">
              <img :src="selectedSalle.image_url.startsWith('http') ? selectedSalle.image_url : '/storage/' + selectedSalle.image_url" 
                   :alt="selectedSalle.nom" 
                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                   @error="$event.target.style.display='none'">
              <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium text-slate-800">
                <i class="fas fa-expand mr-1"></i>
                Cliquez pour agrandir
              </div>
            </div>
          </div>

          <!-- Galerie d'images -->
          <div v-if="selectedSalle.images && selectedSalle.images.length > 0" class="mb-8">
            <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
              <i class="fas fa-images text-primary"></i>
              Galerie d'images
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
              <div v-for="(image, index) in selectedSalle.images.slice(0, 8)" :key="index" 
                   class="aspect-square rounded-lg overflow-hidden group cursor-pointer hover:shadow-lg transition-all">
                <img :src="'/storage/' + image" :alt="`${selectedSalle.nom} - ${index + 1}`" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
              </div>
              <div v-if="selectedSalle.images.length > 8" 
                   class="aspect-square bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 rounded-lg flex items-center justify-center cursor-pointer hover:shadow-lg transition-all">
                <div class="text-center">
                  <span class="text-2xl font-bold text-slate-600 dark:text-slate-300">+{{ selectedSalle.images.length - 8 }}</span>
                  <p class="text-xs text-slate-500 dark:text-slate-400">photos</p>
                </div>
              </div>
            </div>
          </div>

      <!-- Informations principales en cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card Statistiques -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
          <div class="flex items-center justify-between mb-4">
            <i class="fas fa-users text-blue-600 text-2xl"></i>
            <span class="text-3xl font-bold text-blue-900 dark:text-blue-100">{{ selectedSalle.capacite_max || 'N/A' }}</span>
          </div>
          <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">Capacité maximale</p>
          <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">personnes</p>
        </div>

        <!-- Card Tarif -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
          <div class="flex items-center justify-between mb-4">
            <i class="fas fa-tag text-green-600 text-2xl"></i>
            <span class="text-3xl font-bold text-green-900 dark:text-green-100">{{ selectedSalle.prix_heure || '0' }}</span>
          </div>
          <p class="text-sm text-green-700 dark:text-green-300 font-medium">Tarif par heure</p>
          <p class="text-xs text-green-600 dark:text-green-400 mt-1">FCFA</p>
        </div>

        <!-- Card Statut -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
          <div class="flex items-center justify-between mb-4">
            <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
            <div class="text-right">
              <span class="text-lg font-bold text-purple-900 dark:text-purple-100">{{ selectedSalle.nombre_vues || '0' }}</span>
              <p class="text-xs text-purple-600 dark:text-purple-400">vues</p>
            </div>
          </div>
          <p class="text-sm text-purple-700 dark:text-purple-300 font-medium">Popularité</p>
          <div class="flex items-center gap-1 mt-2">
            <i v-for="i in 5" :key="i" class="fas fa-star text-yellow-500 text-xs"></i>
            <span class="text-xs text-purple-600 dark:text-purple-400 ml-1">{{ selectedSalle.note_moyenne || '0.0' }}</span>
          </div>
        </div>
      </div>

      <!-- Informations détaillées -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Localisation et Contact -->
        <div class="space-y-6">
          <!-- Localisation -->
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
              <i class="fas fa-map-marker-alt text-primary"></i>
              Localisation
            </h4>
            <div class="space-y-3">
              <div class="flex items-start gap-3">
                <i class="fas fa-home text-slate-400 mt-1"></i>
                <div>
                  <p class="text-slate-900 dark:text-white font-medium">{{ selectedSalle.adresse || 'Adresse non spécifiée' }}</p>
                  <p class="text-slate-600 dark:text-slate-400 text-sm">{{ selectedSalle.code_postal }} {{ selectedSalle.ville }}, {{ selectedSalle.pays }}</p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <i class="fas fa-globe text-slate-400"></i>
                <p class="text-slate-600 dark:text-slate-400 text-sm">
                  {{ selectedSalle.latitude }}, {{ selectedSalle.longitude }}
                </p>
              </div>
            </div>
            
            <!-- Carte Google Maps -->
            <div v-if="selectedSalle.latitude && selectedSalle.longitude" class="mt-4">
              <div class="rounded-xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-700">
                <div id="admin-google-map" class="w-full h-64 bg-slate-100 dark:bg-slate-700"></div>
              </div>
              
              <!-- Lien vers Google Maps pour navigation -->
              <div v-if="getGoogleMapsUrl(selectedSalle)" class="mt-3">
                <a 
                  :href="getGoogleMapsUrl(selectedSalle)" 
                  target="_blank"
                  rel="noopener noreferrer"
                  class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors"
                >
                  <i class="fas fa-external-link-alt"></i>
                  <span>Ouvrir dans Google Maps</span>
                </a>
              </div>
            </div>
          </div>

          <!-- Contact -->
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
              <i class="fas fa-phone text-primary"></i>
              Contact
            </h4>
            <div class="space-y-3">
              <div class="flex items-center gap-3">
                <i class="fas fa-phone text-slate-400"></i>
                <p class="text-slate-900 dark:text-white">{{ selectedSalle.telephone || 'Téléphone non spécifié' }}</p>
              </div>
              <div class="flex items-center gap-3">
                <i class="fas fa-envelope text-slate-400"></i>
                <p class="text-slate-900 dark:text-white">{{ selectedSalle.email || 'Email non spécifié' }}</p>
              </div>
              <div v-if="selectedSalle.whatsapp" class="flex items-center gap-3">
                <i class="fab fa-whatsapp text-green-500"></i>
                <p class="text-slate-900 dark:text-white">{{ selectedSalle.whatsapp }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Promoteur et Réseaux -->
        <div class="space-y-6">
          <!-- Promoteur -->
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
              <i class="fas fa-user-tie text-primary"></i>
              Promoteur
            </h4>
            <div v-if="selectedSalle.promoter || selectedSalle.promoteur" class="flex items-center gap-4 p-4 bg-white dark:bg-slate-700 rounded-lg">
              <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-primary text-lg"></i>
              </div>
              <div class="flex-1">
                <p class="font-semibold text-slate-900 dark:text-white">
                  {{ selectedSalle.promoter?.name || selectedSalle.promoteur?.name || 'Promoteur inconnu' }}
                </p>
                <p class="text-sm text-slate-600 dark:text-slate-400">
                  {{ selectedSalle.promoter?.email || selectedSalle.promoteur?.email || 'Email non disponible' }}
                </p>
              </div>
            </div>
            <div v-else class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
              <p class="text-amber-800 dark:text-amber-300 flex items-center gap-2 text-sm">
                <i class="fas fa-exclamation-triangle"></i>
                Promoteur ID: {{ selectedSalle.promoter_id || 'Non défini' }}
              </p>
            </div>
          </div>

          <!-- Réseaux sociaux -->
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
              <i class="fas fa-share-alt text-primary"></i>
              Réseaux sociaux
            </h4>
            <div class="flex gap-3">
              <a v-if="selectedSalle.facebook" :href="selectedSalle.facebook" class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                <i class="fab fa-facebook-f text-blue-600 dark:text-blue-400"></i>
              </a>
              <a v-if="selectedSalle.instagram" :href="selectedSalle.instagram" class="w-10 h-10 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center hover:bg-pink-200 dark:hover:bg-pink-900/50 transition-colors">
                <i class="fab fa-instagram text-pink-600 dark:text-pink-400"></i>
              </a>
              <a v-if="selectedSalle.site_web" :href="selectedSalle.site_web" class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                <i class="fas fa-globe text-slate-600 dark:text-slate-400"></i>
              </a>
              <div v-if="!selectedSalle.facebook && !selectedSalle.instagram && !selectedSalle.site_web" class="text-slate-500 dark:text-slate-400 text-sm">
                Aucun réseau social spécifié
              </div>
            </div>
          </div>
        </div>
      </div>

          <!-- Services et Équipements -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Services -->
            <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6">
              <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-concierge-bell text-primary"></i>
                Services disponibles
              </h4>
              <div v-if="formatServices(selectedSalle.services).length > 0" class="flex flex-wrap gap-2">
                <span v-for="service in formatServices(selectedSalle.services)" :key="service" 
                      class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800">
                  <i class="fas fa-check-circle mr-1.5 text-xs"></i>
                  {{ service.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                </span>
              </div>
              <p v-else class="text-slate-500 dark:text-slate-400 text-sm">Aucun service spécifié</p>
            </div>

            <!-- Équipements -->
            <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6">
              <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-gamepad text-primary"></i>
                Équipements
              </h4>
              <div v-if="selectedSalle.equipements && Array.isArray(selectedSalle.equipements) && selectedSalle.equipements.length > 0" class="flex flex-wrap gap-2">
                <span v-for="equipement in selectedSalle.equipements" :key="equipement" 
                      class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                  <i class="fas fa-cube mr-1.5 text-xs"></i>
                  {{ equipement }}
                </span>
              </div>
              <p v-else class="text-slate-500 dark:text-slate-400 text-sm">Aucun équipement spécifié</p>
            </div>
          </div>

          <!-- Description -->
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6 mb-8">
            <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
              <i class="fas fa-info-circle text-primary"></i>
              Description
            </h4>
            <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ selectedSalle.description || 'Aucune description disponible' }}</p>
          </div>

          <!-- Horaires -->
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6 mb-8">
            <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
              <i class="fas fa-clock text-primary"></i>
              Horaires d'ouverture
            </h4>
            <div v-if="selectedSalle.horaires && typeof selectedSalle.horaires === 'object'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
              <template v-for="(horaire, jour) in selectedSalle.horaires" :key="jour">
                <div v-if="typeof horaire === 'object' && horaire.ouvert" 
                     class="bg-white dark:bg-slate-700 rounded-lg p-3 border border-slate-200 dark:border-slate-600">
                  <div class="flex items-center justify-between">
                    <span class="font-semibold text-slate-900 dark:text-white text-sm">{{ formatDayName(jour) }}</span>
                    <span class="text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 px-2 py-1 rounded-full">
                      Ouvert
                    </span>
                  </div>
                  <p class="text-slate-600 dark:text-slate-400 text-sm mt-1">
                    <i class="fas fa-door-open mr-1"></i>
                    {{ horaire.ouverture || '09:00' }} - {{ horaire.fermeture || '22:00' }}
                  </p>
                </div>
                <div v-else-if="typeof horaire === 'boolean' && horaire" 
                     class="bg-white dark:bg-slate-700 rounded-lg p-3 border border-slate-200 dark:border-slate-600">
                  <div class="flex items-center justify-between">
                    <span class="font-semibold text-slate-900 dark:text-white text-sm">{{ formatDayName(jour) }}</span>
                    <span class="text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 px-2 py-1 rounded-full">
                      Ouvert
                    </span>
                  </div>
                </div>
              </template>
            </div>
            <p v-else class="text-slate-500 dark:text-slate-400 text-sm">Horaires non spécifiés</p>
          </div>

          <!-- Actions -->
          <div class="flex flex-wrap gap-4 p-6 bg-slate-50 dark:bg-slate-800 rounded-xl">
            <button @click="showDetailModal = false" 
                    class="px-6 py-3 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg font-medium transition-colors">
              <i class="fas fa-times mr-2"></i>
              Fermer
            </button>
            <button v-if="!selectedSalle.valide" 
                    @click="approveSalle(selectedSalle); showDetailModal = false" 
                    class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-500 hover:to-green-400 text-white rounded-lg font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
              <i class="fas fa-check-circle mr-2"></i>
              Approuver la salle
            </button>
            <button class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white rounded-lg font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
              <i class="fas fa-edit mr-2"></i>
              Modifier
            </button>
            <button class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white rounded-lg font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
              <i class="fas fa-trash mr-2"></i>
              Supprimer
            </button>
          </div>
        </div>
      </div>
    </div>

<!-- Modal de confirmation simple -->
<div v-if="showConfirmModal" class="fixed inset-0 z-[99999] bg-black/50 flex items-center justify-center">
  <div class="bg-white dark:bg-slate-800 rounded-lg p-6 max-w-md w-full mx-4">
    <h3 class="text-lg font-semibold mb-2">{{ confirmTitle }}</h3>
    <p class="text-slate-600 dark:text-slate-400 mb-4">{{ confirmMessage }}</p>
    <div class="flex gap-3 justify-end">
      <button @click="showConfirmModal = false" class="px-4 py-2 text-slate-600 hover:text-slate-800">
        Annuler
      </button>
      <button @click="confirmActionHandler" class="bg-primary text-white px-4 py-2 rounded hover:bg-blue-600">
        Confirmer
      </button>
    </div>
  </div>
</div>

<!-- Notification Modal -->
<NotificationModal
  :show="showNotificationModal"
  :type="notificationType"
  :title="notificationTitle"
  :message="notificationMessage"
  @close="showNotificationModal = false"
/>
</template>

<style scoped>
.icon-sm {
  font-size: 20px;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 20px;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #475569;
}
</style>

