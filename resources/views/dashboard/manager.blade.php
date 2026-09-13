@extends('layouts.app')

@section('title', 'Espace Gestion — Devia Technologic')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--text-main);">Tableau de Bord Gestionnaire</h2>
        <p style="color: var(--text-sub); font-size: 0.9rem;">
            Agent : <strong>{{ $user->name }}</strong> | Habilitation : <span class="role-badge role-{{ $user->role }}">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
        </p>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Dossiers Enregistrés</div>
        <div class="stat-value">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--warning);">
        <div class="stat-label">En Attente de Traitement</div>
        <div class="stat-value" style="color: var(--warning);">{{ $stats['en_attente'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--accent-purple);">
        <div class="stat-label">Avis Pédagogique Requis</div>
        <div class="stat-value" style="color: var(--accent-purple);">{{ $stats['avis_pedagogique'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--success);">
        <div class="stat-label">Dossiers Approuvés</div>
        <div class="stat-value" style="color: var(--success);">{{ $stats['approuvees'] }}</div>
    </div>
</div>

<!-- Management Table -->
<div class="card-panel">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-main);">File des Requêtes Étudiantes à Traiter</h3>
        <span style="font-size: 0.8rem; color: var(--text-sub);">Trié par ordre chronologique</span>
    </div>

    @if($requests->isEmpty())
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-sub);">
            <p style="font-size: 1.1rem;">Aucune requête en attente dans votre file de traitement.</p>
        </div>
    @else
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Réf. Requête</th>
                        <th>Étudiant & Matricule</th>
                        <th>Filière</th>
                        <th>Type de Demande</th>
                        <th>Avis Pédagogique</th>
                        <th>Statut Actuel</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                        <tr>
                            <td><strong style="color: var(--primary);">{{ $req->reference_code }}</strong></td>
                            <td>
                                <div><strong>{{ $req->student->name ?? 'N/A' }}</strong></div>
                                <div style="font-size: 0.75rem; color: var(--text-sub);">{{ $req->student->matricule ?? '' }}</div>
                            </td>
                            <td>{{ $req->student->filiere ?? 'Non renseigné' }}</td>
                            <td>{{ $req->requestType->title ?? 'Général' }}</td>
                            <td>
                                @if($req->pedagogical_opinion === 'favorable')
                                    <span style="background: #d1fae5; color: #047857; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">✓ Favorable</span>
                                @elseif($req->pedagogical_opinion === 'defavorable')
                                    <span style="background: #fee2e2; color: #b91c1c; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">✗ Défavorable</span>
                                @elseif($req->pedagogical_opinion === 'en_attente')
                                    <span style="background: #fef3c7; color: #b45309; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">⏳ Requis</span>
                                @else
                                    <span style="color: var(--text-sub); font-size: 0.75rem;">Non requis</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-pill status-{{ $req->status }}">
                                    {{ $req->status_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('requests.show', $req->id) }}" class="btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">
                                    Examiner / Décider
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
