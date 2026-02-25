<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Afficher la liste des produits
     */
    public function index(Request $request)
    {
        $query = Product::with('creator');

        // Filtrage par recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        // Filtrage par catégorie
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filtrage par statut
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->where('is_active', 1);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', 0);
            }
        }

        // Filtrage par featured
        if ($request->has('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Transformer les produits pour garantir les types corrects
        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => (float) $product->price,
                'original_price' => $product->old_price ? (float) $product->old_price : null,
                'category' => $product->category,
                'stock' => (int) $product->stock,
                'is_active' => (bool) $product->is_active,
                'is_featured' => (bool) $product->is_featured,
                'rating' => (float) $product->rating,
                'image' => $product->image,
                'created_at' => $product->created_at->toISOString(),
                'updated_at' => $product->updated_at->toISOString(),
            ];
        });

        // Statistiques
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', 1)->count(),
            'inactive' => Product::where('is_active', 0)->count(),
            'featured' => Product::where('is_featured', 1)->count(),
            'out_of_stock' => Product::where('stock', '<=', 0)->count(),
        ];

        // Catégories disponibles
        $categories = Product::distinct()->pluck('category')->filter()->values();

        return Inertia::render('Admin/Products', [
            'products' => $products,
            'stats' => $stats,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'status', 'featured']),
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Product::distinct()->pluck('category')->filter()->values();

        return Inertia::render('Admin/ProductCreate', [
            'categories' => $categories,
        ]);
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0|gt:price',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'specifications' => 'nullable|array',
            'is_active' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Debug: Vérifions si l'image est bien envoyée
        \Log::info('Image file exists: ' . ($request->hasFile('image') ? 'YES' : 'NO'));
        if ($request->hasFile('image')) {
            \Log::info('Image file: ' . $request->file('image')->getClientOriginalName());
        }

        // Gérer l'image principale
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        } else {
            $validated['image'] = null; // Forcer le champ même si null
        }

        // Gérer les images multiples
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        // Supprimer les champs qui n'existent pas dans la base de données
        unset($validated['featured']);
        unset($validated['images']);
        unset($validated['created_by']);

        // Debug: Vérifions les données finales
        $imageStatus = $validated['image'] ?? 'NULL';
        
        Product::create($validated);

        // Debug temporaire : affichons les données dans la session
        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès. Image: ' . $imageStatus);
    }

    /**
     * Afficher les détails d'un produit
     */
    public function show(Product $product)
    {
        $product->load('creator', 'reviews');

        return Inertia::render('Admin/ProductShow', [
            'product' => $product,
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Product $product)
    {
        $categories = Product::distinct()->pluck('category')->filter()->values();

        return Inertia::render('Admin/ProductEdit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Mettre à jour un produit
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0|gt:price',
            'category' => 'sometimes|required|string|max:100',
            'stock' => 'sometimes|required|integer|min:0',
            'featured' => 'boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'specifications' => 'nullable|array',
            'is_active' => 'sometimes|required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
        ]);

        $data = [];

        // Only update fields that are provided
        if ($request->has('name')) {
            $data['name'] = $validated['name'];
        }
        if ($request->has('description')) {
            $data['description'] = $validated['description'];
        }
        if ($request->has('price')) {
            $data['price'] = $validated['price'];
        }
        if ($request->has('original_price')) {
            $data['original_price'] = $validated['original_price'];
        }
        if ($request->has('category')) {
            $data['category'] = $validated['category'];
        }
        if ($request->has('stock')) {
            $data['stock'] = $validated['stock'];
        }
        if ($request->has('featured')) {
            $data['featured'] = $validated['featured'];
        }
        if ($request->has('rating')) {
            $data['rating'] = $validated['rating'];
        }
        if ($request->has('specifications')) {
            $data['specifications'] = $validated['specifications'];
        }
        if ($request->has('is_active')) {
            $data['is_active'] = $validated['is_active'];
        }
        
        // Handle optional fields
        if ($request->has('remove_images')) {
            $data['remove_images'] = $validated['remove_images'];
        }

        // Gérer l'image principale
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // Gérer les images multiples
        $currentImages = $product->images ?? [];
        
        // Supprimer les images sélectionnées
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageToRemove) {
                if (($key = array_search($imageToRemove, $currentImages)) !== false) {
                    unset($currentImages[$key]);
                    Storage::disk('public')->delete($imageToRemove);
                }
            }
        }

        // Ajouter les nouvelles images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $currentImages[] = $path;
            }
        }

        $validated['images'] = array_values($currentImages);

        // Supprimer les champs qui n'existent pas dans la base de données
        unset($validated['featured']);
        unset($validated['images']);
        unset($validated['created_by']);

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour avec succès');
    }

    /**
     * Supprimer un produit
     */
    public function destroy(Product $product)
    {
        // Supprimer les images
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé avec succès');
    }

    /**
     * Dupliquer un produit
     */
    public function duplicate(Product $product)
    {
        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' (Copie)';
        $newProduct->is_active = 0; // Inactif par défaut pour la copie
        $newProduct->is_featured = 0;
        $newProduct->save();

        return redirect()->route('admin.products.edit', $newProduct)
            ->with('success', 'Produit dupliqué avec succès');
    }

    /**
     * Action groupée
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,activate,deactivate,feature,unfeature',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $products = Product::whereIn('id', $validated['product_ids']);

        switch ($validated['action']) {
            case 'delete':
                foreach ($products->get() as $product) {
                    if ($product->image) {
                        Storage::disk('public')->delete($product->image);
                    }
                    if ($product->images) {
                        foreach ($product->images as $image) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                }
                $products->delete();
                $message = 'Produits supprimés avec succès';
                break;
            case 'activate':
                $products->update(['is_active' => 1]);
                $message = 'Produits activés avec succès';
                break;
            case 'deactivate':
                $products->update(['is_active' => 0]);
                $message = 'Produits désactivés avec succès';
                break;
            case 'feature':
                $products->update(['is_featured' => 1]);
                $message = 'Produits mis en avant avec succès';
                break;
            case 'unfeature':
                $products->update(['is_featured' => 0]);
                $message = 'Produits retirés de la mise en avant';
                break;
        }

        return redirect()->route('admin.products.index')
            ->with('success', $message);
    }

    /**
     * Exporter les produits
     */
    public function export(Request $request)
    {
        $products = Product::with('creator')->get();

        $csv = "Nom,Description,Prix,Catégorie,Stock,Statut,Mis en avant,Créé par,Créé le\n";
        
        foreach ($products as $product) {
            $csv .= sprintf(
                "\"%s\",\"%s\",%.2f,\"%s\",%d,\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $product->name,
                str_replace('"', '""', $product->description),
                $product->price,
                $product->category,
                $product->stock,
                $product->status,
                $product->featured ? 'Oui' : 'Non',
                $product->creator->name ?? 'N/A',
                $product->created_at->format('d/m/Y H:i')
            );
        }

        $filename = 'products_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
