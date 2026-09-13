@extends('layouts.app')

@section('title', 'Connexion — Devia Technologic')

@section('content')
<div style="max-width: 1040px; margin: 2rem auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 2rem; align-items: center;">
    
    <!-- Hero Showcase Card (Left) -->
    <div style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #db2777 100%); padding: 3rem 2.5rem; border-radius: 28px; color: white; box-shadow: 0 20px 40px rgba(79, 70, 229, 0.3); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; min-height: 520px;">
        <div style="position: absolute; top: -40px; right: -40px; width: 180px; height: 180px; background: rgba(255,255,255,0.1); border-radius: 50%; pointer-events: none;"></div>
        <div style="position: absolute; bottom: -60px; left: -60px; width: 220px; height: 220px; background: rgba(255,255,255,0.08); border-radius: 50%; pointer-events: none;"></div>
        
        <div style="position: relative; z-index: 2;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3); padding: 6px 14px; border-radius: 50px; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1.5rem;">
                ✨ Devia Technologic
            </div>
            <h2 style="font-size: 2.2rem; font-weight: 800; line-height: 1.25; margin-bottom: 1rem; color: white;">
                Gestion Numérique & Intelligente des Requêtes
            </h2>
            <p style="font-size: 1rem; opacity: 0.9; line-height: 1.6; font-weight: 400;">
                Plateforme centralisée de traitement des demandes académiques, réclamations de notes et attestations en temps réel.
            </p>
        </div>

        <div style="position: relative; z-index: 2; margin-top: 2rem;">
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.12); padding: 12px 16px; border-radius: 14px; backdrop-filter: blur(8px);">
                    <div style="font-size: 1.2rem;">⚡</div>
                    <div>
                        <strong style="display: block; font-size: 0.9rem;">Traitement Accéléré</strong>
                        <span style="font-size: 0.78rem; opacity: 0.85;">Suivi étape par étape jusqu'à la décision</span>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.12); padding: 12px 16px; border-radius: 14px; backdrop-filter: blur(8px);">
                    <div style="font-size: 1.2rem;">🔒</div>
                    <div>
                        <strong style="display: block; font-size: 0.9rem;">Sécurité & Traçabilité</strong>
                        <span style="font-size: 0.78rem; opacity: 0.85;">Historique d'audit complet par dossier</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Form Card (Right) -->
    <div style="background: white; border: 1px solid var(--border-subtle); border-radius: 28px; padding: 3rem 2.5rem; box-shadow: var(--shadow-lg);">
        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.8rem; font-weight: 800; color: var(--text-heading); margin-bottom: 6px;">Connexion</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Accédez à votre espace sécurisé Devia Technologic</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.88rem; font-weight: 700; margin-bottom: 8px; color: var(--text-heading);">
                    Identifiant (Matricule ou Email) *
                </label>
                <div style="position: relative;">
                    <input type="text" name="email" id="inputEmail" value="" placeholder="ex: DEV-2026-001 ou nom@deviatech.com" required style="width: 100%; padding: 14px 16px; background: #f8fafc; border: 1.5px solid var(--border-subtle); border-radius: 14px; color: var(--text-heading); font-size: 0.95rem; outline: none; transition: all 0.2s ease;">
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label style="font-size: 0.88rem; font-weight: 700; color: var(--text-heading);">Mot de passe *</label>
                </div>
                <input type="password" name="password" id="inputPassword" value="" placeholder="••••••••" required style="width: 100%; padding: 14px 16px; background: #f8fafc; border: 1.5px solid var(--border-subtle); border-radius: 14px; color: var(--text-heading); font-size: 0.95rem; outline: none; transition: all 0.2s ease;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 1rem; border-radius: 14px;">
                Se Connecter au Portail
            </button>
        </form>

        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle); text-align: center; font-size: 0.9rem; color: var(--text-muted);">
            Nouveau sur la plateforme ? <a href="{{ route('register') }}" style="color: var(--brand-primary); text-decoration: none; font-weight: 700;">Créer un compte</a>
        </div>
    </div>
</div>
@endsection
