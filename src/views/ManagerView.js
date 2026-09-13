// View: Manager / Scolarité Dashboard

import { state, getStatusBadgeClass, getStatusLabel } from '../models/State.js';

export function renderManagerView(container, attachInspectFn) {
  container.innerHTML = `
    <div class="card-panel">
      <div class="card-header">
        <h3 class="card-title">
          <svg class="icon-svg" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          <span>Registre Général des Requêtes Reçues</span>
        </h3>
        <div class="filter-bar">
          <select class="select-custom" id="filterStatus">
            <option value="all">Tous les statuts</option>
            <option value="en_attente">En attente d'instruction</option>
            <option value="avis_pedagogique_requis">Avis Pédagogique Requis</option>
            <option value="approuvee">Approuvées</option>
            <option value="rejetee">Rejetées</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Référence</th>
              <th>Demandeur & Filière</th>
              <th>Type</th>
              <th>Objet</th>
              <th>Statut</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            ${state.requests.length === 0 ? '<tr><td colspan="6" style="text-align:center; padding: 2rem; color: var(--text-muted);">Aucune requête reçue pour le moment.</td></tr>' :
              state.requests.map(r => `
                <tr>
                  <td style="font-weight: 800; color: #1d4ed8;">${r.reference}</td>
                  <td>
                    <div style="font-weight: 800; color: #0f172a;">${r.studentName}</div>
                    <div style="font-size: 0.76rem; color: var(--text-muted);">${r.filiere} — ${r.studentMatricule}</div>
                  </td>
                  <td style="font-weight: 700;">${r.typeName}</td>
                  <td>${r.subject}</td>
                  <td><span class="badge ${getStatusBadgeClass(r.status)}">${getStatusLabel(r.status)}</span></td>
                  <td>
                    <button class="btn-primary btn-inspect" data-id="${r.id}" style="padding: 7px 14px; font-size: 0.82rem;">Instruire le dossier</button>
                  </td>
                </tr>
              `).join('')
            }
          </tbody>
        </table>
      </div>
    </div>
  `;

  if (attachInspectFn) attachInspectFn();
}
