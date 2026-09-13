@extends('layouts.app')

@section('title', 'Administration Système — Devia Technologic')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--text-main);">Administration & Supervision Système</h2>
        <p style="color: var(--text-sub); font-size: 0.9rem;">Vue globale des utilisateurs, types de requêtes et métriques d'efficacité</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Comptes Utilisateurs</div>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--info);">
        <div class="stat-label">Étudiants Inscrits</div>
        <div class="stat-value" style="color: var(--info);">{{ $stats['total_etudiants'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--primary);">
        <div class="stat-label">Volume Total Requêtes</div>
        <div class="stat-value" style="color: var(--primary);">{{ $stats['total_requests'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--success);">
        <div class="stat-label">Taux d'Approbation</div>
        <div class="stat-value" style="color: var(--success);">{{ $stats['approval_rate'] }}%</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; width: 100%;">
    <!-- Types de Requêtes -->
    <div class="card-panel">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem;">Types de Requêtes Configurés</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Intitulé</th>
                        <th>Volume</th>
                        <th>Pièce Oblig.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requestTypes as $type)
                        <tr>
                            <td><code>{{ $type->code }}</code></td>
                            <td>{{ $type->title }}</td>
                            <td><strong>{{ $type->student_requests_count }}</strong></td>
                            <td>{{ $type->requires_attachment ? 'Oui' : 'Non' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Derniers Inscrits -->
    <div class="card-panel">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem;">Derniers Comptes Créés</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nom & Email</th>
                        <th>Matricule</th>
                        <th>Rôle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $u)
                        <tr>
                            <td>
                                <div><strong>{{ $u->name }}</strong></div>
                                <div style="font-size: 0.75rem; color: var(--text-sub);">{{ $u->email }}</div>
                            </td>
                            <td><code>{{ $u->matricule }}</code></td>
                            <td>
                                <span class="role-badge role-{{ $u->role }}">{{ ucfirst(str_replace('_', ' ', $u->role)) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
