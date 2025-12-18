<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { debounce } from 'lodash'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  salles: Object,
  filters: Object,
  stats: Object
})

const searchQuery = ref(props.filters?.search || '')
const statusFilter = ref(props.filters?.status || '')
const showCreateModal = ref(false)
const showDetailModal = ref(false)
const selectedSalle = ref(null)

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
}

const approveSalle = (salle) => {
  if (confirm(`Êtes-vous sûr de vouloir valider la salle "${salle.nom}" ?`)) {
    router.patch(route('admin.salles.approve', salle.id), {}, {
      onSuccess: () => {
        // Message de succès géré par le backend
      }
    })
  }
}

const toggleSalleStatus = (salle, newStatus) => {
  const action = newStatus === 'actif' ? 'réactiver' : 'mettre en maintenance'
  if (confirm(`Êtes-vous sûr de vouloir ${action} la salle "${salle.nom}" ?`)) {
    router.patch(route('admin.salles.toggle-status', salle.id), { status: newStatus }, {
      onSuccess: () => {
        // Message de succès géré par le backend
      }
    })
  }
}

const deleteSalle = (salle) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer définitivement la salle "${salle.nom}" ? Cette action est irréversible.`)) {
    router.delete(route('admin.salles.destroy', salle.id), {
      onSuccess: () => {
        // Message de succès géré par le backend
      }
    })
  }
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
        <p class="text-slate-500 dark:text-slate-400 mt-1">Gérez, modérez et surveillez l'activité des salles de jeux.</p>
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
                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ salle.type || 'Salle de jeux' }}</div>
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

    <!-- Modal de détails de la salle -->
    <div v-if="showDetailModal && selectedSalle" class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center p-4 z-50" @click.self="showDetailModal = false">
      <div class="bg-white dark:bg-slate-850 rounded-xl max-w-5xl w-full max-h-[95vh] overflow-hidden flex flex-col">
        <!-- Header du modal -->
        <div class="sticky top-0 bg-white dark:bg-slate-850 border-b border-slate-200 dark:border-slate-800 p-6">
          <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Détails de la salle</h3>
            <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
        </div>

        <!-- Contenu du modal -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-4 bg-white dark:bg-slate-850">
          <!-- Image et informations principales -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
              <div class="aspect-square bg-slate-100 dark:bg-slate-700 rounded-lg overflow-hidden">
                <img v-if="selectedSalle.image_url" :src="selectedSalle.image_url" :alt="selectedSalle.nom" class="w-full h-full object-cover">
                <div v-else class="w-full h-full flex items-center justify-center">
                  <i class="fas fa-store text-3xl text-slate-400"></i>
                </div>
              </div>
            </div>
            
            <div class="md:col-span-2 space-y-3">
              <div>
                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">{{ selectedSalle.nom }}</h4>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ selectedSalle.type || 'Salle de jeux' }}</p>
              </div>
              
              <div class="flex items-center gap-2">
                <span :class="getStatusClass(selectedSalle.status)">
                  <span class="size-1.5 rounded-full" :class="getStatusDotClass(selectedSalle.status)"></span>
                  {{ getStatusText(selectedSalle.status) }}
                </span>
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-50 dark:bg-slate-800 p-3 rounded-lg">
                  <p class="text-xs text-slate-500 dark:text-slate-400">Capacité</p>
                  <p class="text-base font-semibold text-slate-900 dark:text-white">{{ selectedSalle.capacite_max || 'N/A' }} pers.</p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800 p-3 rounded-lg">
                  <p class="text-xs text-slate-500 dark:text-slate-400">Prix/heure</p>
                  <p class="text-base font-semibold text-slate-900 dark:text-white">{{ selectedSalle.prix_heure ? selectedSalle.prix_heure + ' FCFA' : 'N/A' }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Informations détaillées -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Localisation -->
            <div class="space-y-2">
              <h5 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2 text-sm">
                <i class="fas fa-map-marker-alt text-primary"></i>
                Localisation
              </h5>
              <div class="space-y-1">
                <p class="text-sm text-slate-600 dark:text-slate-400">{{ selectedSalle.adresse || 'Adresse non spécifiée' }}</p>
                <p class="text-sm text-slate-600 dark:text-slate-400">{{ selectedSalle.ville || 'Ville non spécifiée' }}, {{ selectedSalle.pays || 'Sénégal' }}</p>
              </div>
            </div>

            <!-- Contact -->
            <div class="space-y-2">
              <h5 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2 text-sm">
                <i class="fas fa-phone text-primary"></i>
                Contact
              </h5>
              <div class="space-y-1">
                <p class="text-sm text-slate-600 dark:text-slate-400">{{ selectedSalle.telephone || 'Téléphone non spécifié' }}</p>
                <p class="text-sm text-slate-600 dark:text-slate-400">{{ selectedSalle.email || 'Email non spécifié' }}</p>
              </div>
            </div>
          </div>

          <!-- Promoteur -->
          <div class="space-y-2">
            <h5 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2 text-sm">
              <i class="fas fa-user-tie text-primary"></i>
              Promoteur
            </h5>
            <div v-if="selectedSalle.promoteur" class="bg-slate-50 dark:bg-slate-800 p-3 rounded-lg">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center">
                  <i class="fas fa-user text-primary text-sm"></i>
                </div>
                <div>
                  <p class="font-medium text-slate-900 dark:text-white text-sm">{{ selectedSalle.promoteur.name }}</p>
                  <p class="text-xs text-slate-500 dark:text-slate-400">{{ selectedSalle.promoteur.email }}</p>
                </div>
              </div>
            </div>
            <div v-else class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-3 rounded-lg">
              <p class="text-amber-800 dark:text-amber-300 flex items-center gap-2 text-sm">
                <i class="fas fa-exclamation-triangle text-xs"></i>
                Aucun promoteur assigné à cette salle
              </p>
            </div>
          </div>

          <!-- Équipements et services -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Équipements -->
            <div class="space-y-2">
              <h5 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2 text-sm">
                <i class="fas fa-gamepad text-primary"></i>
                Équipements
              </h5>
              <div v-if="selectedSalle.equipements && selectedSalle.equipements.length > 0" class="flex flex-wrap gap-1">
                <span v-for="equipement in JSON.parse(selectedSalle.equipements)" :key="equipement" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                  {{ equipement }}
                </span>
              </div>
              <p v-else class="text-sm text-slate-500 dark:text-slate-400">Aucun équipement spécifié</p>
            </div>

            <!-- Services -->
            <div class="space-y-2">
              <h5 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2 text-sm">
                <i class="fas fa-concierge-bell text-primary"></i>
                Services
              </h5>
              <div v-if="selectedSalle.services && selectedSalle.services.length > 0" class="flex flex-wrap gap-1">
                <span v-for="service in JSON.parse(selectedSalle.services)" :key="service" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800">
                  {{ service }}
                </span>
              </div>
              <p v-else class="text-sm text-slate-500 dark:text-slate-400">Aucun service spécifié</p>
            </div>
          </div>

          <!-- Description -->
          <div class="space-y-2">
            <h5 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2 text-sm">
              <i class="fas fa-info-circle text-primary"></i>
              Description
            </h5>
            <p class="text-sm text-slate-600 dark:text-slate-400">{{ selectedSalle.description || 'Aucune description disponible' }}</p>
          </div>

          <!-- Horaires -->
          <div class="space-y-2">
            <h5 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2 text-sm">
              <i class="fas fa-clock text-primary"></i>
              Horaires d'ouverture
            </h5>
            <div v-if="selectedSalle.horaires" class="grid grid-cols-1 md:grid-cols-2 gap-2">
              <div v-for="(horaire, jour) in JSON.parse(selectedSalle.horaires)" :key="jour" class="flex justify-between text-xs">
                <span class="font-medium text-slate-700 dark:text-slate-300">{{ jour }}</span>
                <span class="text-slate-600 dark:text-slate-400">{{ horaire }}</span>
              </div>
            </div>
            <p v-else class="text-sm text-slate-500 dark:text-slate-400">Horaires non spécifiés</p>
          </div>

          <!-- Actions -->
          <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <button @click="showDetailModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg font-medium transition-colors">
              Fermer
            </button>
            <button v-if="selectedSalle.status === 'pending'" @click="approveSalle(selectedSalle); showDetailModal = false" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
              <i class="fas fa-check-circle mr-2"></i>
              Approuver
            </button>
            <button v-else-if="selectedSalle.status === 'active'" @click="toggleSalleStatus(selectedSalle, 'disabled'); showDetailModal = false" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium transition-colors">
              <i class="fas fa-pause-circle mr-2"></i>
              Désactiver
            </button>
            <button v-else-if="selectedSalle.status === 'disabled'" @click="toggleSalleStatus(selectedSalle, 'active'); showDetailModal = false" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
              <i class="fas fa-play-circle mr-2"></i>
              Réactiver
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
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
