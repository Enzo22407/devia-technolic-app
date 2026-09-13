@extends('layouts.app')

@section('title', 'Créer un compte — Devia Technologic')

@section('content')
<div style="max-width: 1080px; margin: 2rem auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 2rem; align-items: start;">
    
    <!-- Hero Showcase Card (Left) -->
    <div style="background: linear-gradient(135deg, #0284c7 0%, #4f46e5 50%, #7c3aed 100%); padding: 3rem 2.5rem; border-radius: 28px; color: white; box-shadow: 0 20px 40px rgba(2, 132, 199, 0.25); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; min-height: 580px;">
        <div style="position: absolute; top: -40px; right: -40px; width: 180px; height: 180px; background: rgba(255,255,255,0.1); border-radius: 50%; pointer-events: none;"></div>
        
        <div style="position: relative; z-index: 2;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3); padding: 6px 14px; border-radius: 50px; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1.5rem;">
                🎓 Espace Inscription
            </div>
            <h2 style="font-size: 2.2rem; font-weight: 800; line-height: 1.25; margin-bottom: 1rem; color: white;">
                Rejoignez le Réseau Numérique Devia
            </h2>
            <p style="font-size: 1rem; opacity: 0.9; line-height: 1.6; font-weight: 400;">
                Créez votre compte en moins d'une minute pour soumettre vos demandes, suivre leur statut et télécharger vos documents certifiés.
            </p>
        </div>

        <div style="position: relative; z-index: 2; margin-top: 2rem;">
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.12); padding: 12px 16px; border-radius: 14px; backdrop-filter: blur(8px);">
                    <div style="font-size: 1.2rem;">📂</div>
                    <div>
                        <strong style="display: block; font-size: 0.9rem;">Dépôt 100% Dématérialisé</strong>
                        <span style="font-size: 0.78rem; opacity: 0.85;">Joignez vos pièces justificatives en toute sécurité</span>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.12); padding: 12px 16px; border-radius: 14px; backdrop-filter: blur(8px);">
                    <div style="font-size: 1.2rem;">🔔</div>
                    <div>
                        <strong style="display: block; font-size: 0.9rem;">Notifications Directes</strong>
                        <span style="font-size: 0.78rem; opacity: 0.85;">Soyez informé dès qu'une décision est enregistrée</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Form Card (Right) -->
    <div style="background: white; border: 1px solid var(--border-subtle); border-radius: 28px; padding: 2.5rem; box-shadow: var(--shadow-lg);">
        <div style="margin-bottom: 1.75rem;">
            <h2 style="font-size: 1.8rem; font-weight: 800; color: var(--text-heading); margin-bottom: 6px;">Créer un Compte</h2>
            <p style="color: var(--text-muted); font-size: 0.88rem;">Remplissez les informations ci-dessous pour créer votre profil</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Profil / Type d'Accès *</label>
                <select name="role" required style="width: 100%; padding: 12px 14px; background: #f8fafc; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
                    <option value="etudiant">👨‍🎓 Étudiant / Demandeur</option>
                    <option value="gestionnaire">📋 Personnel Administratif (Gestionnaire)</option>
                    <option value="responsable_pedagogique">🎓 Responsable Pédagogique (Enseignant)</option>
                </select>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Nom complet *</label>
                <input type="text" name="name" required placeholder="ex: Thirdboy Devia" style="width: 100%; padding: 12px 14px; background: #f8fafc; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Matricule *</label>
                    <input type="text" name="matricule" required placeholder="DEV-2026-0099" style="width: 100%; padding: 12px 14px; background: #f8fafc; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Filière / Service *</label>
                    <input type="text" name="filiere" required placeholder="GL2 Prépa / Scolarité" style="width: 100%; padding: 12px 14px; background: #f8fafc; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Adresse Email *</label>
                <input type="email" name="email" required placeholder="nom@deviatech.com" style="width: 100%; padding: 12px 14px; background: #f8fafc; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.75rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Mot de passe *</label>
                    <input type="password" name="password" required style="width: 100%; padding: 12px 14px; background: #f8fafc; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--text-heading);">Confirmer *</label>
                    <input type="password" name="password_confirmation" required style="width: 100%; padding: 12px 14px; background: #f8fafc; border: 1.5px solid var(--border-subtle); border-radius: 12px; color: var(--text-heading); font-size: 0.92rem; outline: none;">
                </div>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 1rem; border-radius: 14px;">
                Finaliser l'Inscription
            </button>
        </form>

        <div style="margin-top: 1.5rem; text-align: center; font-size: 0.88rem; color: var(--text-muted);">
            Vous possédez déjà un compte ? <a href="{{ route('login') }}" style="color: var(--brand-primary); text-decoration: none; font-weight: 700;">Se connecter</a>
        </div>
    </div>
</div>
@endsection
