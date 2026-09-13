<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matricule' => 'required|string|max:100|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'role' => 'required|in:etudiant,gestionnaire,responsable_pedagogique,admin_systeme',
            'filiere' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'matricule' => $validated['matricule'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'filiere' => $validated['filiere'],
            'password' => Hash::make($validated['password']),
        ]);

        try {
            Mail::to($user->email)->send(new UserCreatedMail($user));
        } catch (\Throwable $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de bienvenue: ' . $e->getMessage());
        }

        return back()->with('success', 'Utilisateur créé avec succès et notification envoyée par e-mail.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:etudiant,gestionnaire,responsable_pedagogique,admin_systeme',
            'filiere' => 'required|string|max:255',
        ]);

        $user->update($validated);

        return back()->with('success', 'Informations utilisateur mises à jour.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        $user->delete();
        return back()->with('success', 'Compte utilisateur supprimé.');
    }
}
