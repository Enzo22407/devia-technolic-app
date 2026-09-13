@extends('layouts.app')

@section('title', 'Paramètres du Compte — Devia Technologic')

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--text-heading);">Paramètres du Compte & Profil</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Gérez vos informations personnelles, coordonnées et sécurité du compte</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; width: 100%;">
    
    <!-- Profile Info Card -->
    <div class="card-panel">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-subtle);">
            <div style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-violet)); color: white; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem;">
                👤
            </div>
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-heading);">Informations Personnelles</h3>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Mettez à jour vos identifiants et coordonnées</p>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Nom complet *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Matricule (Lecture seule)</label>
                <input type="text" value="{{ $user->matricule }}" disabled style="width: 100%; padding: 12px 14px; background: #f8fafc; border: 1px solid var(--border-subtle); border-radius: 12px; color: var(--text-muted); font-size: 0.92rem; cursor: not-allowed;">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Adresse Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Filière / Département *</label>
                <input type="text" name="filiere" value="{{ old('filiere', $user->filiere) }}" required style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
            </div>

            <div style="margin-bottom: 1.75rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Numéro de Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+237 6XX XX XX XX" style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                Enregistrer les Modifications
            </button>
        </form>
    </div>

    <!-- Security & Password Card -->
    <div class="card-panel">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-subtle);">
            <div style="background: linear-gradient(135deg, var(--brand-pink), var(--brand-rose)); color: white; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem;">
                🔒
            </div>
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-heading);">Sécurité & Mot de Passe</h3>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Renforcez l'accès à votre compte</p>
            </div>
        </div>

        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            @method('PATCH')

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Mot de passe actuel *</label>
                <input type="password" name="current_password" required placeholder="••••••••" style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Nouveau mot de passe *</label>
                <input type="password" name="password" required placeholder="••••••••" style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
            </div>

            <div style="margin-bottom: 1.75rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Confirmer le nouveau mot de passe *</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••" style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; background: linear-gradient(135deg, var(--brand-pink), var(--brand-rose));">
                Changer mon Mot de Passe
            </button>
        </form>
    </div>

</div>
@endsection
