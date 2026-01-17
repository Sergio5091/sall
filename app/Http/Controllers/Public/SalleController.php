<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $salles = Salle::with(['promoter', 'images'])
            ->where('statut', 'approuvee')
            ->when($request->search, function($query, $search) {
                $query->where('nom', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('adresse', 'like', "%{$search}%");
            })
            ->when($request->type, function($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->min_price, function($query, $price) {
                $query->where('prix_heure', '>=', $price);
            })
            ->when($request->max_price, function($query, $price) {
                $query->where('prix_heure', '<=', $price);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Public/Salles/Index', [
            'salles' => $salles,
            'filters' => $request->only(['search', 'type', 'min_price', 'max_price'])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Salle $salle)
    {
        // Charger la salle avec toutes ses relations
        $salle->load(['promoter']);
        
        // Formater les données pour l'affichage
        $formattedSalle = [
            'id' => $salle->id,
            'nom' => $salle->nom,
            'slug' => $salle->slug,
            'description' => $salle->description,
            'type' => $salle->type,
            'categorie' => $salle->categorie,
            'adresse' => $salle->adresse,
            'code_postal' => $salle->code_postal,
            'ville' => $salle->ville,
            'pays' => $salle->pays,
            'region' => $salle->region,
            'departement' => $salle->departement,
            'quartier' => $salle->quartier,
            'latitude' => $salle->latitude,
            'longitude' => $salle->longitude,
            'telephone' => $salle->telephone,
            'whatsapp' => $salle->whatsapp,
            'email' => $salle->email,
            'site_web' => $salle->site_web,
            'reseaux_sociaux' => $salle->reseaux_sociaux,
            'horaires' => $salle->horaires,
            'capacite_max' => $salle->capacite_max,
            'surface_area' => $salle->surface_area,
            'machines_arcade' => $salle->machines_arcade,
            'casques_vr' => $salle->casques_vr,
            'flippers' => $salle->flippers,
            'consoles_retro' => $salle->consoles_retro,
            'pc_gaming' => $salle->pc_gaming,
            'tables_bowling' => $salle->tables_bowling,
            'tables_billard' => $salle->tables_billard,
            'wifi_gratuit' => $salle->wifi_gratuit,
            'parking' => $salle->parking,
            'climatisation' => $salle->climatisation,
            'accessibilite_pmr' => $salle->accessibilite_pmr,
            'surveillance_24h' => $salle->surveillance_24h,
            'snack_bar' => $salle->snack_bar,
            'restaurant' => $salle->restaurant,
            'bar' => $salle->bar,
            'terrasse' => $salle->terrasse,
            'espace_fumeur' => $salle->espace_fumeur,
            'vestiaires' => $salle->vestiaires,
            'services' => $salle->services,
            'prix_heure' => $salle->prix_heure,
            'prix_journee' => $salle->prix_journee,
            'tarifs' => $salle->tarifs,
            'image_url' => $salle->image_url,
            'images' => $salle->images,
            'video_presentation' => $salle->video_presentation,
            'images_360' => $salle->images_360,
            'statut' => $salle->statut,
            'valide' => $salle->valide,
            'note_moyenne' => $salle->note_moyenne,
            'nombre_avis' => $salle->nombre_avis,
            'nombre_vues' => $salle->nombre_vues,
            'nombre_favoris' => $salle->nombre_favoris,
            'promoter' => $salle->promoter,
            'created_at' => $salle->created_at,
            'updated_at' => $salle->updated_at,
        ];

        return Inertia::render('Public/Salles/Show', [
            'salle' => $formattedSalle
        ]);
    }
}
