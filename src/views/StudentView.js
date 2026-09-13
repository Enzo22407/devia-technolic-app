// View: Student / Demandeur Dashboard

import { state, getStatusBadgeClass, getStatusLabel } from '../models/State.js';

export function renderStudentView(container, attachInspectFn) {
  const studentReqs = state.requests.filter(r => r.studentName === state.currentUser.name);

  container.innerHTML = `
    <div class="card-panel">
      <div class="card-header">
        <h3 class="card-title">
          <svg class="icon-svg" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          <span>Mes Requêtes Déposées — Devia Technologic</span>
        </h3>
        <div class="filter-bar">
          <input type="text" class="input-custom" placeholder="Rechercher une référence..." id="searchStudentReq">
        </div>
      </div>

      <div class="table-responsive">
        <table class="data-table">
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
            ${studentReqs.length === 0 ? '<tr><td colspan="6" style="text-align:center; padding: 2rem; color: var(--text-muted);">Vous n\'avez déposé aucune requête pour le moment.</td></tr>' : 
              studentReqs.map(r => `
                <tr>
                  <td style="font-weight: 800; color: #1d4ed8;">${r.reference}</td>
                  <td style="font-weight: 700;">${r.typeName}</td>
                  <td>${r.subject}</td>
                  <td>${r.createdAt}</td>
                  <td><span class="badge ${getStatusBadgeClass(r.status)}">${getStatusLabel(r.status)}</span></td>
                  <td>
                    <button class="btn-secondary btn-inspect" data-id="${r.id}">Consulter & Suivi</button>
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
