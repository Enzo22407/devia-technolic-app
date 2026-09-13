@extends('layouts.app')

@section('title', 'Créer un compte — Devia Technologic')

@section('content')
<div style="max-width: 520px; margin: 2rem auto;">
    <div class="card-panel" style="padding: 2.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-main);">Création de Compte Etudiant / Personnel</h2>
        <p style="color: var(--text-sub); font-size: 0.88rem; margin-bottom: 2rem;">Inscrivez-vous pour soumettre et suivre l'avancement de vos demandes académiques.</p>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Rôle / Type de compte *</label>
                <select name="role" required style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-main); font-size: 0.95rem;">
                    <option value="etudiant">Étudiant / Demandeur</option>
                    <option value="gestionnaire">Personnel Administratif (Gestionnaire)</option>
                    <option value="responsable_pedagogique">Responsable Pédagogique (Enseignant/Chef Dép.)</option>
                </select>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Nom complet *</label>
                <input type="text" name="name" required placeholder="ex: Thirdboy Devia" style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-main); font-size: 0.95rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Matricule *</label>
                    <input type="text" name="matricule" required placeholder="DEV-2026-XXXX" style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-main); font-size: 0.95rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Filière / Service *</label>
                    <input type="text" name="filiere" required placeholder="GL2 Prépa / Scolarité" style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-main); font-size: 0.95rem;">
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Adresse Email *</label>
                <input type="email" name="email" required placeholder="nom@deviatech.com" style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-main); font-size: 0.95rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Mot de passe *</label>
                    <input type="password" name="password" required style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-main); font-size: 0.95rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Confirmer *</label>
                    <input type="password" name="password_confirmation" required style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-main); font-size: 0.95rem;">
                </div>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 12px; font-size: 1rem;">
                Finaliser l'Inscription
            </button>
        </form>

        <div style="margin-top: 1.5rem; text-align: center; font-size: 0.85rem; color: var(--text-sub);">
            Vous possédez déjà un compte ? <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 700;">Se connecter</a>
        </div>
    </div>
</div>
@endsection
