@extends('layouts.app')

@section('title', 'Nouveau Mot de Passe — Devia Technologic')

@section('content')
<div style="max-width: 520px; margin: 3rem auto;">
    <div class="card-panel">
        <div style="margin-bottom: 1.5rem; text-align: center;">
            <div style="background: linear-gradient(135deg, var(--brand-emerald), var(--brand-primary)); color: white; width: 52px; height: 52px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; box-shadow: var(--shadow-glow);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
            </div>
            <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--text-heading);">Nouveau Mot de Passe</h2>
            <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 6px;">
                Définissez votre nouveau mot de passe pour accéder à votre compte.
            </p>
        </div>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.88rem; font-weight: 700; margin-bottom: 8px; color: var(--text-heading);">Adresse Email *</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" required readonly style="width: 100%; padding: 14px 16px; background: #f8fafc; border: 1.5px solid var(--border-subtle); border-radius: 14px; color: var(--text-muted); font-size: 0.95rem; cursor: not-allowed;">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.88rem; font-weight: 700; margin-bottom: 8px; color: var(--text-heading);">Nouveau mot de passe *</label>
                <input type="password" name="password" required placeholder="••••••••" style="width: 100%; padding: 14px 16px; background: #ffffff; border: 1.5px solid var(--border-subtle); border-radius: 14px; color: var(--text-heading); font-size: 0.95rem; outline: none;">
            </div>

            <div style="margin-bottom: 1.75rem;">
                <label style="display: block; font-size: 0.88rem; font-weight: 700; margin-bottom: 8px; color: var(--text-heading);">Confirmer le nouveau mot de passe *</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••" style="width: 100%; padding: 14px 16px; background: #ffffff; border: 1.5px solid var(--border-subtle); border-radius: 14px; color: var(--text-heading); font-size: 0.95rem; outline: none;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 1rem;">
                Réinitialiser et Se Connecter
            </button>
        </form>
    </div>
</div>
@endsection
