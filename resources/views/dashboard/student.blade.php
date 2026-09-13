@extends('layouts.app')

@section('title', 'Espace Étudiant — Devia Technologic')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <div style="background: linear-gradient(135deg, var(--primary), var(--accent-purple)); color: white; width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.3rem; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);">
            🎓
        </div>
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main);">Bonjour, {{ $user->name }}</h2>
            <p style="color: var(--text-sub); font-size: 0.88rem;">Matricule : <strong>{{ $user->matricule }}</strong> | Filière : <strong>{{ $user->filiere }}</strong></p>
        </div>
    </div>
    <a href="{{ route('requests.create') }}" class="btn-primary">
        <span>+</span> Nouvelle Requête
    </a>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Requêtes Soumises</div>
        <div class="stat-value">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--warning);">
        <div class="stat-label">En Attente de Traitement</div>
        <div class="stat-value" style="color: var(--warning);">{{ $stats['en_attente'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--info);">
        <div class="stat-label">En Cours d'Instruction</div>
        <div class="stat-value" style="color: var(--info);">{{ $stats['en_instruction'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--success);">
        <div class="stat-label">Requêtes Approuvées</div>
        <div class="stat-value" style="color: var(--success);">{{ $stats['approuvees'] }}</div>
    </div>
</div>

<!-- Requests Table -->
<div class="card-panel">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-main);">Mes Requêtes Académiques</h3>
        <span style="font-size: 0.8rem; color: var(--text-sub);">Dernières requêtes enregistrées</span>
    </div>

    @if($requests->isEmpty())
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-sub);">
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
                            <td><strong style="color: var(--primary);">{{ $req->reference_code }}</strong></td>
                            <td>{{ $req->requestType->title ?? 'Requête Générale' }}</td>
                            <td>{{ $req->subject }}</td>
                            <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="status-pill status-{{ $req->status }}">
                                    {{ $req->status_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('requests.show', $req->id) }}" style="background: #f1f5f9; border: 1px solid var(--card-border); color: var(--text-main); padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
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
