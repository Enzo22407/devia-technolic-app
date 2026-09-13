// Controller: Master Application Orchestrator (MVC)

import { state } from '../models/State.js';
import { showToast } from '../views/ToastView.js';
import { updateHeaderProfileUI } from '../views/HeaderView.js';
import { renderNotifications } from '../views/NotificationView.js';
import { renderStats } from '../views/StatsView.js';
import { renderStudentView } from '../views/StudentView.js';
import { renderManagerView } from '../views/ManagerView.js';
import { renderPedagogicalView } from '../views/PedagogicalView.js';
import { renderAdminMultiPageView } from '../views/AdminView.js';
import { openInspectModal, openSettingsModal } from '../views/ModalView.js';

import { loginUser, logoutUser, registerUser } from './AuthController.js';
import { createRequest, handleManagerDecision, handlePedagogicalOpinion } from './RequestController.js';
import { approveUser, rejectUser, changeUserRole, addRequestType } from './AdminController.js';

export class AppController {
  static init() {
    AppController.bindEvents();
  }

  static renderMainView() {
    const user = state.currentUser;
    const viewContainer = document.getElementById('mainViewArea');
    if (!viewContainer || !user) return;

    if (user.role === 'etudiant') {
      renderStudentView(viewContainer, AppController.attachInspectButtons);
    } else if (user.role === 'gestionnaire') {
      renderManagerView(viewContainer, AppController.attachInspectButtons);
    } else if (user.role === 'responsable_pedagogique') {
      renderPedagogicalView(viewContainer, AppController.attachInspectButtons);
    } else {
      renderAdminMultiPageView(viewContainer, {
        onApprove: (uId) => approveUser(uId, AppController.renderMainView),
        onReject: (uId) => rejectUser(uId, AppController.renderMainView),
        onRoleChange: (uId, newRole) => changeUserRole(uId, newRole, AppController.renderMainView)
      });
    }
  }

  static attachInspectButtons() {
    document.querySelectorAll('.btn-inspect').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const reqId = parseInt(e.target.getAttribute('data-id'));
        openInspectModal(
          reqId,
          (req) => {
            const status = document.getElementById('selectManagerStatus').value;
            const comment = document.getElementById('inputManagerComment').value;
            handleManagerDecision(req, status, comment, AppController.renderMainView);
          },
          (req) => {
            const opinion = document.getElementById('selectPedagogicalOpinion').value;
            const comment = document.getElementById('inputPedagogicalComment').value;
            handlePedagogicalOpinion(req, opinion, comment, AppController.renderMainView);
          }
        );
      });
    });
  }

  static bindEvents() {
    const tabLogin = document.getElementById('tabLoginBtn');
    const tabRegister = document.getElementById('tabRegisterBtn');
    const formLogin = document.getElementById('formLogin');
    const formRegister = document.getElementById('formRegister');
    const regRoleSelect = document.getElementById('regRole');
    const staffNotice = document.getElementById('staffNotice');

    if (tabLogin && tabRegister) {
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
    }

    if (regRoleSelect) {
      regRoleSelect.addEventListener('change', () => {
        if (regRoleSelect.value !== 'etudiant') {
          if (staffNotice) staffNotice.style.display = 'flex';
        } else {
          if (staffNotice) staffNotice.style.display = 'none';
        }
      });
    }

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

    // Login Submit
    if (formLogin) {
      formLogin.addEventListener('submit', (e) => {
        e.preventDefault();
        const roleVal = document.getElementById('loginRole').value;
        const emailVal = document.getElementById('loginEmail').value;

        let targetUser = state.users.find(u => (u.email === emailVal || u.matricule === emailVal) && u.role === roleVal);
        if (!targetUser) {
          targetUser = state.users.find(u => u.role === roleVal);
        }

        if (targetUser) {
          loginUser(targetUser, AppController.renderMainView);
        } else {
          showToast('Identifiants incorrects ou compte inexistant.', 'error');
        }
      });
    }

    // Register Submit
    if (formRegister) {
      formRegister.addEventListener('submit', (e) => {
        e.preventDefault();
        const role = regRoleSelect.value;
        const name = document.getElementById('regName').value;
        const matricule = document.getElementById('regMatricule').value;
        const filiere = document.getElementById('regFiliere').value;
        const email = document.getElementById('regEmail').value;

        registerUser(name, matricule, filiere, email, role, AppController.renderMainView);
      });
    }

    // Logout button
    const btnLogout = document.getElementById('btnLogout');
    if (btnLogout) btnLogout.addEventListener('click', logoutUser);

    // Settings Gear Button
    const btnSettings = document.getElementById('btnOpenSettings');
    if (btnSettings) btnSettings.addEventListener('click', openSettingsModal);

    const modalSettings = document.getElementById('modalSettings');
    const btnCloseSet = document.getElementById('btnCloseSettingsModal');
    const btnCancelSet = document.getElementById('btnCancelSettingsModal');
    if (btnCloseSet) btnCloseSet.addEventListener('click', () => modalSettings.classList.remove('active'));
    if (btnCancelSet) btnCancelSet.addEventListener('click', () => modalSettings.classList.remove('active'));

    // Submit Profile Settings
    const formProfile = document.getElementById('formUserProfile');
    if (formProfile) {
      formProfile.addEventListener('submit', (e) => {
        e.preventDefault();
        const user = state.currentUser;
        if (!user) return;

        user.name = document.getElementById('profileName').value;
        user.email = document.getElementById('profileEmail').value;
        user.filiere = document.getElementById('profileFiliere').value;
        user.avatar = user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);

        updateHeaderProfileUI();
        modalSettings.classList.remove('active');
        AppController.renderMainView();
        showToast('Vos informations personnelles ont été mises à jour chez Devia Technologic.', 'success');
      });
    }

    // Notifications Bell Drawer toggle
    const btnBell = document.getElementById('btnNotifBell');
    const drawerNotif = document.getElementById('notifDrawer');

    if (btnBell && drawerNotif) {
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
    }

    // Mark all notifications read
    const btnMarkAll = document.getElementById('btnMarkAllRead');
    if (btnMarkAll) {
      btnMarkAll.addEventListener('click', () => {
        state.notifications.forEach(n => n.isRead = true);
        renderNotifications();
        showToast('Toutes les notifications ont été marquées comme lues', 'info');
      });
    }

    // Modal Request Triggers
    const modalReq = document.getElementById('modalNewRequest');
    const btnOpenReq = document.getElementById('btnOpenNewRequest');
    const btnCloseReq = document.getElementById('btnCloseModalRequest');
    const btnCancelReq = document.getElementById('btnCancelModalRequest');

    if (btnOpenReq && modalReq) btnOpenReq.addEventListener('click', () => modalReq.classList.add('active'));
    if (btnCloseReq && modalReq) btnCloseReq.addEventListener('click', () => modalReq.classList.remove('active'));
    if (btnCancelReq && modalReq) btnCancelReq.addEventListener('click', () => modalReq.classList.remove('active'));

    const btnCloseInspect = document.getElementById('btnCloseInspectModal');
    if (btnCloseInspect) {
      btnCloseInspect.addEventListener('click', () => {
        document.getElementById('modalInspectRequest').classList.remove('active');
      });
    }

    const modalType = document.getElementById('modalNewRequestType');
    const btnCancelType = document.getElementById('btnCancelNewTypeModal');
    const btnCloseType = document.getElementById('btnCloseNewTypeModal');
    if (btnCancelType && modalType) btnCancelType.addEventListener('click', () => modalType.classList.remove('active'));
    if (btnCloseType && modalType) btnCloseType.addEventListener('click', () => modalType.classList.remove('active'));

    // Submit New Request Type
    const formNewType = document.getElementById('formNewRequestType');
    if (formNewType) {
      formNewType.addEventListener('submit', (e) => {
        e.preventDefault();
        const code = document.getElementById('newTypeCode').value;
        const title = document.getElementById('newTypeTitle').value;
        const attach = document.getElementById('newTypeAttachment').checked;
        const pedago = document.getElementById('newTypePedagogical').checked;

        addRequestType(code, title, attach, pedago, AppController.renderMainView);
      });
    }

    // Dynamic field update on type selection
    const selectType = document.getElementById('selectType');
    const dynamicContainer = document.getElementById('dynamicFieldsContainer');

    if (selectType && dynamicContainer) {
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
    }

    // Submit Student Request Form
    const formNewReq = document.getElementById('formNewRequest');
    if (formNewReq) {
      formNewReq.addEventListener('submit', (e) => {
        e.preventDefault();
        const typeId = parseInt(selectType.value);
        const subject = document.getElementById('inputSubject').value;
        const reason = document.getElementById('inputReason').value;
        const fileInput = document.getElementById('inputFile');

        createRequest(typeId, subject, reason, fileInput, AppController.renderMainView);
        if (modalReq) modalReq.classList.remove('active');
        formNewReq.reset();
      });
    }
  }
}
