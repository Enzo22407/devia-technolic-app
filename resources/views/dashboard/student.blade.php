@extends('layouts.app')

@section('title', 'Espace Étudiant — Devia Technologic')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <div style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-violet)); color: white; width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-glow);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
        </div>
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-heading);">Bonjour, {{ $user->name }}</h2>
            <p style="color: var(--text-muted); font-size: 0.88rem;">Matricule : <strong>{{ $user->matricule }}</strong> | Filière : <strong>{{ $user->filiere }}</strong></p>
        </div>
    </div>
    <a href="{{ route('requests.create') }}" class="btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Nouvelle Requête
    </a>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Requêtes Soumises</div>
        <div class="stat-value">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--brand-amber);">
        <div class="stat-label">En Attente de Traitement</div>
        <div class="stat-value" style="color: var(--brand-amber);">{{ $stats['en_attente'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--brand-primary);">
        <div class="stat-label">En Cours d'Instruction</div>
        <div class="stat-value" style="color: var(--brand-primary);">{{ $stats['en_instruction'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--brand-emerald);">
        <div class="stat-label">Requêtes Approuvées</div>
        <div class="stat-value" style="color: var(--brand-emerald);">{{ $stats['approuvees'] }}</div>
    </div>
</div>

<!-- Requests Table -->
<div class="card-panel">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-heading);">Mes Requêtes Académiques</h3>
        <span style="font-size: 0.8rem; color: var(--text-muted);">Dernières requêtes enregistrées</span>
    </div>

    @if($requests->isEmpty())
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <p style="font-size: 1.1rem; margin-bottom: 1rem;">Vous n'avez pas encore déposé de requête.</p>
            <a href="{{ route('requests.create') }}" class="btn-primary">Déposer ma première requête</a>
        </div>
    @else
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Type de Requête</th>
                        <th>Objet</th>
                        <th>Date Dépôt</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                        <tr>
                            <td><strong style="color: var(--brand-primary);">{{ $req->reference_code }}</strong></td>
                            <td>{{ $req->requestType->title ?? 'Requête Générale' }}</td>
                            <td>{{ $req->subject }}</td>
                            <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="status-pill status-{{ $req->status }}">
                                    {{ $req->status_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('requests.show', $req->id) }}" style="background: #f1f5f9; border: 1px solid var(--border-subtle); color: var(--text-heading); padding: 6px 14px; border-radius: 8px; text-decoration: none; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    Consulter →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
