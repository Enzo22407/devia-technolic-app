// View: Header & Hero Profile UI

import { state, getRoleLabel } from '../models/State.js';

export function updateHeaderProfileUI() {
  const user = state.currentUser;
  if (!user) return;
  const avatarEl = document.getElementById('userAvatar');
  const nameEl = document.getElementById('userName');
  const roleEl = document.getElementById('userRoleBadge');
  if (avatarEl) avatarEl.textContent = user.avatar;
  if (nameEl) nameEl.textContent = user.name;
  if (roleEl) roleEl.textContent = getRoleLabel(user.role);
}

export function updateHeroBannerUI() {
  const userObj = state.currentUser;
  if (!userObj) return;

  const heroTitle = document.getElementById('heroGreeting');
  const heroSubtitle = document.getElementById('heroSubtitle');
  const heroActions = document.getElementById('heroActions');

  if (userObj.role === 'etudiant') {
    heroTitle.textContent = `Bienvenue chez Devia Technologic, ${userObj.name}`;
    heroSubtitle.textContent = `Filière / Spécialité : ${userObj.filiere} — Matricule : ${userObj.matricule}. Déposez et suivez vos requêtes.`;
    if (heroActions) heroActions.style.display = 'block';
  } else if (userObj.role === 'gestionnaire') {
    heroTitle.textContent = `Console Gestionnaire Devia Technologic`;
    heroSubtitle.textContent = `Instruction des dossiers, révision des pièces justificatives et validation des demandes.`;
    if (heroActions) heroActions.style.display = 'none';
  } else if (userObj.role === 'responsable_pedagogique') {
    heroTitle.textContent = `Console Responsable Pédagogique`;
    heroSubtitle.textContent = `Examen académique des réclamations de notes et changements de filière.`;
    if (heroActions) heroActions.style.display = 'none';
  } else {
    heroTitle.textContent = `Console Administrateur Système — Devia Technologic`;
    heroSubtitle.textContent = `Naviguez via la barre latérale gauche ou le menu mobile burger pour gérer le système.`;
    if (heroActions) heroActions.style.display = 'none';
  }
}
