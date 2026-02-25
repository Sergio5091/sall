<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  product: Object,
  categories: Array
})

const form = useForm({
  name: props.product.name,
  description: props.product.description,
  price: props.product.price,
  original_price: props.product.original_price || '',
  category: props.product.category,
  stock: props.product.stock,
  featured: props.product.featured || false,
  rating: props.product.rating || null,
  specifications: props.product.specifications || {},
  status: props.product.status,
  image: null,
  images: [],
  remove_images: []
})

const imagePreview = ref(props.product.image ? `/storage/${props.product.image}` : null)
const imagePreviews = ref([])
const specificationsInput = ref('')

// Initialiser les aperçus d'images existantes
onMounted(() => {
  if (props.product.images && Array.isArray(props.product.images)) {
    imagePreviews.value = props.product.images.map(img => `/storage/${img}`)
  }
  
  // Initialiser les spécifications
  if (props.product.specifications) {
    specificationsInput.value = JSON.stringify(props.product.specifications, null, 2)
  }
})

const handleImageChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.image = file
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const handleImagesChange = (event) => {
  const files = Array.from(event.target.files)
  form.images = files
  
  files.forEach(file => {
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreviews.value.push(e.target.result)
    }
    reader.readAsDataURL(file)
  })
}

const removeExistingImage = (index) => {
  if (props.product.images && props.product.images[index]) {
    form.remove_images.push(props.product.images[index])
    imagePreviews.value.splice(index, 1)
  }
}

const removeNewImage = (index) => {
  const existingImagesCount = props.product.images ? props.product.images.length : 0
  const actualIndex = index - existingImagesCount
  
  if (actualIndex >= 0 && actualIndex < form.images.length) {
    imagePreviews.value.splice(index, 1)
    form.images.splice(actualIndex, 1)
  }
}

const parseSpecifications = () => {
  try {
    if (specificationsInput.value.trim()) {
      form.specifications = JSON.parse(specificationsInput.value)
    } else {
      form.specifications = {}
    }
  } catch (error) {
    console.error('Invalid JSON:', error)
  }
}

const submit = () => {
  parseSpecifications()
  form.put(route('admin.products.update', {id: props.product.id}), {
    onSuccess: () => {
      // Réinitialiser les champs de fichiers
      form.image = null
      form.images = []
      form.remove_images = []
    }
  })
}
</script>

<template>
  <div>
    <!-- En-tête -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Modifier le produit</h1>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Modifiez les informations du produit "{{ product.name }}"
          </p>
        </div>
        <div class="flex items-center gap-3">
          <Link
            :href="route('admin.products.show', {id: product.id})"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            Voir
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

    <!-- Formulaire -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Colonne de gauche -->
          <div class="space-y-6">
            <!-- Nom du produit -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Nom du produit *
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Nom du produit"
              />
              <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                {{ form.errors.name }}
              </div>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Description *
              </label>
              <textarea
                v-model="form.description"
                required
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Description détaillée du produit"
              ></textarea>
              <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                {{ form.errors.description }}
              </div>
            </div>

            <!-- Catégorie -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Catégorie *
              </label>
              <input
                v-model="form.category"
                type="text"
                required
                list="categories"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Catégorie du produit"
              />
              <datalist id="categories">
                <option v-for="category in categories" :key="category" :value="category" />
              </datalist>
              <div v-if="form.errors.category" class="mt-1 text-sm text-red-600">
                {{ form.errors.category }}
              </div>
            </div>

            <!-- Prix -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Prix *
                </label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
                  <input
                    v-model="form.price"
                    type="number"
                    step="0.01"
                    min="0"
                    required
                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    placeholder="0.00"
                  />
                </div>
                <div v-if="form.errors.price" class="mt-1 text-sm text-red-600">
                  {{ form.errors.price }}
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Prix original
                </label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
                  <input
                    v-model="form.original_price"
                    type="number"
                    step="0.01"
                    min="0"
                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    placeholder="0.00"
                  />
                </div>
                <div v-if="form.errors.original_price" class="mt-1 text-sm text-red-600">
                  {{ form.errors.original_price }}
                </div>
              </div>
            </div>

            <!-- Stock -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Stock *
              </label>
              <input
                v-model.number="form.stock"
                type="number"
                min="0"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Quantité en stock"
              />
              <div v-if="form.errors.stock" class="mt-1 text-sm text-red-600">
                {{ form.errors.stock }}
              </div>
            </div>
          </div>

          <!-- Colonne de droite -->
          <div class="space-y-6">
            <!-- Image principale -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Image principale
              </label>
              <div class="mt-1 flex items-center space-x-4">
                <div class="flex-shrink-0">
                  <div v-if="imagePreview" class="w-24 h-24 rounded-lg overflow-hidden">
                    <img :src="imagePreview" alt="Aperçu" class="w-full h-full object-cover" />
                  </div>
                  <div
                    v-else
                    class="w-24 h-24 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center"
                  >
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                </div>
                <div class="flex-1">
                  <input
                    type="file"
                    @change="handleImageChange"
                    accept="image/*"
                    class="hidden"
                    id="main-image"
                  />
                  <label
                    for="main-image"
                    class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600"
                  >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Changer l'image
                  </label>
                  <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    PNG, JPG, GIF jusqu'à 2MB
                  </p>
                </div>
              </div>
              <div v-if="form.errors.image" class="mt-1 text-sm text-red-600">
                {{ form.errors.image }}
              </div>
            </div>

            <!-- Images multiples -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Images additionnelles
              </label>
              <input
                type="file"
                @change="handleImagesChange"
                accept="image/*"
                multiple
                class="hidden"
                id="additional-images"
              />
              <label
                for="additional-images"
                class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter des images
              </label>
              
              <!-- Aperçu des images -->
              <div v-if="imagePreviews.length > 0" class="mt-4 grid grid-cols-4 gap-2">
                <div
                  v-for="(preview, index) in imagePreviews"
                  :key="index"
                  class="relative group"
                >
                  <img
                    :src="preview"
                    alt="Aperçu"
                    class="w-full h-20 object-cover rounded-lg"
                  />
                  <button
                    type="button"
                    @click="index < (product.images?.length || 0) ? removeExistingImage(index) : removeNewImage(index)"
                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                  >
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
              <div v-if="form.errors.images" class="mt-1 text-sm text-red-600">
                {{ form.errors.images }}
              </div>
            </div>

            <!-- Options -->
            <div class="space-y-4">
              <div class="flex items-center">
                <input
                  v-model="form.featured"
                  type="checkbox"
                  id="featured"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                />
                <label for="featured" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                  Mettre en avant
                </label>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Statut *
                </label>
                <select
                  v-model="form.status"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                  <option value="draft">Brouillon</option>
                  <option value="active">Actif</option>
                  <option value="inactive">Inactif</option>
                </select>
                <div v-if="form.errors.status" class="mt-1 text-sm text-red-600">
                  {{ form.errors.status }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Note (0-5)
                </label>
                <input
                  v-model.number="form.rating"
                  type="number"
                  min="0"
                  max="5"
                  step="0.1"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  placeholder="4.5"
                />
                <div v-if="form.errors.rating" class="mt-1 text-sm text-red-600">
                  {{ form.errors.rating }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Spécifications -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Spécifications (JSON)
          </label>
          <textarea
            v-model="specificationsInput"
            rows="4"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono text-sm"
            placeholder='{"couleur": "rouge", "taille": "M", "matière": "coton"}'
          ></textarea>
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Format JSON. Ex: {"couleur": "rouge", "taille": "M"}
          </p>
          <div v-if="form.errors.specifications" class="mt-1 text-sm text-red-600">
            {{ form.errors.specifications }}
          </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
          <Link
            href="/admin/products"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
          >
            Annuler
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="form.processing">Mise à jour en cours...</span>
            <span v-else>Mettre à jour le produit</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
