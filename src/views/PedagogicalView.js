// View: Responsable Pédagogique Dashboard

import { state, getStatusBadgeClass, getStatusLabel } from '../models/State.js';

export function renderPedagogicalView(container, attachInspectFn) {
  const pendingPedagogical = state.requests.filter(r => r.status === 'avis_pedagogique_requis' || r.pedagogicalOpinion !== 'non_requis');

  container.innerHTML = `
    <div class="card-panel">
      <div class="card-header">
        <h3 class="card-title">
          <svg class="icon-svg" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
          <span>Demandes Nécessitant un Avis Académique</span>
        </h3>
      </div>

      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Référence</th>
              <th>Demandeur</th>
              <th>Type & Objet</th>
              <th>Avis Pédagogique</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            ${pendingPedagogical.length === 0 ? '<tr><td colspan="5" style="text-align:center; padding: 2rem; color: var(--text-muted);">Aucune demande en attente d\'avis académique.</td></tr>' :
              pendingPedagogical.map(r => `
                <tr>
                  <td style="font-weight: 800; color: #1d4ed8;">${r.reference}</td>
                  <td>
                    <div style="font-weight: 800; color: #0f172a;">${r.studentName}</div>
                    <div style="font-size: 0.76rem; color: var(--text-muted);">${r.filiere}</div>
                  </td>
                  <td>
                    <div style="font-weight: 700; color: #0f172a;">${r.typeName}</div>
                    <div style="font-size: 0.82rem; color: var(--text-muted);">${r.subject}</div>
                  </td>
                  <td>
                    ${r.pedagogicalOpinion === 'en_attente' 
                      ? '<span class="badge badge-pending">En attente de votre avis</span>'
                      : `<span class="badge badge-approved">Avis ${r.pedagogicalOpinion}</span>`
                    }
                  </td>
                  <td>
                    <button class="btn-primary btn-inspect" data-id="${r.id}" style="padding: 7px 14px; font-size: 0.82rem;">Donner mon avis</button>
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
