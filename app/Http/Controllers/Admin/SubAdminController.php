<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class SubAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subAdmins = SubAdmin::with('user')->latest()->get();
        
        return Inertia::render('Admin/SubAdmins/Index', [
            'subAdmins' => $subAdmins
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/SubAdmins/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'country' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'sub_admin',
            'country' => $validated['country'],
            'city' => $validated['city'],
        ]);

        // Create sub admin
        SubAdmin::create([
            'user_id' => $user->id,
            'country' => $validated['country'],
            'city' => $validated['city'],
            'permissions' => $validated['permissions'] ?? [],
            'status' => 'active',
        ]);

        return redirect()->route('admin.sub-admins.index')
            ->with('success', 'Sous-admin créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubAdmin $subAdmin)
    {
        $subAdmin->load('user');
        
        return Inertia::render('Admin/SubAdmins/Show', [
            'subAdmin' => $subAdmin
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubAdmin $subAdmin)
    {
        $subAdmin->load('user');
        
        return Inertia::render('Admin/SubAdmins/Edit', [
            'subAdmin' => $subAdmin
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubAdmin $subAdmin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $subAdmin->user_id,
            'country' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        // Update user
        $subAdmin->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'country' => $validated['country'],
            'city' => $validated['city'],
        ]);

        // Update sub admin
        $subAdmin->update([
            'country' => $validated['country'],
            'city' => $validated['city'],
            'permissions' => $validated['permissions'] ?? [],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.sub-admins.index')
            ->with('success', 'Sous-admin mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubAdmin $subAdmin)
    {
        // Delete user
        $subAdmin->user->delete();
        
        // Delete sub admin
        $subAdmin->delete();

        return redirect()->route('admin.sub-admins.index')
            ->with('success', 'Sous-admin supprimé avec succès.');
    }

    /**
     * Toggle sub admin status
     */
    public function toggleStatus(SubAdmin $subAdmin)
    {
        $subAdmin->status = $subAdmin->status === 'active' ? 'inactive' : 'active';
        $subAdmin->save();

        return back()->with('success', 'Statut du sous-admin mis à jour avec succès.');
    }
}
