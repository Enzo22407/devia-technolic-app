// Controller: Request Management & Decisions

import { state, getStatusLabel } from '../models/State.js';
import { showToast } from '../views/ToastView.js';
import { renderNotifications } from '../views/NotificationView.js';
import { renderStats } from '../views/StatsView.js';

export function createRequest(typeId, subject, reason, fileInput, refreshAppFn) {
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
    attachment: fileInput && fileInput.files.length > 0 ? fileInput.files[0].name : (typeObj.requiresAttachment ? 'justificatif_joint.pdf' : null),
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

  typeObj.count += 1;
  state.requests.unshift(newReq);

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
  if (refreshAppFn) refreshAppFn();

  showToast(`Votre requête #${refCode} a été transmise au service Devia Technologic.`, 'success');
}

export function handleManagerDecision(req, status, comment, refreshAppFn) {
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

  document.getElementById('modalInspectRequest').classList.remove('active');
  renderNotifications();
  renderStats();
  if (refreshAppFn) refreshAppFn();
  showToast(`Décision enregistrée pour la requête #${req.reference}`, 'success');
}

export function handlePedagogicalOpinion(req, opinion, comment, refreshAppFn) {
  req.pedagogicalOpinion = opinion;
  req.pedagogicalComment = comment;
  req.status = 'en_instruction';
  req.history.push({
    action: `Avis Pédagogique : ${opinion.toUpperCase()}`,
    user: state.currentUser.name + ' (Resp. Pédagogique)',
    date: new Date().toISOString().slice(0, 16).replace('T', ' '),
    comment: comment
  });

  document.getElementById('modalInspectRequest').classList.remove('active');
  renderNotifications();
  renderStats();
  if (refreshAppFn) refreshAppFn();
  showToast(`Avis pédagogique ${opinion} transmis au gestionnaire`, 'success');
}
