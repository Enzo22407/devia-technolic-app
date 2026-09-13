@extends('layouts.app')

@section('title', 'Déposer une Requête — Devia Technologic')

@section('content')
<div style="max-width: 680px; margin: 1rem auto;">
    <div class="card-panel">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--card-border);">
            <div>
                <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--text-main);">Formulaire de Dépôt de Requête</h2>
                <p style="color: var(--text-sub); font-size: 0.85rem;">Remplissez les informations requises pour transmettre votre dossier à la scolarité</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Retour
            </a>
        </div>

        <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Selection du type -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Type de Demande / Motif *</label>
                <select name="request_type_id" id="selectRequestType" required onchange="updateRequirements()" style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-main); font-size: 0.95rem;">
                    <option value="" disabled selected>-- Sélectionnez la catégorie de votre requête --</option>
                    @foreach($types as $t)
                        <option value="{{ $t->id }}" data-attachment="{{ $t->requires_attachment ? '1' : '0' }}" data-pedago="{{ $t->requires_pedagogical_review ? '1' : '0' }}" data-desc="{{ $t->description }}">
                            {{ $t->title }} {{ $t->requires_attachment ? '(Pièce requise)' : '' }}
                        </option>
                    @endforeach
                </select>
                <p id="typeDescription" style="font-size: 0.8rem; color: var(--primary); margin-top: 6px; display: none; font-weight: 500;"></p>
            </div>

            <!-- Objet de la requete -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Objet synthétique de la requête *</label>
                <input type="text" name="subject" required placeholder="ex: Erreur de report de note CC d'Algorithmique" style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-main); font-size: 0.95rem;">
            </div>

            <!-- Justification detaillee -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Explication détaillée & Justification *</label>
                <textarea name="reason" rows="5" required placeholder="Détaillez précisément les faits, dates, matières ou éléments justificatifs..." style="width: 100%; padding: 12px 14px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-main); font-size: 0.95rem; resize: vertical;"></textarea>
            </div>

            <!-- Piece jointe -->
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">
                    Pièce Justificative <span id="attachmentBadge" style="color: var(--warning); font-size: 0.8rem; font-weight: 500;">(PDF, PNG, JPG - Max 5Mo)</span>
                </label>
                <input type="file" name="attachment" id="fileAttachment" style="width: 100%; padding: 10px; background: #f8fafc; border: 1px dashed var(--card-border); border-radius: 10px; color: var(--text-main);">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 1rem;">
                Soumettre la Requête à la Scolarité
            </button>
        </form>
    </div>
</div>

<script>
    function updateRequirements() {
        const select = document.getElementById('selectRequestType');
        const selectedOption = select.options[select.selectedIndex];
        const desc = selectedOption.getAttribute('data-desc');
        const reqAttachment = selectedOption.getAttribute('data-attachment') === '1';

        const descEl = document.getElementById('typeDescription');
        const badgeEl = document.getElementById('attachmentBadge');
        const fileEl = document.getElementById('fileAttachment');

        if (desc) {
            descEl.innerText = desc;
            descEl.style.display = 'block';
        } else {
            descEl.style.display = 'none';
        }

        if (reqAttachment) {
            badgeEl.innerText = '(OBLIGATOIRE pour cette catégorie - PDF/JPG)';
            badgeEl.style.color = '#dc2626';
            fileEl.required = true;
        } else {
            badgeEl.innerText = '(Optionnelle - PDF/JPG - Max 5Mo)';
            badgeEl.style.color = 'var(--text-sub)';
            fileEl.required = false;
        }
    }
</script>
@endsection
