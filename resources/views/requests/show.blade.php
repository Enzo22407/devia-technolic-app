@extends('layouts.app')

@section('title', 'Dossier #' . $studentRequest->reference_code . ' — Devia Technologic')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('dashboard') }}" style="color: var(--text-sub); text-decoration: none; font-size: 0.88rem; font-weight: 600;">← Retour au Tableau de Bord</a>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; width: 100%;">
    <!-- Main Content Column -->
    <div>
        <!-- Main Card -->
        <div class="card-panel">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span style="color: var(--primary); font-weight: 800; font-size: 0.9rem; letter-spacing: 0.5px;">DOSSIER N° {{ $studentRequest->reference_code }}</span>
                    <h2 style="font-size: 1.5rem; font-weight: 800; margin-top: 4px; color: var(--text-main);">{{ $studentRequest->subject }}</h2>
                </div>
                <span class="status-pill status-{{ $studentRequest->status }}" style="font-size: 0.85rem; padding: 6px 14px;">
                    {{ $studentRequest->status_label }}
                </span>
            </div>

            <div style="background: #f8fafc; border: 1px solid var(--card-border); border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; font-size: 0.85rem;">
                <div>
                    <div style="color: var(--text-sub);">Demandeur</div>
                    <strong>{{ $studentRequest->student->name ?? 'Étudiant' }}</strong>
                </div>
                <div>
                    <div style="color: var(--text-sub);">Matricule / Filière</div>
                    <strong>{{ $studentRequest->student->matricule ?? 'N/A' }}</strong> ({{ $studentRequest->student->filiere ?? 'N/A' }})
                </div>
                <div>
                    <div style="color: var(--text-sub);">Date Dépôt</div>
                    <strong>{{ $studentRequest->created_at->format('d/m/Y à H:i') }}</strong>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--text-sub); text-transform: uppercase; margin-bottom: 8px;">Détails & Motif de la Demande :</h4>
                <div style="background: #f8fafc; border: 1px solid var(--card-border); padding: 1.25rem; border-radius: 12px; font-size: 0.95rem; line-height: 1.6; white-space: pre-wrap; color: var(--text-main);">{{ $studentRequest->reason }}</div>
            </div>

            <!-- Attachments section -->
            @if($studentRequest->attachments->isNotEmpty())
                <div style="margin-bottom: 1.5rem;">
                    <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--text-sub); text-transform: uppercase; margin-bottom: 8px;">Pièces Justificatives Jointes :</h4>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach($studentRequest->attachments as $file)
                            <div style="background: #ffffff; border: 1px solid var(--card-border); padding: 10px 14px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                                        <strong>{{ $file->file_name }}</strong>
                                    </span>
                                    <span style="font-size: 0.75rem; color: var(--text-sub); margin-left: 10px;">({{ round($file->file_size / 1024, 1) }} Ko)</span>
                                </div>
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" style="background: #e0f2fe; color: #0369a1; padding: 5px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">Consulter Document</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Decision Box if decided -->
            @if(in_array($studentRequest->status, ['approuvee', 'rejetee']) && $studentRequest->decision_comment)
                <div style="background: {{ $studentRequest->status === 'approuvee' ? '#d1fae5' : '#fee2e2' }}; border: 1px solid {{ $studentRequest->status === 'approuvee' ? '#6ee7b7' : '#fca5a5' }}; padding: 1.25rem; border-radius: 12px; margin-top: 1.5rem;">
                    <h4 style="color: {{ $studentRequest->status === 'approuvee' ? '#047857' : '#b91c1c' }}; font-weight: 800; font-size: 1rem; margin-bottom: 6px;">
                        Décision Finale : {{ strtoupper($studentRequest->status_label) }}
                    </h4>
                    <p style="font-size: 0.9rem; margin-bottom: 8px; color: var(--text-main);">{{ $studentRequest->decision_comment }}</p>
                    <div style="font-size: 0.75rem; color: var(--text-sub);">
                        Décidé par <strong>{{ $studentRequest->decider->name ?? 'Responsable Administration' }}</strong> le {{ optional($studentRequest->decided_at)->format('d/m/Y H:i') }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Historical Audit Timeline -->
        <div class="card-panel">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.25rem;">Traçabilité & Historique du Traitement</h3>

            <div style="display: flex; flex-direction: column; gap: 1rem; position: relative; padding-left: 20px; border-left: 2px solid var(--card-border);">
                @foreach($studentRequest->history as $h)
                    <div style="position: relative;">
                        <div style="position: absolute; left: -27px; top: 2px; width: 12px; height: 12px; border-radius: 50%; background: var(--primary); border: 2px solid #ffffff;"></div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 600; color: var(--text-main);">
                            <span>{{ $h->user->name ?? 'Système' }}</span>
                            <span style="color: var(--text-sub); font-size: 0.75rem;">{{ $h->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p style="font-size: 0.85rem; color: var(--text-sub); margin-top: 4px;">{{ $h->comment }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Actions Column (Side panel) -->
    <div>
        <!-- Pedagogical Review Section for Responsable Pedagogique -->
        @if(auth()->user()->isResponsablePedagogique() || auth()->user()->isAdminSysteme())
            <div class="card-panel" style="border-left: 4px solid var(--accent-purple);">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    Avis Pédagogique
                </h3>

                @if($studentRequest->pedagogical_opinion !== 'en_attente' && $studentRequest->pedagogical_opinion !== 'non_requis')
                    <div style="margin-bottom: 1rem;">
                        <span style="font-size: 0.8rem; color: var(--text-sub);">Avis émis :</span>
                        <strong style="display: block; color: {{ $studentRequest->pedagogical_opinion === 'favorable' ? '#047857' : '#b91c1c' }};">
                            {{ ucfirst($studentRequest->pedagogical_opinion) }}
                        </strong>
                        <p style="font-size: 0.85rem; color: var(--text-sub); margin-top: 4px;">"{{ $studentRequest->pedagogical_comment }}"</p>
                    </div>
                @else
                    <form action="{{ route('requests.submit-pedagogical-opinion', $studentRequest->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8rem; color: var(--text-main); font-weight: 600; margin-bottom: 4px;">Orientations pédagogiques *</label>
                            <select name="opinion" required style="width: 100%; padding: 10px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 8px; color: var(--text-main); font-size: 0.85rem;">
                                <option value="favorable">Avis Favorable (Approbation recommandée)</option>
                                <option value="defavorable">Avis Défavorable (Motif de rejet)</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8rem; color: var(--text-main); font-weight: 600; margin-bottom: 4px;">Commentaire / Remarques *</label>
                            <textarea name="comment" rows="3" required placeholder="Observations pour le gestionnaire de la scolarité..." style="width: 100%; padding: 10px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 8px; color: var(--text-main); font-size: 0.85rem;"></textarea>
                        </div>
                        <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 10px; font-size: 0.85rem; background: var(--accent-purple);">
                            Soumettre l'Avis Pédagogique
                        </button>
                    </form>
                @endif
            </div>
        @endif

        <!-- Final Decision Section for Manager & Admin -->
        @if(auth()->user()->isGestionnaire() || auth()->user()->isAdminSysteme())
            <div class="card-panel" style="border-left: 4px solid var(--primary);">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="m9 14 2 2 4-4"></path></svg>
                    Décision Administrative
                </h3>

                <form action="{{ route('requests.update-status', $studentRequest->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.8rem; color: var(--text-main); font-weight: 600; margin-bottom: 4px;">Nouveau Statut *</label>
                        <select name="status" required style="width: 100%; padding: 10px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 8px; color: var(--text-main); font-size: 0.85rem;">
                            <option value="en_instruction" {{ $studentRequest->status === 'en_instruction' ? 'selected' : '' }}>En cours d'instruction</option>
                            <option value="avis_pedagogique_requis" {{ $studentRequest->status === 'avis_pedagogique_requis' ? 'selected' : '' }}>Solliciter Avis Pédagogique</option>
                            <option value="approuvee" {{ $studentRequest->status === 'approuvee' ? 'selected' : '' }}>Approuvée (Accordée)</option>
                            <option value="rejetee" {{ $studentRequest->status === 'rejetee' ? 'selected' : '' }}>Rejetée (Refusée)</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.8rem; color: var(--text-main); font-weight: 600; margin-bottom: 4px;">Motifs & Instructions officielles *</label>
                        <textarea name="comment" rows="4" required placeholder="Précisez la réponse officielle transmise à l'étudiant..." style="width: 100%; padding: 10px; background: #ffffff; border: 1px solid var(--card-border); border-radius: 8px; color: var(--text-main); font-size: 0.85rem;"></textarea>
                    </div>

                    <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 10px; font-size: 0.85rem;">
                        Enregistrer la Décision
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
