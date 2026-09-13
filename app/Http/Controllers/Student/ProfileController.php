<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('profile.settings', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'filiere' => 'required|string|max:255',
        ]);

        $user->update($validated);

        return back()->with('success', 'Vos paramètres de profil ont été mis à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Votre mot de passe a été modifié avec succès.');
    }
}
