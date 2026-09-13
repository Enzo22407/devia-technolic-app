// View: Statistics Grid Cards (SVG Vector Icons)

import { state } from '../models/State.js';

export function renderStats() {
  const container = document.getElementById('statsGrid');
  if (!container || !state.currentUser) return;

  const user = state.currentUser;
  const requests = state.requests;
  let html = '';

  if (user.role === 'etudiant') {
    const studentReqs = requests.filter(r => r.studentName === user.name);
    const total = studentReqs.length;
    const pending = studentReqs.filter(r => ['en_attente', 'en_instruction', 'avis_pedagogique_requis'].includes(r.status)).length;
    const approved = studentReqs.filter(r => r.status === 'approuvee').length;
    const rejected = studentReqs.filter(r => r.status === 'rejetee').length;

    html = `
      <div class="stat-card">
        <div class="stat-info"><div class="value">${total}</div><div class="label">Mes Requêtes Totales</div></div>
        <div class="stat-icon total"><svg class="icon-svg" viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg></div>
      </div>
      <div class="stat-card">
        <div class="stat-info"><div class="value">${pending}</div><div class="label">En cours de traitement</div></div>
        <div class="stat-icon pending"><svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
      </div>
      <div class="stat-card">
        <div class="stat-info"><div class="value">${approved}</div><div class="label">Demandes Approuvées</div></div>
        <div class="stat-icon approved"><svg class="icon-svg" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
      </div>
      <div class="stat-card">
        <div class="stat-info"><div class="value">${rejected}</div><div class="label">Demandes Rejetées</div></div>
        <div class="stat-icon rejected"><svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
      </div>
    `;
  } else if (user.role === 'gestionnaire' || user.role === 'responsable_pedagogique') {
    const total = requests.length;
    const pending = requests.filter(r => r.status === 'en_attente').length;
    const pedagogical = requests.filter(r => r.status === 'avis_pedagogique_requis').length;
    const approved = requests.filter(r => r.status === 'approuvee').length;

    html = `
      <div class="stat-card">
        <div class="stat-info"><div class="value">${total}</div><div class="label">Requêtes Reçues</div></div>
        <div class="stat-icon total"><svg class="icon-svg" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
      </div>
      <div class="stat-card">
        <div class="stat-info"><div class="value">${pending}</div><div class="label">À Instruire</div></div>
        <div class="stat-icon pending"><svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
      </div>
      <div class="stat-card">
        <div class="stat-info"><div class="value">${pedagogical}</div><div class="label">Avis Pédagogique Requis</div></div>
        <div class="stat-icon pedagogical"><svg class="icon-svg" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></div>
      </div>
      <div class="stat-card">
        <div class="stat-info"><div class="value">${approved}</div><div class="label">Dossiers Validés</div></div>
        <div class="stat-icon approved"><svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg></div>
      </div>
    `;
  } else {
    // Admin Système Stats
    const pendingApprovalUsers = state.users.filter(u => !u.isApproved).length;
    html = `
      <div class="stat-card">
        <div class="stat-info"><div class="value">${state.users.length}</div><div class="label">Utilisateurs Enregistrés</div></div>
        <div class="stat-icon total"><svg class="icon-svg" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
      </div>
      <div class="stat-card">
        <div class="stat-info"><div class="value" style="color: ${pendingApprovalUsers > 0 ? '#d97706' : '#0f172a'};">${pendingApprovalUsers}</div><div class="label">Comptes Staff à valider</div></div>
        <div class="stat-icon pending"><svg class="icon-svg" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
      </div>
      <div class="stat-card">
        <div class="stat-info"><div class="value">${requests.length}</div><div class="label">Requêtes Système</div></div>
        <div class="stat-icon pedagogical"><svg class="icon-svg" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>
      </div>
      <div class="stat-card">
        <div class="stat-info"><div class="value">100%</div><div class="label">Conformité Devia</div></div>
        <div class="stat-icon approved"><svg class="icon-svg" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
      </div>
    `;
  }

  container.innerHTML = html;
}
