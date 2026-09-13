// Model: Central Application State (Devia Technologic)

export const state = {
  currentUser: null,
  adminSubTab: 'overview', // 'overview' | 'approvals' | 'users' | 'request_types' | 'audit'
  users: [
    { 
      id: 1, 
      name: 'Admin Système Devia', 
      email: 'admin@deviatech.com', 
      role: 'admin_systeme', 
      matricule: 'DEV-SYS-001', 
      filiere: 'Direction des Systèmes d\'Information', 
      avatar: 'AD', 
      isApproved: true 
    }
  ],
  notifications: [
    { 
      id: 1, 
      userId: 1, 
      title: 'Plateforme Initialisée', 
      message: 'Bienvenue sur la plateforme Devia Technologic. La base de données a été réinitialisée.', 
      time: 'À l\'instant', 
      isRead: false 
    }
  ],
  requestTypes: [
    { id: 1, code: 'autorisation_absence', title: 'Autorisation d\'absence', requiresAttachment: true, requiresPedagogical: false, count: 0 },
    { id: 2, code: 'changement_filiere', title: 'Changement de filière ou groupe', requiresAttachment: false, requiresPedagogical: true, count: 0 },
    { id: 3, code: 'reclamation_note', title: 'Réclamation sur note / évaluation', requiresAttachment: true, requiresPedagogical: true, count: 0 },
  ],
  requests: []
};

export function getRoleLabel(role) {
  switch (role) {
    case 'etudiant': return 'Demandeur';
    case 'gestionnaire': return 'Gestionnaire Scolarité';
    case 'responsable_pedagogique': return 'Resp. Pédagogique';
    case 'admin_systeme': return 'Admin Système';
    default: return role;
  }
}

export function getStatusBadgeClass(status) {
  switch (status) {
    case 'en_attente': return 'badge-pending';
    case 'en_instruction': return 'badge-instruction';
    case 'avis_pedagogique_requis': return 'badge-pedagogical';
    case 'approuvee': return 'badge-approved';
    case 'rejetee': return 'badge-rejected';
    default: return 'badge-pending';
  }
}

export function getStatusLabel(status) {
  switch (status) {
    case 'en_attente': return 'En attente';
    case 'en_instruction': return 'En instruction';
    case 'avis_pedagogique_requis': return 'Avis Pédagogique requis';
    case 'approuvee': return 'Approuvée';
    case 'rejetee': return 'Rejetée';
    default: return status;
  }
}
