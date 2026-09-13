@extends('layouts.app')

@section('title', 'Connexion — Devia Technologic')

@section('content')
<div style="max-width: 440px; margin: 3rem auto;">
    <div class="card-panel" style="padding: 2.5rem; text-align: center;">
        <div class="brand-badge" style="display: inline-block; font-size: 1.1rem; padding: 8px 18px; margin-bottom: 1rem;">DEVIA</div>
        <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-main);">Espace Authentification</h2>
        <p style="color: var(--text-sub); font-size: 0.88rem; margin-bottom: 2rem;">Accédez à votre portail de gestion des requêtes</p>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div style="text-align: left; margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Identifiant (Matricule ou Email) *</label>
                <input type="text" name="email" id="inputEmail" value="" placeholder="ex: DEV-2026-001 ou nom@deviatech.com" required style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-main); font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
            </div>

            <div style="text-align: left; margin-bottom: 1.75rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Mot de passe *</label>
                <input type="password" name="password" id="inputPassword" value="" placeholder="••••••••" required style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-main); font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 12px; font-size: 1rem;">
                Se Connecter au Portail
            </button>
        </form>

        <div style="margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1px solid var(--card-border); font-size: 0.85rem; color: var(--text-sub);">
            Nouveau sur la plateforme ? <a href="{{ route('register') }}" style="color: var(--primary); text-decoration: none; font-weight: 700;">Créer un compte</a>
        </div>
    </div>
</div>
@endsection
