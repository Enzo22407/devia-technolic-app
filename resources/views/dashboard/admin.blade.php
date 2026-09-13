@extends('layouts.app')

@section('title', 'Administration Système — Devia Technologic')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--text-heading);">Administration & Supervision Système</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Vue globale des utilisateurs, types de requêtes et métriques d'efficacité</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.users.index') }}" class="btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            Gérer les Utilisateurs
        </a>
        <a href="{{ route('admin.request-types.index') }}" style="background: #ffffff; border: 1.5px solid var(--border-subtle); color: var(--text-heading); padding: 12px 20px; border-radius: 12px; font-weight: 700; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
            Configurer les Types
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Comptes Utilisateurs</div>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--brand-primary);">
        <div class="stat-label">Étudiants Inscrits</div>
        <div class="stat-value" style="color: var(--brand-primary);">{{ $stats['total_etudiants'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--brand-violet);">
        <div class="stat-label">Volume Total Requêtes</div>
        <div class="stat-value" style="color: var(--brand-violet);">{{ $stats['total_requests'] }}</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--brand-emerald);">
        <div class="stat-label">Taux d'Approbation</div>
        <div class="stat-value" style="color: var(--brand-emerald);">{{ $stats['approval_rate'] }}%</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; width: 100%;">
    <!-- Types de Requêtes -->
    <div class="card-panel">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-heading);">Types de Requêtes Configurés</h3>
            <a href="{{ route('admin.request-types.index') }}" style="color: var(--brand-primary); font-size: 0.82rem; font-weight: 700; text-decoration: none;">Voir tout →</a>
        </div>
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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-heading);">Derniers Comptes Créés</h3>
            <a href="{{ route('admin.users.index') }}" style="color: var(--brand-primary); font-size: 0.82rem; font-weight: 700; text-decoration: none;">Gérer les utilisateurs →</a>
        </div>
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
                                <div><strong style="color: var(--text-heading);">{{ $u->name }}</strong></div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $u->email }}</div>
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
