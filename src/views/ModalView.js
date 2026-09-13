// View: Modals Renderer (Request Inspection, Settings, Forms)

import { state, getStatusBadgeClass, getStatusLabel, getRoleLabel } from '../models/State.js';

export function openInspectModal(reqId, onManagerSubmit, onPedagogicalSubmit) {
  const req = state.requests.find(r => r.id === reqId);
  if (!req) return;

  const modal = document.getElementById('modalInspectRequest');
  const body = document.getElementById('inspectModalBody');
  const role = state.currentUser.role;

  let actionFormHtml = '';

  if (role === 'gestionnaire') {
    actionFormHtml = `
      <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border-subtle);">
        <h4 style="font-size: 0.95rem; margin-bottom: 0.75rem; color: #1d4ed8; display: flex; align-items: center; gap: 8px;">
          <svg class="icon-svg" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          <span>Décision Administrative</span>
        </h4>
        <form id="formManagerDecision">
          <div class="form-group">
            <label>Nouveau Statut de la Requête</label>
            <select class="form-control" id="selectManagerStatus" required>
              <option value="en_instruction" ${req.status === 'en_instruction' ? 'selected' : ''}>Mettre En Instruction</option>
              <option value="avis_pedagogique_requis" ${req.status === 'avis_pedagogique_requis' ? 'selected' : ''}>Demander un Avis au Responsable Pédagogique</option>
              <option value="approuvee" ${req.status === 'approuvee' ? 'selected' : ''}>Approuver la requête</option>
              <option value="rejetee" ${req.status === 'rejetee' ? 'selected' : ''}>Rejeter la requête</option>
            </select>
          </div>
          <div class="form-group">
            <label>Motif ou Commentaire de Décision *</label>
            <textarea class="form-control" id="inputManagerComment" placeholder="Justification transmise à l'étudiant..." required>${req.decisionComment || ''}</textarea>
          </div>
          <button type="submit" class="btn-primary" style="width: 100%;">Enregistrer la Décision & Notifier</button>
        </form>
      </div>
    `;
  } else if (role === 'responsable_pedagogique' && req.status === 'avis_pedagogique_requis') {
    actionFormHtml = `
      <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border-subtle);">
        <h4 style="font-size: 0.95rem; margin-bottom: 0.75rem; color: #6b21a8; display: flex; align-items: center; gap: 8px;">
          <svg class="icon-svg" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
          <span>Avis du Responsable Pédagogique</span>
        </h4>
        <form id="formPedagogicalOpinion">
          <div class="form-group">
            <label>Avis Académique</label>
            <select class="form-control" id="selectPedagogicalOpinion" required>
              <option value="favorable">Avis FAVORABLE</option>
              <option value="defavorable">Avis DÉFAVORABLE</option>
            </select>
          </div>
          <div class="form-group">
            <label>Observations & Avis détaillé *</label>
            <textarea class="form-control" id="inputPedagogicalComment" placeholder="Évaluation pédagogique de la demande..." required></textarea>
          </div>
          <button type="submit" class="btn-primary" style="width: 100%; background: linear-gradient(135deg, #9333ea, #2563eb);">Transmettre l'Avis au Gestionnaire</button>
        </form>
      </div>
    `;
  }

  body.innerHTML = `
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
      <div>
        <div style="font-size: 1.35rem; font-weight: 800; color: var(--primary-navy);">${req.subject}</div>
        <div style="font-size: 0.85rem; color: var(--text-muted);">Référence : <span style="color: #1d4ed8; font-weight: 800;">${req.reference}</span> — Déposée le ${req.createdAt}</div>
      </div>
      <span class="badge ${getStatusBadgeClass(req.status)}" style="font-size: 0.9rem; padding: 6px 14px;">${getStatusLabel(req.status)}</span>
    </div>

    <div style="background: #f8fafc; padding: 1.15rem; border-radius: 12px; border: 1px solid var(--border-subtle); margin-bottom: 1.25rem;">
      <div style="font-size: 0.78rem; font-weight: 800; color: var(--text-muted); margin-bottom: 4px;">DEMANDEUR</div>
      <div style="font-weight: 800; color: var(--primary-navy); font-size: 1.05rem;">${req.studentName}</div>
      <div style="font-size: 0.85rem; color: var(--text-secondary);">${req.filiere} — ${req.studentMatricule}</div>
    </div>

    <div style="margin-bottom: 1.25rem;">
      <div style="font-size: 0.88rem; font-weight: 800; color: var(--primary-navy); margin-bottom: 6px;">Motif détaillé de la requête :</div>
      <p style="font-size: 0.92rem; color: #334155; background: #f8fafc; padding: 14px; border-radius: 10px; border-left: 4px solid var(--primary-blue); line-height: 1.6;">${req.reason}</p>
    </div>

    ${req.attachment ? `
      <div style="margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between; background: #eff6ff; padding: 12px 16px; border-radius: 10px; border: 1px solid #bfdbfe;">
        <div style="display: flex; align-items: center; gap: 10px;">
          <svg class="icon-svg" viewBox="0 0 24 24" style="color: #1e40af;"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
          <span style="font-size: 0.9rem; font-weight: 800; color: #1e40af;">${req.attachment}</span>
        </div>
        <button class="btn-secondary" onclick="alert('Téléchargement du fichier justificatif...')">Télécharger</button>
      </div>
    ` : ''}

    ${req.pedagogicalComment ? `
      <div style="margin-bottom: 1.25rem; background: #f3e8ff; padding: 14px; border-radius: 10px; border: 1px solid #e9d5ff;">
        <div style="font-weight: 800; color: #6b21a8; font-size: 0.88rem; margin-bottom: 4px;">Avis du Responsable Pédagogique (${req.pedagogicalOpinion.toUpperCase()}) :</div>
        <p style="font-size: 0.9rem; color: #3b0764;">${req.pedagogicalComment}</p>
      </div>
    ` : ''}

    <div style="margin-top: 1.25rem;">
      <div style="font-size: 0.88rem; font-weight: 800; color: var(--primary-navy); margin-bottom: 8px;">Historique & Traçabilité des actions :</div>
      <div class="timeline">
        ${req.history.map(h => `
          <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <div class="timeline-header">
                <span class="timeline-user">${h.user}</span>
                <span class="timeline-date">${h.date}</span>
              </div>
              <div style="font-weight: 800; font-size: 0.86rem; color: var(--primary-navy); margin-bottom: 2px;">${h.action}</div>
              <p style="font-size: 0.84rem; color: #475569;">${h.comment}</p>
            </div>
          </div>
        `).join('')}
      </div>
    </div>

    ${actionFormHtml}
  `;

  modal.classList.add('active');

  const formManager = document.getElementById('formManagerDecision');
  if (formManager && onManagerSubmit) {
    formManager.addEventListener('submit', (e) => {
      e.preventDefault();
      onManagerSubmit(req);
    });
  }

  const formPedagogical = document.getElementById('formPedagogicalOpinion');
  if (formPedagogical && onPedagogicalSubmit) {
    formPedagogical.addEventListener('submit', (e) => {
      e.preventDefault();
      onPedagogicalSubmit(req);
    });
  }
}

export function openSettingsModal() {
  const modal = document.getElementById('modalSettings');
  const user = state.currentUser;
  if (!modal || !user) return;

  document.getElementById('profileName').value = user.name;
  document.getElementById('profileEmail').value = user.email;
  document.getElementById('profileMatricule').value = user.matricule || 'N/A';
  document.getElementById('profileRole').value = getRoleLabel(user.role);
  document.getElementById('profileFiliere').value = user.filiere || '';
  document.getElementById('profilePasswordNew').value = '';

  modal.classList.add('active');
}
