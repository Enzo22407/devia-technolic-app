@extends('layouts.app')

@section('title', 'Types de Requêtes — Devia Technologic')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--text-heading);">Configuration des Types de Requêtes</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Gérez les catégories de demandes académiques et leurs exigences de pièces justificatives</p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('dashboard') }}" class="btn-back">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Retour au Tableau de Bord
        </a>
        <button onclick="document.getElementById('modalAddType').style.display='flex'" class="btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Nouveau Type
        </button>
    </div>
</div>

<div class="card-panel">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Intitulé de la Catégorie</th>
                    <th>Description</th>
                    <th>Pièce Obligatoire</th>
                    <th>Avis Pédagogique</th>
                    <th>Volume Total</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($types as $t)
                    <tr>
                        <td><code>{{ $t->code }}</code></td>
                        <td><strong style="color: var(--text-heading);">{{ $t->title }}</strong></td>
                        <td style="font-size: 0.82rem; color: var(--text-muted); max-width: 250px;">{{ $t->description }}</td>
                        <td>{{ $t->requires_attachment ? 'Oui (Fichier Requis)' : 'Non' }}</td>
                        <td>{{ $t->requires_pedagogical_review ? 'Requis' : 'Non Requis' }}</td>
                        <td><strong>{{ $t->student_requests_count }} dossier(s)</strong></td>
                        <td>
                            @if($t->is_active)
                                <span style="background: #ecfdf5; color: #047857; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">Actif</span>
                            @else
                                <span style="background: #fff1f2; color: #be123c; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">Désactivé</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.request-types.toggle', $t->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background: #f1f5f9; border: 1px solid var(--border-subtle); color: var(--text-heading); padding: 5px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; cursor: pointer;">
                                    {{ $t->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add Type -->
<div id="modalAddType" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 2000; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: white; border-radius: 20px; padding: 2rem; max-width: 500px; width: 100%; box-shadow: var(--shadow-lg);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-heading);">Ajouter une Catégorie</h3>
            <button onclick="document.getElementById('modalAddType').style.display='none'" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>

        <form action="{{ route('admin.request-types.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Code Identifiant (ex: RECL_BULLETIN) *</label>
                <input type="text" name="code" required style="width: 100%; padding: 10px; border: 1.5px solid var(--border-subtle); border-radius: 10px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Intitulé Lisible *</label>
                <input type="text" name="title" required style="width: 100%; padding: 10px; border: 1.5px solid var(--border-subtle); border-radius: 10px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Description</label>
                <textarea name="description" rows="3" style="width: 100%; padding: 10px; border: 1.5px solid var(--border-subtle); border-radius: 10px;"></textarea>
            </div>
            <div style="margin-bottom: 1rem; display: flex; flex-direction: column; gap: 8px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" name="requires_attachment" value="1"> Pièce justificative obligatoire
                </label>
                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" name="requires_pedagogical_review" value="1"> Avis du responsable pédagogique requis
                </label>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">Enregistrer le Type</button>
        </form>
    </div>
</div>
@endsection
