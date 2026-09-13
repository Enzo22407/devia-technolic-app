@extends('layouts.app')

@section('title', 'Mot de Passe Oublié — Devia Technologic')

@section('content')
<div style="max-width: 520px; margin: 3rem auto;">
    <div class="card-panel">
        <div style="margin-bottom: 1.5rem; text-align: center;">
            <div style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-violet)); color: white; width: 52px; height: 52px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; box-shadow: var(--shadow-glow);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>
            <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--text-heading);">Mot de passe oublié ?</h2>
            <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 6px;">
                Saisissez votre adresse e-mail ci-dessous pour recevoir un lien de réinitialisation sécurisé.
            </p>
        </div>

        @if (session('status'))
            <div class="alert-success" style="margin-bottom: 1.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.88rem; font-weight: 700; margin-bottom: 8px; color: var(--text-heading);">
                    Adresse Email *
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nom@deviatech.com" style="width: 100%; padding: 14px 16px; background: #f8fafc; border: 1.5px solid var(--border-subtle); border-radius: 14px; color: var(--text-heading); font-size: 0.95rem; outline: none;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 1rem;">
                Envoyer le lien de réinitialisation
            </button>
        </form>

        <div style="margin-top: 1.5rem; text-align: center; font-size: 0.88rem; color: var(--text-muted);">
            <a href="{{ route('login') }}" style="color: var(--brand-primary); text-decoration: none; font-weight: 700;">← Retour à la connexion</a>
        </div>
    </div>
</div>
@endsection
