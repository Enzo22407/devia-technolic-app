// Complete State & Multi-Page Admin Logic - Devia Technologic Requests Platform

const state = {
  currentUser: null,
  adminSubTab: 'overview', // 'overview' | 'approvals' | 'users' | 'request_types' | 'audit'
  users: [
    { id: 1, name: 'Admin Système Devia', email: 'admin@deviatech.com', role: 'admin_systeme', matricule: 'DEV-SYS-001', filiere: 'Direction des Systèmes d\'Information', avatar: 'AD', isApproved: true }
  ],
  notifications: [
    { id: 1, userId: 1, title: 'Plateforme Initialisée', message: 'Bienvenue sur la plateforme Devia Technologic. La base de données a été réinitialisée.', time: 'À l\'instant', isRead: false }
  ],
  requestTypes: [
    { id: 1, code: 'autorisation_absence', title: 'Autorisation d\'absence', requiresAttachment: true, requiresPedagogical: false, count: 0 },
    { id: 2, code: 'changement_filiere', title: 'Changement de filière ou groupe', requiresAttachment: false, requiresPedagogical: true, count: 0 },
    { id: 3, code: 'reclamation_note', title: 'Réclamation sur note / évaluation', requiresAttachment: true, requiresPedagogical: true, count: 0 },
  ],
  requests: []
};

// Toast notification helper using SVG vector icons exclusively
function showToast(message, type = 'info') {
  const container = document.getElementById('toastContainer');
  if (!container) return;

  const toast = document.createElement('div');
  toast.className = `toast ${type}`;

  const iconSvg = type === 'success' 
    ? `<svg class="icon-svg" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`
    : type === 'warning'
    ? `<svg class="icon-svg" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`
    : type === 'error'
    ? `<svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`
    : `<svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`;

  toast.innerHTML = `
    <span>${iconSvg}</span>
    <div>${message}</div>
  `;

  container.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100%)';
    setTimeout(() => toast.remove(), 300);
  }, 4000);
}

// Helpers
function getStatusBadgeClass(status) {
  switch (status) {
    case 'en_attente': return 'badge-pending';
    case 'en_instruction': return 'badge-instruction';
    case 'avis_pedagogique_requis': return 'badge-pedagogical';
    case 'approuvee': return 'badge-approved';
    case 'rejetee': return 'badge-rejected';
    default: return 'badge-pending';
  }
}

function getStatusLabel(status) {
  switch (status) {
    case 'en_attente': return 'En attente';
    case 'en_instruction': return 'En instruction';
    case 'avis_pedagogique_requis': return 'Avis Pédagogique requis';
    case 'approuvee': return 'Approuvée';
    case 'rejetee': return 'Rejetée';
    default: return status;
  }
}

function getRoleLabel(role) {
  switch (role) {
    case 'etudiant': return 'Demandeur';
    case 'gestionnaire': return 'Gestionnaire Scolarité';
    case 'responsable_pedagogique': return 'Resp. Pédagogique';
    case 'admin_systeme': return 'Admin Système';
    default: return role;
  }
}

// Authentication Logic
function loginUser(userObj) {
  if (!userObj.isApproved) {
    showToast(`Accès refusé : Le compte de ${userObj.name} (${getRoleLabel(userObj.role)}) est en attente d'approbation par l'Administrateur Système Devia Technologic.`, 'error');
    return;
  }

  state.currentUser = userObj;
  
  document.getElementById('authScreen').classList.add('hidden');
  document.getElementById('mainApp').classList.remove('hidden');

  updateHeaderProfileUI();

  const heroTitle = document.getElementById('heroGreeting');
  const heroSubtitle = document.getElementById('heroSubtitle');
  const heroActions = document.getElementById('heroActions');

  if (userObj.role === 'etudiant') {
    heroTitle.textContent = `Bienvenue chez Devia Technologic, ${userObj.name}`;
    heroSubtitle.textContent = `Filière / Spécialité : ${userObj.filiere} — Matricule : ${userObj.matricule}. Déposez et suivez vos requêtes.`;
    heroActions.style.display = 'block';
  } else if (userObj.role === 'gestionnaire') {
    heroTitle.textContent = `Console Gestionnaire Devia Technologic`;
    heroSubtitle.textContent = `Instruction des dossiers, révision des pièces justificatives et validation des demandes.`;
    heroActions.style.display = 'none';
  } else if (userObj.role === 'responsable_pedagogique') {
    heroTitle.textContent = `Console Responsable Pédagogique`;
    heroSubtitle.textContent = `Examen académique des réclamations de notes et changements de filière.`;
    heroActions.style.display = 'none';
  } else {
    heroTitle.textContent = `Console Administrateur Système — Devia Technologic`;
    heroSubtitle.textContent = `Naviguez via la barre latérale gauche ou le menu mobile burger pour gérer le système.`;
    heroActions.style.display = 'none';
  }

  renderNotifications();
  renderStats();
  renderMainView();

  showToast(`Bienvenue chez Devia Technologic, ${userObj.name} !`, 'success');
}

function updateHeaderProfileUI() {
  const user = state.currentUser;
  if (!user) return;
  const avatarEl = document.getElementById('userAvatar');
  const nameEl = document.getElementById('userName');
  const roleEl = document.getElementById('userRoleBadge');
  if (avatarEl) avatarEl.textContent = user.avatar;
  if (nameEl) nameEl.textContent = user.name;
  if (roleEl) roleEl.textContent = getRoleLabel(user.role);
}

function logoutUser() {
  state.currentUser = null;
  document.getElementById('mainApp').classList.add('hidden');
  document.getElementById('authScreen').classList.remove('hidden');
  showToast('Vous avez été déconnecté de Devia Technologic.', 'info');
}

// Notifications Renderer
function renderNotifications() {
  if (!state.currentUser) return;
  const userNotifs = state.notifications.filter(n => n.userId === state.currentUser.id || state.currentUser.role === 'admin_systeme');
  const unreadCount = userNotifs.filter(n => !n.isRead).length;

  const badgeEl = document.getElementById('notifBadgeCount');
  if (badgeEl) {
    badgeEl.textContent = unreadCount;
    badgeEl.style.display = unreadCount > 0 ? 'flex' : 'none';
  }

  const listEl = document.getElementById('notifList');
  if (listEl) {
    if (userNotifs.length === 0) {
      listEl.innerHTML = '<div style="padding: 1rem; text-align: center; color: var(--text-muted); font-size: 0.82rem;">Aucune notification.</div>';
    } else {
      listEl.innerHTML = userNotifs.map(n => `
        <div class="notif-item ${n.isRead ? '' : 'unread'}" data-id="${n.id}">
          <div class="notif-title">${n.title}</div>
          <div class="notif-msg">${n.message}</div>
          <div class="notif-time">${n.time}</div>
        </div>
      `).join('');
    }
  }
}

// Stats Renderer (SVG Vector Logos/Icons)
function renderStats() {
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

// Render Main Role View
function renderMainView() {
  const user = state.currentUser;
  const viewContainer = document.getElementById('mainViewArea');

  if (user.role === 'etudiant') {
    renderStudentView(viewContainer);
  } else if (user.role === 'gestionnaire') {
    renderManagerView(viewContainer);
  } else if (user.role === 'responsable_pedagogique') {
    renderPedagogicalView(viewContainer);
  } else {
    renderAdminMultiPageView(viewContainer);
  }
}

function renderStudentView(container) {
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

  attachInspectButtons();
}

function renderManagerView(container) {
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
            ${state.requests.map(r => `
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
            `).join('')}
          </tbody>
        </table>
      </div>
    </div>
  `;

  attachInspectButtons();
}

function renderPedagogicalView(container) {
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
            ${pendingPedagogical.map(r => `
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
            `).join('')}
          </tbody>
        </table>
      </div>
    </div>
  `;

  attachInspectButtons();
}

// MULTI-PAGE ADMIN SYSTEM VIEW (LEFT VERTICAL SIDEBAR ON DESKTOP & MOBILE BURGER DRAWER)
function renderAdminMultiPageView(container) {
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
      // Close mobile drawer if active
      const sidebar = document.querySelector('.admin-sidebar');
      const overlay = document.getElementById('mobileSidebarOverlay');
      if (sidebar) sidebar.classList.remove('active');
      if (overlay) overlay.classList.remove('active');
      renderAdminMultiPageView(container);
    });
  });

  const contentArea = document.getElementById('adminSubTabContent');

  if (state.adminSubTab === 'overview') {
    renderAdminOverviewSubTab(contentArea);
  } else if (state.adminSubTab === 'approvals') {
    renderAdminApprovalsSubTab(contentArea);
  } else if (state.adminSubTab === 'users') {
    renderAdminUsersSubTab(contentArea);
  } else if (state.adminSubTab === 'request_types') {
    renderAdminRequestTypesSubTab(contentArea);
  } else if (state.adminSubTab === 'audit') {
    renderAdminAuditSubTab(contentArea);
  }
}

// 1. Admin Page: Overview
function renderAdminOverviewSubTab(container) {
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
            ${state.requests.map(r => `
              <tr>
                <td style="font-weight: 800; color: #1d4ed8;">${r.reference}</td>
                <td style="font-weight: 700;">${r.studentName}</td>
                <td>${r.typeName}</td>
                <td>${r.createdAt}</td>
                <td><span class="badge ${getStatusBadgeClass(r.status)}">${getStatusLabel(r.status)}</span></td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      </div>
    </div>
  `;
}

// 2. Admin Page: Approvals
function renderAdminApprovalsSubTab(container) {
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
      const targetU = state.users.find(u => u.id === uId);
      if (targetU) {
        targetU.isApproved = true;
        showToast(`Le compte de ${targetU.name} (${getRoleLabel(targetU.role)}) a été approuvé.`, 'success');
        renderStats();
        renderAdminMultiPageView(document.getElementById('mainViewArea'));
      }
    });
  });

  document.querySelectorAll('.btn-reject-user').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const uId = parseInt(e.target.getAttribute('data-id'));
      state.users = state.users.filter(u => u.id !== uId);
      showToast(`La demande de compte a été refusée.`, 'warning');
      renderStats();
      renderAdminMultiPageView(document.getElementById('mainViewArea'));
    });
  });
}

// 3. Admin Page: Users Directory & Role Management
function renderAdminUsersSubTab(container) {
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

      const targetU = state.users.find(u => u.id === targetUserId);
      if (targetU) {
        const oldRole = targetU.role;
        targetU.role = newRole;

        showToast(`Rôle de ${targetU.name} mis à jour de ${getRoleLabel(oldRole)} à : ${getRoleLabel(newRole)}`, 'success');
        
        if (targetU.id === state.currentUser.id) {
          updateHeaderProfileUI();
        }

        renderStats();
        renderAdminUsersSubTab(container);
      }
    });
  });
}

// 4. Admin Page: Request Types
function renderAdminRequestTypesSubTab(container) {
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

// 5. Admin Page: Audit Logs
function renderAdminAuditSubTab(container) {
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
            <div style="font-weight: 800; font-size: 0.88rem; color: var(--primary-navy); margin-bottom: 2px;">Mise à jour des rôles d'accès</div>
            <p style="font-size: 0.84rem; color: #334155;">Consultation et mise à jour de l'annuaire des utilisateurs et attribution des privilèges.</p>
          </div>
        </div>

        <div class="timeline-item">
          <div class="timeline-dot" style="background: #10b981;"></div>
          <div class="timeline-content">
            <div class="timeline-header">
              <span class="timeline-user">Dr. Jean-Paul Mbarga</span>
              <span class="timeline-date">15/08 15:30</span>
            </div>
            <div style="font-weight: 800; font-size: 0.86rem; color: var(--primary-navy); margin-bottom: 2px;">Émission d'un avis pédagogique favorable</div>
            <p style="font-size: 0.84rem; color: #334155;">Avis émis sur la requête #REQ-2026-0815-004 (Réclamation sur note Algorithmique II).</p>
          </div>
        </div>

        <div class="timeline-item">
          <div class="timeline-dot" style="background: #3b82f6;"></div>
          <div class="timeline-content">
            <div class="timeline-header">
              <span class="timeline-user">Thirdboy Devia (Demandeur)</span>
              <span class="timeline-date">17/08 09:30</span>
            </div>
            <div style="font-weight: 800; font-size: 0.86rem; color: var(--primary-navy); margin-bottom: 2px;">Dépôt initial de requête</div>
            <p style="font-size: 0.84rem; color: #334155;">Dépôt de la demande #REQ-2026-0817-001 avec certificat médical scanné.</p>
          </div>
        </div>
      </div>
    </div>
  `;
}

// Inspect Modal
function attachInspectButtons() {
  document.querySelectorAll('.btn-inspect').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const reqId = parseInt(e.target.getAttribute('data-id'));
      openInspectModal(reqId);
    });
  });
}

function openInspectModal(reqId) {
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
  if (formManager) {
    formManager.addEventListener('submit', (e) => {
      e.preventDefault();
      const status = document.getElementById('selectManagerStatus').value;
      const comment = document.getElementById('inputManagerComment').value;

      req.status = status;
      req.decisionComment = comment;
      req.history.push({
        action: `Statut mis à jour -> ${getStatusLabel(status)}`,
        user: state.currentUser.name + ' (Gestionnaire)',
        date: new Date().toISOString().slice(0, 16).replace('T', ' '),
        comment: comment
      });

      const studentUser = state.users.find(u => u.name === req.studentName);
      if (studentUser) {
        state.notifications.unshift({
          id: Date.now(),
          userId: studentUser.id,
          title: `Mise à jour #${req.reference}`,
          message: `Votre requête est passée au statut : ${getStatusLabel(status)}. Motif : ${comment}`,
          time: 'À l\'instant',
          isRead: false
        });
      }

      modal.classList.remove('active');
      renderNotifications();
      renderStats();
      renderMainView();
      showToast(`Décision enregistrée pour la requête #${req.reference}`, 'success');
    });
  }

  const formPedagogical = document.getElementById('formPedagogicalOpinion');
  if (formPedagogical) {
    formPedagogical.addEventListener('submit', (e) => {
      e.preventDefault();
      const opinion = document.getElementById('selectPedagogicalOpinion').value;
      const comment = document.getElementById('inputPedagogicalComment').value;

      req.pedagogicalOpinion = opinion;
      req.pedagogicalComment = comment;
      req.status = 'en_instruction';
      req.history.push({
        action: `Avis Pédagogique : ${opinion.toUpperCase()}`,
        user: state.currentUser.name + ' (Resp. Pédagogique)',
        date: new Date().toISOString().slice(0, 16).replace('T', ' '),
        comment: comment
      });

      modal.classList.remove('active');
      renderNotifications();
      renderStats();
      renderMainView();
      showToast(`Avis pédagogique ${opinion} transmis au gestionnaire`, 'success');
    });
  }
}

// Settings Modal Handler
function openSettingsModal() {
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

// Initial Events
function initEvents() {
  const tabLogin = document.getElementById('tabLoginBtn');
  const tabRegister = document.getElementById('tabRegisterBtn');
  const formLogin = document.getElementById('formLogin');
  const formRegister = document.getElementById('formRegister');
  const regRoleSelect = document.getElementById('regRole');
  const staffNotice = document.getElementById('staffNotice');

  tabLogin.addEventListener('click', () => {
    tabLogin.classList.add('active');
    tabRegister.classList.remove('active');
    formLogin.classList.add('active');
    formRegister.classList.remove('active');
  });

  tabRegister.addEventListener('click', () => {
    tabRegister.classList.add('active');
    tabLogin.classList.remove('active');
    formRegister.classList.add('active');
    formLogin.classList.remove('active');
  });

  regRoleSelect.addEventListener('change', () => {
    if (regRoleSelect.value !== 'etudiant') {
      staffNotice.style.display = 'flex';
    } else {
      staffNotice.style.display = 'none';
    }
  });

  // Mobile Burger Menu Toggle Handler
  const btnBurger = document.getElementById('btnBurgerMenu');
  const sidebarOverlay = document.getElementById('mobileSidebarOverlay');

  if (btnBurger) {
    btnBurger.addEventListener('click', () => {
      const sidebar = document.querySelector('.admin-sidebar');
      if (sidebar) {
        sidebar.classList.toggle('active');
        if (sidebarOverlay) sidebarOverlay.classList.toggle('active');
      }
    });
  }

  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', () => {
      const sidebar = document.querySelector('.admin-sidebar');
      if (sidebar) sidebar.classList.remove('active');
      sidebarOverlay.classList.remove('active');
    });
  }

  // Quick Demo Login buttons
  document.querySelectorAll('.quick-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const demoRole = e.target.closest('.quick-btn').getAttribute('data-demo');
      const targetUser = state.users.find(u => u.role === demoRole && u.isApproved);
      if (targetUser) {
        loginUser(targetUser);
      }
    });
  });

  // Login Submit
  formLogin.addEventListener('submit', (e) => {
    e.preventDefault();
    const roleVal = document.getElementById('loginRole').value;
    const emailVal = document.getElementById('loginEmail').value;

    let targetUser = state.users.find(u => (u.email === emailVal || u.matricule === emailVal) && u.role === roleVal);
    if (!targetUser) {
      targetUser = state.users.find(u => u.role === roleVal);
    }

    if (targetUser) {
      loginUser(targetUser);
    } else {
      showToast('Identifiants incorrects.', 'error');
    }
  });

  // Register Submit
  formRegister.addEventListener('submit', (e) => {
    e.preventDefault();
    const role = regRoleSelect.value;
    const name = document.getElementById('regName').value;
    const matricule = document.getElementById('regMatricule').value;
    const filiere = document.getElementById('regFiliere').value;
    const email = document.getElementById('regEmail').value;

    const isStudent = role === 'etudiant';

    const newUser = {
      id: Date.now(),
      name: name,
      email: email,
      role: role,
      matricule: matricule,
      filiere: filiere,
      avatar: name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2),
      isApproved: isStudent
    };

    state.users.push(newUser);

    if (isStudent) {
      loginUser(newUser);
      showToast(`Compte créé avec succès chez Devia Technologic. Bienvenue ${name} !`, 'success');
    } else {
      state.notifications.unshift({
        id: Date.now(),
        userId: 4,
        title: 'Nouveau compte Staff à valider',
        message: `Le compte ${getRoleLabel(role)} de ${name} (${matricule}) nécessite votre approbation.`,
        time: 'À l\'instant',
        isRead: false
      });

      formRegister.reset();
      tabLogin.click();

      showToast(`Votre demande de compte ${getRoleLabel(role)} a été transmise. L'Administrateur Système doit la valider avant votre première connexion.`, 'warning');
    }
  });

  // Logout button
  document.getElementById('btnLogout').addEventListener('click', logoutUser);

  // Settings Gear Button
  document.getElementById('btnOpenSettings').addEventListener('click', openSettingsModal);

  const modalSettings = document.getElementById('modalSettings');
  document.getElementById('btnCloseSettingsModal').addEventListener('click', () => modalSettings.classList.remove('active'));
  document.getElementById('btnCancelSettingsModal').addEventListener('click', () => modalSettings.classList.remove('active'));

  // Submit Profile Settings
  document.getElementById('formUserProfile').addEventListener('submit', (e) => {
    e.preventDefault();
    const user = state.currentUser;
    if (!user) return;

    user.name = document.getElementById('profileName').value;
    user.email = document.getElementById('profileEmail').value;
    user.filiere = document.getElementById('profileFiliere').value;
    user.avatar = user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);

    updateHeaderProfileUI();
    modalSettings.classList.remove('active');
    renderMainView();
    showToast('Vos informations personnelles ont été mises à jour chez Devia Technologic.', 'success');
  });

  // Notifications Bell Drawer toggle
  const btnBell = document.getElementById('btnNotifBell');
  const drawerNotif = document.getElementById('notifDrawer');

  btnBell.addEventListener('click', (e) => {
    e.stopPropagation();
    drawerNotif.classList.toggle('active');
  });

  document.addEventListener('click', () => {
    drawerNotif.classList.remove('active');
  });

  drawerNotif.addEventListener('click', (e) => {
    e.stopPropagation();
  });

  // Mark all notifications read
  document.getElementById('btnMarkAllRead').addEventListener('click', () => {
    state.notifications.forEach(n => n.isRead = true);
    renderNotifications();
    showToast('Toutes les notifications ont été marquées comme lues', 'info');
  });

  // Modal Request Triggers
  const modalReq = document.getElementById('modalNewRequest');
  document.getElementById('btnOpenNewRequest').addEventListener('click', () => {
    modalReq.classList.add('active');
  });

  document.getElementById('btnCloseModalRequest').addEventListener('click', () => {
    modalReq.classList.remove('active');
  });

  document.getElementById('btnCancelModalRequest').addEventListener('click', () => {
    modalReq.classList.remove('active');
  });

  document.getElementById('btnCloseInspectModal').addEventListener('click', () => {
    document.getElementById('modalInspectRequest').classList.remove('active');
  });

  const modalType = document.getElementById('modalNewRequestType');
  const btnCancelType = document.getElementById('btnCancelNewTypeModal');
  const btnCloseType = document.getElementById('btnCloseNewTypeModal');
  if (btnCancelType) btnCancelType.addEventListener('click', () => modalType.classList.remove('active'));
  if (btnCloseType) btnCloseType.addEventListener('click', () => modalType.classList.remove('active'));

  // Submit New Request Type
  const formNewType = document.getElementById('formNewRequestType');
  if (formNewType) {
    formNewType.addEventListener('submit', (e) => {
      e.preventDefault();
      const code = document.getElementById('newTypeCode').value;
      const title = document.getElementById('newTypeTitle').value;
      const attach = document.getElementById('newTypeAttachment').checked;
      const pedago = document.getElementById('newTypePedagogical').checked;

      state.requestTypes.push({
        id: Date.now(),
        code: code,
        title: title,
        requiresAttachment: attach,
        requiresPedagogical: pedago,
        count: 0
      });

      modalType.classList.remove('active');
      renderMainView();
      showToast(`Nouveau type de requête "${title}" ajouté avec succès.`, 'success');
    });
  }

  // Dynamic field update on type selection
  const selectType = document.getElementById('selectType');
  const dynamicContainer = document.getElementById('dynamicFieldsContainer');

  selectType.addEventListener('change', () => {
    const typeId = parseInt(selectType.value);
    if (typeId === 1) { // Absence
      const today = new Date().toISOString().split('T')[0];
      const nextWeek = new Date(Date.now() + 3 * 86400000).toISOString().split('T')[0];
      dynamicContainer.innerHTML = `
        <div class="form-grid-2col form-group">
          <div>
            <label>Date de début d'absence *</label>
            <input type="date" class="form-control" value="${today}" required>
          </div>
          <div>
            <label>Date de fin d'absence *</label>
            <input type="date" class="form-control" value="${nextWeek}" required>
          </div>
        </div>
      `;
    } else if (typeId === 2) { // Changement groupe
      dynamicContainer.innerHTML = `
        <div class="form-grid-2col form-group">
          <div>
            <label>Groupe / Filière actuel</label>
            <input type="text" class="form-control" value="${state.currentUser ? state.currentUser.filiere : 'GL1-B'}" readonly>
          </div>
          <div>
            <label>Groupe / Filière souhaité *</label>
            <select class="form-control" required>
              <option value="GL1-A">GL1-A (Génie Logiciel)</option>
              <option value="Génie Informatique & IA">Génie Informatique & IA</option>
              <option value="Réseaux & Cybersécurité">Réseaux & Cybersécurité</option>
            </select>
          </div>
        </div>
      `;
    } else if (typeId === 3) { // Note
      dynamicContainer.innerHTML = `
        <div class="form-grid-3col form-group">
          <div>
            <label>Matière concernée *</label>
            <input type="text" class="form-control" placeholder="Ex: Algorithmique II" required>
          </div>
          <div>
            <label>Évaluation *</label>
            <input type="text" class="form-control" placeholder="Ex: CC 1" required>
          </div>
          <div>
            <label>Note reçue (/20) *</label>
            <input type="number" step="0.5" class="form-control" placeholder="Ex: 08" required>
          </div>
        </div>
      `;
    }
  });

  selectType.dispatchEvent(new Event('change'));

  // Submit Student Request Form
  document.getElementById('formNewRequest').addEventListener('submit', (e) => {
    e.preventDefault();
    const typeId = parseInt(selectType.value);
    const subject = document.getElementById('inputSubject').value;
    const reason = document.getElementById('inputReason').value;
    const fileInput = document.getElementById('inputFile');

    const typeObj = state.requestTypes.find(t => t.id === typeId);
    const refCode = 'REQ-2026-0817-0' + (state.requests.length + 1);

    const newReq = {
      id: Date.now(),
      reference: refCode,
      studentName: state.currentUser.name,
      studentMatricule: state.currentUser.matricule,
      filiere: state.currentUser.filiere,
      typeId: typeId,
      typeName: typeObj.title,
      subject: subject,
      reason: reason,
      attachment: fileInput.files.length > 0 ? fileInput.files[0].name : (typeObj.requiresAttachment ? 'justificatif_joint.pdf' : null),
      status: 'en_attente',
      pedagogicalOpinion: typeObj.requiresPedagogical ? 'en_attente' : 'non_requis',
      pedagogicalComment: null,
      decisionComment: null,
      createdAt: new Date().toISOString().slice(0, 16).replace('T', ' '),
      history: [
        {
          action: 'Dépôt de la requête',
          user: state.currentUser.name + ' (Demandeur)',
          date: new Date().toISOString().slice(0, 16).replace('T', ' '),
          comment: 'Soumission initiale de la demande.'
        }
      ]
    };

    state.requests.unshift(newReq);
    modalReq.classList.remove('active');
    document.getElementById('formNewRequest').reset();

    state.notifications.unshift({
      id: Date.now(),
      userId: state.currentUser.id,
      title: 'Accusé de réception Devia',
      message: `Votre requête #${refCode} a été enregistrée avec succès.`,
      time: 'À l\'instant',
      isRead: false
    });

    renderNotifications();
    renderStats();
    renderMainView();

    showToast(`Votre requête #${refCode} a été transmise au service Devia Technologic.`, 'success');
  });
}

document.addEventListener('DOMContentLoaded', () => {
  initEvents();
});
