<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  product: Object
})

const getStatusClass = (status) => {
  const classes = {
    active: 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800',
    inactive: 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-800',
    draft: 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 border border-gray-200 dark:border-gray-800'
  }
  return classes[status] || classes.draft
}

const getStatusLabel = (status) => {
  const labels = {
    active: 'Actif',
    inactive: 'Inactif',
    draft: 'Brouillon'
  }
  return labels[status] || status
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR'
  }).format(price)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<template>
  <div>
    <!-- En-tête -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Détails du produit</h1>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Consultez les informations détaillées du produit "{{ product.name }}"
          </p>
        </div>
        <div class="flex items-center gap-3">
          <Link
            :href="`/admin/products/${product.id}/edit`"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Modifier
          </Link>
          <Link
            href="/admin/products"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour
          </Link>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Colonne principale -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Informations générales -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations générales</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Nom du produit
              </label>
              <p class="text-gray-900 dark:text-white">{{ product.name }}</p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Catégorie
              </label>
              <p class="text-gray-900 dark:text-white">{{ product.category }}</p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                SKU / Slug
              </label>
              <p class="text-gray-900 dark:text-white font-mono text-sm">{{ product.slug }}</p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Statut
              </label>
              <div class="flex items-center gap-2">
                <span :class="getStatusClass(product.status)">
                  {{ getStatusLabel(product.status) }}
                </span>
                <span
                  v-if="product.featured"
                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800"
                >
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                  Mis en avant
                </span>
              </div>
            </div>
          </div>
          
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Description
            </label>
            <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ product.description }}</p>
          </div>
        </div>

        <!-- Images -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Images</h2>
          
          <div class="space-y-4">
            <!-- Image principale -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Image principale
              </label>
              <div v-if="product.image" class="w-64 h-64 rounded-lg overflow-hidden">
                <img
                  :src="`/storage/${product.image}`"
                  :alt="product.name"
                  class="w-full h-full object-cover"
                />
              </div>
              <div
                v-else
                class="w-64 h-64 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center"
              >
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            </div>
            
            <!-- Images additionnelles -->
            <div v-if="product.images && product.images.length > 0">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Images additionnelles
              </label>
              <div class="grid grid-cols-4 gap-2">
                <div
                  v-for="(image, index) in product.images"
                  :key="index"
                  class="w-24 h-24 rounded-lg overflow-hidden"
                >
                  <img
                    :src="`/storage/${image}`"
                    :alt="`${product.name} - Image ${index + 1}`"
                    class="w-full h-full object-cover"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Spécifications -->
        <div
          v-if="product.specifications && Object.keys(product.specifications).length > 0"
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Spécifications</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="(value, key) in product.specifications"
              :key="key"
              class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700"
            >
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ key }}
              </span>
              <span class="text-sm text-gray-900 dark:text-white">
                {{ value }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Colonne latérale -->
      <div class="space-y-6">
        <!-- Prix et stock -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Prix et stock</h2>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Prix
              </label>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ formatPrice(product.price) }}
              </p>
            </div>
            
            <div v-if="product.original_price && product.original_price > product.price">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Prix original
              </label>
              <p class="text-lg text-gray-500 line-through">
                {{ formatPrice(product.original_price) }}
              </p>
              <p class="text-sm text-green-600 dark:text-green-400">
                Économisez {{ formatPrice(product.original_price - product.price) }}
                ({{ Math.round(((product.original_price - product.price) / product.original_price) * 100) }}%)
              </p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Stock
              </label>
              <div class="flex items-center gap-2">
                <span
                  :class="product.stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                  class="text-lg font-semibold"
                >
                  {{ product.stock }}
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                  unités
                </span>
              </div>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ product.stock > 0 ? 'En stock' : 'Rupture de stock' }}
              </p>
            </div>
            
            <div v-if="product.rating">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Note moyenne
              </label>
              <div class="flex items-center gap-2">
                <div class="flex items-center">
                  <span class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ product.rating }}
                  </span>
                  <span class="text-sm text-gray-500 dark:text-gray-400">/5</span>
                </div>
                <div class="flex items-center">
                  <svg
                    v-for="i in 5"
                    :key="i"
                    class="w-4 h-4"
                    :class="i <= Math.round(product.rating) ? 'text-yellow-400' : 'text-gray-300'"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </div>
              </div>
              <p v-if="product.reviews_count" class="text-sm text-gray-500 dark:text-gray-400">
                {{ product.reviews_count }} avis
              </p>
            </div>
          </div>
        </div>

        <!-- Métadonnées -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Métadonnées</h2>
          
          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                ID
              </label>
              <p class="text-gray-900 dark:text-white font-mono text-sm">{{ product.id }}</p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Créé le
              </label>
              <p class="text-gray-900 dark:text-white">{{ formatDate(product.created_at) }}</p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Modifié le
              </label>
              <p class="text-gray-900 dark:text-white">{{ formatDate(product.updated_at) }}</p>
            </div>
            
            <div v-if="product.creator">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Créé par
              </label>
              <p class="text-gray-900 dark:text-white">{{ product.creator.name }}</p>
            </div>
          </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions rapides</h2>
          
          <div class="space-y-2">
            <Link
              :href="`/admin/products/${product.id}/edit`"
              class="block w-full text-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
            >
              Modifier le produit
            </Link>
            
            <Link
              :href="`/admin/products/${product.id}/duplicate`"
              method="post"
              as="button"
              class="block w-full px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700"
            >
              Dupliquer le produit
            </Link>
            
            <Link
              href="/admin/products"
              class="block w-full text-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
            >
              Retour à la liste
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
