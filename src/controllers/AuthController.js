// Controller: User Authentication & Access Control

import { state, getRoleLabel } from '../models/State.js';
import { showToast } from '../views/ToastView.js';
import { updateHeaderProfileUI, updateHeroBannerUI } from '../views/HeaderView.js';
import { renderNotifications } from '../views/NotificationView.js';
import { renderStats } from '../views/StatsView.js';

export function loginUser(userObj, renderMainViewFn) {
  if (!userObj.isApproved) {
    showToast(`Accès refusé : Le compte de ${userObj.name} (${getRoleLabel(userObj.role)}) est en attente d'approbation par l'Administrateur Système.`, 'error');
    return;
  }

  state.currentUser = userObj;
  
  document.getElementById('authScreen').classList.add('hidden');
  document.getElementById('mainApp').classList.remove('hidden');

  updateHeaderProfileUI();
  updateHeroBannerUI();
  renderNotifications();
  renderStats();
  if (renderMainViewFn) renderMainViewFn();

  showToast(`Bienvenue chez Devia Technologic, ${userObj.name} !`, 'success');
}

export function logoutUser() {
  state.currentUser = null;
  document.getElementById('mainApp').classList.add('hidden');
  document.getElementById('authScreen').classList.remove('hidden');
  showToast('Vous avez été déconnecté de Devia Technologic.', 'info');
}

export function registerUser(name, matricule, filiere, email, role, renderMainViewFn) {
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
    loginUser(newUser, renderMainViewFn);
    showToast(`Compte créé avec succès chez Devia Technologic. Bienvenue ${name} !`, 'success');
  } else {
    state.notifications.unshift({
      id: Date.now(),
      userId: 1, // Admin notification
      title: 'Nouveau compte Staff à valider',
      message: `Le compte ${getRoleLabel(role)} de ${name} (${matricule}) nécessite votre approbation.`,
      time: 'À l\'instant',
      isRead: false
    });

    showToast(`Votre demande de compte ${getRoleLabel(role)} a été transmise. L'Administrateur Système doit la valider avant votre première connexion.`, 'warning');
  }
}
