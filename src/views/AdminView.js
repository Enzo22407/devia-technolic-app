// View: System Admin Layout & Sub-Tab Modules

import { state, getRoleLabel, getStatusBadgeClass, getStatusLabel } from '../models/State.js';

export function renderAdminMultiPageView(container, adminSubTabCallbacks = {}) {
  const pendingCount = state.users.filter(u => !u.isApproved).length;

  let layoutHtml = `
    <div class="admin-sidebar-layout">
      
      <!-- LEFT VERTICAL SIDEBAR NAVIGATION -->
      <aside class="admin-sidebar">
        <div class="sidebar-title">NAVIGATION ADMIN</div>
        <div class="admin-nav-list">
          <button class="admin-nav-btn ${state.adminSubTab === 'overview' ? 'active' : ''}" data-subtab="overview">
            <div class="admin-nav-btn-content">
              <svg class="icon-svg" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
              <span>Vue d'ensemble</span>
            </div>
          </button>

          <button class="admin-nav-btn ${state.adminSubTab === 'approvals' ? 'active' : ''}" data-subtab="approvals">
            <div class="admin-nav-btn-content">
              <svg class="icon-svg" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              <span>Validation Staff</span>
            </div>
            ${pendingCount > 0 ? `<span class="admin-nav-badge">${pendingCount}</span>` : ''}
          </button>

          <button class="admin-nav-btn ${state.adminSubTab === 'users' ? 'active' : ''}" data-subtab="users">
            <div class="admin-nav-btn-content">
              <svg class="icon-svg" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              <span>Annuaire & Rôles</span>
            </div>
          </button>

          <button class="admin-nav-btn ${state.adminSubTab === 'request_types' ? 'active' : ''}" data-subtab="request_types">
            <div class="admin-nav-btn-content">
              <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
              <span>Types Requêtes</span>
            </div>
          </button>

          <button class="admin-nav-btn ${state.adminSubTab === 'audit' ? 'active' : ''}" data-subtab="audit">
            <div class="admin-nav-btn-content">
              <svg class="icon-svg" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
              <span>Journal d'Audit</span>
            </div>
          </button>
        </div>
      </aside>

      <!-- RIGHT MAIN CONTENT AREA -->
      <div class="admin-main-content" id="adminSubTabContent"></div>

    </div>
  `;

  container.innerHTML = layoutHtml;

  document.querySelectorAll('.admin-nav-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      state.adminSubTab = e.target.closest('.admin-nav-btn').getAttribute('data-subtab');
      const sidebar = document.querySelector('.admin-sidebar');
      const overlay = document.getElementById('mobileSidebarOverlay');
      if (sidebar) sidebar.classList.remove('active');
      if (overlay) overlay.classList.remove('active');
      renderAdminMultiPageView(container, adminSubTabCallbacks);
    });
  });

  const contentArea = document.getElementById('adminSubTabContent');

  if (state.adminSubTab === 'overview') {
    renderAdminOverviewSubTab(contentArea);
  } else if (state.adminSubTab === 'approvals') {
    renderAdminApprovalsSubTab(contentArea, adminSubTabCallbacks.onApprove, adminSubTabCallbacks.onReject);
  } else if (state.adminSubTab === 'users') {
    renderAdminUsersSubTab(contentArea, adminSubTabCallbacks.onRoleChange);
  } else if (state.adminSubTab === 'request_types') {
    renderAdminRequestTypesSubTab(contentArea);
  } else if (state.adminSubTab === 'audit') {
    renderAdminAuditSubTab(contentArea);
  }
}

// Sub-Tab 1: Overview
export function renderAdminOverviewSubTab(container) {
  container.innerHTML = `
    <div class="card-panel">
      <div class="card-header">
        <h3 class="card-title">
          <svg class="icon-svg" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          <span>Synthèse Globale Devia Technologic</span>
        </h3>
      </div>
      <div class="form-grid-3col" style="margin-bottom: 1.5rem;">
        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border-subtle);">
          <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted);">VOLUME TOTAL REQUÊTES</div>
          <div style="font-size: 2.2rem; font-weight: 800; color: var(--primary-navy);">${state.requests.length}</div>
          <div style="font-size: 0.8rem; color: #10b981; font-weight: 700;">↑ 100% dématérialisé</div>
        </div>
        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border-subtle);">
          <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted);">DÉLAI MOYEN DE TRAITEMENT</div>
          <div style="font-size: 2.2rem; font-weight: 800; color: #2563eb;">1.8 Jour</div>
          <div style="font-size: 0.8rem; color: var(--text-secondary); font-weight: 600;">Standard Devia Technologic</div>
        </div>
        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border-subtle);">
          <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted);">TAUX DE CONFORMITÉ SÉCURITÉ</div>
          <div style="font-size: 2.2rem; font-weight: 800; color: #10b981;">100%</div>
          <div style="font-size: 0.8rem; color: #10b981; font-weight: 700;">Audit & Validation RBAC</div>
        </div>
      </div>

      <h4 style="font-size: 1rem; margin-bottom: 1rem; color: var(--primary-navy);">Dernières requêtes soumises dans le système</h4>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Référence</th>
              <th>Demandeur</th>
              <th>Type</th>
              <th>Date</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            ${state.requests.length === 0 ? '<tr><td colspan="5" style="text-align:center; padding: 2rem; color: var(--text-muted);">Aucune requête soumise pour le moment.</td></tr>' :
              state.requests.map(r => `
                <tr>
                  <td style="font-weight: 800; color: #1d4ed8;">${r.reference}</td>
                  <td style="font-weight: 700;">${r.studentName}</td>
                  <td>${r.typeName}</td>
                  <td>${r.createdAt}</td>
                  <td><span class="badge ${getStatusBadgeClass(r.status)}">${getStatusLabel(r.status)}</span></td>
                </tr>
              `).join('')
            }
          </tbody>
        </table>
      </div>
    </div>
  `;
}

// Sub-Tab 2: Approvals
export function renderAdminApprovalsSubTab(container, onApprove, onReject) {
  const pendingUsers = state.users.filter(u => !u.isApproved);

  container.innerHTML = `
    <div class="card-panel" style="border-left: 6px solid #d97706;">
      <div class="card-header">
        <h3 class="card-title">
          <svg class="icon-svg" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          <span>Validation des Demandes de Comptes Staff & Enseignants</span>
        </h3>
        <span class="badge badge-pending">${pendingUsers.length} demande(s) en attente</span>
      </div>

      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Nom & Prénom</th>
              <th>Matricule & Email</th>
              <th>Rôle Sollicité</th>
              <th>Filière / Service</th>
              <th>Action Administrateur</th>
            </tr>
          </thead>
          <tbody>
            ${pendingUsers.length === 0 ? '<tr><td colspan="5" style="text-align:center; padding: 2rem; color: var(--text-muted); font-weight: 600;">Aucun compte agent en attente d\'approbation. Tous les comptes staff Devia Technologic sont vérifiés.</td></tr>' :
              pendingUsers.map(u => `
                <tr style="background: #fffbeb;">
                  <td style="font-weight: 800; color: #0f172a;">${u.name}</td>
                  <td>
                    <div style="font-weight: 700;">${u.matricule}</div>
                    <div style="font-size: 0.78rem; color: var(--text-muted);">${u.email}</div>
                  </td>
                  <td><span class="badge badge-instruction">${getRoleLabel(u.role)}</span></td>
                  <td>${u.filiere}</td>
                  <td>
                    <button class="btn-primary btn-approve-user" data-id="${u.id}" style="background: #10b981; padding: 6px 12px; font-size: 0.8rem; border: none; margin-right: 6px;">Approuver le compte</button>
                    <button class="btn-secondary btn-reject-user" data-id="${u.id}" style="background: #fee2e2; color: #b91c1c; border-color: #fca5a5; padding: 6px 12px; font-size: 0.8rem;">Refuser</button>
                  </td>
                </tr>
              `).join('')
            }
          </tbody>
        </table>
      </div>
    </div>
  `;

  document.querySelectorAll('.btn-approve-user').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const uId = parseInt(e.target.getAttribute('data-id'));
      if (onApprove) onApprove(uId);
    });
  });

  document.querySelectorAll('.btn-reject-user').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const uId = parseInt(e.target.getAttribute('data-id'));
      if (onReject) onReject(uId);
    });
  });
}

// Sub-Tab 3: Users Directory & Role Switcher
export function renderAdminUsersSubTab(container, onRoleChange) {
  container.innerHTML = `
    <div class="card-panel">
      <div class="card-header">
        <h3 class="card-title">
          <svg class="icon-svg" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <span>Annuaire Général des Utilisateurs & Attribution des Rôles</span>
        </h3>
        <div class="filter-bar">
          <input type="text" class="input-custom" placeholder="Rechercher par nom ou matricule..." id="searchUserDir">
        </div>
      </div>

      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Utilisateur</th>
              <th>Matricule & Email</th>
              <th>Filière / Service</th>
              <th>Modifier le Rôle Attribué</th>
              <th>Statut d'Accès</th>
            </tr>
          </thead>
          <tbody>
            ${state.users.map(u => `
              <tr>
                <td style="font-weight: 800; color: #0f172a;">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <div class="user-avatar" style="width:28px; height:28px; font-size: 0.75rem;">${u.avatar}</div>
                    <span>${u.name}</span>
                  </div>
                </td>
                <td>
                  <div style="font-weight: 700;">${u.matricule}</div>
                  <div style="font-size: 0.76rem; color: var(--text-muted);">${u.email}</div>
                </td>
                <td>${u.filiere}</td>
                <td>
                  <select class="select-role-inline" data-userid="${u.id}">
                    <option value="etudiant" ${u.role === 'etudiant' ? 'selected' : ''}>Demandeur</option>
                    <option value="gestionnaire" ${u.role === 'gestionnaire' ? 'selected' : ''}>Gestionnaire Scolarité</option>
                    <option value="responsable_pedagogique" ${u.role === 'responsable_pedagogique' ? 'selected' : ''}>Resp. Pédagogique</option>
                    <option value="admin_systeme" ${u.role === 'admin_systeme' ? 'selected' : ''}>Admin Système</option>
                  </select>
                </td>
                <td>
                  ${u.isApproved 
                    ? '<span class="badge badge-approved">Actif & Approuvé</span>' 
                    : '<span class="badge badge-pending">En attente d\'approbation</span>'
                  }
                </td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      </div>
    </div>
  `;

  document.querySelectorAll('.select-role-inline').forEach(selectEl => {
    selectEl.addEventListener('change', (e) => {
      const targetUserId = parseInt(e.target.getAttribute('data-userid'));
      const newRole = e.target.value;
      if (onRoleChange) onRoleChange(targetUserId, newRole);
    });
  });
}

// Sub-Tab 4: Request Types Config
export function renderAdminRequestTypesSubTab(container) {
  container.innerHTML = `
    <div class="card-panel">
      <div class="card-header">
        <h3 class="card-title">
          <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
          <span>Configuration des Types de Requêtes & Formulaires</span>
        </h3>
        <button class="btn-primary" id="btnOpenNewTypeModal" style="font-size: 0.85rem;">+ Ajouter un Type de Requête</button>
      </div>

      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Code Identifiant</th>
              <th>Intitulé du Type</th>
              <th>Pièce Justificative</th>
              <th>Avis Pédagogique</th>
              <th>Volume Soumis</th>
            </tr>
          </thead>
          <tbody>
            ${state.requestTypes.map(t => `
              <tr>
                <td style="font-family: monospace; color: #1d4ed8; font-weight: 800;">${t.code}</td>
                <td style="font-weight: 700;">${t.title}</td>
                <td>${t.requiresAttachment ? 'Obligatoire' : 'Facultatif'}</td>
                <td>${t.requiresPedagogical ? 'Requis' : 'Non requis'}</td>
                <td><span class="badge badge-instruction">${t.count} requêtes</span></td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      </div>
    </div>
  `;

  const btnNewType = document.getElementById('btnOpenNewTypeModal');
  if (btnNewType) {
    btnNewType.addEventListener('click', () => {
      document.getElementById('modalNewRequestType').classList.add('active');
    });
  }
}

// Sub-Tab 5: Audit Logs
export function renderAdminAuditSubTab(container) {
  container.innerHTML = `
    <div class="card-panel">
      <div class="card-header">
        <h3 class="card-title">
          <svg class="icon-svg" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          <span>Journal d'Audit Système & Sécurité Devia Technologic</span>
        </h3>
      </div>

      <div class="timeline">
        <div class="timeline-item">
          <div class="timeline-dot"></div>
          <div class="timeline-content">
            <div class="timeline-header">
              <span class="timeline-user">Admin Système Devia</span>
              <span class="timeline-date">Aujourd'hui 10:15</span>
            </div>
            <div style="font-weight: 800; font-size: 0.88rem; color: var(--primary-navy); margin-bottom: 2px;">Initialisation du système Devia</div>
            <p style="font-size: 0.84rem; color: #334155;">Structure MVC configurée, modules d'administration et droits RBAC activés.</p>
          </div>
        </div>
      </div>
    </div>
  `;
}
