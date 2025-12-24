<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalleController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Salle $salle)
    {
        return Inertia::render('Public/Salles/Show', [
            'salle' => $salle
        ]);
    }
}
