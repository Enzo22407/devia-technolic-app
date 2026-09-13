@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs — Devia Technologic')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--text-heading);">Gestion des Utilisateurs</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Consultez, ajoutez ou modifiez les habilitations d'accès de la plateforme</p>
    </div>
    <button onclick="document.getElementById('modalAddUser').style.display='flex'" class="btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Nouveau Compte
    </button>
</div>

<!-- Filters & Search -->
<div class="card-panel" style="padding: 1.25rem; margin-bottom: 1.5rem;">
    <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, email ou matricule..." style="flex: 1; min-width: 260px; padding: 10px 14px; background: #ffffff; border: 1.5px solid var(--border-subtle); border-radius: 10px; color: var(--text-heading); font-size: 0.9rem;">
        
        <select name="role" style="padding: 10px 14px; background: #ffffff; border: 1.5px solid var(--border-subtle); border-radius: 10px; color: var(--text-heading); font-size: 0.9rem;">
            <option value="">Tous les rôles</option>
            <option value="etudiant" {{ request('role') == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
            <option value="gestionnaire" {{ request('role') == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
            <option value="responsable_pedagogique" {{ request('role') == 'responsable_pedagogique' ? 'selected' : '' }}>Resp. Pédagogique</option>
            <option value="admin_systeme" {{ request('role') == 'admin_systeme' ? 'selected' : '' }}>Admin Système</option>
        </select>

        <button type="submit" class="btn-primary" style="padding: 10px 18px; font-size: 0.88rem;">Filtrer</button>
        @if(request('search') || request('role'))
            <a href="{{ route('admin.users.index') }}" style="color: var(--text-muted); font-size: 0.85rem; text-decoration: none;">Réinitialiser</a>
        @endif
    </form>
</div>

<!-- Users Table -->
<div class="card-panel">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Matricule</th>
                    <th>Filière / Service</th>
                    <th>Rôle Habilité</th>
                    <th>Date Création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                    <tr>
                        <td>
                            <div><strong style="color: var(--text-heading);">{{ $u->name }}</strong></div>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $u->email }}</div>
                        </td>
                        <td><code>{{ $u->matricule }}</code></td>
                        <td>{{ $u->filiere }}</td>
                        <td>
                            <span class="role-badge role-{{ $u->role }}">{{ ucfirst(str_replace('_', ' ', $u->role)) }}</span>
                        </td>
                        <td>{{ $u->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($u->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; padding: 5px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; cursor: pointer;">
                                        Supprimer
                                    </button>
                                </form>
                            @else
                                <span style="font-size: 0.75rem; color: var(--text-muted);">Compte actif</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $users->links() }}
    </div>
</div>

<!-- Modal Add User -->
<div id="modalAddUser" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 2000; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: white; border-radius: 20px; padding: 2rem; max-width: 500px; width: 100%; box-shadow: var(--shadow-lg);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-heading);">Ajouter un Utilisateur</h3>
            <button onclick="document.getElementById('modalAddUser').style.display='none'" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Rôle *</label>
                <select name="role" required style="width: 100%; padding: 10px; border: 1.5px solid var(--border-subtle); border-radius: 10px;">
                    <option value="etudiant">Étudiant</option>
                    <option value="gestionnaire">Gestionnaire (Scolarité)</option>
                    <option value="responsable_pedagogique">Responsable Pédagogique</option>
                    <option value="admin_systeme">Administrateur Système</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Nom complet *</label>
                <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1.5px solid var(--border-subtle); border-radius: 10px;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Matricule *</label>
                    <input type="text" name="matricule" required style="width: 100%; padding: 10px; border: 1.5px solid var(--border-subtle); border-radius: 10px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Filière / Service *</label>
                    <input type="text" name="filiere" required style="width: 100%; padding: 10px; border: 1.5px solid var(--border-subtle); border-radius: 10px;">
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Adresse Email *</label>
                <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1.5px solid var(--border-subtle); border-radius: 10px;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Mot de passe provisoire *</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1.5px solid var(--border-subtle); border-radius: 10px;">
            </div>
            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">Créer le Compte</button>
        </form>
    </div>
</div>
@endsection
