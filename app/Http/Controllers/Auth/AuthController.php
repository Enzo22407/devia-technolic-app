<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Support login by Email OR Matricule
        $fieldType = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'matricule';

        if (Auth::attempt([$fieldType => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['Les identifiants fournis ne correspondent à aucun compte enregistré.'],
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matricule' => 'required|string|max:100|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:etudiant,gestionnaire,responsable_pedagogique',
            'filiere' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'matricule' => $validated['matricule'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'filiere' => $validated['filiere'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Votre compte a été créé avec succès. Bienvenue sur la plateforme !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
