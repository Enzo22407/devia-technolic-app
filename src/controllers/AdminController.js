// Controller: System Administration & Governance

import { state, getRoleLabel } from '../models/State.js';
import { showToast } from '../views/ToastView.js';
import { renderStats } from '../views/StatsView.js';
import { updateHeaderProfileUI } from '../views/HeaderView.js';

export function approveUser(userId, refreshAdminViewFn) {
  const targetU = state.users.find(u => u.id === userId);
  if (targetU) {
    targetU.isApproved = true;
    showToast(`Le compte de ${targetU.name} (${getRoleLabel(targetU.role)}) a été approuvé.`, 'success');
    renderStats();
    if (refreshAdminViewFn) refreshAdminViewFn();
  }
}

export function rejectUser(userId, refreshAdminViewFn) {
  const targetU = state.users.find(u => u.id === userId);
  if (targetU) {
    state.users = state.users.filter(u => u.id !== userId);
    showToast(`La demande de compte a été refusée.`, 'warning');
    renderStats();
    if (refreshAdminViewFn) refreshAdminViewFn();
  }
}

export function changeUserRole(userId, newRole, refreshAdminViewFn) {
  const targetU = state.users.find(u => u.id === userId);
  if (targetU) {
    const oldRole = targetU.role;
    targetU.role = newRole;

    showToast(`Rôle de ${targetU.name} mis à jour de ${getRoleLabel(oldRole)} à : ${getRoleLabel(newRole)}`, 'success');
    
    if (state.currentUser && targetU.id === state.currentUser.id) {
      updateHeaderProfileUI();
    }

    renderStats();
    if (refreshAdminViewFn) refreshAdminViewFn();
  }
}

export function addRequestType(code, title, attach, pedago, refreshAdminViewFn) {
  state.requestTypes.push({
    id: Date.now(),
    code: code,
    title: title,
    requiresAttachment: attach,
    requiresPedagogical: pedago,
    count: 0
  });

  document.getElementById('modalNewRequestType').classList.remove('active');
  showToast(`Nouveau type de requête "${title}" ajouté avec succès.`, 'success');
  if (refreshAdminViewFn) refreshAdminViewFn();
}
